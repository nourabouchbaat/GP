<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion Personnels";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Personnels";
	$_SESSION['breadcrumb_nav3'] ="Historique de changement du salaire";
	
?>
<?php require_once('menu.php'); ?>
<div id="page-wrapper">
    <div class="header"> 
		<h1 class="page-header">
			Gestion <small> Historique Salaire</small>
		</h1>
		<ol class="breadcrumb">
		  <li><a href="#">Acceuil</a></li>
		  <li><a href="#">Historique Salaire</a></li>
	    </ol> 									
    </div>
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
				<?php 
				
					$sql = "select * from historique_salaire where ID_PERSONNEL=".$_REQUEST['personnels']." order by id";
					$res = doQuery($sql);

					$nb = mysql_num_rows($res);
					if( $nb==0){
					 echo _VIDE;
					}
					else
					{
				?>
				<br/>
					<table class="table table-striped table-bordered table-hover">
				      <thead>
				         <th>Date</th>
				         <th>Nouveau salaire</th>
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
							<td><?php echo $ligne['DATE_CHANGEMENT']?></td>
							<td><?php echo $ligne['NOUEAU_SALAIRE'] ?></td>
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