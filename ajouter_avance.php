<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des avances";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Avances";
	$_SESSION['breadcrumb_nav3'] ="Ajout des avances";
	
	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="avances.php";
	$_SESSION['link_nav3'] ="ajouter_avance.php";
?>
<?php require_once('menu.php'); ?>
			<div class="row">
				<div class="col-lg-12">
					<?php if(isset($_REQUEST['m'])) {?>
							<div class="alert alert-success">
								<?php echo $_REQUEST['m']?>
								<a href="#" data-dismiss="alert" class="close">x</a>
							</div>
					<?php } ?>
				</div>
			</div>
            <div id="page-inner"> 
			  <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
						
                        <div class="panel-body">
                      
			<div class="widget-content nopadding">

<?php 
					$sql = "select * from personnels where STATUS=1 order by ID";
					$res = doQuery($sql);

					$nb = mysql_num_rows($res);
					if( $nb==0){
					 echo _VIDE;
					}
					else
					{
				?>
				<form action="gestion.php" name="frm" method="post" 
					onsubmit="return checkForm(document.frm);" class="form-horizontal">
					<input type="hidden" name="act" value="ajouter_avance"/>
					
				 <table class="table table-striped table-bordered table-hover" id="dataTables-example">
					  <thead>

				         <th>Nom</th>
				         <th>CNSS</th>
				         <th>CIN</th>
				         <th>Code</th>
				         <th style="width:150px">Montant</th>
					</thead>	
					<tbody>
						<?php 
						$i = 0;
						while ($ligne = mysql_fetch_array($res)){
							
							if($i%2==0)
								$c = "c";
							else
								$c = "";	
						?>
						<tr class="<?php echo $c ?>">
							<input type="hidden" name="id_<?php echo $i ?>" value="<?php echo $ligne['ID'] ?>"/>
							<td><?php echo $ligne['NOM']." ".$ligne['PRENOM'] ?></td>
							<td><?php echo $ligne['CNSS'] ?></td>
							<td><?php echo $ligne['CIN'] ?></td>
							<td><?php echo $ligne['CODE'] ?></td>
							<td><input type='number' name='avance_<?php echo $i;  ?>'><?php echo isset($_REQUEST['avance_'.$i]) and !empty($_REQUEST['avance_'.$i])?$_REQUEST['avance_'.$i]:"" ?> </td>
							
						</tr>
						<?php
							$i++; 
						}
						?>
						<input type="hidden" name="nb_personnage" value="<?php echo $i ?>"/>
					  </tbody>
					</table>
				<br/>
				<?php 
				} //Fin If
				?>
				<div class="form-actions">
					<input type="submit" class="btn btn-primary" value="<?php echo _VALIDER ?>" /> ou <a class="text-danger" href="avances.php">Retour</a>
				</div>
			</form>
 		</div>
	   </div>
	</div>
</div>
<?php require_once('foot.php'); ?>