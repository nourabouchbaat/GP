<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion des postes";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Postes";
$_SESSION['breadcrumb_nav3'] = "";
$_SESSION['breadcrumb_nav4'] = "";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "postes.php";
$_SESSION['link_nav3'] = "";
$_SESSION['link_nav4'] = "";
?>
<?php require_once('menu.php'); ?>
<div id="page-inner"> 
    <div class="row">
        <div class="col-lg-12">
            <?php if (isset($_REQUEST['m'])) { ?>
                <div class="alert alert-success">
                    <?php echo $_REQUEST['m'] ?>
                    <a href="#" data-dismiss="alert" class="close">x</a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
                <div class="panel-body">                      
                    <div class="widget-content nopadding">
                        <?php
                        $sql = "select * from postes order by id";
                        $res = doQuery($sql);

                        $nb = mysql_num_rows($res);
                        if ($nb == 0) {
                            echo _VIDE;
                        } else {
                            ?>
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Poste</th>
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
                                            <td><?php echo $ligne['POSTE'] ?></td>
                                            <td class="op">
                                                <a href="modifier_poste.php?postes=<?php echo $ligne['ID'] ?>" class="modifier2" title="<?php echo _MODIFIER ?>">
                                                    <i class="glyphicon glyphicon-edit"></i> 
                                                </a>
                                                <!--	&nbsp;
                                        <a href="#ancre" 
                                        class="supprimer2" 
                                        onclick="javascript:supprimer(
                                                                                                'postes',
                                                                    '<?php echo $ligne['id'] ?>',
                                                                    'postes.php',
                                                                    '1',
                                                                    '1')
                                                                        " 
                                                        title="<?php echo _SUPPRIMER ?>">
                                                
                                             <i class="glyphicon glyphicon-remove"></i> 
                                        </a>-->
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
        </div>
    </div>                  
</div>
<?php require_once('foot.php'); ?>