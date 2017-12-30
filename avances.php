<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des avances et credits";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Avances et credits";
	$_SESSION['breadcrumb_nav3'] ="";
	$_SESSION['breadcrumb_nav4'] ="";
	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="avances.php";
	$_SESSION['link_nav4'] ="";
	$_SESSION['link_nav4'] ="";

?>
<?php require_once('menu.php'); ?>

            <div id="page-inner"> 
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
			  <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
						
                        <div class="panel-body">
                      
			<div class="widget-content nopadding">
				<?php 
					$sql = "select * from avances order by ID desc";
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
					        <th>Code</th>
				    	     <th>Nom</th>
				        	 <th>Date</th>
				        	 <th>Type</th>
				         	<th>Montant</th>
				         <th class="op"> <?php echo _OP ?> </th>
					</thead>	
					<tbody>
						<?php 
						$i = 0;
						while ($ligne = mysql_fetch_array($res)){
							
							if($ligne['type']=="credit")
								$c = "credit";
							else
								$c = "avance";	
						?>
						<tr class="<?php echo $c ?>">
							<td><?php echo getValeurChamp('CODE','personnels', 'ID',$ligne['ID_PERSONNELS']) ?></td>
							<td><?php echo getValeurChamp('NOM','personnels', 'ID',$ligne['ID_PERSONNELS'])." ".getValeurChamp('PRENOM','personnels', 'ID',$ligne['ID_PERSONNELS']) ?></td>
							<td><?php echo $ligne['DATE_EMPREINTE'] ?></td>
							<td><?php echo $ligne['type'] ?></td>
							<td><?php echo $ligne['MONTANT'] ?> Dh</td>
							<td class="op">
							    &nbsp;
								<a href="modifier_avances.php?avances=<?php echo $ligne['ID'] ?>" class="modifier2" title="<?php echo _MODIFIER ?>">
									<i class="glyphicon glyphicon-edit"></i> 
				                </a>
								&nbsp;
								
				                <a href="#ancre" 
				                class="supprimer2" 
				                onclick="javascript:supprimer(
				                							'avances',
				                                            '<?php echo $ligne['ID'] ?>',
				                                            'avances.php',
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
    </div>
<?php require_once('foot.php'); ?>