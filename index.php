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


$app->get('/prestations/', function(){
    $liste = \giftbox\models\Prestation::all();
    $vue = new \giftbox\view\PrestaView($liste->toArray());
    $vue->render(1);
});

/*
$app->get('/prestations/categories/', function(){
    $c = new \giftbox\models\Categorie();
    foreach ($c->categories() as $ca){
        echo $ca->nom."<br>";
    }
});
*/

$app->get('/prestations/:id', function($id){
    $prestation = \giftbox\models\Prestation::find($id);
    $vue = new \giftbox\view\PrestaView([$prestation]);
    $vue->render(2);
});

$app->get('/prestations/categories/:categorie', function($categorie){
    $cat = \giftbox\models\Categorie::find($categorie);
    $vue = new \giftbox\view\PrestaView([$cat]);
    $vue->render(3);
});




$app->run();