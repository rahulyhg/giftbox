<?php

namespace giftbox\view;


use giftbox\models\Prestation;

class CatView
{
    private $data, $order;
    public function __construct($array, $ord = null)
    {
        $this->data = $array;
        $this->order = $ord;
    }

    private function listeCategories(){
        $contenu = "";
        foreach ($this->data as $d){
            $contenu .= '<h2>' . $d->nom . '</h2>';
            $contenu .= '<a href="/projet_giftbox/categories/' . $d['id'] . '/asc">voir les prestations</a>';
        }
        return $contenu;
    }
    private function categoriePrest(){
        $categorie = $this->data[0];
        if ($this->order == "desc") {
            $prestations = new PrestaView($categorie->prestations()->get()->sortByDesc('prix'));
        } else {
            $prestations = new PrestaView($categorie->prestations()->get()->sortBy('prix'));
        }
        $contenu = $prestations->render(1);
        return $contenu;
    }

    public function render($aff){
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

        return $content;
    }
}