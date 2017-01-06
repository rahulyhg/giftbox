<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 06/01/17
 * Time: 15:49
 */

namespace giftbox\view;


class CoffretView
{
    private $data;
    private $app;

    public function __construct($app = null, $array)
    {
        $this->app = $app;
        $this->data = $array;
    }
    private function gererCoffret(){
        return "";
    }
    private function afficherCoffret(){
        return "";
    }
    public function render($aff){
        switch ($aff){
            case 'gestion_coffret':
                $this->gererCoffret();
                break;
            case 'coffret':
                $this->afficherCoffret();
                break;
            default:
                return "";
                break;
        }
    }
}