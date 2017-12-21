
<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des chantiers";
	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="marches.php";
	$_SESSION['link_nav3'] ="chantiers.php";
	$_SESSION['link_nav4'] ="modifier_chantier.php";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Marches";
	$_SESSION['breadcrumb_nav3'] ="Chantiers";
	$_SESSION['breadcrumb_nav4'] ="Modifier chantier";
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
									    <input type="hidden" name="table" value="chantiers"/>
										<input type="hidden" name="page" value="chantiers.php"/>
										<input type="hidden" name="ID_MARCHE" value="<?php echo $_REQUEST['marches'] ?>"/>
					

										<input type="hidden" name="id_nom" value="ID"/>
										<input type="hidden" name="id_valeur" value="<?php echo $_REQUEST['chantiers'] ?>"/>	

									    <input type="hidden" name="id_noms_retour" value="marches,chantiers"/>
										<input type="hidden" name="id_valeurs_retour" value="<?php echo $_REQUEST['marches'] ?>,<?php echo $_REQUEST['chantiers'] ?>"/>	

										<div class="form-group">
											<label class="control-label"><?php echo "Chantier" ?> : </label>
											<input type="text" id="<?php echo "CODE" ?>__required"  value="<?php echo getValeurChamp('CODE','chantiers','ID',$_REQUEST['chantiers']); ?>"
													name="CODE"  class="form-control"/>
									    </div>
   									    <div class="form-group">
                            				<label class="control-label">Chef : </label>
                            				<?php 
                            					$valeur = getValeurChamp('ID_CHEF','chantiers','ID',$_REQUEST['chantiers']);
                            					$where = " where ID_POSTES =1";
                            				 ?>
                				            <?php echo getTableList5('personnels','ID_CHEF',$valeur,'CODE','NOM','PRENOM',$change,$where,$libelle) ?>
				                        </div>

									    <div class="form-group">
										<button type="submit" class="btn btn-default">
											<?php echo _AJOUTER ?>
										</button>
										ou <a class="text-danger" href="chantiers.php">Annuler</a>
                                            
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