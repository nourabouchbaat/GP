
<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion des caution_retenue_garantie";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "marches.php";
$_SESSION['link_nav3'] = "caution_retenue_garanties.php";
$_SESSION['link_nav4'] = "modifier_caution_retenue_garantie.php";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Marches";
$_SESSION['breadcrumb_nav3'] = "Caution retenue_garantie";
$_SESSION['breadcrumb_nav4'] = "Modifier caution retenue_garantie";
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
                                <input type="hidden" name="table" value="caution_retenue_garantie"/>
                                <input type="hidden" name="page" value="caution_retenue_garanties.php"/>
                                <input type="hidden" name="ID_MARCHE" value="<?php echo $_REQUEST['marches'] ?>"/>

                                <input type="hidden" name="id_nom" value="ID"/>
                                <input type="hidden" name="id_valeur" value="<?php echo $_REQUEST['caution_retenue_garanties'] ?>"/>	


                                <input type="hidden" name="id_noms_retour" value="marches,caution_retenue_garanties"/>
                                <input type="hidden" name="id_valeurs_retour" value="<?php echo $_REQUEST['marches'] ?>,<?php echo $_REQUEST['caution_retenue_garanties'] ?>"/>	


                                <div class="form-group">
                                    <label class="control-label"><?php echo "Date Caution de " ?> : </label>
                                    <input type="date" id="cal_required"   value="<?php echo getValeurChamp('DATE_CAUTION_RETENUE_GARANTIE', 'caution_retenue_garantie', 'ID', $_REQUEST['caution_retenue_garanties']); ?>"	name="DATE_CAUTION_RETENUE_GARANTIE"  class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "N° Caution " ?> : </label>
                                    <input type="text" id="<?php echo "N_CAUTION" ?>__required"  value="<?php echo getValeurChamp('N_CAUTION', 'caution_retenue_garantie', 'ID', $_REQUEST['caution_retenue_garanties']); ?>"	name="N_CAUTION"  class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "Montant " ?> : </label>
                                    <input type="text" id="<?php echo "MONTANT" ?>__required"  value="<?php echo getValeurChamp('MONTANT', 'caution_retenue_garantie', 'ID', $_REQUEST['caution_retenue_garanties']); ?>"	name="MONTANT"  class="form-control"/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "Banque " ?> : </label>
                                    <input type="text" id="<?php echo "BANQUE" ?>__required"  value="<?php echo getValeurChamp('BANQUE', 'caution_retenue_garantie', 'ID', $_REQUEST['caution_retenue_garanties']); ?>" name="BANQUE"  class="form-control"/>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <?php echo _MODIFIER ?>
                                    </button>
                                    ou <a class="text-danger" href="caution_retenue_garanties.php?marches=<?php echo $_REQUEST['marches'] ?>">Annuler</a>

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