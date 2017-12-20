
<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des personnels";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Personnels";
	$_SESSION['breadcrumb_nav3'] ="Mis a jour du personnel";
?>
<?php require_once('menu.php'); ?>

	 <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
		    <div class="header"> 
				<h1 class="page-header">
					Nouveau <small> Personnel.</small>
				</h1>
				<ol class="breadcrumb">
				  <li><a href="#">Accueil</a></li>
				  <li><a href="#">Nouveau</a></li>
				</ol> 									
		    </div>
			<div id="page-inner"> 
              <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                            	<div class="col-lg-6">	
					<form action="gestion.php" name="frm" method="post" 
					onsubmit="return checkForm(document.frm);" >
						<input type="hidden" name="act" value="m"/>
					    <input type="hidden" name="table" value="personnels"/>
						<input type="hidden" name="page" value="personnels.php"/>
						
						<input type="hidden" name="id_nom" value="ID"/>
						<input type="hidden" name="id_valeur" value="<?php echo $_REQUEST['personnels'] ?>"/>	
					    
					    <input type="hidden" name="id_noms_retour" value="personnels"/>
						<input type="hidden" name="id_valeurs_retour" value="<?php echo $_REQUEST['personnels'] ?>"/>	
						
					    <div class="form-group">
							<label class="control-label"><?php echo "nom" ?> : </label>
								<input type="text" id="<?php echo "NOM" ?>__required" value="<?php echo getValeurChamp('NOM','personnels','ID',$_REQUEST['personnels']); ?>"
									name="NOM"  class="form-control"/>
					    </div>
					    <div class="form-group">
							<label class="control-label"><?php echo "prenom" ?> : </label>
								<input type="text" id="<?php echo "PRENOM" ?>_required"  value="<?php echo getValeurChamp('PRENOM','personnels','ID',$_REQUEST['personnels']); ?>"
								name="PRENOM"  class="form-control"/>							
						</div>
						
					    <div class="form-group">
							<label class="control-label"><?php echo "CIN" ?> : </label>
								<input type="text" id="<?php echo "CIN" ?>_required"  value="<?php echo getValeurChamp('CIN','personnels','ID',$_REQUEST['personnels']); ?>"
									name="CIN"  class="form-control input-small"/>
						</div>
						
					    <div class="form-group">
							<label class="control-label"><?php echo "TELEPHONE"?> : </label>
								<input type="text" id="<?php echo "TELEPHONE" ?>_required"  value="<?php echo getValeurChamp('TELEPHONE','personnels','ID',$_REQUEST['personnels']); ?>"
									name="TELEPHONE"  class="form-control input-small"/>
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo "ADRESSE" ?> : </label>
								<input type="text" id="<?php echo "ADRESSE" ?>_required"  value="<?php echo getValeurChamp('ADRESSE','personnels','ID',$_REQUEST['personnels']); ?>"
									name="ADRESSE"  class="form-control input-small" />
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo "CNSS" ?> : </label>
								<input type="text" id="<?php echo "CNSS" ?>_required"  value="<?php echo getValeurChamp('CNSS','personnels','ID',$_REQUEST['personnels']); ?>"
									name="CNSS"  class="form-control input-small" />
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo "RIB" ?> : </label>
								<input type="text" id="<?php echo "RIB" ?>_required"  value="<?php echo getValeurChamp('RIB','personnels','ID',$_REQUEST['personnels']); ?>"
									name="RIB"  class="form-control input-small" />
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo "Date d'embauche" ?> : </label>
								<input type="date" id="cal_required"  value="<?php echo getValeurChamp('DATE_EMBAUCHE','personnels','ID',$_REQUEST['personnels']); ?>"
									name="DATE_EMBAUCHE"  class="form-control input-small" />
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo "Type" ?> : </label>
								<?php $v=  getValeurChamp('TYPE','personnels','ID',$_REQUEST['personnels']);
									$isSalarie = $v=="Salarie" ? "selected='selected'":"";
									$isOuvrier = $v=="Ouvrier" ? "selected='selected'":"";
								 ?>
								<select name = "TYPE" id="TYPE_required">	
									<option value="">- -  - - _</option>
									<option <?php echo $isSalarie; ?> value="Salarie">Salarie</option>
									<option <?php echo $isOuvrier; ?>  value="Ouvrier">Ouvrier</option>
								</select>
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo "Salaire mensuel" ?> : </label>
								<input type="text" id="<?php echo "SALAIRE_MENSUELLE" ?>_required"  value="<?php echo getValeurChamp('SALAIRE_MENSUELLE','personnels','ID',$_REQUEST['personnels']); ?>"
									name="SALAIRE_MENSUELLE"  class="form-control input-small" />
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo "Tarif journaliere" ?> : </label>
								<input type="text" id="<?php echo "TARIF_JOURNALIERS" ?>_required"  value="<?php echo getValeurChamp('TARIF_JOURNALIERS','personnels','ID',$_REQUEST['personnels']); ?>"
									name="TARIF_JOURNALIERS"  class="form-control input-small" />
						</div>
						<div class="form-group">
							<label class="control-label"><?php echo "Code" ?> : </label>
								<input type="text" id="<?php echo "CODE" ?>_required" value="<?php echo getValeurChamp('CODE','personnels','ID',$_REQUEST['personnels']); ?>"
									name="CODE"  class="form-control input-small" />
						</div>
                        <div class="form-group">
                            <label class="control-label">Poste : </label>
                            <?php echo getTableList('postes','ID_POSTES',getValeurChamp('ID_POSTES','personnels','ID',$_REQUEST['personnels']),'POSTE',$change,$where,$libelle) ?>
                        </div>
						<div class="form-actions">
							<input type="submit" class="btn btn-primary" value="<?php echo _MODIFIER ?>" /> ou <a class="text-danger" href="personnels.php">Annuler</a>
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
<?php require_once('foot.php'); ?>