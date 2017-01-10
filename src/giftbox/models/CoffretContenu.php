<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 06/01/2017
 * Time: 15:30
 */

namespace giftbox\models;


use Illuminate\Database\Eloquent\Model;

class CoffretContenu extends Model
{

    public $table = 'coffretcontenu';
    public $fillable = array('coffret_id', 'prestation_id', 'qua');
    public $timestamps = false;

    public function coffret(){
        return $this->belongsTo('\giftbox\models\Coffret', 'coffret_id')->first();
    }

    public function prestation(){
        return $this->belongsTo('\giftbox\models\Prestation', 'prestation_id')->first();
    }
}