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
            $contenu = $contenu."<h2>Prestation : ".$d['nom']."</h2><h3>Categorie : <a href=\"/cours/TD13/prestations/categories/${d['cat_id']}\">".$nom."</a></h3>".$d['descr']."<br><b>Prix : </b>".$d['prix']."<br><a href=\"/cours/TD13/prestations/${d['id']}\">voir plus</a><br>";
        }
        return $contenu;
    }
    private function categoriePrest(){
        $contenu = "";
        $categorie = $this->data[0];
        $contenu = $contenu."<h2>Categorie : ".$categorie->nom."</h2><h3>Prestations : </h3>";
        foreach ($categorie->prestations()->get() as $presta ){
            $contenu = $contenu."<h4>".$presta->nom."</h4>".$presta->descr."<br><b>Prix : </b>".$presta->prix."<br>";
        }
        return $contenu;
    }
    private function prestation(){
        $contenu = "<h2>Prestation : ".$this->data[0]['nom']."</h2>".$this->data[0]['descr']."<br>Prix : <b></b>".$this->data[0]['prix']."<br><a href=\"/cours/TD13/prestations/\">liste des prestations</a>";
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
            case 3:
                $content = $this->categoriePrest();
                break;
            default:
                $content = "contenu inexistant";
                break;
        }
        $html = "
            <!DOCTYPE html>
            <html>
                <head></head>
                <body>
                    <content>$content</content>
                </body>
            </html>
            
            
        ";
        echo $html;
    }

}