<?php

/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 29/12/16
 * Time: 10:49
 */
$directory = "/projet_giftbox/";
echo "<!DOCTYPE html>
 <htmt>
    <head>
        <title>Accueil</title>
        <meta charset='UTF-8'>
        <link rel='stylesheet' type='text/css' href='{$directory}web/css/style.css'>
    </head>
    <body>
        <nav>
            <ul>
                <li><a href=$directory>home</a></li>
                <li><a href=\"{$directory}prestations/all/asc\">prestations</a></li>
                <li><a href=\"{$directory}categories/\">categories</a></li>
            </ul>
        </nav>
        <content>$content</content>
    </body>
</htmt>
    ";