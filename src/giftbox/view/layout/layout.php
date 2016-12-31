<!DOCTYPE html>
<htmt>
    <head>
        <title>Accueil<?php echo isset($title) ? ' - ' . $title : ''; ?></title>
        <meta charset='UTF-8'>
        <link rel='stylesheet' type='text/css' href='<?php echo BASE_URL; ?>/web/css/style.css'>
    </head>
    <body>
    <nav>
        <p>
            <a href="<?php echo BASE_URL; ?>/panier">
                <img src="<?php echo BASE_URL; ?>/web/img/cart.png" width="32" style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo isset($_SESSION['panier']) ? $_SESSION['panier']['qua'] : '0'; ?> article(s)
            </a>
        </p>
        <ul>
            <li><a href="<?php echo BASE_URL; ?>/">home</a></li>
            <li><a href="<?php echo BASE_URL; ?>/prestations/all/asc">prestations</a></li>
            <li><a href="<?php echo BASE_URL; ?>/categories/">categories</a></li>
        </ul>
    </nav>
    <content>
        <p>

            <?php if (isset($_SESSION['flash']['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['flash']['type']; ?>"><?php echo $_SESSION['flash']['message']; ?></div>
            <?php $_SESSION['flash'] = array(); endif; ?>
        </p>
        <?php echo $content; ?>
    </content>
    </body>
</htmt>