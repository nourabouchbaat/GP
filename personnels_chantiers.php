<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion Personnels";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Marche";
$_SESSION['breadcrumb_nav3'] = "Chantier";
$_SESSION['breadcrumb_nav4'] = "Personnels Chantiers";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "marches.php";
$_SESSION['link_nav3'] = "chantiers.php?marches=" . $_REQUEST['marches'];
$_SESSION['link_nav4'] = "personnels_chantiers.php?chantiers=" . $_REQUEST['chantiers'];
?>
<?php require_once('menu.php'); ?>
<div id="page-inner"> 
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="widget-content nopadding">
                        <a href="ajouter_personnels_chantiers.php?marches=<?php echo $_REQUEST['marches'] ?>&chantiers=<?php echo $_REQUEST['chantiers'] ?>"><i class="glyphicon glyphicon-plus"></i> Ajouter personnels au chantier</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <?php
                        $sql = "select * from personnels p, personnels_chantiers pc where p.ID = pc.ID_PERSONNELS and p.STATUS=1 and pc.ID_CHNATIERS=" . $_REQUEST['chantiers'] . " and DATE_SORTIE is null order by p.ID";
                        $res = doQuery($sql);
                        $nb = mysql_num_rows($res);
                        if ($nb == 0) {
                            echo _VIDE;
                        } else {
                            ?>
                            <br/>
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Poste</th>
                                <th>Date Debut</th>
                                <th class="op"> <?php echo _OP ?> </th>
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
                                            <td><?php echo $ligne['NOM'] . " " . $ligne['PRENOM'] ?></td>
                                            <td><?php echo $ligne['CODE'] ?></td>
                                            <td><?php echo getValeurChamp('POSTE', 'postes', 'id', $ligne['ID_POSTES']); ?></td>
                                            <td><?php echo $ligne['DATE_AFFECTATION'] ?></td>
                                            <td class="op">
                                                <a href="gestion.php?act=removePersonnelChantier&ID=<?php echo $ligne['ID'] ?>&personnels=<?php echo $ligne['ID_PERSONNELS'] ?>&chantiers=<?php echo $_REQUEST['chantiers'] ?>&marches=<?php echo $_REQUEST['marches'] ?>" 
                                                   class="supprimer2"  
                                                   title="<?php echo _ARCHIVER ?>">
                                                    <i class="glyphicon glyphicon-remove"></i> 
                                                </a>
                                            </td>
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