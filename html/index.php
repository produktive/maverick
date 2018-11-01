<?php
require 'header.php';
if ($_SESSION['auth'] == true) : 
?>
    <script src="./nosleep.js"></script>
    <script>
    var noSleep = new NoSleep();
    var allowedToSleep=true;

	$(function(){
		silenceAlerts=false;
		var audio = new Audio('alarm.mp3');
		audio.loop=true;

		$("#silenceAlert").click(function(){
			$("#silenceAlert").prop("disabled",true);
			audio.pause();
			silenceAlerts=true;

			var counter=300;
			var countdown = function(){
				counter--;
				$("#silenceAlert").prop('value', '('+counter+')');
				if (counter==0) {
					//$("#silenceAlertDiv").css("display","none");
					$("#silenceAlertDiv").hide();
					$("#alertType").html('');
					$("#silenceAlert").prop('value', 'Silence');
					$("#silenceAlert").prop("disabled",false);
					clearInterval(handle);
					handle=0;
					silenceAlerts=false;
				}
			}
			var handle=setInterval(countdown,1000);
		});

		$('#toggleCook').click(function(){
			$.ajax({
				url: 'togglecook.php',
				type: 'POST',
				data: $("#alertsForm").serialize(),
				success: function(data) {
					if (data=='Start Cook') {
						$('#toggleCook').prop('value',data);
						$('#toggleCook').removeClass().addClass("btn btn-lg btn-success");
						//$('#alertsDiv').css("display","block");
						$('#alertsDiv').show();
						noSleep.disable();
						allowedToSleep=true;
					} else {
						$('#toggleCook').prop('value',data);
						$('#toggleCook').removeClass().addClass("btn btn-lg btn-danger");
						//$('#alertsDiv').css("display","none");
						$('#alertsDiv').hide();
						if (allowedToSleep) {
							noSleep.enable();
							allowedToSleep=false;
						}
					}
				},
			});
			$('#silenceAlertDiv').hide();
			$('#alertType').html("");
		});

		var callAjax = function(){
			$.ajax({
				url:'interval.php',
				type:'POST',
				success:function(data){
					if(data=='Start Cook') {
						$('#toggleCook').prop('value',data);
						$('#toggleCook').removeClass().addClass("btn btn-lg btn-success");
						noSleep.disable();
					} else {
						$('#toggleCook').prop('value', data);
						$('#toggleCook').removeClass().addClass("btn btn-lg btn-danger");
					}
				}
			});

		}
		setInterval(callAjax,1000);

		var checkAlerts = function(){
			noSleep.disable();
			allowedToSleep=true;
			$.ajax({
				url:'togglecook.php',
				type:'POST',
				data: 'p1=alerts',
				success:function(data){
					if(data!='' && silenceAlerts==false) {
						audio.play();
						//$("#silenceAlertDiv").css("display","block");
						$("#silenceAlertDiv").show();
						$("#alertType").html(data);
					} else {
						audio.pause();
						if (silenceAlerts==false) {
							//$("#silenceAlertDiv").css("display","none");
							$("#silenceAlertDiv").hide();
							$("#alertType").html("");
						}
					}
				}
			});
		}
		setInterval(checkAlerts,5000);
	});
   </script>
<?php endif; ?>
  </head>

  <body>

    <div class="container">
    <?php $btnActive[0]=" class='active'";?>
    <?php require 'menu.php';?>
      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
       <h2>Maverick ET-732 BBQ Thermometer</h2>
	   
         <?php
		 if ($_SESSION['auth'] == true) :
			exec("pgrep maverick", $pids);
			if(empty($pids)) {
				$val='Start Cook';
				$btnClass='btn btn-lg btn-success';
				$showAlertsRow='display:block';
			} else {
				$val='Stop Cook';
				$btnClass='btn btn-lg btn-danger';
				$showAlertsRow='display:none';
			}

			class MyDB extends SQLite3 {
				function __construct() {
					$this->open('the.db');
				}
			}
			$database=new MyDB();

			$smokersList=null;
			$query="SELECT start,end,pitLow,pitHi,foodLow,foodHi,email FROM cooks ORDER BY id DESC LIMIT 1;";
			if($result=$database->query($query))
			{
				while($row=$result->fetchArray())
				{
					$pL=$row['pitLow'];
					$pH=$row['pitHi'];
					$fL=$row['foodLow'];
					$fH=$row['foodHi'];
					$email=$row['email'];
					$start=$row['start'];
					$end=$row['end'];
				}
			}

			if (($database->querySingle('SELECT cookid FROM activecook'))>-1) {
				$keepAwake="noSleep.enable(); allowedToSleep=false;";
			} else {
				$keepAwake="noSleep.disable(); allowedToSleep=true;";
			}

			if (empty($pids)) {
				$query="SELECT * FROM smokers ORDER BY id DESC;";
				$smokersList=$database->query($query);
			}
         ?>
	    <div id="alertsDiv" class="row" style="<?=$showAlertsRow?>">
	   	 <form id="alertsForm">
	   	  <div class="form-group">
	   	   <div class="col-sm-2 col-xs-4">
	   	    <input type="hidden" name="p1" id="p1" value="clicked"></input>
	   	    <label for="smoker">Smoker:</label>
	   	    <select name="smoker" id="smoker">
	   	    <?php if ($smokersList) { ?>
	   	    <?php  while($smokersRow=$smokersList->fetchArray()) { ?>
	   	     <option value=<?=$smokersRow['id']?>><?=htmlspecialchars($smokersRow['desc'])?></option>
	   	    <?php  } ?>
	   	    <?php } ?>
	   	    <?php $database->close(); ?>
	   	    </select>
                    <br />
	   	    <label for="pitLow">Pit Low:</label><input type="number" class="form-control" name="pitLow" id="pitLow" min="1" max="500" value=<?=$pL?>>
	   	    <label for="pitHigh">Pit High:</label><input type="number" class="form-control" name="pitHi" id="pitHi" min="1" max="500" value=<?=$pH?>>
	   	    <label for="foodLow">Food Low:</label><input type="number" class="form-control" name="foodLow" id="foodLow" min="1" max="500" value=<?=$fL?>>
	   	    <label for="foodHigh">Food High:</label><input type="number" class="form-control" name="foodHi" id="foodHi" min="1" max="500" value=<?=$fH?>>
	   	    <label for="alertEmail">Send To:</label><input type="email" class="form-control" name="alertEmail" id="alertEmail" value=<?=$email?>>
	   	   </div>
	   	  </div>
	   	 </form>
        </div><br />
        <div class="row">
         <input class="<?=$btnClass?>" type="submit" value="<?=$val?>" id="toggleCook">
        </div><br />
        <div class="row" id="silenceAlertDiv" style="display:none">
	 <input class="btn btn-lg btn-danger" type="button" value="Silence" id="silenceAlert">
        </div>
        <div class="row">
         <div class="col-md-2">
          <p id="alertType" class="h2 bg-danger"></p>
         </div>
         <div class="col-md-10">&nbsp;</div>
        </div>
      </div>
  <?php endif; ?>
    </div> <!-- /container -->
    <?php require 'footer.php';?>
    <script><?=$keepAwake?></script>
  </body>
</html>

