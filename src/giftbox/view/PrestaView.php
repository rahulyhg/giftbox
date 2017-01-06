<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 28/12/16
 * Time: 15:50
 */

namespace giftbox\view;

use giftbox\models\Categorie;

class PrestaView
{
    
    private $data;
    private $app;

    public function __construct($app = null, $array)
    {
        $this->app = $app;
        $this->data = $array;

    }

    private function listePrestations(){
        $uri = $this->app->request->getRootUri();
        $contenu = '<a href="' . $this->app->urlFor('prestations', ['order' => 'asc']) . '">croissant</a>&nbsp;&nbsp;';
        $contenu .= '<a href="' . $this->app->urlFor('prestations', ['order' => 'desc']) . '">decroissant</a>';
        foreach ($this->data as $d){
            $categorie = $d->categorie()->first()->nom;
            $contenu .= '<h2>Prestation : ' . $d->nom . '</h2>';
            $contenu .= '<p><img class="prestaImg" src="' . $uri . '/web/img/' . $d->img . '"></p>';
            $contenu .= '<h3>Categorie : <a href="' . $this->app->urlFor('categories.order', ['categorie'=>$d['cat_id'],'order' => 'asc']) . '">' . $categorie . '</a></h3>';
            $contenu .= '<p>' . $d->descr . '</p>';
            $contenu .= '<p>Prix : ' . $d->prix . '</p>';
            $contenu .= '<a href="' . $this->app->urlFor('ajouter', ['id' => $d->id]) . '"><img src="' . $uri . '/web/img/add.png" width="32" alt="Ajouter"></a>';
            $contenu .= '<p><a href="' . $this->app->urlFor('prestation', ['id' => $d->id]) . '">Voir plus &rarr;</a></p>';


        }
        return $contenu;
    }
    private function prestation(){
        $uri = $this->app->request->getRootUri();
        $contenu = '<h2>Prestation : ' . $this->data[0]->nom . '</h2>';
        $contenu .= '<p>' . $this->data[0]->descr . '</p>';
        $contenu .= '<p>Prix : ' . $this->data[0]->prix . '</p>';
        $contenu .= '<a href="' . $this->app->urlFor('ajouter', ['id' => $this->data[0]->id]) . '"><img src="' . $uri . '/web/img/add.png" width="32" alt="Ajouter"></a>';
        $contenu .= '<p><a href="' . $this->app->urlFor('prestations', ['order' => 'asc']) . '">Liste des prestations</a></p>';
        return $contenu;
    }
    public function render($aff){

        switch ($aff){
            case 1:
                $content = $this->listePrestations();
                break;
            case 2:
                $content = $this->prestation();
                break;
            default:
                $content = "contenu inexistant";
                break;
        }

        return $content;
    }

}