<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 06/01/17
 * Time: 15:49
 */

namespace giftbox\view;


use giftbox\models\Coffret;

class CoffretView
{
    private $data;
    private $app;

    public function __construct($app = null, $array = null)
    {
        $this->app = $app;
        $this->data = $array;
    }
    private function gererCoffret(){
        // gerer la connexion grace au mdp du coffret
        if(!empty($_SESSION['coffret_edit'])){
            if($_SESSION['coffret_edit'] === "allowed"){
                $contenu = "<h1>Gestion du coffret</h1>";
                $contenu.= "<h2>Statut du coffret : " . $this->data[0]->statut . "</h2>";
                $contenu.= '<a href="'.$this->app->urlFor('coffret_disconnect').'">deconnexion</a>';
            }
        }else{
            $contenu = '<form action="' . $this->app->urlFor('coffret_connect', ["url"=>$this->data[0]->urlGestion]) . '" method="post">';
            $contenu .= '<label for="password">Mot de passe du coffret </label>';
            $contenu .= '<input type="password" name="password" id="password" required>';
            $contenu .= '<button name="Se connecter" value="Se Connecter">Se connecter</button>';
            $contenu .= '</form>';
        }
        return $contenu;
    }

    private function connect(){
        $post = $this->app->request->post();
        if(!empty($post['password'])){
            $password = filter_var($post['password'], FILTER_SANITIZE_STRING);
            if(password_verify($password, $this->data[0]->password)){
                $_SESSION['coffret_edit'] = "allowed";
            }
        }
        $this->app->response->redirect($this->app->urlFor('coffret_ges', ['url'=>$this->data[0]->urlGestion]), 200);

        return null;
    }

    private function disconnect(){
        if(isset($_SESSION['coffret_edit'])){
            unset($_SESSION['coffret_edit']);
            $this->app->response->redirect($this->app->urlFor('index'),200);
        }
        return null;
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
            case "connect":
                $this->connect();
                break;
            case "disconnect":
                $this->disconnect();
                break;
            default:
                return "";
                break;
        }
    }
}