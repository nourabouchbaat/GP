
<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des marches";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Marche";
	$_SESSION['breadcrumb_nav3'] ="Nouveau marche";
$_SESSION['link_nav4'] ="";
	$_SESSION['breadcrumb_nav4'] ="";


	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="marches.php";
	$_SESSION['link_nav3'] ="ajouter_marche.php";
?>
<?php require_once('menu.php'); ?>
<div id="page-inner"> 
              <div class="row">

			<form action="gestion.php" name="frm" method="post" 
					onsubmit="return checkForm(document.frm);" >
						<input type="hidden" name="act" value="a"/>
					    <input type="hidden" name="table" value="marches"/>
						<input type="hidden" name="page" value="marches.php"/>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-body">

					    <div class="form-group">
							<label class="control-label"><?php echo "Appel d'offre N° :" ?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "NUM_APPEL_OFFRE" ?>__required" 
									name="NUM_APPEL_OFFRE"  class="form-control input-small"/>
							</div>
					    </div>
					    <div class="form-group">
							<label class="control-label"><?php echo "Marché N° : " ?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "NUM_MARCHE" ?>_required" 
								name="NUM_MARCHE"  class="form-control input-small"/>
							</div>
						</div>
						
					    <div class="form-group">
							<label class="control-label"><?php echo "Objet :" ?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "OBJET" ?>_required" 
									name="OBJET"  class="form-control input-small"/>
							</div>
						</div>
						
					    <div class="form-group">
							<label class="control-label"><?php echo "Maître d'ouvrage : "?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "MAITRE_OUVRAGE" ?>_required" 
									name="MAITRE_OUVRAGE"  class="form-control input-small"/>
							</div>
						</div>


						<div class="form-group">
							<label class="control-label"><?php echo "Montant du Marché :" ?>  </label>
							<div class="controls">
								<input type="number" id="<?php echo "MONTANT_MARCHE" ?>_required" 
									name="MONTANT_MARCHE"  class="form-control input-small"/>
							</div>
						</div>
												<div class="form-group">
							<label class="control-label"><?php echo "Délai du marché :" ?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "DELAI_MARCHE" ?>_required" 
									name="DELAI_MARCHE"  class="form-control input-small" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo "Cahier des préscriptions Spéciales " ?> : </label>
							<div class="controls">
								<input type="text" id="<?php echo "CAHIER_SPECIAL" ?>_required" 
									name="CAHIER_SPECIAL"  class="form-control input-small" />
							</div>
						</div>

					</div>
						</div>
							</div>
				<div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
						
						<div class="form-group">
							<label class="control-label"><?php echo "Date notification :" ?> : </label>
							<div class="controls">
								<input type="date" id="cal_required" 
									name="DATE_NOTIFICATION"  class="form-control input-small" />
							</div>
						</div>

						<div class="form-group">
							<label class="control-label"><?php echo "Date d'enregistrement :" ?> : </label>
							<div class="controls">
								<input type="date" id="cal_required" 
									name="DATE_ENREGISTREMENT"  class="form-control input-small" />
							</div>
						</div>


						<div class="form-group">
							<label class="control-label"><?php echo "Date de commencemet des travaux:" ?> : </label>
							<div class="controls">
								<input type="date" id="cal_required" 
									name="DATE_COMMENCEMENT"  class="form-control input-small" />
							</div>
						</div>
						

						<div class="form-group">
							<label class="control-label"><?php echo "Date de réception Provisoire: " ?> : </label>
							<div class="controls">
								<input type="date" id="cal_required" 
									name="DATE_RECEPTION_PROVISOIRE"  class="form-control input-small" />
							</div>
						</div>


						<div class="form-group">
							<label class="control-label"><?php echo "Date de réception Définitive" ?> : </label>
							<div class="controls">
								<input type="date" id="cal_required" 
									name="DATE_RECEPTION_DEFINITIVE"  class="form-control input-small" />
							</div>
						</div>

						<div class="form-group">
							<label class="control-label"><?php echo "Divers" ?> : </label>
							<div class="controls">
								<textarea id="<?php echo "DIVERS" ?>_required" rows="7"
									name="DIVERS"  class="form-control input-small" ></textarea>
							</div>
						</div>
						<div class="form-actions">
							<input type="submit" class="btn btn-primary" value="<?php echo _AJOUTER ?>" /> ou <a class="text-danger" href="marches.php">Annuler</a>
						</div>
					</div>
				</div>
			</div>
						</form>	
					</div>
			
				</div>
			</div>
		</div>
	</div>
</div>	


<?php require_once('foot.php'); ?>