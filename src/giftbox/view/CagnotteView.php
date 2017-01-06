<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 06/01/2017
 * Time: 15:35
 */

namespace giftbox\view;

use giftbox\models\Cagnotte;

class CagnotteView
{
    private $data;
    private $app;

    public function __construct($app = null, $array)
    {
        $this->data = $array;
        $this->app = $app;
    }

    private function creationCagnotte(){

    }

    private function participerCagnotte(){

    }

    private function cloturerCagnotte(){

    }

    public function render($aff){
        switch ($aff){
            case 1:
                $content = $this->creationCagnotte();
                break;
            case 2:
                $content = $this->participerCagnotte();
                break;
            case 3:
                $content = $this->cloturerCagnotte();
                break;
        }
        return $content;
    }
}