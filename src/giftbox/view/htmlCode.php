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
    </head>
    <body>
        <nav>
            <ul>
                <li><a href=$directory>home</a></li>
                <li><a href=\"{$directory}prestations/\">prestations</a></li>
                <li><a href=\"{$directory}categories/\">categories</a></li>
            </ul>
        </nav>
        <content>$content</content>
    </body>
</htmt>
    ";