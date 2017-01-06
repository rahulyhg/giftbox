<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 06/01/2017
 * Time: 15:06
 */

namespace giftbox\models;


class Cagnotte extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'cagnotte';
    protected $primaryKey = 'id';
    protected $fillable = array('id', 'coffret_id', 'montant', 'urlContribution', 'urlGestion', 'cloture');
    protected $timestamps = false;

    public function cagnottes(){
        return Cagnotte::all();
    }

    public function addCagnotte($coffret, $montant, $contribution, $gestion, $cloture){
        Cagnotte::create(array(
            'coffret_id' => $coffret,
            'montant' => $montant,
            'urlContribution' => $contribution,
            'urlGestion' => $gestion,
            'cloture' => $cloture
        ));
    }

    public function getById($id){
        return Cagnotte::where('id', '=', $id)->first();
    }

    public function coffret(){
        return $this->belongsTo('\giftbox\models\Coffret.php', 'coffret_id');
    }
}