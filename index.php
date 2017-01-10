<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 12/12/16
 * Time: 16:34
 */

require 'vendor/autoload.php';

\giftbox\Factory\ConnectionFactory::setConfig('src/giftbox/conf/conf.ini');
\giftbox\Factory\ConnectionFactory::makeConnection();

$app = new \Slim\Slim();

$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'slim_session',
    'secret' => 'CHANGE_ME',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));

$app->get('/',function(){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\PrestaView($app);
	$html = new \giftbox\view\htmlView($vue->render('index'));
	$html->render();
})->name('index');

$app->get('/prestations/all/:order', function($order){
	$app = \Slim\Slim::getInstance();
	if ($order == "desc"){
		$liste = \giftbox\models\Prestation::all()->sortByDesc('prix');
	}else{
		$liste = \giftbox\models\Prestation::all()->sortBy('prix');
	}
    $vue = new \giftbox\view\PrestaView($app, $liste);
	$html = new \giftbox\view\htmlView($vue->render(1));
	$html->render();
})->name('prestations');

$app->get('/prestations/:id', function($id){
	$app = \Slim\Slim::getInstance();
	$prestation = \giftbox\models\Prestation::find($id);
	$vue = new \giftbox\view\PrestaView($app, [$prestation]);
	$html = new \giftbox\view\htmlView($vue->render(2));
	$html->render();
})->name('prestation');

$app->get('/categories', function(){
	$app = \Slim\Slim::getInstance();
	$categories = \giftbox\models\Categorie::all();
	$vue = new \giftbox\view\CatView($app, $categories);
	$html = new \giftbox\view\htmlView($vue->render(1));
	$html->render();
})->name('categories');

$app->get('/categories/:categorie/:order', function($categorie, $order){
	$app = \Slim\Slim::getInstance();
	$cat = \giftbox\models\Categorie::find($categorie);
	$vue = new \giftbox\view\CatView($app, [$cat], $order);
	$html = new \giftbox\view\htmlView($vue->render(2));
	$html->render();
})->name('categories.order');

$app->get('/prestation/add/:id', function($id) {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\PanierView($app, [$id]);
	$html = new \giftbox\view\htmlView($vue->render('add'));
	$html->render();
})->name('ajouter');

$app->get('/prestation/delete/:id', function($id) {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\PanierView($app, [$id]);
	$html = new \giftbox\view\htmlView($vue->render('remove'));
	$html->render();
})->name('supprimer');

$app->get('/panier', function() {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\PanierView($app);
	$html = new \giftbox\view\htmlView($vue->render('panier'));
	$html->render();
})->name('panier');


$app->get('/panier/informations', function() {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\PanierView($app);
	$html = new \giftbox\view\htmlView($vue->render('infos'));
	$html->render();
})->name('informations');

$app->post('/panier/validation', function() {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\PanierView($app);
	$html = new \giftbox\view\htmlView($vue->render('validation'));
	$html->render();
})->name('validation');

$app->get('/panier/save', function() {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\PanierView($app);
	$html = new \giftbox\view\htmlView($vue->render('save'));
	$html->render();
})->name('save');

$app->get('/coffret/edit/:url', function($url){
    $app = \Slim\Slim::getInstance();
    $coffret = \giftbox\models\Coffret::where('urlGestion', '=', $url)->first();
    $vue = new \giftbox\view\CoffretView($app, [$coffret]);
    $html = new giftbox\view\htmlView($vue->render('gestion_coffret'));
    $html->render();
})->name('coffret_ges');

$app->get('/coffret/:url', function($url){
    $app = \Slim\Slim::getInstance();
    $coffret = \giftbox\models\Coffret::where('url', '=', $url)->first();
    $vue = new \giftbox\view\CoffretView($app, [$coffret]);
    $html = new giftbox\view\htmlView($vue->render('coffret'));
    $html->render();
})->name('coffret');

$app->get('/note/:id/:note', function($id, $note){
    $app = \Slim\Slim::getInstance();
    $vue = new \giftbox\view\PrestaView($app, [$id, $note]);
    $html = new giftbox\view\htmlView($vue->render('note'));
    $html->render();
})->name('notation');

$app->run();
