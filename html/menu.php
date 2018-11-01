<!-- Static navbar -->
<nav class="navbar navbar-default">
<div class="container-fluid">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="./index.php">Maverick</a>
  </div>
  <div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav">
      <li<?=$btnActive[0]?>><a href="./index.php">Home</a></li>
	  <li<?=$btnActive[3]?>><a href="cooks.php">Cooks</a></li>
  <?php if ($_SESSION['auth'] == true) : ?>
      <li<?=$btnActive[1]?>><a href="alerts.php">Alerts</a></li>
      <li<?=$btnActive[2]?>><a href="smokers.php">Smokers</a></li>
      <li<?=$btnActive[4]?>><a href="bbq.php">Gauges</a></li>
      <li<?=$btnActive[5]?>><a href="line.php">Graphs</a></li>
  <?php endif; ?>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="#">Something else here</a></li>
          <li role="separator" class="divider"></li>
          <li class="dropdown-header">Nav header</li>
          <li><a href="#">Separated link</a></li>
          <li><a href="#">One more separated link</a></li>
        </ul>
      </li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
		<?php if ($_SESSION['auth'] == true) : ?>
			<form action="" method="GET">
			<input type="hidden" name="action" value="logout" />	
			<input type="submit" class="btn btn-default" value="Log Out" />
			</form>
		<?php else : ?>
				<form class="form-inline" action="" method="POST">
				<input name="username" type="text" class="form-control" placeholder="Username" size="15" maxlength="40" />
				<input name="password" type="password" class="form-control" placeholder="Password" size="15" maxlength="40" />
				<input type="submit" class="btn btn-default" value="Login" />
				</form>
		<?php endif; ?>
    </ul>
  </div><!--/.nav-collapse -->
</div><!--/.container-fluid -->
</nav>
