<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 30/12/2016
 * Time: 22:44
 */

namespace giftbox\controller;


use giftbox\models\Categorie;
use giftbox\models\Prestation;

class CategoriesController extends BaseController
{
    protected $name = 'categories';

    public function index() {
        $categories = Categorie::all();
        $this->set('categories', $categories);
    }

    public function view($params) {
        $prestations = Prestation::where('cat_id', '=', $params['id'])->get()->sortBy('prix');
        if($params['order'] == "desc"){
            $prestations = Prestation::where('cat_id', '=', $params['id'])->get()->sortByDesc('prix');
        }
        $url = BASE_URL . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . $params['id'];
        $this->set('url', $url);
        $this->set('prestations', $prestations);
    }

}