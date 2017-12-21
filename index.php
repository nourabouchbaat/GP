*<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Statistique & Tableau de bord";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="";
	$_SESSION['breadcrumb_nav3'] ="";
?>
<?php require_once('menu.php'); ?>     
	 <!-- /. NAV SIDE  -->
            <div id="page-inner"> 
				<div class="row">
				<div class="col-xs-6 col-md-3">
					<div class="panel panel-default">
						<div class="panel-body easypiechart-panel">
							<h4>Personnels</h4>
							<div class="easypiechart" id="easypiechart-blue" data-percent="82" >
							<a href="personnels.php"><img src="images/client-icone-9349-64.png"  alt="personnels" title="personnels"/></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="panel panel-default">
						<div class="panel-body easypiechart-panel">
							<h4>Pointages</h4>
							<div class="easypiechart" id="easypiechart-orange" data-percent="55" >								
								<a href="pointages.php"> <img src="images/controle-poitage-icone.png"  alt="Pointages" title="Pointages"/></a>

							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="panel panel-default">
						<div class="panel-body easypiechart-panel">
							<h4>Paiements</h4>
							<div class="easypiechart" id="easypiechart-teal" data-percent="84" >								
								<a href="paiements.php"><img src="images/argent-paiement-icone.png" alt="Paiements" title="Paiements" /></a>

							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="panel panel-default">
						<div class="panel-body easypiechart-panel">
							<h4>Les avances</h4>
							<div class="easypiechart" id="easypiechart-red" data-percent="46" >								
								<a href="avances.php"> <img src="images/okteta-icone-7976-64.png" alt="Avances" title="Avances"/> </a>

							</div>
						</div>
					</div>
				</div>
		</div><!--/.row-->
			<div class="row">
				<div class="col-xs-6 col-md-3">
					<div class="panel panel-default">
						<div class="panel-body easypiechart-panel">
							<h4>Marches</h4>
							<div class="easypiechart" id="easypiechart-blue" data-percent="82" >
							<a href="marches.php"><img src="images/projet-icon.jpg"  alt="Marchies" title="Marchies" style="border-radius: 7px;"/></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="panel panel-default">
						<div class="panel-body easypiechart-panel">
							<h4>Chantiers</h4>
							<div class="easypiechart" id="easypiechart-orange" data-percent="55" >								
								<a href="chantiers.php"> <img src="images/chantier-travaux.png"  alt="Chantiers" title="Chantiller"/></a>

							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="panel panel-default">
						<div class="panel-body easypiechart-panel">
							<h4>Postes</h4>
							<div class="easypiechart" id="easypiechart-teal" data-percent="84" >								
								<a href="postes.php"><img src="images/ouvrier-du-batiment-96.png" alt="Postes" title="Postes" /></a>

							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3">
					<div class="panel panel-default">
						<div class="panel-body easypiechart-panel">
							<h4>Administrations</h4>
							<div class="easypiechart" id="easypiechart-red" data-percent="46" >								
								<a href="ajouter_ventes.php"> <img src="images/admin-user.png" alt="Marchies" title="Marchies"/> </a>

							</div>
						</div>
					</div>
				</div>
		</div><!--/.row-->


			<footer><p>All right reserved. Template by: <a href="http://webthemez.com/">WebThemez.com</a></p></footer>
			</div>
             <!-- /. PAGE INNER  -->
        </div>
<?php require_once('foot.php'); ?>