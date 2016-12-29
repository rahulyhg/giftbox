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

$app->get('/',function(){

    ob_start();
    $content = "<h1>home</h1>";
    include "src/giftbox/view/htmlCode.php";
    ob_end_flush();
});

$app->get('/prestations/', function(){
    $liste = \giftbox\models\Prestation::all();
    $vue = new \giftbox\view\PrestaView($liste->toArray());
    $html = new \giftbox\view\htmlView($vue->render(1));
    $html->render();

});
$app->get('/prestations/:id', function($id){
    $prestation = \giftbox\models\Prestation::find($id);
    $vue = new \giftbox\view\PrestaView([$prestation]);
    $vue->render(2);
});

$app->get('/categories/', function(){
    $categories = \giftbox\models\Categorie::all();
    $vue = new \giftbox\view\CatView($categories);
    $vue->render(1);
});
$app->get('/categories/:categorie', function($categorie){
    $cat = \giftbox\models\Categorie::find($categorie);
    $vue = new \giftbox\view\CatView([$cat]);
    $vue->render(2);
});




$app->run();