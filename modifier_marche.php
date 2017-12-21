
<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des marches";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Marche";
	$_SESSION['breadcrumb_nav3'] ="Modifier marche";


	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="marches.php";
	$_SESSION['link_nav3'] ="ajouter_marche.php";
?>
<?php require_once('menu.php'); ?>
<div id="page-inner"> 
              <div class="row">

			<form action="gestion.php" name="frm" method="post" 
					onsubmit="return checkForm(document.frm);" >
						<input type="hidden" name="act" value="m"/>
					    <input type="hidden" name="table" value="marches"/>
						<input type="hidden" name="page" value="marches.php"/>

						<input type="hidden" name="id_nom" value="ID"/>
						<input type="hidden" name="id_valeur" value="<?php echo $_REQUEST['marches'] ?>"/>	
					    
					    <input type="hidden" name="id_noms_retour" value="marches"/>
						<input type="hidden" name="id_valeurs_retour" value="<?php echo $_REQUEST['marches'] ?>"/>	

                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-body">

					    <div class="form-group">
							<label class="control-label"><?php echo "Code :" ?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "CODE" ?>__required" value="<?php echo getValeurChamp('CODE','marches','ID',$_REQUIRED['marches']); ?>"
									name="CODE"  class="form-control input-small"/>
							</div>
					    </div>
						
					    <div class="form-group">
							<label class="control-label"><?php echo "Appel d'offre N° :" ?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "NUM_APPEL_OFFRE" ?>__required" value="<?php echo getValeurChamp('NUM_APPEL_OFFRE','marches','ID',$_REQUIRED['marches']); ?>"
									name="NUM_APPEL_OFFRE"  class="form-control input-small"/>
							</div>
					    </div>

					    <div class="form-group">
							<label class="control-label"><?php echo "Marché N° : " ?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "NUM_MARCHE" ?>_required"  value="<?php echo getValeurChamp('NUM_MARCHE','marches','ID',$_REQUIRED['marches']); ?>"
								name="NUM_MARCHE"  class="form-control input-small"/>
							</div>
						</div>
						
					    <div class="form-group">
							<label class="control-label"><?php echo "Objet :" ?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "OBJET" ?>_required"  value="<?php echo getValeurChamp('OBJET','marches','ID',$_REQUIRED['marches']); ?>"
									name="OBJET"  class="form-control input-small"/>
							</div>
						</div>
						
					    <div class="form-group">
							<label class="control-label"><?php echo "Maître d'ouvrage : "?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "MAITRE_OUVRAGE" ?>_required"  value="<?php echo getValeurChamp('MAITRE_OUVRAGE','marches','ID',$_REQUIRED['marches']); ?>"
									name="MAITRE_OUVRAGE"  class="form-control input-small"/>
							</div>
						</div>


						<div class="form-group">
							<label class="control-label"><?php echo "Montant du Marché :" ?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "MONTANT_MARCHE" ?>_required"  value="<?php echo getValeurChamp('MONTANT_MARCHE','marches','ID',$_REQUIRED['marches']); ?>"
									name="MONTANT_MARCHE"  class="form-control input-small"/>
							</div>
						</div>
												<div class="form-group">
							<label class="control-label"><?php echo "Délai du marché :" ?>  </label>
							<div class="controls">
								<input type="text" id="<?php echo "DELAI_MARCHE" ?>_required"  value="<?php echo getValeurChamp('DELAI_MARCHE','marches','ID',$_REQUIRED['marches']); ?>"
									name="DELAI_MARCHE"  class="form-control input-small" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo "Cahier des préscriptions Spéciales " ?> : </label>
							<div class="controls">
								<input type="text" id="<?php echo "CAHIER_SPECIAL" ?>_required"  value="<?php echo getValeurChamp('CAHIER_SPECIAL','marches','ID',$_REQUIRED['marches']); ?>"
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
								<input type="date" id="cal_required"  value="<?php echo getValeurChamp('DATE_NOTIFICATION','marches','ID',$_REQUIRED['marches']); ?>"
									name="DATE_NOTIFICATION"  class="form-control input-small" />
							</div>
						</div>

						<div class="form-group">
							<label class="control-label"><?php echo "Date d'enregistrement :" ?> : </label>
							<div class="controls">
								<input type="date" id="cal_required"  value="<?php echo getValeurChamp('DATE_ENREGISTREMENT','marches','ID',$_REQUIRED['marches']); ?>"
									name="DATE_ENREGISTREMENT"  class="form-control input-small" />
							</div>
						</div>


						<div class="form-group">
							<label class="control-label"><?php echo "Date de commencemet des travaux:" ?> : </label>
							<div class="controls">
								<input type="date" id="cal_required"  value="<?php echo getValeurChamp('DATE_COMMENCEMENT','marches','ID',$_REQUIRED['marches']); ?>"
									name="DATE_COMMENCEMENT"  class="form-control input-small" />
							</div>
						</div>
						

						<div class="form-group">
							<label class="control-label"><?php echo "Date de réception Provisoire: " ?> : </label>
							<div class="controls">
								<input type="date" id="cal_required"  value="<?php echo getValeurChamp('DATE_RECEPTION_PROVISOIRE','marches','ID',$_REQUIRED['marches']); ?>"
									name="DATE_RECEPTION_PROVISOIRE"  class="form-control input-small" />
							</div>
						</div>


						<div class="form-group">
							<label class="control-label"><?php echo "Date de réception Définitive" ?> : </label>
							<div class="controls">
								<input type="date" id="cal_required"  value="<?php echo getValeurChamp('DATE_RECEPTION_DEFINITIVE','marches','ID',$_REQUIRED['marches']); ?>" 
									name="DATE_RECEPTION_DEFINITIVE"  class="form-control input-small" />
							</div>
						</div>

						<div class="form-group">
							<label class="control-label"><?php echo "Divers" ?> : </label>
							<div class="controls">
								<textarea id="<?php echo "DIVERS" ?>_required" rows="7" 
									name="DIVERS"  class="form-control input-small" > <?php echo getValeurChamp('DIVERS','marches','ID',$_REQUIRED['marches']); ?></textarea>
							</div>
						</div>
						<div class="form-actions">
							<input type="submit" class="btn btn-primary" value="<?php echo _MODIFIER ?>" /> ou <a class="text-danger" href="marches.php">Annuler</a>
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