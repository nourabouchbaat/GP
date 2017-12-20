<?php session_start() ?>
<?php error_reporting(0) ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title>Gestion patisserie</title>  
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-glyphicons.css" />
<link rel="stylesheet" href="css/unicorn.login.css" />
<!--////////////////////////////////// Include PHP //////////////////////////////////-->
<?php require_once('lang.php'); ?>
<?php require_once('fonctions.php'); ?>
<?php require_once('tabs.php'); ?>
<!--////////////////////////////////// Include PHP //////////////////////////////////-->


<!--////////////////////////////////// Include JS //////////////////////////////////-->
<script type="text/javascript" src="js/javascript.js"></script>
<script type="text/javascript" src="js/form.js"></script>

<!-- Calendrier JS -->
<script type="text/javascript" src="js/epoch_classes.js"></script>
<script type="text/javascript">
/*<![CDATA[*/
/*You can also place this code in a separate file and link to it like epoch_classes.js*/
	var bas_cal,dp_cal,ms_cal;      
window.onload = function () {
	dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('cal_required'));
	dp_cal2  = new Epoch('epoch_popup','popup',document.getElementById('cal2_required'));
};
/*]]>*/
</script>
<!-- Fin Calendrier JS -->
<!--////////////////////////////////// Include JS //////////////////////////////////-->
</head>
