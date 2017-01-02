<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 30/12/2016
 * Time: 23:30
 */

namespace giftbox\controller;


use giftbox\models\Categorie;
use giftbox\models\Prestation;

class PanierController extends BaseController
{

    protected $name = 'panier';

    public function index() {
        if (isset($_SESSION['panier'])) {
            $this->set('panier', $_SESSION['panier']);
        } else {
            $this->set('panier', null);
        }
    }

    public function delete($id) {
        $prestation = Prestation::where('id', '=', $id)->first();
        if (isset($_SESSION['panier']['article'][$prestation->nom])) {
            $_SESSION['panier']['qua'] = ($_SESSION['panier']['qua'] - 1);
            if ($_SESSION['panier']['article'][$prestation->nom]['qua'] == 1) {
                unset($_SESSION['panier']['article'][$prestation->nom]);
            } else {
                $_SESSION['panier']['article'][$prestation->nom]['qua'] = ($_SESSION['panier']['article'][$prestation->nom]['qua'] - 1);
                $_SESSION['panier']['article'][$prestation->nom]['prix'] = ($_SESSION['panier']['article'][$prestation->nom]['prix'] - $prestation->prix);
            }
            $_SESSION['flash'] = array(
                'message' => 'Prestation supprimée du panier',
                'type' => 'success'
            );
            $this->redirect('/panier');
        } else {
            $this->redirect('/panier');
        }
    }

    public function save() {
        if (isset($_SESSION['panier'])) {
            $categories = array();
            foreach ($_SESSION['panier']['article'] as $article => $a) {
                $cat_id = Prestation::where('id', '=', $a['id'])->first()->cat_id;
                $cat = Categorie::where('id', '=', $cat_id)->first();
                if (!isset($categories[$cat['nom']])) {
                    $categories[$cat['nom']] = 1;
                } else {
                    $categories[$cat['nom']] = ($categories[$cat['nom']] + 1);
                }
            }
            var_dump( $categories);
            $this->set('panier', $_SESSION['panier']);
        } else {
            $this->redirect(BASE_URL . '/');
        }
    }

}