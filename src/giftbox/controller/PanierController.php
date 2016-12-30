<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 30/12/2016
 * Time: 23:30
 */

namespace giftbox\controller;


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

    }

}