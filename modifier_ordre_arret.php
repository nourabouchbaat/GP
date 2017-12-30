
<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion des ordre d'arrêt";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "marches.php";
$_SESSION['link_nav3'] = "ordre_arrets.php";
$_SESSION['link_nav4'] = "modifier_ordre_arret.php";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Marches";
$_SESSION['breadcrumb_nav3'] = "Ordre d'arret";
$_SESSION['breadcrumb_nav4'] = "Modifier ordre d'arrêt";
?>
<?php require_once('menu.php'); ?>
<div id="page-inner"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">	
                            <form action="gestion.php" name="frm" method="post" 
                                  onsubmit="return checkForm(document.frm);" >
                                <input type="hidden" name="act" value="m"/>
                                <input type="hidden" name="table" value="ordre_arret"/>
                                <input type="hidden" name="page" value="ordre_arrets.php"/>
                                <input type="hidden" name="ID_MARCHE" value="<?php echo $_REQUEST['marches'] ?>"/>

                                <input type="hidden" name="id_nom" value="ID"/>
                                <input type="hidden" name="id_valeur" value="<?php echo $_REQUEST['ordre_arrets'] ?>"/>	


                                <input type="hidden" name="id_noms_retour" value="marches,ordre_arrets"/>
                                <input type="hidden" name="id_valeurs_retour" value="<?php echo $_REQUEST['marches'] ?>,<?php echo $_REQUEST['ordre_arrets'] ?>"/>	

                                <div class="form-group">
                                    <label class="control-label"><?php echo "Date " ?> : </label>
                                    <input type="date" id="cal_required" value="<?php echo getValeurChamp('DATE_ORDRE_ARRET', 'ordre_arret', 'ID', $_REQUIRED['ordre_arrets']); ?>"
                                           name="DATE_ORDRE_ARRET"  class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "N° Ordre d'arrêt" ?> : </label>
                                    <input type="text" id="<?php echo "N_ORDRE_ARRET" ?>__required"  value="<?php echo getValeurChamp('N_ORDRE_ARRET', 'ordre_arret', 'ID', $_REQUIRED['ordre_arrets']); ?>"
                                           name="N_ORDRE_ARRET"  class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "Justificatif " ?> : </label>
                                    <input type="text" id="<?php echo "JUSTIFICAION" ?>__required"  value="<?php echo getValeurChamp('JUSTIFICAION', 'ordre_arret', 'ID', $_REQUIRED['ordre_arrets']); ?>"
                                           name="JUSTIFICAION"  class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <?php echo _MODIFIER ?>
                                    </button>
                                    ou <a class="text-danger" href="ordre_arrets.php?marches=<?php echo $_REQUEST['marches'] ?>">Annuler</a>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?php require_once('foot.php'); ?>