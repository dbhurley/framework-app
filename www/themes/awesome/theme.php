<html>
<head>
	<title>Awesome</title>
	<link href="<?php echo BASE_URL; ?>/assets/css/bootstrap.css" style="text/css" rel="stylesheet" />
	<link href="<?php echo BASE_URL; ?>/assets/css/bootstrap-responsive.css" style="text/css" rel="stylesheet" />
	<link href="<?php echo DEFAULT_THEME; ?>/css/theme.css" style="text/css" rel="stylesheet" />
</head>
<body>

	<div class="container-narrow">

      <div class="masthead">
        <ul class="nav nav-pills pull-right">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#">Docs</a></li>
          <li><a href="#">Questions</a></li>
        </ul>
        <h3 class="muted">Joomla! Framework</h3>
      </div>

      <hr>

      <div class="jumbotron">
        <h1>Congratulations!<br />It's Installed</h1>
        <p class="lead">You've now got the Joomla! Framework installed and you're ready to get started coding. If you have questions, be sure to check out the docs.</p>
        <a class="btn btn-large btn-success" href="#">Documentation</a>
      </div>

      <hr>

      <div class="row-fluid marketing">
        <div class="span6">
          <h4>Bootstrap</h4>
          <p>This install comes preloaded with the Twitter Bootstrap package pre-installed, symlinked and ready to use.</p>

          <h4>jQuery</h4>
          <p>This install comes preloaded with the latest jQuery packages pre-installed, symlinked and ready to use.</p>

          <h4>Twig</h4>
          <p>This install comes preloaded with Twig as the templating package pre-installed and ready to use.</p>
        </div>

        <div class="span6">
          <h4>Themes</h4>
          <p>This install has a sample theme called "Awesome". Yes, you're looking at it. Create your own theme and set as the default in config.json</p>

          <h4>Database</h4>
          <p>This install has a database setup included. (Be sure to checkout the config.json file)</p>

          <h4>Routing</h4>
          <p>This install uses a static routing file (routes.json) and also includes minor code to demo automatic routing.</p>
        </div>
      </div>

      <hr>

      <div class="footer">
        <p>&copy; Joomla! Framework 2013</p>
      </div>

    </div>

	<script src="<?php echo BASE_URL; ?>/assets/js/jquery.js"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/bootstrap.min.js"></script>
</body>
</html>