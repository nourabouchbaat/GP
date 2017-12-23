<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des avances";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Marches";
	$_SESSION['breadcrumb_nav3'] ="Caution Retenue de Garantie";
	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="marches.php";
	$_SESSION['link_nav3'] ="caution_retenue_garanties.php";
	$_SESSION['link_nav4'] ="";
	$_SESSION['breadcrumb_nav4'] ="";

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
								<a href="ajouter_caution_retenue_garantie.php?marches=<?php echo $_REQUEST['marches'] ?>"><i class="glyphicon glyphicon-plus"></i> Ajouter caution retenue de garantie</a>
							</div>
						</div>

                        <div class="panel-body">
                      
			<div class="widget-content nopadding">
				<?php 
				 	$sql = "select * from caution_retenue_garantie where ID_MARCHE=".$_REQUEST['marches']." order by ID desc";
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
				    	<tr class="<?php echo $c ?>">
					         <th>Date Caution provisoire </th>
					         <th>N° Caution</th>
					         <th>Montant</th>
					         <th>Banque</th>
				        	 <th class="op" style="width:100px"> <?php echo _OP ?> </th>
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
							<td><?php echo $ligne['DATE_CAUTION_RETENUE_GARANTIE'] ?></td>
							<td><?php echo $ligne['N_CAUTION'] ?></td>
							<td><?php echo $ligne['MONTANT'] ?></td>
							<td><?php echo $ligne['BANQUE'] ?></td>
							<td class="op">
							    &nbsp;
								<a href="modifier_caution_retenue_garantie.php?marches=<?php echo $_REQUEST['marches'] ?>&caution_retenue_garanties=<?php echo $ligne['ID'] ?>" class="modifier2" title="<?php echo _MODIFIER ?>">
									<i class="glyphicon glyphicon-edit"></i> 
				                </a>
								&nbsp;
								
				                <a href="#ancre" 
				                class="supprimer2" 
				                onclick="javascript:supprimer(
				                							'caution_retenue_garantie',
				                                            '<?php echo $ligne['ID'] ?>',
				                                            'caution_retenue_garanties.php',
				                                            'marches',
				                                            '<?php echo $_REQUEST['marches'] ?>')
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
    </div>
<?php require_once('foot.php'); ?>