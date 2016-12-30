<h2>Prestation : <?php echo $prestation->nom; ?></h2>
<p><?php echo $prestation->descr; ?></p>
<p>Prix : <?php echo $prestation->prix; ?></p>
<p><a href="<?php echo BASE_URL; ?>/prestation/add/<?php echo $prestation->id; ?>"><img src="<?php echo BASE_URL; ?>/web/img/add.png" width="32" alt="Ajouter au panier" title="Ajouter au panier"></a></p>
<p><a href="<?php echo BASE_URL; ?>/prestations/all/asc">liste des prestations</a></p>