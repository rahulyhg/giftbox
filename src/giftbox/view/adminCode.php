<?php

/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 29/12/16
 * Time: 10:49
 */

$directory = \Slim\Slim::getInstance()->urlFor('index');

$flash = \Slim\Slim::getInstance()->flashData();
$flashMessage = '';
if ($flash != null) {
    $alertType = array_keys($flash)[0];
    $flashMessage = '<div class="alert alert-' . $alertType . '">' . $flash[$alertType] . '</div>';
}
echo '<!DOCTYPE html>
 <html>
    <head>
        <title>Administration</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="' . $directory . 'web/css/style.css">
    </head>
    <body>
        <nav>
            <ul>
                <li><a href="' . $directory . '">Site</a></li>
                <li><a href="' . $directory . 'prestations/all/asc">Prestations</a></li>
                <li><a href="' . $directory . 'administration">Administration</a>' . (isset($_SESSION['admin']) ? '&nbsp;|&nbsp;<a href="deconnexion">Se deconnecter</a>' : '') . '</li>
            </ul>
        </nav>
        ' . $flashMessage . '
        <content>' . $content . '</content>
    </body>
</html>
';