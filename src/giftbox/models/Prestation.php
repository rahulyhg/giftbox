<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 10/12/16
 * Time: 13:51
 */

namespace giftbox\models;


class Prestation extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'prestation';
    protected $primaryKey = 'id';
    protected $fillable = array('id', 'nom', 'descr', 'cat_id', 'img', 'prix', 'votes', 'visible');
    public $timestamps = false;

    public function prestations(){
        return Prestation::all();
    }

    public function addPrestation($nom, $des, $cat, $img, $prix){
        Prestation::create(array(
            'nom'=>$nom,
            'descr'=>$des,
            'cat_id'=>$cat,
            'img'=>$img,
            'prix'=>$prix
        ));
    }

    public function getById($id){
        return Prestation::where('id','=',$id)->first();
    }

    public function categorie(){
        return $this->belongsTo('\giftbox\models\Categorie', 'cat_id');
    }
}