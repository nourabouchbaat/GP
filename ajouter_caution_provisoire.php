
<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des caution_provisoire";
	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="marches.php";
	$_SESSION['link_nav3'] ="caution_provisoires.php";
	$_SESSION['link_nav4'] ="ajouter_caution_provisoire.php";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Marches";
	$_SESSION['breadcrumb_nav3'] ="Caution provisoire";
	$_SESSION['breadcrumb_nav4'] ="Nouveau caution provisoire";
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
							    <input type="hidden" name="table" value="caution_provisoire"/>
								<input type="hidden" name="page" value="caution_provisoires.php"/>
								<input type="hidden" name="ID_MARCHE" value="<?php echo $_REQUEST['marches'] ?>"/>
			
							    <input type="hidden" name="id_noms_retour" value="marches"/>
								<input type="hidden" name="id_valeurs_retour" value="<?php echo $_REQUEST['marches'] ?>"/>	

								<div class="form-group">
									<label class="control-label"><?php echo "Date Caution provisoire " ?> : </label>
									<input type="date" id="cal_required" 
											name="DATE_CAUTION_PROVISOIRE"  class="form-control"/>
							    </div>
								<div class="form-group">
									<label class="control-label"><?php echo "N° Caution " ?> : </label>
									<input type="text" id="<?php echo "N_CAUTION" ?>__required" 
											name="N_CAUTION"  class="form-control"/>
							    </div>
								<div class="form-group">
									<label class="control-label"><?php echo "Montant " ?> : </label>
									<input type="text" id="<?php echo "MONTANT" ?>__required" 
											name="MONTANT"  class="form-control"/>
							    </div>
								<div class="form-group">
									<label class="control-label"><?php echo "Banque " ?> : </label>
									<input type="text" id="<?php echo "BANQUE" ?>__required" 
											name="BANQUE"  class="form-control"/>
							    </div>
								  
							    <div class="form-group">
								<button type="submit" class="btn btn-default">
									<?php echo _AJOUTER ?>
								</button>
								ou <a class="text-danger" href="caution_provisoires.php?marches=<?php echo $_REQUEST['marches'] ?>">Annuler</a>
                                    
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