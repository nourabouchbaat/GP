<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Pointage des personnels";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Pointage";
	$_SESSION['breadcrumb_nav3'] ="Ajout des pointages";
$_SESSION['link_nav4'] ="";
	$_SESSION['breadcrumb_nav4'] ="";

	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="pointages.php";
	$_SESSION['link_nav3'] ="ajouter_pointage.php";
	
?>
<?php require_once('menu.php'); ?>
<div class="row">
	<div class="col-12">
	<?php if(isset($_REQUEST['m'])) {?>
			<div class="alert alert-info">
				<?php echo $_REQUEST['m']?>
				<a href="#" data-dismiss="alert" class="close">x</a>
			</div>
		<?php } ?>
	</div>
</div>
<div id="page-inner"> 
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
							<form name="frm1" action="" method="post" >
                       	<div class="col-lg-4">	
								<div class="form-group">
									<?php $checked = isset($_REQUEST['admin']) && !empty($_REQUEST['admin']) ? "checked='true'":""?>
							  		<label class="control-label"><input type="checkbox" name="admin"  onchange="this.form.submit()" <?php echo $checked ?>/>&nbsp;&nbsp;Pointage d'administration:
							        	
							     	</label>
						 		</div>
								<div class="form-actions">
									<input type="submit" name="v" class="btn btn-primary" value="<?php echo _RECHERCHE."r" ?>" />
								</div>
							</div>
						</form>
					</div>						
				</div>
			</div>
		</div>
	</div>

	<hr>
	<div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">
				
                <div class="panel-body">
                      
					<div class="widget-content nopadding">
						<?php 
					$where1="";
					if(isset($_POST['admin']) and !empty($_REQUEST['admin']))
					 $where1.="and admin=1";

					$sql = "select * from personnels where status=1 ".$where1." order by id";
					$res = doQuery($sql);

					$nb = mysql_num_rows($res);
					if( $nb==0){
					 echo _VIDE;
					}
					else
					{
				?>
				<form action="gestion.php" name="frm" method="post" 
					onsubmit="return checkForm(document.frm);" >
						<input type="hidden" name="act" value="ajouter_pointage"/>
					    <input type="hidden" name="table" value="pointages"/>
						<input type="hidden" name="page" value="pointages.php"/>
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
				    
					       <th>Code</th>
				         <th>Nom</th>
				         <th>Date</th>
				         <th>Heur N</th>
				         <th>Heur S</th>
				         <th>Chantier</th>
					</thead>	
					<tbody>
						<?php 
						$i = 0;
						$nb=mysql_num_rows($res);
						while ($ligne = mysql_fetch_array($res)){
							
							if($i%2==0)
								$c = "c";
							else
								$c = "";	
						?>
						<tr class="<?php echo $c ?>">
							<input type="hidden" name="id_<?php echo $i ?>" value="<?php echo $ligne['ID'] ?>"/>
							<td><?php echo $ligne['CODE'] ?></td>
							<td><?php echo $ligne['NOM']." ".$ligne['PRENOM'] ?></td>
							<td><input type="date" id="cal_required" name="DATE_POINTAGE_<?php echo $i ?>"   class="form-control input-small" /></td>
							<td><input type="number" id="<?php echo "HEUR_N" ?>__required" name="HEUR_N_<?php echo $i ?>"  class="form-control input-small"/></td>
							<td><input type="number" id="<?php echo "HEUR_S" ?>__required" name="HEUR_S_<?php echo $i ?>"  class="form-control input-small"/></td>
							<td><?php echo getTableList('chantiers','ID_CHANTIER_'.$i,$valeur,'CODE',$change,$where,$libelle) ?></td>
						<input type="hidden" name="nb_personnage" value="<?php echo $nb ?>"/>
						</tr>
						<?php
							$i++; 
						}
						?>
					  </tbody>
					</table>
				<?php 
				} //Fin If
				?>
				<div class="form-actions">
							<input type="submit" class="btn btn-primary" value="<?php echo _VALIDER ?>" /> ou <a class="text-danger" href="personnels.php">Annuler</a>
						</div>
					</form>
 			</div>
       <!--End Advanced Tables -->
        </div>
    </div>
</div>
<?php require_once('foot.php'); ?>