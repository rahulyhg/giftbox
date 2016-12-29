<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 29/12/16
 * Time: 11:06
 */

namespace giftbox\view;


class CatView
{
    private $data;
    public function __construct($array)
    {
        $this->data = $array;
    }

    private function listeCategories(){
        $contenu = "";
        foreach ($this->data as $d){
            $contenu = $contenu."<h2>".$d->nom."</h2><a href=\"/projet_giftbox/categories/${d['id']}\">voir les prestations</a>";
        }
        return $contenu;
    }
    private function categoriePrest(){
        $contenu = "";
        $categorie = $this->data[0];
        foreach ($categorie->prestations()->get() as $d){
            $nom = $categorie->nom;
            $contenu = $contenu."<h2>Prestation : ".$d['nom']."</h2><h3>Categorie : <a href=\"/projet_giftbox/categories/${d['cat_id']}\">".$nom."</a></h3>".$d['descr']."<br><b>Prix : </b>".$d['prix']."<br><a href=\"/projet_giftbox/prestations/${d['id']}\">voir plus</a><br>";
        }

        return $contenu;
    }

    public function render($aff){
        ob_start();

        switch ($aff){
            case 1:
                $content = $this->listeCategories();
                break;
            case 2:
                $content = $this->categoriePrest();
                break;
            default:
                $content = "contenu inexistant";
                break;
        }
        include 'htmlCode.php';
        ob_end_flush();
    }
}