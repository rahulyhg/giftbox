<?php

/**
 * Created by PhpStorm.
 * User: keiko
 * Date: 29/12/16
 * Time: 10:49
 */
$directory = '/projet_giftbox/';
$flash = \Slim\Slim::getInstance()->flashData();
$flashMessage = '';
if ($flash != null) {
    $alertType = array_keys($flash)[0];
    $flashMessage = '<div class="alert alert-' . $alertType . '">' . $flash[$alertType] . '</div>';
}
echo '<!DOCTYPE html>
 <html>
    <head>
        <title>Accueil</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="' . $directory . 'web/css/style.css">
    </head>
    <body>
        <p><a href="' . $directory . 'panier"><img src="' . $directory . 'web/img/cart.png" alt="Panier" width="24" class="cart">Article(s) : ' . (isset($_SESSION['panier']) ? $_SESSION['panier']['qua'] : '0') . '</a></p>
        <nav>
            <ul>
                <li><a href="' . $directory . '">home</a></li>
                <li><a href="' . $directory . 'prestations/all/asc">prestations</a></li>
                <li><a href="' . $directory . 'categories">categories</a></li>
            </ul>
        </nav>
        ' . $flashMessage . '
        <content>' . $content . '</content>
    </body>
</html>
';