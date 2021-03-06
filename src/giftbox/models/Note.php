<?php

namespace giftbox\models;

use \Illuminate\Database\Eloquent\Model as Model;

class Note extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'id';
    protected $fillable = array('id', 'prestationId', 'note');
    public $timestamps = false;

}