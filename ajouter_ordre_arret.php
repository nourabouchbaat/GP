
<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion des marches";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "marches.php";
$_SESSION['link_nav3'] = "ordre_arrets.php";
$_SESSION['link_nav4'] = "ajouter_ordre_arret.php";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Marches";
$_SESSION['breadcrumb_nav3'] = "Ordre d'arret";
$_SESSION['breadcrumb_nav4'] = "Nouveau ordre d'arrêt";
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
                                <input type="hidden" name="act" value="a"/>
                                <input type="hidden" name="table" value="ordre_arret"/>
                                <input type="hidden" name="page" value="ordre_arrets.php"/>
                                <input type="hidden" name="ID_MARCHE" value="<?php echo $_REQUEST['marches'] ?>"/>

                                <input type="hidden" name="id_noms_retour" value="marches"/>
                                <input type="hidden" name="id_valeurs_retour" value="<?php echo $_REQUEST['marches'] ?>"/>	

                                <div class="form-group">
                                    <label class="control-label"><?php echo "Date " ?> : </label>
                                    <input type="date" id="cal_required" 
                                           name="DATE_ORDRE_ARRET"  class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "N° Ordre d'arrêt" ?> : </label>
                                    <input type="text" id="<?php echo "N_ORDRE_ARRET" ?>__required" 
                                           name="N_ORDRE_ARRET"  class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "Justificatif " ?> : </label>
                                    <input type="text" id="<?php echo "JUSTIFICAION" ?>__required" 
                                           name="JUSTIFICAION"  class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <?php echo _AJOUTER ?>
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