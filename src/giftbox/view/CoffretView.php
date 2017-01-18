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
        if(isset($this->data[0]->urlGestion)){
            $contenu = "";
            if(!empty($_SESSION['coffret_edit'])){

                $uri = $this->app->request->getRootUri();
                $coffret = $this->data[0];
                $prestations = $coffret->prestationsCoffret();


                $contenu = '<div class="page-header">';
                $contenu .= '<h1>Contenu du coffret</h1>';
                $contenu .= '</div>';
                $contenu .="<table class='table table-bordered table-striped'>";
                $contenu .="<thead>";
                $contenu .="<tr>";
                $contenu .="<th>Nom</th>";
                $contenu .="<th>Quantité</th>";
                $contenu .="<th>Actions</th>";
                $contenu .="</tr>";
                $contenu .="</thead>";
                $contenu .="<tbody>";
                foreach ($prestations as $p){
                    $contenu .="<tr>";
                    $contenu .="<td>".$p->prestation()->nom."</td>";
                    $contenu .="<td>".$p->qua."</td>";
                    $contenu .= '<td>';
                    $contenu .= '<a href="' . $this->app->urlFor('coffret.ajouter', ['idPresta' => $p->prestation()->id,'urlGestion'=>$coffret->urlGestion]) . '"><span class="sign glyphicon glyphicon-plus-sign" aria-hidden="true"></span></a>';
                    $contenu .= '<a href="' . $this->app->urlFor('coffret.supprimer', ['idPresta' => $p->prestation()->id ,'urlGestion'=>$coffret->urlGestion]) . '"><span class="sign glyphicon glyphicon-minus-sign" aria-hidden="true"></span> </a>';
                    $contenu .= '</td>';
                    $contenu .="</tr>";
                }
                $contenu.="</tbody>";
                $contenu.="</table>";
                $contenu.= "<h2>Statut du coffret : " . $coffret->destinataire . " - " . $coffret->statut . "</h2>";
                $contenu.= '<p><a class="btn btn-success btn-lg" href="'.$this->app->urlFor('coffret_disconnect').'">deconnexion</a></p>';

            }else{
                $contenu .= '<form action="' . $this->app->urlFor('coffret_connect', ["url"=>$this->data[0]->urlGestion]) . '" method="post">';
                $contenu .="<div class='form-group'>";
                $contenu .= '<label class="col-md-12" for="password">Mot de passe du coffret </label>';
                $contenu .= '<input type="password" name="password" id="password" required class="form-control" placeholder="Password">';
                $contenu .="</div>";
                $contenu .= '<button class="btn btn-info" name="Se connecter" value="Se Connecter">Se connecter</button>';
                $contenu .= '</form>';
            }
            return $contenu;
        }else{
            $this->app->flash('danger','Url incorrect, veuillez utiliser l\'url de gestion fournis lors de la commande !');
            $this->app->redirect($this->app->urlFor('index'));
        }

    }

    private function connect(){
        $post = $this->app->request->post();
        if(!empty($post['password'])){
            $password = filter_var($post['password'], FILTER_SANITIZE_STRING);
            if(password_verify($password, $this->data[0]->password)){
                $_SESSION['coffret_edit'] = "allowed";
                $this->app->flash('success', 'Connexion réussie !');
            }else{
                $this->app->flash('danger', 'Connexion impossible, mot de passe incorrect !');
            }
        }else{
            $this->app->flash('danger', 'Veuillez entrer un mot de passe valide !');
        }

        $this->app->redirect($this->app->urlFor('coffret_ges', ['url'=>$this->data[0]->urlGestion]));

        return null;
    }

    private function disconnect(){
        if(isset($_SESSION['coffret_edit'])){
            unset($_SESSION['coffret_edit']);
            $this->app->flash('success', 'Déconnexion réussie !');
            $this->app->redirect($this->app->urlFor('index'));
        }else{
            $this->app->flashNow('danger', 'Une erreur s\'est produite !');
            $this->app->redirect($this->app->urlFor('index'));
        }
    }

    private function afficherCoffret(){
        $uri = $this->app->request->getRootUri();

        $contenu = '<div class="container">';
        $contenu .= '<div class="row">';
        $contenu .= "<h1 class='h1 col-md-12 text-center'>Contenu de votre coffret</h1>";
        $contenu .= '</div>';

        $contenu .= '<div class="row">';
        $contenu .= '<table class="table">';
        if ($this->data[0]->statut == 'payé') {
            if (empty($this->data[0]->destinataire)) {
                $this->data[0]->update(array('destinataire' => 'ouvert'));
            }
            foreach ($this->data[0]->prestationsCoffret() as $contenuCoffret){
                $d = $contenuCoffret->prestation();
                $contenu .= '<tr>';
                $contenu .= '<td class="h4 vert-align text-center"><u>Prestation</u> : ' . $d->nom . '</td>';
                $contenu .= '<td><img class="img-responsive" width="120em" src="' . $uri . '/web/img/' . $d->img . '"></td>';
                $contenu .= '<td class="vert-align text-center lead">' . $d->descr . '</td>';
                $contenu .= '</tr>';
            }
            if ($this->data[0]->destinataire != 'récupéré') {
                $contenu .= '<tfoot>';
                $contenu .= '<tr>';
                $contenu .= '<td colspan="3">';
                $contenu .= '<a class="btn btn-primary" href="' . $this->app->urlFor('coffret.recuperer', ['id' => $this->data[0]->id]) . '">Récupérer le coffret</a>';
                $contenu .= '</td>';
                $contenu .= '</tr>';
                $contenu .= '</tfoot>';
            }
            $contenu .= '</table>';
            $contenu .= '</div>';
            $contenu .= '<div class="row">';
            $contenu .= '<div class="col-md-12"><div class="h4 text-center">Message de <u class="lead">'.$this->data[0]->prenom.' '.$this->data[0]->nom.'</u> : </div><div class="text-center lead">'.$this->data[0]->message.'</div>';
            $contenu .= '</div>';
            $contenu .= '</div>';

            $contenu .= '</div>';   
        } else {
            $this->app->flash('info', 'Impossible de voir le contenu du coffret car il n\'est pas encore payé !');
            $this->app->redirect($this->app->urlFor('index'));
        }
        return $contenu;
    }



    private function ajouter(){
        $prestation = $this->data[0];
        $urlGestion = $this->data[1];
        $coffret = Coffret::where('urlGestion', '=', $urlGestion)->first();


        $qua = CoffretContenu::where('coffret_id', '=', $coffret->id)->where('prestation_id', '=', $prestation->id)->first()->qua;

        $qua+=1;

        CoffretContenu::where('coffret_id', '=', $coffret->id)->where('prestation_id', '=', $prestation->id)->update(array('qua'=>$qua));

        $total = 0;
        foreach ($coffret->prestationsCoffret() as $contenu){
            $d = $contenu->prestation();
            $total+= $d->prix * $contenu->qua;
        }

        if($total !== $coffret->montant){
            $coffret->montant = $total;
            $coffret->save();
        }

        $this->app->flash('success', 'Ajout réussi !');

        $this->app->redirect($this->app->urlFor('coffret_ges', ['url' => $urlGestion]));
    }
    private function supprimer(){
        $prestation = $this->data[0];
        $urlGestion = $this->data[1];
        $coffret = Coffret::where('urlGestion', '=', $urlGestion)->first();

        $qua = CoffretContenu::where('coffret_id', '=', $coffret->id)->where('prestation_id', '=', $prestation->id)->first()->qua;
        if($qua === 1){
            CoffretContenu::where('coffret_id', '=', $coffret->id)->where('prestation_id', '=', $prestation->id)->delete();
        }else{
            $qua-=1;
            CoffretContenu::where('coffret_id', '=', $coffret->id)->where('prestation_id', '=', $prestation->id)->update(array('qua'=>$qua));
        }

        $total = 0;
        foreach ($coffret->prestationsCoffret() as $contenu){
            $d = $contenu->prestation();
            $total+= $d->prix * $contenu->qua;
        }

        if($total !== $coffret->montant){
            $coffret->montant = $total;
            $coffret->save();
        }
        $this->app->flash('success', 'Suppression réussie !');
        $this->app->redirect($this->app->urlFor('coffret_ges', ['url' => $urlGestion]));
    }

    private function recuperer() {
        $coffret = Coffret::where('id', '=', $this->data)->first();

        if (!is_null($coffret)) {
            $coffret->update(array('destinataire' => 'récupéré'));
            $this->app->flash('success', 'Vous venez de récupérer votre coffret.');
            $this->app->redirect($this->app->urlFor('coffret', ['url' => $coffret->url]));
        } else {
            $this->app->flash('danger', 'Le coffret n\'existe pas !');
            $this->app->redirect($this->app->urlFor('index'));
        }
        return null;
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
            case 'recuperer':
                $content = $this->recuperer();
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