<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 12/12/16
 * Time: 16:34
 */

session_start();
define('BASE_URL', dirname($_SERVER['SCRIPT_NAME']));

require 'vendor/autoload.php';

\giftbox\Factory\ConnectionFactory::setConfig('src/giftbox/conf/conf.ini');
\giftbox\Factory\ConnectionFactory::makeConnection();

$app = new \Slim\Slim();

$app->get('/',function(){
    ob_start();
    $content = "<h1>home</h1>";
    include "src/giftbox/view/htmlCode.php";
    ob_end_flush();
});

$app->get('/prestations/all/:order', function($order){
    $controller = new \giftbox\controller\PrestationsController('index', $order);
});

$app->get('/prestations/:id', function($id){
    $controller = new \giftbox\controller\PrestationsController('view', $id);
});

$app->get('/categories/', function(){
    $controller = new \giftbox\controller\CategoriesController('index');
});

$app->get('/categories/:categorie/:order', function($categorie, $order){
    $controller = new \giftbox\controller\CategoriesController('view', ['id' => $categorie, 'order' => $order]);
});

$app->get('/prestation/add/:id', function($id) {
    $controller = new \giftbox\controller\PrestationsController('add', $id);
});

$app->get('/panier', function() {
    $controller = new \giftbox\controller\PanierController('index');
});

$app->get('/panier/delete/:id', function($id) {
    $controller = new \giftbox\controller\PanierController('delete', $id);
});

$app->get('/panier/save', function() {
    $controller = new \giftbox\controller\PanierController('save');
});

$app->run();
