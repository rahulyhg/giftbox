<?php
/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 29/12/16
 * Time: 11:34
 */

namespace giftbox\view;


class htmlView
{
    private $contenu;
    public function __construct($data)
    {
        $this->contenu = $data;
    }
    public function render(){
        ob_start();
        include 'htmlCode.php';
        $content = $this->contenu;
        ob_end_flush();
    }
}