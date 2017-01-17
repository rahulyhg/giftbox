<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 06/01/17
 * Time: 15:49
 */

namespace giftbox\view;


use giftbox\models\Coffret;
use giftbox\models\CoffretContenu;

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
        $contenu = "";
        if(!empty($_SESSION['coffret_edit'])){
            if(!empty($_SESSION['coffret_edit'])){
                $uri = $this->app->request->getRootUri();
                $coffret = $this->data[0];
                $prestations = $coffret->prestationsCoffret();


                $contenu.= "<h1>Contenu du coffret</h1>";
                $contenu.="<table>";
                $contenu.="<thead>";
                $contenu.="<tr>";
                $contenu.="<th>Nom</th>";
                $contenu.="<th>Quantité</th>";
                $contenu.="<th>Actions</th>";
                $contenu.="</tr>";
                $contenu.="</thead>";
                $contenu.="<tbody>";
                foreach ($prestations as $p){
                    $contenu.="<tr>";
                    $contenu.="<td>".$p->prestation()->nom."</td>";
                    $contenu.="<td>".$p->qua."</td>";
                    $contenu .= '<td>';
                    $contenu .= '<a href="' . $this->app->urlFor('coffret.ajouter', ['idPresta' => $p->prestation()->id,'urlGestion'=>$coffret->urlGestion]) . '"><img src="' . $uri . '/web/img/add.png" width="32" alt="Ajouter"></a>';
                    $contenu .= '<a href="' . $this->app->urlFor('coffret.supprimer', ['idPresta' => $p->prestation()->id ,'urlGestion'=>$coffret->urlGestion]) . '"><img src="' . $uri . '/web/img/trash.png" width="32" alt="Supprimer"></a>';
                    $contenu .= '</td>';
                    $contenu.="</tr>";
                }
                $contenu.="</tbody>";
                $contenu.="</table>";
                $contenu.= "<h2>Statut du coffret : " . $coffret->statut . "</h2>";
                $contenu.= '<a href="'.$this->app->urlFor('coffret_disconnect').'">deconnexion</a>';
            }
        }else{
            $contenu .= '<form action="' . $this->app->urlFor('coffret_connect', ["url"=>$this->data[0]->urlGestion]) . '" method="post">';
            $contenu .= '<label for="password">Mot de passe du coffret :</label>';
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

    public function ajouter(){
        $prestation = $this->data[0];
        $urlGestion = $this->data[1];
        $idCoffret = Coffret::where('urlGestion', '=', $urlGestion)->first()->id;
        $qua = CoffretContenu::where('coffret_id', '=', $idCoffret)->where('prestation_id', '=', $prestation->id)->first()->qua;

        $qua+=1;

        CoffretContenu::where('coffret_id', '=', $idCoffret)->where('prestation_id', '=', $prestation->id)->update(array('qua'=>$qua));

        $this->app->response->redirect($this->app->urlFor('coffret_ges', ['url' => $urlGestion]),200);
    }
    public function supprimer(){
        $prestation = $this->data[0];
        $urlGestion = $this->data[1];
        $idCoffret = Coffret::where('urlGestion', '=', $urlGestion)->first()->id;

        $qua = CoffretContenu::where('coffret_id', '=', $idCoffret)->where('prestation_id', '=', $prestation->id)->first()->qua;
        if($qua === 1){
            CoffretContenu::where('coffret_id', '=', $idCoffret)->where('prestation_id', '=', $prestation->id)->delete();
        }else{
            $qua-=1;
            CoffretContenu::where('coffret_id', '=', $idCoffret)->where('prestation_id', '=', $prestation->id)->update(array('qua'=>$qua));
        }
        $this->app->response->redirect($this->app->urlFor('coffret_ges', ['url' => $urlGestion]),200);
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
            case "add":
                $this->ajouter();
                break;
            case "del":
                $this->supprimer();
                break;
            default:
                return "";
                break;
        }
    }
}