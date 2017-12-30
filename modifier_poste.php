
<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion des postes";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Poste";
$_SESSION['breadcrumb_nav3'] = "Mis a jour du poste";


$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "postes.php";
$_SESSION['link_nav3'] = "modifier_poste.php?postes=" . $_REQUEST['postes'];
$_SESSION['link_nav4'] = "";
$_SESSION['breadcrumb_nav4'] = "";
?>
<?php require_once('menu.php'); ?>
<div id="page-inner"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="gestion.php" name="frm" method="post" 
                          onsubmit="return checkForm(document.frm);" >
                        <input type="hidden" name="act" value="m"/>
                        <input type="hidden" name="table" value="postes"/>
                        <input type="hidden" name="page" value="postes.php"/>

                        <input type="hidden" name="id_nom" value="ID"/>
                        <input type="hidden" name="id_valeur" value="<?php echo $_REQUEST['postes'] ?>"/>	

                        <input type="hidden" name="id_noms_retour" value="postes"/>
                        <input type="hidden" name="id_valeurs_retour" value="<?php echo $_REQUEST['postes'] ?>"/>	

                        <div class="form-group">
                            <label class="control-label"><?php echo "Poste" ?> : </label>
                            <div class="controls">
                                <input type="text" id="<?php echo "POSTE" ?>__required" value="<?php echo getValeurChamp('POSTE', 'postes', 'ID', $_REQUEST['postes']); ?>"
                                       name="POSTE"  class="form-control input-small"/>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" class="btn btn-primary" value="<?php echo _MODIFIER ?>" /> ou <a class="text-danger" href="postes.php">Annuler</a>
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