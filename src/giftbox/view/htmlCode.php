<?php

/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 29/12/16
 * Time: 10:49
 */

$directory = \Slim\Slim::getInstance()->urlFor('index');
$ressourceUri = \Slim\Slim::getInstance()->request->getResourceUri();
$flash = \Slim\Slim::getInstance()->flashData();
$flashMessage = '';
if ($flash != null) {
	$alertType = array_keys($flash)[0];
	$flashMessage .= '<div class="col-md-12">';
	$flashMessage .= '<div class="alert alert-' . $alertType . '">' . $flash[$alertType] . '</div>';
	$flashMessage .= '</div>';
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
	<link rel="icon" href="' . $directory . 'web/img/icon.ico" />
	<title>Giftbox</title>
	<link href="' . $directory . 'web/css/bootstrap.min.css" rel="stylesheet">
	<link href="' . $directory . 'web/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
	<link href="' . $directory . 'web/css/offcanvas.css" rel="stylesheet">
	<script src="' . $directory . 'web/js/ie-emulation-modes-warning.js"></script>
	<link href="' . $directory . 'web/css/style.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script src="' . $directory . 'web/js/bootstrap.min.js"></script>
	<script src="' . $directory . 'web/js/ie10-viewport-bug-workaround.js"></script>
	<script src="' . $directory . 'web/js/offcanvas.js"></script>
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
		  <a class="navbar-brand" href="' . $directory . '">Giftbox</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
		  <ul class="nav navbar-nav">
			<li' . (($ressourceUri == '/' || empty($ressourceUri)) ? ' class="active"' : '') . '><a href="' . $directory . '">Accueil</a></li>
			<li' . (strstr($ressourceUri, "prestations", true) ? ' class="active"' : '') . '><a href="' . $directory . 'prestations/all/asc">Prestations</a></li>
			<li' . (strstr($ressourceUri, "categories", true) ? ' class="active"' : '') . '><a href="' . $directory . 'categories">Catégories</a></li>
		  </ul>
		  <ul class="nav navbar-nav navbar-right">
			<a href="' . $directory . 'panier" class="btn btn-' . (strstr($ressourceUri, "administration", true) ? 'success' : 'warning') . ' navbar-btn">
				<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
				' . (isset($_SESSION['panier']) ? $_SESSION['panier']['qua'] : 0) . '</a>
			<a href="' . $directory . 'administration" class="btn btn-' . (strstr($ressourceUri, "administration", true) ? 'info' : 'primary') . '"><span class="glyphicon glyphicon-cog"></span> Administration</a>
			' . (isset($_SESSION['admin']) ? '<a href="' . $directory . 'administration/deconnexion"  class="btn btn-danger">Se deconnecter</a>' : '') . '
		  </ul>
		</div>
	  </div>
	</nav>

	<div class="container">

	  <div class="row row-offcanvas row-offcanvas-right">

		<div class="col-md-12">
		  <p class="pull-right visible-xs">
			<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
		  </p>
		  <div class="row">
			<div class="col-md-12">
				' . $flashMessage . '
				' . $content . '
			</div>
		  </div>
		</div>
	  </div>

	  <hr>

	  <footer>
		<p>&copy; 2017 IUT Nancy-Charlemagne | Département Informatique.</p>
		<p>Made with &hearts; by BOURRELY Thomas &amp; WILMOUTH Steven</p>
	  </footer>

	</div>
  </body>
</html>
';
