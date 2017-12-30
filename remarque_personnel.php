<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Remarques des Personnels";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Personnels";
$_SESSION['breadcrumb_nav3'] = "Remarques";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "personnels.php";
$_SESSION['link_nav3'] = "remarque_personnels.php?personnels=" . $_REQUEST['personnels'];
$_SESSION['link_nav4'] = "";
$_SESSION['breadcrumb_nav4'] = "";
?>
<?php require_once('menu.php'); ?>

<div id="page-inner"> 
    <div class="row">
        <div class="col-lg-12">
            <?php if (isset($_REQUEST['m'])) { ?>
                <div class="alert alert-info">
                    <?php echo $_REQUEST['m'] ?>
                    <a href="#" data-dismiss="alert" class="close">x</a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <!-- Advanced Tables -->
            <div class="panel panel-default" style="border:none;">

                <div class="panel-body" >

                    <div class="widget-content nopadding">
                        <?php
                        $sql = "select * from remarque_personnels where ID_PERSONNELS=" . $_REQUEST['personnels'] . " order by ID desc";
                        $res = doQuery($sql);

                        $nb = mysql_num_rows($res);
                        if ($nb == 0) {
                            echo _VIDE;
                        } else {
                            ?>
                            <table class="table table-bordered table-striped table-hover data-table">
                                <thead>
                                <th>Date</th>
                                <th>Remarque</th>
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
                                            <td><?php echo $ligne['DATE_REMARQUE'] ?></td>
                                            <td><?php echo $ligne['REMARQUE'] ?></td>
                                            <td class="op">
                                                <a href="remarque_personnel.php?personnels=<?php echo $ligne['ID_PERSONNELS'] ?>&remarques=<?php echo $ligne['ID'] ?>" class="modifier2" title="<?php echo _MODIFIER ?>">
                                                    <i class="glyphicon glyphicon-edit"></i> 
                                                </a>
                                                &nbsp;

                                                &nbsp;
                                                <a href="#ancre" 
                                                   class="supprimer2" 
                                                   onclick="javascript:supprimer(
                                                                           'remarque_personnels',
                                                                           '<?php echo $ligne['ID'] ?>',
                                                                           'remarque_personnel.php',
                                                                           'personnels',
                                                                           '<?php echo $_REQUEST['personnels'] ?>')
                                                   " 
                                                   title="<?php echo _SUPPRIMER ?>">

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
        </div>
        <div class="col-lg-6">
            <div class="panel panel-default" >

                <div class="panel-body" style="min-height: 370px;">
                    <div class="widget-content nopadding">
                        <form action="gestion.php" name="frm" method="post" 
                              onsubmit="return checkForm(document.frm);">
                            <input type="hidden" name="table" value="remarque_personnels"/>
                            <input type="hidden" name="page" value="remarque_personnel.php"/>
                            <input type="hidden" name="ID_PERSONNELS" value="<?php echo $_REQUEST['personnels'] ?>"/>
                            <input type="hidden" name="id_noms_retour" value="personnels"/>
                            <input type="hidden" name="id_valeurs_retour" value="<?php echo $_REQUEST['personnels'] ?>"/>	

                            <?php if (isset($_REQUEST['remarques']) && !empty($_REQUEST['remarques'])) { ?>
                                <input type="hidden" name="act" value="m"/>
                                <input type="hidden" name="id_nom" value="ID"/>
                                <input type="hidden" name="id_valeur" value="<?php echo $_REQUEST['remarques'] ?>"/>	
                            <?php } else { ?>
                                <input type="hidden" name="act" value="a"/>
                                <input type="hidden" name="DATE_REMARQUE" value="<?php echo date('Y-m-d') ?>"/>
                            <?php } ?>

                            <div class="form-group">
                                <label class="control-label"><?php echo "Remarque" ?> : </label>
                                <textarea id="<?php echo "REMARQUE" ?>__required" rows='10' cols="50"
                                          name="REMARQUE" class="form-control "  ><?php echo isset($_REQUEST['remarques']) && !empty($_REQUEST['remarques']) ? getValeurChamp('REMARQUE', 'remarque_personnels', 'ID', $_REQUEST['remarques']) : "" ?>	
                                </textarea>
                            </div>
                            <div class="form-actions" style="float:right;">
                                <input type="submit" class="btn btn-primary" value="<?php echo _VALIDER ?>" />
                            </div>
                        </form>
                    </div>
                </div>						
            </div>
        </div>
    </div>
</div>
</div>
<?php require_once('foot.php'); ?>