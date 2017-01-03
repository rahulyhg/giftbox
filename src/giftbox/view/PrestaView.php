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
    public function __construct($array)
    {
        $this->data = $array;
    }

    private function listePrestations(){
        if(strpos($_SERVER['REQUEST_URI'], "asc")){
            $url = str_replace("asc", "", $_SERVER['REQUEST_URI']);
        }elseif (strpos($_SERVER['REQUEST_URI'], "desc")){
            $url = str_replace("desc", "", $_SERVER['REQUEST_URI']);
        }
        $contenu = '<a href="' . $url . 'asc">croissant</a>&nbsp;&nbsp;';
        $contenu .= '<a href="' . $url . 'desc">decroissant</a>';
        foreach ($this->data as $d){
            $nom = Categorie::find($d['cat_id'])->nom;
            $contenu .= '<h2>Prestation : ' . $d['nom'] . '</h2>';
            $contenu .= '<p><img class="prestaImg" src="/projet_giftbox/web/img/' . $d['img'] . '"></p>';
            $contenu .= '<h3>Categorie : <a href="/projet_giftbox/categories/' . $d['cat_id'] . '/asc">' . $nom . '</a></h3>';
            $contenu .= '<p>' . $d['descr'] . '</p>';
            $contenu .= '<p>Prix : ' . $d['prix'] . '</p>';
            $contenu .= '<p><a href="/projet_giftbox/prestations/' . $d['id'] . '">Voir plus &rarr;</a></p>';
        }
        return $contenu;
    }
    private function prestation(){
        $contenu = '<h2>Prestation : ' . $this->data[0]['nom'] . '</h2>';
        $contenu .= '<p>' . $this->data[0]['descr'] . '</p>';
        $contenu .= '<p>Prix : ' . $this->data[0]['prix'] . '</p>';
        $contenu .= '<a href="/projet_giftbox/prestations/all/asc">liste des prestations</a>';
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