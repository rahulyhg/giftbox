<?php

namespace giftbox\models;


class Coffret extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'coffret';
    protected $primaryKey = 'id';
    protected $fillable = array('id', 'nom', 'prenom', 'email', 'message', 'password', 'paiement', 'url', 'urlGestion', 'statut', 'montant');
    public $timestamps = false;
    
}