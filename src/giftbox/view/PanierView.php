<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 03/01/2017
 * Time: 17:44
 */

namespace giftbox\view;


use giftbox\models\Prestation;

class PanierView
{

    private $data;
    private $app;

    public function __construct($app = null, $array = null)
    {
        $this->app = $app;
        $this->data = $array;
    }

    private function panier() {
        $total = 0;
        $html = '<table>';
        $html .= '<caption>Article(s) : ' . (isset($_SESSION['panier']) ? $_SESSION['panier']['qua'] : '0') . '</caption>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Nom</th>';
        $html .= '<th>Quantité</th>';
        $html .= '<th>Prix</th>';
        $html .= '<th>Actions</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        if (isset($_SESSION['panier']) && $_SESSION['panier']['qua'] > 0) {
            $html .= '<tbody>';
            $uri = $this->app->request->getRootUri();
            foreach ($_SESSION['panier']['article'] as $article => $a) {
                $html .= '<tr>';
                $html .= '<td><a href="' . $this->app->urlFor('prestation', ['id' => $a['id']]) . '">' . $article . '</a></td>';
                $html .= '<td>' . $a['qua'] . '</td>';
                $html .= '<td>' . $a['prix'] . ' &euro;</td>';
                $html .= '<td>';
                $html .= '<a href="' . $this->app->urlFor('ajouter', ['id' => $a['id']]) . '"><img src="' . $uri . '/web/img/add.png" width="32" alt="Ajouter"></a>';
                $html .= '<a href="' . $this->app->urlFor('supprimer', ['id' => $a['id']]) . '"><img src="' . $uri . '/web/img/trash.png" width="32" alt="Supprimer"></a>';
                $html .= '</td>';
                $html .= '</tr>';
                $total = $total + $a['prix'];
            }
            $html .= '</tbody>';
        } else {
            $html .= '<tr class="alert alert-info"><td colspan="4">Panier vide</td></tr>';
        }
        $html .= '<tfoot>';
        $html .= '<tr><td colspan="3" style="text-align: right">Total:</td><td>' . $total . ' &euro;</td></tr>';
        $html .= '</tfoot>';
        $html .= '<table>';
        $html .= '<p><a href="' . $this->app->urlFor('informations') . '">Sauvegarder le coffret</a></p>';
        return $html;
    }

    private function add() {
        $html = '';
        $prestationId = $this->data[0];
        $prestation = Prestation::where('id', '=', $prestationId)->first();
        if (!empty($prestation)) {
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = array(
                    'qua' => 1,
                    'article' => array()
                );
                $_SESSION['panier']['article'][$prestation->nom] = array(
                    'id' => $prestation->id,
                    'qua' => 1,
                    'prix' => $prestation->prix
                );
            } else {
                $_SESSION['panier']['qua'] = ($_SESSION['panier']['qua'] + 1);
                if (isset($_SESSION['panier']['article'][$prestation->nom])) {
                    $_SESSION['panier']['article'][$prestation->nom]['qua'] = ($_SESSION['panier']['article'][$prestation->nom]['qua'] + 1);
                    $_SESSION['panier']['article'][$prestation->nom]['prix'] = ($_SESSION['panier']['article'][$prestation->nom]['prix'] + $prestation->prix);
                } else {
                    $_SESSION['panier']['article'][$prestation->nom] = array(
                        'id' => $prestation->id,
                        'qua' => 1,
                        'prix' => $prestation->prix
                    );
                }
            }
            $html = '<div class="alert alert-success">Prestation ajoutée au panier</div>';
        } else {
            $html = '<div class="alert alert-error">Impossible de trouver la prestation</div>';
        }
        return $html;
    }

    public function remove(){
        $html = '';
        $prestation = Prestation::where('id', '=', $this->data[0])->first();
        if (isset($_SESSION['panier']['article'][$prestation->nom])) {
            $_SESSION['panier']['qua'] = ($_SESSION['panier']['qua'] - 1);
            if ($_SESSION['panier']['article'][$prestation->nom]['qua'] == 1) {
                unset($_SESSION['panier']['article'][$prestation->nom]);
            } else {
                $_SESSION['panier']['article'][$prestation->nom]['qua'] = ($_SESSION['panier']['article'][$prestation->nom]['qua'] - 1);
                $_SESSION['panier']['article'][$prestation->nom]['prix'] = ($_SESSION['panier']['article'][$prestation->nom]['prix'] - $prestation->prix);
            }
            $html .= '<div class="alert alert-success">Prestation supprimée du panier</div>';
        } else {
            $html .= '<div class="alert alert-error">Whoops ! Des erreurs ont été rencontrées</div>';
        }
        return $html;
    }

    private function informations() {
        $formulaire = '<form id="formulaire" action="' . $this->app->urlFor('validation') . '" method="post">';
        $formulaire .= '<label for="nom">Nom : </label>';
        $formulaire .= '<input type="text" name="nom"id="nom" placeholder="Nom">';
        $formulaire .= '<label for="prenom">Prénom : </label>';
        $formulaire .= '<input type="text" name="prenom" id="prenom" placeholder="Prénom">';
        $formulaire .= '<label for="email">Email : </label>';
        $formulaire .= '<input type="email" name="email" id="email" placeholder="Email">';
        $formulaire .= '<label for="message">Message : </label>';
        $formulaire .= '<textarea name="message" id="message" cols="50" rows="5"></textarea>';
        $formulaire .= '<label for="password">Mot de passe : </label>';
        $formulaire .= '<input type="password" name="password" id="password" placeholder="Mot de passe">';
        $formulaire .= '<label for="password_repeat">Mot de passe (Vérif.) : </label>';
        $formulaire .= '<input type="password" name="password_repeat" id="password_repeat" placeholder="Mot de passe (Vérif.)">';
        $formulaire .= '<label for="paiement">Mode de paiement : </label>';
        $formulaire .= '<select name="paiement">';
        $formulaire .= '<option value="classique">Classique</value>';
        $formulaire .= '<option value="cagnotte">Cagnotte</value>';
        $formulaire .= '</select>';
        $formulaire .= '<button>Valider</button>';
        $formulaire .= '</form>';
        return $formulaire;
    }

    private function validation() {
        $errors = array();
        $contenu = '';
        
        $data = $this->app->request->post();

        if (!empty($errors)) {
            $contenu .= '<ul class="alert alert-error">';
            $contenu .= 'Whoops, des erreurs ont été rencontrées :';
            foreach ($errors as $error) {
                $contenu .= '<li>' . $error . '</li>';
            }
            $contenu .= '</ul>';
        } else {
            // TODO : Sauvegarder en BDD
            $contenu .= '<div class="alert alert-success">Coffret sauvegardé avec succès !</div>';
        }
        return $contenu;
    }

    public function render($v) {
        switch ($v) {
            case 'panier':
            default:
                $content = $this->panier();
                break;

            case 'add':
                $content = $this->add();
                break;

            case 'remove':
                $content = $this->remove();
                break;

            case 'infos':
                $content = $this->informations();
                break;

            case 'validation':
                $content = $this->validation();
                break;
        }
        return $content;
    }

}