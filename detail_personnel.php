
<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion des personnels";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Personnels";
$_SESSION['breadcrumb_nav3'] = "Profil";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "personnels.php";
$_SESSION['link_nav3'] = "detail_personnels.php?persosnnels=" . $_REQUEST['personnels'];
$_SESSION['link_nav4'] = "";
$_SESSION['breadcrumb_nav4'] = "";
?>
<?php require_once('menu.php'); ?>		    
<div id="page-inner"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">	
                            <form action="<?php echo $_REQUEST['page'] ?>" name="frm" method="post" 
                                  onsubmit="return checkForm(document.frm);" >

                                <div class="form-group">
                                    <label class="control-label"><?php echo "nom" ?> : </label>
                                    <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('NOM', 'personnels', 'ID', $_REQUEST['personnels']); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "prenom" ?> : </label>
                                    <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('PRENOM', 'personnels', 'ID', $_REQUEST['personnels']); ?>">
                                </div>

                                <div class="form-group">
                                    <label class="control-label"><?php echo "CIN" ?> : </label>
                                    <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('CIN', 'personnels', 'ID', $_REQUEST['personnels']); ?>">

                                </div>

                                <div class="form-group">
                                    <label class="control-label"><?php echo "TELEPHONE" ?> : </label>
                                    <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('TELEPHONE', 'personnels', 'ID', $_REQUEST['personnels']); ?>">

                                </div>

                                <div class="form-group">
                                    <label class="control-label"><?php echo "Code" ?> : </label>
                                    <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('CODE', 'personnels', 'ID', $_REQUEST['personnels']); ?>">

                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "ADRESSE" ?> : </label>
                                    <textarea class="form-control" id="disabledInput"  placeholder="Disabled input" disabled="" rows="5">
                                        <?php echo getValeurChamp('ADRESSE', 'personnels', 'ID', $_REQUEST['personnels']); ?>
                                    </textarea>

                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn 	btn-default">
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <form action="personnels.php" name="frm" method="post" 
                                  onsubmit="return checkForm(document.frm);" >
                                <div class="form-group">
                                    <label class="control-label"><?php echo "Date d'embauche" ?> : </label>
                                    <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('DATE_EMBAUCHE', 'personnels', 'ID', $_REQUEST['personnels']); ?>">

                                </div>
                                <div class="form-group">
                                    <label class="control-label">Poste : </label>
                                    <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('POSTE', 'postes', 'ID', $_REQUEST['ID_POSTE']) ?>">

                                </div>
                                <?php if (getValeurChamp('TYPE', 'personnels', 'ID', $_REQUEST['personnels']) == "Salarie") { ?>
                                    <div class="form-group">
                                        <label class="control-label"><?php echo "Salaire mensuel" ?> : </label>
                                        <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('SALAIRE_MENSUELLE', 'personnels', 'ID', $_REQUEST['personnels']); ?>">
                                    </div>
                                <?php } else { ?>
                                    <div class="form-group">
                                        <label class="control-label"><?php echo "Tarif journaliere" ?> : </label>
                                        <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('TARIF_JOURNALIERS', 'personnels', 'ID', $_REQUEST['personnels']); ?>">												
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "CNSS" ?> : </label>
                                    <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('CNSS', 'personnels', 'ID', $_REQUEST['personnels']); ?>">

                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo "RIB" ?> : </label>
                                    <input class="form-control" id="disabledInput" type="text" placeholder="Disabled input" disabled="" value="<?php echo getValeurChamp('RIB', 'personnels', 'ID', $_REQUEST['personnels']); ?>">

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