
<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des postes";
	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="postes.php";
	$_SESSION['link_nav3'] ="ajouter_poste.php";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Postes";
	$_SESSION['breadcrumb_nav3'] ="Nouveau poste";
?>
<?php require_once('menu.php'); ?>	 
			<div id="page-inner"> 
              <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                            	<div class="col-lg-12">	
									<form action="gestion.php" name="frm" method="post" onsubmit="return checkForm(document.frm);" >
										<input type="hidden" name="act" value="a"/>
									    <input type="hidden" name="table" value="postes"/>
										<input type="hidden" name="page" value="postes.php"/>
										<div class="form-group">
											<label class="control-label"><?php echo "Poste" ?> : </label>
											<input type="text" id="<?php echo "POSTE" ?>__required" 
													name="POSTE"  class="form-control"/>
									    </div>

									    <div class="form-group">
										<button type="submit" class="btn btn-default">
											<?php echo _AJOUTER ?>
										</button>
										ou <a class="text-danger" href="postes.php">Annuler</a>
                                            
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