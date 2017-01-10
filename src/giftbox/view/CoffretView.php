<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 06/01/17
 * Time: 15:49
 */

namespace giftbox\view;


class CoffretView
{
    private $data;
    private $app;

    public function __construct($app = null, $array)
    {
        $this->app = $app;
        $this->data = $array;
    }
    private function gererCoffret(){
        return "gestion coffret";
    }
    private function afficherCoffret(){
        $contenu = "<h1>Contenu de votre coffret</h1>";
        $uri = $this->app->request->getRootUri();
        foreach ($this->data[0]->prestationsCoffret() as $contenuCoffret){
            $d = $contenuCoffret->prestation();
            $contenu .= '<h2><u>Prestation</u> : ' . $d->nom . '</h2>';
            $contenu .= '<p><img class="prestaImg" src="' . $uri . '/web/img/' . $d->img . '"></p>';
            $contenu .= '<p>' . $d->descr . '</p>';
        }
        $contenu.= '<div class="message"><h2>Message de'.$this->data[0]->nom.' '.$this->data[0]->prenom.' : </h2>'.$this->data[0]->message.'</div>';
        return $contenu;
    }
    public function render($aff){
        switch ($aff){
            case 'gestion_coffret':
                $content = $this->gererCoffret();
                return $content;
                break;
            case 'coffret':
                $content = $this->afficherCoffret();
                return $content;
                break;
            default:
                return "";
                break;
        }
    }
}