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
	'expires' => '60 minutes',
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
		$liste = \giftbox\models\Prestation::where('visible', '=', 1)->get()->sortByDesc('prix');
	}else{
		$liste = \giftbox\models\Prestation::where('visible', '=', 1)->get()->sortBy('prix');
	}
	$vue = new \giftbox\view\PrestaView($app, [$liste, $order]);
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

$app->get('/paiement', function() {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\PanierView($app);
	$html = new \giftbox\view\htmlView($vue->render('paiementForm'));
	$html->render();
})->name('paiement.form');

$app->post('/paiement/validation', function() {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\PanierView($app);
	$html = new \giftbox\view\htmlView($vue->render('paiementValidation'));
	$html->render();
})->name('paiement.validation');

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

$app->get('/coffret/disconnect/true', function(){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\CoffretView($app);
	$html = new giftbox\view\htmlView($vue->render('disconnect'));
	$html->render();
})->name('coffret_disconnect');

$app->post('/coffret/edit/connect/:url', function($url){
	$app = \Slim\Slim::getInstance();
	$coffret = \giftbox\models\Coffret::where('urlGestion', '=', $url)->first();
	$vue = new \giftbox\view\CoffretView($app, [$coffret]);
	$html = new \giftbox\view\htmlView($vue->render('connect'));
	$html->render();
})->name('coffret_connect');

$app->get('/coffret/edit/add/:idPresta/:urlGestion', function($idPresta, $urlGestion){
	$app = \Slim\Slim::getInstance();
	$prestation = \giftbox\models\Prestation::where('id', '=', $idPresta)->first();
	$data = [$prestation, $urlGestion];
	$vue = new \giftbox\view\CoffretView($app, $data);
	$html = new \giftbox\view\htmlView($vue->render('add'));
	$html->render();
})->name('coffret.ajouter');

$app->get('/coffret/edit/del/:idPresta/:urlGestion', function($idPresta, $urlGestion){
	$app = \Slim\Slim::getInstance();
	$prestation = \giftbox\models\Prestation::where('id', '=', $idPresta)->first();
	$data = [$prestation, $urlGestion];
	$vue = new \giftbox\view\CoffretView($app, $data);
	$html = new \giftbox\view\htmlView($vue->render('del'));
	$html->render();
})->name('coffret.supprimer');

$app->get('/cagnotte/cloturer/:url', function($url) {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\CagnotteView($app, $url);
	$html = new \giftbox\view\htmlView($vue->render('cloturer'));
	$html->render();
})->name('cagnotte.cloturer');

$app->get('/cagnotte/connexion/:url', function($url) {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\CagnotteView($app, $url);
	$html = new giftbox\view\htmlView($vue->render('connexionForm'));
	$html->render();
})->name('cagnotte.connexionForm');

$app->post('/cagnotte/connexion/:url', function($url) {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\CagnotteView($app, $url);
	$html = new \giftbox\view\htmlView($vue->render('connexion'));
	$html->render();
})->name('cagnotte.connexion');

$app->get('/cagnotte/participer/:url', function($url) {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\CagnotteView($app, $url);
	$html = new \giftbox\view\htmlView($vue->render('participerForm'));
	$html->render();
})->name('cagnotte.participationForm');

$app->post('/cagnotte/participer/:url', function($url) {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\CagnotteView($app, $url);
	$html = new \giftbox\view\htmlView($vue->render('participer'));
	$html->render();
})->name('cagnotte.participation');

$app->get('/cagnotte/gestion/:url', function($url) {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\CagnotteView($app, $url);
	$html = new \giftbox\view\htmlView($vue->render('gestion'));
	$html->render();
})->name('cagnotte.gestion');

$app->get('/note/:id/:note', function($id, $note){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\PrestaView($app, [$id, $note]);
	$html = new giftbox\view\htmlView($vue->render('note'));
	$html->render();
})->name('notation');

$app->get('/administration', function(){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\AdministrationView($app);
	$html = new giftbox\view\htmlView($vue->render('index'));
	$html->render();
})->name('administration');

$app->get('/administration/deconnexion', function(){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\AdministrationView($app);
	$html = new giftbox\view\htmlView($vue->render('deconnexion'));
	$html->render();
})->name('deconnexion');

$app->post('/administration/connexion', function() {
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\AdministrationView($app);
	$html = new giftbox\view\htmlView($vue->render('connexion'));
	$html->render();
})->name('administration.connexion');

$app->get('/administration/prestations', function(){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\AdministrationView($app);
	$html = new giftbox\view\htmlView($vue->render('aprestations'));
	$html->render();
})->name('administration.prestations');

$app->get('/administration/prestation/cacher/:id', function($id){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\AdministrationView($app, [$id]);
	$html = new giftbox\view\htmlView($vue->render('cacher'));
	$html->render();
})->name('prestation.cacher');

$app->get('/administration/prestation/afficher/:id', function($id){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\AdministrationView($app, [$id]);
	$html = new giftbox\view\htmlView($vue->render('afficher'));
	$html->render();
})->name('prestation.afficher');

$app->get('/administration/prestation/supprimer/:id', function($id){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\AdministrationView($app, [$id]);
	$html = new giftbox\view\htmlView($vue->render('supprimer'));
	$html->render();
})->name('prestation.supprimer');

$app->get('/administration/prestation/ajouter', function(){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\AdministrationView($app);
	$html = new giftbox\view\htmlView($vue->render('ajouter'));
	$html->render();
})->name('prestation.ajouter');

$app->post('/administration/prestation/ajouts', function(){
	$app = \Slim\Slim::getInstance();
	$vue = new \giftbox\view\AdministrationView($app);
	$html = new giftbox\view\htmlView($vue->render('ajouterPrestation'));
	$html->render();
})->name('administration.prestation.ajouter');


$app->run();
