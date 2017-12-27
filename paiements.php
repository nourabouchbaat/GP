<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion des Paiements";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Paiements";
	$_SESSION['breadcrumb_nav3'] ="";
	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="paiements.php";
	$_SESSION['link_nav3'] ="";
		$_SESSION['link_nav4'] ="";
	$_SESSION['breadcrumb_nav4'] ="";
	
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
                       	<div class="col-lg-12">	
							<form name="frm1" action="" method="post" >
								 <div class="form-group">
									  <label class="control-label">Employe ou chantier:</label>
									      <div class="controls">
									        <input type="text" name="txtrechercher" value="<?php if(isset($_POST['txtrechercher'])) echo $_POST['txtrechercher']; ?>" class="form-control input-small-recherche" />
									     </div>
								 </div>
								 <div class="form-group">
									  <label class="control-label">Date Paiement entre:</label>
									      <div class="controls">
									        <input type="date" id="cal_required" name="dateDebut"  value="<?php if(isset($_POST['dateDebut'])) echo $_POST['dateDebut']; ?>" class="form-control input-small" />
									     </div>
								 </div>
								 <div class="form-group">
									  <label class="control-label">Et :</label>
									      <div class="controls">
									       <input type="date" id="cal_required" name="dateFin"  value="<?php if(isset($_POST['dateFin'])) echo $_POST['dateFin']; ?>"   class="form-control input-small" />
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
							if(isset($_POST['txtrechercher']) && !empty($_REQUEST['txtrechercher']))
							 $where1.=" and (ID_PERSONNELS in (select ID from personnels where status=1 and  NOM like '%".$_POST['txtrechercher']."%' or PRENOM like '%".$_POST['txtrechercher']."%' or CODE like '%".$_POST['txtrechercher']."%')  or ID_CHANTIER in (select ID from chantiers where CODE like '%".$_POST['txtrechercher']."%'))";

							if(isset($_POST['dateDebut']) && !empty($_REQUEST['dateDebut']))
							 $where1.=" and DATE_PAIEMENT >= DATE_FORMAT('".$_POST['dateDebut']."', '%Y-%m-%d')";

							if(isset($_POST['dateFin']) && !empty($_REQUEST['dateFin']))
							 $where1.=" and DATE_PAIEMENT <= DATE_FORMAT('".$_POST['dateFin']."', '%Y-%m-%d')";

							$sql = "select * from paiements where 1=1 ".$where1." order by ID desc";
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
								<tr>
							         <th>Code</th>
							         <th>Nom</th>
							         <th>Date Paiement</th>
							         <th>Depuis</th>
							         <th>Jusqu'a</th>
							         <th>Somme des heurs</th>
							         <th>Somme des  jours</th>
							         <th>Montant</th>
							         <th>Avances</th>
							         <th>Credit</th>
							         <th>Net à payer</th>
							         <th class="op"> <?php echo _OP ?> </th>
						    	 </tr>
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
									<td><?php echo getValeurChamp('CODE','personnels', 'ID',$ligne['ID_PERSONNELS']) ?></td>
									<td><?php echo getValeurChamp('NOM','personnels', 'ID',$ligne['ID_PERSONNELS'])." ".getValeurChamp('PRENOM','personnels', 'ID',$ligne['ID_PERSONNELS']) ?></td>
									<td><?php echo $ligne['DATE_PAIEMENT'] ?></td>
									<td><?php echo $ligne['DATE_POINTAGE_START'] ?></td>
									<td><?php echo $ligne['DATE_POINTAGE_END'] ?></td>
									<td><?php echo $ligne['SOMME_HEUR_N']+$ligne['SOMME_HEUR_S'] ?></td>
									<td><?php echo number_format(($ligne['SOMME_HEUR_N']+$ligne['SOMME_HEUR_S'])/9, 0, '.', ''); ?></td>
									<td><?php echo $ligne['MONTANT'] ?> Dh</td>
									<td><?php echo $ligne['AVANCE'] ?> Dh</td>
									<td><?php echo $ligne['CREDIT'] ?> Dh</td>
									<td><?php echo $ligne['NETAPAYER'] ?> Dh</td>
									<td class="op">
									    &nbsp;
										<a href="modifier_paiement.php?paiements=<?php echo $ligne['ID'] ?>" class="modifier2" title="<?php echo _MODIFIER ?>">
											<i class="glyphicon glyphicon-edit"></i> 
						                </a>
										&nbsp;
										
						                <a href="#ancre" 
						                class="supprimer2" 
						                onclick="javascript:supprimer(
						                							'paiements',
						                                            '<?php echo $ligne['ID'] ?>',
						                                            'paiements.php',
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