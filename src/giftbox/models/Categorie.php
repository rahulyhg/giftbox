<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 10/12/16
 * Time: 13:50
 */

namespace giftbox\models;


class Categorie extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    protected $fillable = array('id','nom');
    public $timestamps = false;

    public function categories(){
        return Categorie::all();
    }

    public function addCategorie($nom){
        Categorie::create(
            array('nom'=>$nom)
        );
    }

    public function getById($id){
        return Categorie::where('id','=', $id)->first();
    }

    public function getByName($name){
        return Categorie::where('nom','=', $name)->first();
    }

    public function prestations(){
        return $this->hasMany('\giftbox\models\Prestation', 'cat_id')->get();
    }



}