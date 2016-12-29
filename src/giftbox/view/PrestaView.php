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
        $contenu = "";
        foreach ($this->data as $d){
            $nom = Categorie::find($d['cat_id'])->nom;
            $contenu = $contenu."<h2>Prestation : ".$d['nom']."</h2><h3>Categorie : <a href=\"/projet_giftbox/categories/${d['cat_id']}\">".$nom."</a></h3>".$d['descr']."<br><b>Prix : </b>".$d['prix']."<br><a href=\"/projet_giftbox/prestations/${d['id']}\">voir plus</a><br>";
        }
        return $contenu;
    }
    private function prestation(){
        $contenu = "<h2>Prestation : ".$this->data[0]['nom']."</h2>".$this->data[0]['descr']."<br>Prix : <b></b>".$this->data[0]['prix']."<br><a href=\"/projet_giftbox/prestations/\">liste des prestations</a>";
        return $contenu;
    }
    public function render($aff){
        ob_start();

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

        include 'htmlCode.php';
        ob_end_flush();
    }

}