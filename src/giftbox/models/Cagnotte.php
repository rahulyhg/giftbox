<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 06/01/2017
 * Time: 15:06
 */

namespace giftbox\models;

use Illuminate\Database\Eloquent\Model;

class Cagnotte extends Model
{
    protected $table = 'cagnotte';
    protected $primaryKey = 'id';
    protected $fillable = array('id', 'coffret_id', 'montant', 'urlContribution', 'urlGestion', 'cloture');
    public $timestamps = false;

    public function cagnottes(){
        return Cagnotte::all();
    }

    public function getById($id){
        return Cagnotte::where('id', '=', $id)->first();
    }

    public function coffret(){
        return $this->belongsTo('\giftbox\models\Coffret.php', 'coffret_id');
    }
}