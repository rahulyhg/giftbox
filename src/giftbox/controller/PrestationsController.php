<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 30/12/2016
 * Time: 21:26
 */

namespace giftbox\controller;


use giftbox\models\Prestation;

class PrestationsController extends BaseController
{

    protected $name = 'prestations';

    public function index($order) {
        $prestations = \giftbox\models\Prestation::all()->sortBy('prix');
        if ($order == "desc"){
            $prestations = \giftbox\models\Prestation::all()->sortByDesc('prix');
        }
        $this->set('prestations', $prestations);
    }

    public function view($id) {
        $prestation = \giftbox\models\Prestation::find($id);
        $this->set('prestation', $prestation);
    }

    public function add($id) {
        $prestation = Prestation::where('id', '=', $id)->first();
        if (!empty($prestation)) {
            if (!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = array(
                    'qua' => 1,
                    'article' => array()
                );
                $_SESSION['panier']['article'][] = array(
                    'id' => $prestation->id,
                    'nom' => $prestation->nom,
                    'prix' => $prestation->prix
                );
            } else {
                $_SESSION['panier']['qua'] = ($_SESSION['panier']['qua'] + 1);
                $_SESSION['panier']['article'][] = array(
                    'id' => $prestation->id,
                    'nom' => $prestation->nom,
                    'prix' => $prestation->prix
                );
            }
            $_SESSION['flash'] = array(
                'message' => 'Préstation ajoutée au panier',
                'type' => 'info'
            );
            if (strstr($_SERVER['HTTP_REFERER'], 'panier')) {
                $this->redirect('/panier');
            } else {
                $this->redirect('/prestations/all/asc');
            }
        } else {
            $this->redirect(BASE_URL . '/');
        }
    }

}