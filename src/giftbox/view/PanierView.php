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
    public function __construct($array = null)
    {
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
        if (isset($_SESSION['panier'])) {
            $html .= '<tbody>';
            foreach ($_SESSION['panier']['article'] as $article => $a) {
                $html .= '<tr>';
                $html .= '<td>' . $article . '</td>';
                $html .= '<td>' . $a['qua'] . '</td>';
                $html .= '<td>' . $a['prix'] . ' &euro;</td>';
                $html .= '<td>';
                $url = dirname($_SERVER['REQUEST_URI']);
                $html .= '<a href="/projet_giftbox/prestation/add/' . $a['id'] . '"><img src="/projet_giftbox/web/img/add.png" width="32" alt="Ajouter"></a>';
                $html .= '<a href="/projet_giftbox/prestation/delete/' . $a['id'] . '"><img src="/projet_giftbox/web/img/trash.png" width="32" alt="Supprimer"></a>';
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
            $html .= '<div class="alert alert-danger">Whoops ! Des erreurs ont été rencontrées</div>';
        }
        return $html;
    }

    public function render($v) {
        switch ($v) {
            case 'panier':
            default:
                $content = $this->panier();
                break;

            case 'add':
                $content = $this->add();
                $content .= $this->panier();
                break;

            case 'remove':
                $content = $this->remove();
                $content .= $this->panier();
                break;
        }
        return $content;
    }

}