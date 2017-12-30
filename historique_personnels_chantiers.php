<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion Personnels";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Personnels";
$_SESSION['breadcrumb_nav3'] = "Chantiers";
$_SESSION['breadcrumb_nav4'] = "";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "personnels.php";
$_SESSION['link_nav3'] = "historique_personnels_chantiers.php?personnels=" . $_REQUEST['personnels'];
$_SESSION['link_nav4'] = "";
?>
<?php require_once('menu.php'); ?>
<div id="page-inner"> 
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <?php
                        $sql = "select * from personnels_chantiers pc where pc.ID_PERSONNELS=" . $_REQUEST['personnels'] . " order by pc.DATE_AFFECTATION";
                        $res = doQuery($sql);
                        $nb = mysql_num_rows($res);
                        if ($nb == 0) {
                            echo _VIDE;
                        } else {
                            ?>
                            <br/>
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <th>Marche</th>
                                <th>Chantier</th>
                                <th>Date Debut</th>
                                <th>Date fin</th>
                                </thead>	
                                <tbody>
    <?php
    $i = 0;
    while ($ligne = mysql_fetch_array($res)) {
        if ($i % 2 == 0)
            $c = "c";
        else
            $c = "";
        ?>
                                        <tr class="<?php echo $c ?>">
                                            <td><?php echo getValeurChamp('NUM_MARCHE', 'marches', 'ID', getValeurChamp('ID_MARCHE', 'chantiers', 'ID', $ligne['ID_CHNATIERS'])); ?></td>
                                            <td><?php echo getValeurChamp('CODE', 'chantiers', 'ID', $ligne['ID_CHNATIERS']); ?></td>
                                            <td><?php echo $ligne['DATE_AFFECTATION']; ?></td>
                                            <td><?php echo $ligne['DATE_SORTIE']; ?></td>
                                        </tr>
        <?php
        $i++;
    }
    ?>
                                </tbody>
                            </table>
    <?php
} //Fin If
?>
                    </div>
                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>
</div>
<?php require_once('foot.php'); ?>