<?php session_start() ?>
<?php error_reporting(0) ?>
<?php require_once('lang.php'); ?>
<?php require_once('fonctions.php'); ?>
<?php require_once('tabs.php'); ?>

<html >
<head>
    <meta charset="iso-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SOMLAKO</title>
	<!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
     <!-- Google Fonts-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <!-- TABLE STYLES-->
   <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
   
   <script type="text/javascript" src="assets/js/javascript.js"></script>
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index-2.html"><strong>SOMLAKO</strong></a>
            </div>
	            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                         <li><a href="profil.php"><i class="fa fa-gear fa-fw"></i> Profil</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="deconnexion.php"><i class="fa fa-sign-out fa-fw"></i> Deconnexion</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>