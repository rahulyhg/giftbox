<?php

namespace giftbox\models;

use \Illuminate\Database\Eloquent\Model as Model;

class Administrateur extends Model
{
    protected $table = 'administrateurs';
    protected $primaryKey = 'id';
    protected $fillable = array('id', 'email', 'password');
    public $timestamps = false;

}