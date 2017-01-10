<?php

namespace giftbox\models;


class Note extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'notes';
    protected $primaryKey = 'id';
    protected $fillable = array('id', 'prestationId', 'note');
    public $timestamps = false;

}