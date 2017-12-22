<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion Personnels";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Personnels";
	$_SESSION['breadcrumb_nav3'] ="";
	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="personnels.php";
	$_SESSION['link_nav3'] ="";

	
?>
<?php require_once('menu.php'); ?>
<div id="page-inner"> 
	<div class="row">
        <div class="col-md-12">
        <!-- Advanced Tables -->
            <div class="panel panel-default">
	            <div class="panel-body">
					<div class="table-responsive">
				<?php 				
					$sql = "select * from personnels where status=1 order by id";
					$res = doQuery($sql);

					$nb = mysql_num_rows($res);
					if( $nb==0){
					 echo _VIDE;
					}
					else
					{
				?>
				<br/>
				<table class="table table-striped table-bordered table-hover" id="dataTables-example">
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
						<?php 
						} //Fin If
						?>
					</div>
                </div>
            </div>
       <!--End Advanced Tables -->
        </div>
    </div>
</div>
             <!-- /. PAGE INNER  -->			 
<?php require_once('foot.php'); ?>