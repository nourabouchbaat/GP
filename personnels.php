<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion Personnels";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Personnels";
	$_SESSION['breadcrumb_nav3'] ="";
	
?>
<?php require_once('menu.php'); ?>

<br/>
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
<div class="row">
		<div class="col-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="glyphicon glyphicon-align-justify"></i>									
					</span>
					<h5>Recherche</h5>
				</div>
				<div class="widget-content nopadding">
					<form name="frm1" action="" method="post" class="form-horizontal">
						 <div class="form-group">
							  <label class="control-label"><?php echo _RECHERCHER ?>:</label>
							      <div class="controls">
							        <input type="text" name="txtrechercher" value="<?php if(isset($_POST['txtrechercher'])) echo $_POST['txtrechercher']; ?>" class="form-control input-small-recherche" />
							     </div>
						 </div>
						
						<div class="form-actions">
							<input type="submit" name="v" class="btn btn-primary" value="<?php echo _RECHERCHE."r" ?>" />
						
						</div>
					</form>
		</div>
	</div>						
</div>
</div>		
<div class="row">
	<div class="col-12">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="glyphicon glyphicon-th"></i>
				</span>

				<div class="element_float"><h5>Personnels</h5></div>
				<div class="add-element">
				    <a href="ajouter_personnel.php" >
				        <i class="glyphicon glyphicon-plus"></i> &nbsp;<?php echo _AJOUTER ?> Personnel
				    </a>
				</div>
				<div class="element-clear"></div>
			</div>
			<br/>
			<div class="widget-content nopadding">
				<?php 
					$where1="";
					if(isset($_POST['txtrechercher']) and !empty($_REQUEST['txtrechercher']))
					 $where1.="and (nom like '%".$_POST['txtrechercher']."%' or prenom like '%".$_POST['txtrechercher']."%' or cin like '%".$_POST['txtrechercher']."%' or telephone like '%".$_POST['txtrechercher']."%' or cnss like '%".$_POST['txtrechercher']."%' or rib like '%".$_POST['txtrechercher']."%' or 	DATE_EMBAUCHE like '%".$_POST['txtrechercher']."%' or code like '%".$_POST['txtrechercher']."%' or poste like '%".$_POST['txtrechercher']."%' or adresse like '%".$_POST['txtrechercher']."%') ";

				
					$sql = "select * from personnels where status=1 ".$where1." order by id";
					$res = doQuery($sql);

					$nb = mysql_num_rows($res);
					if( $nb==0){
					 echo _VIDE;
					}
					else
					{
				?>
				<br/>
					<table class="table table-bordered table-striped table-hover data-table">
				      <thead>
				         <th>Nom</th>
				         <th>Code</th>
				         <th>Poste</th>
				         <th>CIN</th>
				         <th>CNSS</th>
				         <th>Téléphone</th>
				         <th class="op"> <?php echo _OP ?> </th>
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
							<td><?php echo $ligne['NOM']." ".$ligne['PRENOM'] ?></td>
							<td><?php echo $ligne['CODE'] ?></td>
							<td><?php echo getValeurChamp('POSTE','postes','id',$ligne['ID_POSTES']); ?></td>
							<td><?php echo $ligne['CIN'] ?></td>
							<td><?php echo $ligne['CNSS'] ?></td>
							<td><?php echo $ligne['TELEPHONE'] ?></td>
							<td class="op">
								<a href="detail_personnel.php?personnels=<?php echo $ligne['ID']; ?>" class="detail" title="profil du personnel">
                                	<i class="fa fa-user"></i>
                                </a>
							    &nbsp;
    							<a href="historique_change_salaire.php?personnels=<?php echo $ligne['ID'] ?>" class="paiement" title="Historique de changement du salaire">
				                	<i class="fa fa-th"></i>
				                </a>
							    &nbsp;
							    
						        <a href="remarque_personnel.php?personnels=<?php echo $ligne['ID'] ?>" class="detail" title="Remarque sur personnel">
						                     <i class="glyphicon glyphicon-list-alt"></i>
						                    </a>
						           &nbsp;
								<a href="modifier_personnel.php?personnels=<?php echo $ligne['ID'] ?>" class="modifier2" title="<?php echo _MODIFIER ?>">
									<i class="glyphicon glyphicon-edit"></i> 
				                </a>
								&nbsp;
								
				                <a href="gestion.php?act=archiver_personnel&personnels=<?php echo $ligne['ID'] ?>" 
				                class="supprimer2"  
								title="<?php echo _ARCHIVER ?>">
				                	 <i class="glyphicon glyphicon-remove"></i> 
				                </a>
							</td>
						</tr>
						<?php
							$i++; 
						}
						?>
					  </tbody>
					</table>
				<br/>
				<?php 
				} //Fin If
				?>
 		</div>
	   </div>
	</div>
</div>
<?php require_once('foot.php'); ?>