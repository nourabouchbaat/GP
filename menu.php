        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <a href="index.php"><i class="fa fa-dashboard"></i> Accueil</a>
                    </li>
                    <li>
                        <a href="#"><i class="glyphicon glyphicon-road"></i> Marches<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="marches.php" >
                                        <i class="glyphicon glyphicon-list-alt"></i> Liste des marches
                                    </a>
                            </li>
                            <li>
                                <a href="ajouter_marche.php" >
                                        <i class="fa fa-plus-circle"></i> &nbsp;<?php echo _AJOUTER ?> march&eacute;
                                    </a>
                            </li>
                        </ul>
                    </li>
					<li>
                        <a href="#"><i class="glyphicon glyphicon-user"></i> Personnels<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="personnels.php" >
                                        <i class="glyphicon glyphicon-list-alt"></i> Liste Personnels
                                    </a>
                            </li>
                            <li>
                                <a href="ajouter_personnel.php" >
                                        <i class="fa fa-plus-circle"></i> &nbsp;<?php echo _AJOUTER ?> personnel
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="glyphicon glyphicon-time"></i> Pointages<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="pointages.php" >
                                        <i class="glyphicon glyphicon-list-alt"></i> Liste Pointages
                                    </a>
                            </li>
                            <li>
                                <a href="ajouter_pointage.php" >
                                        <i class="fa fa-plus-circle"></i> &nbsp;<?php echo _AJOUTER ?> pointages
                                    </a>
                            </li>
                        </ul></a>
                    </li>                    
                    <li>
                        <a href="#"><i class="glyphicon glyphicon-usd"></i> Paiements<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="paiements.php" >
                                        <i class="glyphicon glyphicon-list-alt"></i> Liste paiements
                                    </a>
                            </li>
                            <li>
                                <a href="ajouter_paiement.php" >
                                        <i class="fa fa-plus-circle"></i> &nbsp;<?php echo _AJOUTER ?> paiements
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="glyphicon glyphicon-euro"></i> Avances<span class="fa arrow"></span> </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="avances.php" >
                                        <i class="glyphicon glyphicon-list-alt"></i> Liste avances
                                    </a>
                            </li>
                            <li>
                                <a href="ajouter_avance.php" >
                                        <i class="fa fa-plus-circle"></i> &nbsp;<?php echo _AJOUTER ?> avance
                                    </a>
                            </li>
                        </ul>
                    </li>
					<li>
                        <a href="#"><i class="glyphicon glyphicon-wrench"></i> Postes <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="postes.php" >
                                        <i class="glyphicon glyphicon-list-alt"></i> Liste des postes
                                    </a>
                            </li>
                            <li>
                                <a href="ajouter_poste.php" >
                                        <i class="fa fa-plus-circle"></i> &nbsp;<?php echo _AJOUTER ?> un poste
                                    </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="database.php"><i class="fa fa-dashboard"></i> Base de donnée</a>
                    </li>
                    <li>
                        <a href="deconnexion.php"><i class="fa fa-sign-out fa-fw"></i> Deconnexion</a>
                    </li>
 
                </ul>

            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div class="header"> 
                <h1 class="page-header">
                    <?php echo $_SESSION['titre'] ?>
                </h1>
                <ol class="breadcrumb">
                  <li><a href="<?php echo $_SESSION['link_nav1'] ?>"><?php echo $_SESSION['breadcrumb_nav1'] ?></a></li>
                  <?php if(isset($_SESSION['breadcrumb_nav2']) && !empty($_SESSION['breadcrumb_nav2'])){ ?>
                  <li><a href="<?php echo $_SESSION['link_nav2'] ?>"><?php echo $_SESSION['breadcrumb_nav2'] ?></a></li>
                  <?php } if(isset($_SESSION['breadcrumb_nav3']) && !empty($_SESSION['breadcrumb_nav3'])){ ?>
                  <li><a href="<?php echo $_SESSION['link_nav3'] ?>"><?php echo $_SESSION['breadcrumb_nav3'] ?></a></li>
                <?php }  if(isset($_SESSION['breadcrumb_nav4']) && !empty($_SESSION['breadcrumb_nav4'])){ ?>
                  <li><a href="<?php echo $_SESSION['link_nav4'] ?>"><?php echo $_SESSION['breadcrumb_nav4'] ?></a></li>
                <?php } ?>
                </ol>                                   
            </div>          