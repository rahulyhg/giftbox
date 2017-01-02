<?php $title = 'Catégories : ' . $nom; ?>
<p><a href="<?php echo $url; ?>/asc">croissant</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $url; ?>/desc">decroissant</a></p>
<?php foreach ($prestations as $prestation => $p): ?>
    <p><img class='prestaImg' src="<?php echo BASE_URL; ?>/web/img/<?php echo $p->img; ?>"></p>
    <h3>Categorie : <a href="<?php echo BASE_URL; ?>/categories/<?php echo $p->cat_id; ?>/asc"><?php echo $nom; ?></a></h3>
    <p><?php echo $p->descr; ?></p>
    <p>Prix : <?php echo $p->prix; ?></p>
    <p><a href="<?php echo BASE_URL; ?>/prestation/add/<?php echo $p->id; ?>"><img src="<?php echo BASE_URL; ?>/web/img/add.png" width="32" alt="Ajouter au panier" title="Ajouter au panier"></a></p>
    <p><a href="<?php echo BASE_URL; ?>/prestations/<?php echo $p->id; ?>">voir plus</a></p>
<?php endforeach; ?>