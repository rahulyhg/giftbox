<?php $title = 'Panier'; ?>
<table border="1">
    <thead>
        <tr>
            <th colspan="4">Article(s) : <?php echo is_null($panier) ? '0' : $panier['qua']; ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php
            $total = 0;
            if (!is_null($panier)):
                foreach ($panier['article'] as $article => $a):
                $total = $total + $a['prix'];
            ?>
                    <tr>
                        <td><a href="<?php echo BASE_URL; ?>/prestations/<?php echo $a['id']; ?>"><?php echo $article; ?></a></td>
                        <td><?php echo $a['qua']; ?></td>
                        <td><?php echo $a['prix']; ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/prestation/add/<?php echo $a['id']; ?>"><img src="<?php echo BASE_URL; ?>/web/img/add.png" width="32" alt="Ajouter" title="Ajouter"></a>
                            <a href="<?php echo BASE_URL; ?>/panier/delete/<?php echo $a['id']; ?>"><img src="<?php echo BASE_URL; ?>/web/img/trash.png" width="32" alt="Supprimer" title="Supprimer"></a>
                        </td>
                    </tr>
            <?php
                endforeach;
            endif;
            ?>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: right">Total</td>
            <td>
                <?php echo $total; ?> &euro;
            </td>
        </tr>
    </tfoot>
</table>
<?php if (!is_null($panier)): ?>
    <a href="<?php echo BASE_URL; ?>/panier/save">Sauvegarder le panier</a>
<?php endif; ?>