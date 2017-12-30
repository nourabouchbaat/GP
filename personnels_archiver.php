<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion Personnels";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Personnels";
$_SESSION['breadcrumb_nav3'] = "Archive";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "personnels.php";
$_SESSION['link_nav3'] = "personnels_archiver";
$_SESSION['link_nav4'] = "";
$_SESSION['breadcrumb_nav4'] = "";
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
                        $sql = "select * from personnels where status=0 order by id";
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
                                <th>CIN</th>
                                <th>CNSS</th>
                                <th>Téléphone</th>
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
                                            <td><?php echo $ligne['CIN'] ?></td>
                                            <td><?php echo $ligne['CNSS'] ?></td>
                                            <td><?php echo $ligne['TELEPHONE'] ?></td>
                                            <td class="op">
                                                <a href="detail_personnel.php?page=personnels_archiver.php&personnels=<?php echo $ligne['ID']; ?>" class="detail" title="profil du personnel">
                                                    <i class="fa fa-user"></i>
                                                </a>
                                                <a href="gestion.php?act=desarchiver_personnel&personnels=<?php echo $ligne['ID'] ?>" 
                                                   class="supprimer2"  
                                                   title="<?php echo _ARCHIVER ?>">
                                                    <i class="glyphicon glyphicon-plus"></i> 
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
<!-- /. PAGE INNER  -->			 
<?php require_once('foot.php'); ?>