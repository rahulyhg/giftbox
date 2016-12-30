<table border="1" >
    <thead>
        <tr>
            <th colspan="3">Article(s) : <?php echo is_null($panier) ? '0' : $panier['qua']; ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php
            $total = 0;
            foreach ($panier['article'] as $article => $a):
                $total = $total + $a['prix'];
            ?>
                <tr>
                    <td><?php echo $a['nom']; ?></td>
                    <td><?php echo $a['prix']; ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/prestation/add/<?php echo $a['id']; ?>"><img src="<?php echo BASE_URL; ?>/web/img/add.png" width="32" alt="Ajouter" title="Ajouter"></a>
                        <a href="<?php echo BASE_URL; ?>/panier/delete/<?php echo $article; ?>"><img src="<?php echo BASE_URL; ?>/web/img/trash.png" width="32" alt="Supprimer" title="Supprimer"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="text-align: right">Total</td>
            <td>
                <?php echo $total; ?>
            </td>
        </tr>
    </tfoot>
</table>