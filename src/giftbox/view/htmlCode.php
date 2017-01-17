<?php

/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 29/12/16
 * Time: 10:49
 */

$directory = \Slim\Slim::getInstance()->urlFor('index');
$flash = \Slim\Slim::getInstance()->flashData();
$flashMessage = '';
if ($flash != null) {
	$alertType = array_keys($flash)[0];
	$flashMessage = '<div class="alert alert-' . $alertType . '">' . $flash[$alertType] . '</div>';
}
echo '
<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="BOURRELY Thomas, WILMOUTH Steven">
	<title>Giftbox</title>
	<link href="' . $directory . 'web/css/bootstrap.min.css" rel="stylesheet">
	<link href="' . $directory . 'web/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
	<link href="' . $directory . 'web/css/offcanvas.css" rel="stylesheet">
	<script src="' . $directory . 'web/js/ie-emulation-modes-warning.js"></script>
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
  </head>

  <body>
	<nav class="navbar navbar-fixed-top navbar-inverse">
	  <div class="container">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="#">Project name</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
		  <ul class="nav navbar-nav">
			<li class="active"><a href="' . $directory . '">Accueil</a></li>
			<li><a href="' . $directory . 'prestations/all/asc">Prestations</a></li>
			<li><a href="' . $directory . 'categories">Catégories</a></li>
			<li><a href="' . $directory . 'administration">Administration</a>' . (isset($_SESSION['admin']) ? '&nbsp;|&nbsp;<a href="' . $directory . '/deconnexion">Se deconnecter</a>' : '') . '</li>
		  </ul>
		</div>
	  </div>
	</nav>

	<div class="container">

	  <div class="row row-offcanvas row-offcanvas-right">

		<div class="col-xs-12 col-sm-9">
		  <p class="pull-right visible-xs">
			<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
		  </p>
		  <div class="row">
			' . $flashMessage . '
			' . $content . '
		  </div>
		</div>
	  </div>

	  <hr>

	  <footer>
		<p>&copy; 2017 IUT Nancy-Charlemagne, Département Informatique.</p>
	  </footer>

	</div>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="' . $directory . 'web/js/bootstrap.min.js"></script>
	<script src="' . $directory . 'web/js/ie10-viewport-bug-workaround.js"></script>
	<script src="' . $directory . 'web/js/offcanvas.js"></script>
  </body>
</html>
';
