<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 12/12/16
 * Time: 16:34
 */

session_start();

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
    if ($order == "desc"){
        $liste = \giftbox\models\Prestation::all()->sortByDesc('prix');
    }else{
        $liste = \giftbox\models\Prestation::all()->sortBy('prix');
    }
    $vue = new \giftbox\view\PrestaView($liste->toArray());
    $html = new \giftbox\view\htmlView($vue->render(1));
    $html->render();
});

$app->get('/prestations/:id', function($id){
   $prestation = \giftbox\models\Prestation::find($id);
    $vue = new \giftbox\view\PrestaView([$prestation]);
    $html = new \giftbox\view\htmlView($vue->render(2));
    $html->render();
});

$app->get('/categories/', function(){
    $categories = \giftbox\models\Categorie::all();
    $vue = new \giftbox\view\CatView($categories);
    $html = new \giftbox\view\htmlView($vue->render(1));
    $html->render();
});

$app->get('/categories/:categorie/:order', function($categorie, $order){
    $cat = \giftbox\models\Categorie::find($categorie);
    $vue = new \giftbox\view\CatView([$cat], $order);
    $html = new \giftbox\view\htmlView($vue->render(2));
    $html->render();
});

$app->get('/prestation/add/:id', function($id) {
    $vue = new \giftbox\view\PanierView([$id]);
    $html = new \giftbox\view\htmlView($vue->render('add'));
    $html->render();
});

$app->get('/panier', function() {
    $vue = new \giftbox\view\PanierView();
    $html = new \giftbox\view\htmlView($vue->render('panier'));
    $html->render();
});

$app->get('/prestation/delete/:id', function($id) {
    $vue = new \giftbox\view\PanierView([$id]);
    $html = new \giftbox\view\htmlView($vue->render('remove'));
    $html->render();
});

$app->get('/panier/save', function() {

});

$app->run();
