<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion Pointages";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Pointages";
	$_SESSION['breadcrumb_nav3'] ="";
	
?>
<?php require_once('menu.php'); ?>
<div id="page-wrapper">
		    <div class="header"> 
				<h1 class="page-header">
					Gestion <small> Pointages</small>
				</h1>
				<ol class="breadcrumb">
				  <li><a href="#">Acceuil</a></li>
				  <li><a href="#">Pointages</a></li>
			    </ol> 
									
		    </div>
	<br/>
	<div class="row">
		<div class="col-md-12">
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
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">

		             <div class="panel-body">
		                        
					<br/>
					<div class="widget-content nopadding">
						<div class="row">
                			<div class="col-md-6">
						<form name="frm1" action="" method="post" >
						 <div class="form-group">
							  <label class="control-label">Employe ou chantier:</label>
							      <div class="controls">
							        <input type="text" name="txtrechercher" value="<?php if(isset($_POST['txtrechercher'])) echo $_POST['txtrechercher']; ?>" class="form-control input-small-recherche" />
							     </div>
						 </div>
						 <div class="form-group">
							  <label class="control-label">Date Pointage entre:</label>
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
</div>		
<div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">

		             <div class="panel-body">
		                        
					<br/>
					<div class="widget-content nopadding">
				<?php 
					$where1="";
					if(isset($_POST['txtrechercher']) && !empty($_REQUEST['txtrechercher']))
					 $where1.=" and (ID_PERSONNELS in (select ID from personnels where NOM like '%".$_POST['txtrechercher']."%' or PRENOM like '%".$_POST['txtrechercher']."%' or CODE like '%".$_POST['txtrechercher']."%')  or ID_CHANTIER in (select ID from chantiers where CODE like '%".$_POST['txtrechercher']."%'))";

					if(isset($_POST['dateDebut']) && !empty($_REQUEST['dateDebut']))
					 $where1.=" and DATE_POINTAGE >= DATE_FORMAT('".$_POST['dateDebut']."', '%Y-%m-%d')";

					if(isset($_POST['dateFin']) && !empty($_REQUEST['dateFin']))
					 $where1.=" and DATE_POINTAGE <= DATE_FORMAT('".$_POST['dateFin']."', '%Y-%m-%d')";

					$sql = "select * from pointages where 1=1 ".$where1." order by ID desc";
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
						<tr>
							<th>Date Debut</th>
							<th>Date Fin</th>
							<th>Heur N</th>
							<th>Heur S</th>
							<th>Total</th>
						</tr>
						<tr>
							<td><?php echo isset($_POST['dateDebut']) && !empty($_REQUEST['dateDebut']) && $_POST['dateDebut']!=1?$_REQUEST['dateDebut']:"Non d&eacute;fini" ?></td>
							<td><?php echo isset($_POST['dateFin']) && !empty($_REQUEST['dateFin'])  && $_POST['dateFin']!=1?$_REQUEST['dateFin']:"Non défini" ?></td>
							<td><?php echo getSommeNombreHeurN($where1) ?></td>
							<td><?php echo getSommeNombreHeurS($where1) ?></td>
							<td><?php echo getSommeNombreHeurN($where1)+getSommeNombreHeurS($where1) ?></td>
						</tr>
					</table><br><br>
					<table class="table table-striped table-bordered table-hover" id="dataTables-example">
				      <thead>
				         <th>Code</th>
				         <th>Nom</th>
				         <th>Date</th>
				         <th>Heur normal</th>
				         <th>Heur supplementaire</th>
				         <th>Chantier</th>
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
							<td><?php echo getValeurChamp('CODE','personnels', 'ID',$ligne['ID_PERSONNELS']) ?></td>
							<td><?php echo getValeurChamp('NOM','personnels', 'ID',$ligne['ID_PERSONNELS'])." ".getValeurChamp('PRENOM','personnels', 'ID',$ligne['ID_PERSONNELS']) ?></td>
							<td><?php echo $ligne['DATE_POINTAGE'] ?></td>
							<td><?php echo $ligne['HEUR_N'] ?></td>
							<td><?php echo $ligne['HEUR_S'] ?></td>
							<td><?php echo getValeurChamp('CODE','chantiers', 'ID',$ligne['ID_CHANTIERS']) ?></td>
							<td class="op">
							    &nbsp;
								<a href="modifier_pointage.php?pointages=<?php echo $ligne['ID'] ?>" class="modifier2" title="<?php echo _MODIFIER ?>">
									<i class="glyphicon glyphicon-edit"></i> 
				                </a>
								&nbsp;
								
				                <a href="#ancre" 
				                class="supprimer2" 
				                onclick="javascript:supprimer(
				                							'pointages',
				                                            '<?php echo $ligne['ID'] ?>',
				                                            'pointages.php',
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
				<br/>
				<?php 
				} //Fin If
				?>
 		</div>
	   </div>
	</div>
</div>
</div>
</div>
</div>
<?php require_once('foot.php'); ?>