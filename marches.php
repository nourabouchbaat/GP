<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des marches";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Marches";
	$_SESSION['breadcrumb_nav3'] ="";
	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="marches.php";
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
				
					$sql = "select * from marches ".$where1." order by id";
					$res = doQuery($sql);

					$nb = mysql_num_rows($res);
					if( $nb==0){
					 echo _VIDE;
					}
					else
					{
				?>
				<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				      <thead>
				      <thead>
				         <th>Code</th>
				         <th>Appel d'offre N°</th>
				         <th>Marché N°</th>
				         <th>Objet</th>
				         <th>Maître d'ouvrage</th>
				         <th style="width:150px">Ordres et caustions</th>
				         
				         <th  style="width:150px" class="op"> <?php echo _OP ?> </th>
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
							<td><?php echo $ligne['CODE'] ?></td>
							<td><?php echo $ligne['NUM_APPEL_OFFRE'] ?></td>
							<td><?php echo $ligne['NUM_MARCHE'] ?></td>
							<td><?php echo $ligne['OBJET'] ?></td>
							<td><?php echo $ligne['MAITRE_OUVRAGE'] ?></td>
							<td class="op">
							    <a href="caution_definitives.php?marches=<?php echo $ligne['ID'] ?>" class="detail" title="les cautions definitives">
						                     CD
						                    </a>
						        &nbsp;
							    <a href="caution_provisoires.php?marches=<?php echo $ligne['ID'] ?>" class="detail" title="les cautions provisoires">
						                     CP
						                    </a>
						        &nbsp;
							    <a href="caution_retenue_garanties.php?marches=<?php echo $ligne['ID'] ?>" class="detail" title="les cautions de retenue garantie">
						                     CRG
						                    </a>
						           &nbsp;

							    <a href="ordre_arrets.php?marches=<?php echo $ligne['ID'] ?>" class="detail" title="les ordres d'arr&ecirc;t">
						                     OA
						                    </a>
						           &nbsp;

							    <a href="ordre_reprises.php?marches=<?php echo $ligne['ID'] ?>" class="detail" title="les ordres de reprise">
						                     OR
						                    </a>
						           &nbsp;
						</td>
							<td class="op">
								<a href="detail_marche.php?marches=<?php echo $ligne['ID']; ?>" class="detail" title="detail du march&eacute;">
                                	<i class="glyphicon glyphicon-inbox"></i>
                                </a>
							    &nbsp;
    							<a href="chantiers.php?marches=<?php echo $ligne['ID'] ?>" class="paiement" title="les chantiers du march&eacute;">
				                	<i class="glyphicon glyphicon-road"></i>
				                </a>
							    &nbsp;
								<a href="modifier_marche.php?marches=<?php echo $ligne['ID'] ?>" class="modifier2" title="<?php echo _MODIFIER ?>">
									<i class="glyphicon glyphicon-edit"></i> 
				                </a>
								&nbsp;
								  <a href="#ancre" 
						                class="supprimer2" 
						                onclick="javascript:supprimer(
						                							'marches',
						                                            '<?php echo $ligne['ID'] ?>',
						                                            'marches.php',
						                                            '1',
						                                            '1')
												" 
										title="<?php echo _SUPPRIMER ?>">
						                	
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