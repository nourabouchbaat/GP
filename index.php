<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Statistique & Tableau de bord";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "";
$_SESSION['breadcrumb_nav3'] = "";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "";
$_SESSION['link_nav3'] = "";
$_SESSION['link_nav4'] = "";
$_SESSION['breadcrumb_nav4'] = "";
?>
<?php require_once('menu.php'); ?>     
<!-- /. NAV SIDE  -->
<div id="page-inner"> 
    <div class="row">
        <div class="col-xs-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body easypiechart-panel">
                    <h4><a href="personnels.php">Personnels</a></h4>
                    <div class="easypiechart" id="easypiechart-blue" data-percent="82" >
                        <a href="personnels.php"><img src="images/client-icone-9349-64.png"  alt="personnels" title="personnels"/></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body easypiechart-panel">
                    <h4><a href="pointages.php">Pointages</a></h4>
                    <div class="easypiechart" id="easypiechart-orange" data-percent="55" >								
                        <a href="pointages.php"> <img src="images/controle-poitage-icone.png"  alt="Pointages" title="Pointages"/></a>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body easypiechart-panel">
                    <h4><a href="paiements.php">Paiements</a></h4>
                    <div class="easypiechart" id="easypiechart-teal" data-percent="84" >								
                        <a href="paiements.php"><img src="images/argent-paiement-icone.png" alt="Paiements" title="Paiements" /></a>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body easypiechart-panel">
                    <h4><a href="avances.php">Credits / Avances</a></h4>
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
                    <h4><a href="marches.php">Marches</a></h4>
                    <div class="easypiechart" id="easypiechart-blue" data-percent="82" >
                        <a href="marches.php"><img src="images/projet-icon.jpg"  alt="Marchies" title="Marchies" style="border-radius: 7px;"/></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body easypiechart-panel">
                    <h4><a href="database.php">Base de données</a></h4>
                    <div class="easypiechart" id="easypiechart-orange" data-percent="55" >								
                        <a href="database.php"> <img src="images/archiver-dossier-services-publics-icone-7132-64.png"  alt="archiver  database" title="archiver database"/></a>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body easypiechart-panel">
                    <h4><a href="postes.php">Postes</a></h4>
                    <div class="easypiechart" id="easypiechart-teal" data-percent="84" >								
                        <a href="postes.php"><img src="images/ouvrier-du-batiment-96.png" alt="Postes" title="Postes" /></a>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-body easypiechart-panel">
                    <h4><a href="ajouter_ventes.php">Deconnexion</a></h4>
                    <div class="easypiechart" id="easypiechart-red" data-percent="46" >								
                        <a href="deconnexion.php"> <img src="images/deconnexion.png" alt="Marchies" title="Marchies"/> </a>

                    </div>
                </div>
            </div>
        </div>
    </div><!--/.row-->
</div>

<?php require_once('foot.php'); ?>