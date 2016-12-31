<?php $title = 'Catégories'; ?>
<?php foreach ($categories as $category => $c): ?>
    <h2><?php echo $c->nom; ?></h2>
    <p><a href="<?php echo BASE_URL; ?>/categories/<?php echo $c->id; ?>/asc">voir les prestations</a></p>
<?php endforeach; ?>
