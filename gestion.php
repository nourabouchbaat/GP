<?php session_start(); ?>
<?php error_reporting(0) ?>

<link href="style.css" rel="stylesheet" type="text/css" />

<?php require_once('lang.php'); ?>
<?php require_once('fonctions.php'); ?>

<?php 
echo "<center><h2>"._REDIRECT."</h2></center>";
//print_r($_REQUEST);
connect ();
//detection de la table et des champs concerné
$tablees= isset($_REQUEST['table']) && !empty($_REQUEST['table']) ? $_REQUEST['table'] : "";
$tab_table = !empty($tablees) ? split(',',$_REQUEST['table']):array( );
$table=count($tab_table)>0?$tab_table[0]:"";

$action = isset($_REQUEST['act']) && !empty($_REQUEST['act']) ? $_REQUEST['act'] : "";;

$id_valeur = isset($_REQUEST['id_valeur']) && !empty($_REQUEST['id_valeur']) ? $_REQUEST['id_valeur'] : "";;
$page = isset($_REQUEST['page']) && !empty($_REQUEST['page']) ? $_REQUEST['page'] : "";;
$id_retour = isset($_REQUEST['id_retour']) && !empty($_REQUEST['id_retour']) ? $_REQUEST['id_retour'] : "";;
$id_noms_retour = isset($_REQUEST['id_noms_retour']) && !empty($_REQUEST['id_noms_retour']) ? $_REQUEST['id_noms_retour'] : "";;
$id_valeurs_retour = isset($_REQUEST['id_valeurs_retour']) && !empty($_REQUEST['id_valeurs_retour']) ? $_REQUEST['id_valeurs_retour'] : "";;
$champ_modif = isset($_REQUEST['champ_modif']) && !empty($_REQUEST['champ_modif']) ? $_REQUEST['champ_modif'] : "";;
$valeur_modif = isset($_REQUEST['valeur_modif']) && !empty($_REQUEST['valeur_modif']) ? $_REQUEST['valeur_modif'] : "";;



$chaine_retour = formuler_retour($id_noms_retour,$id_valeurs_retour);

//AJOUT
if($action== "exporter_database"){
	exportDatabase();
	$msg="Votre base de données est sauvgardés avec succes";
}

if($action== "importer_database"){
	exportDatabase();
	$msg=importerDatabase($_REQUEST['files']);
	redirect($page."?m=".$msg);

}

if($action== "ajouter_avance"){
	$nb =  isset($_REQUEST['nb_personnage']) && !empty($_REQUEST['nb_personnage']) ? $_REQUEST['nb_personnage'] : 0;
	for($i=0;$i<$nb;$i++){
		$idPersonne = isset($_REQUEST['id_'.$i]) && !empty($_REQUEST['id_'.$i]) ? $_REQUEST['id_'.$i] : 0;
		$datePaiement = date("Y-m-d");
		$montant = isset($_REQUEST['avance_'.$i]) && !empty($_REQUEST['avance_'.$i]) ? $_REQUEST['avance_'.$i] : 0;
		if($montant>0){
			$req ="INSERT INTO `avances`(`ID_PERSONNELS`, `DATE_EMPREINTE`, `MONTANT`) VALUES(".$idPersonne.",'".$datePaiement."',".$montant.")";
			doQuery($req);
			doQuery('COMMIT');			
		}
	}

	redirect("ajouter_avance.php?m=Ajout d'empreint pour : ".$_REQUEST['txtrechercher']." est validé");
}


if($action== "valider_paiement"){
	$idPersonne = isset($_REQUEST['personnels']) && !empty($_REQUEST['personnels']) ? $_REQUEST['personnels'] : "";
	$start =  isset($_REQUEST['dateDebut']) && !empty($_REQUEST['dateDebut']) ? $_REQUEST['dateDebut'] : "";
	$end =  isset($_REQUEST['dateFin']) && !empty($_REQUEST['dateFin']) ? $_REQUEST['dateFin'] : "";
	$datePaiement = date("Y-m-d");
	$sommeHeurN = getSommeHeurN($idPersonne, $start, $end);
	$sommeHeurS = getSommeHeurS($idPersonne, $start, $end);
	$datePointageStart = $start;
	$datePointageEnd = $end;
	$montant = getMontant($idPersonne,$start,$end);
	$req ="INSERT INTO `paiements`( `ID_PERSONNELS`, `DATE_PAIEMENT`, `SOMME_HEUR_N`, `SOMME_HEUR_S`, `DATE_POINTAGE_START`, `DATE_POINTAGE_END`, `MONTANT`) VALUES(".$idPersonne.",'".$datePaiement."',".$sommeHeurN.",".$sommeHeurN.",'".$datePointageStart."','".$datePointageEnd."',".$montant.")";
	doQuery($req);
	doQuery('COMMIT');
	redirect("ajouter_paiement.php?dateDebut=".$_REQUEST['dateDebut']."&dateFin=".$_REQUEST['dateFin']."&m=Ajout du paiement de ".$_REQUEST['txtrechercher']." est validé");
}

if ($action == "ajouter_pointage"){
	$nb = isset($_REQUEST['nb_personnage']) && !empty($_REQUEST['nb_personnage']) ? $_REQUEST['nb_personnage'] : 0;
	for($i=0;$i<$nb;$i++){
		$id =  isset($_REQUEST['id_'.$i]) && !empty($_REQUEST['id_'.$i]) ? $_REQUEST['id_'.$i] : "";
		$date_pointage =  isset($_REQUEST['DATE_POINTAGE_'.$i]) && !empty($_REQUEST['DATE_POINTAGE_'.$i]) ? $_REQUEST['DATE_POINTAGE_'.$i] : "";
		$heurN =  isset($_REQUEST['HEUR_N_'.$i]) && !empty($_REQUEST['HEUR_N_'.$i]) ? $_REQUEST['HEUR_N_'.$i] : "";
		$heurS =  isset($_REQUEST['HEUR_S_'.$i]) && !empty($_REQUEST['HEUR_S_'.$i]) ? $_REQUEST['HEUR_S_'.$i] : "";
		$codeChantier =  isset($_REQUEST['CODE_CHANTIER_'.$i]) && !empty($_REQUEST['CODE_CHANTIER_'.$i]) ? $_REQUEST['CODE_CHANTIER_'.$i] : "";
		$idChantier = getValeurChamp('ID','chantiers','CODE',$codeChantier);
		if(!empty($heurN)){
			$req = "INSERT INTO `pointages`(`ID_PERSONNELS`, `DATE_POINTAGE`, `HEUR_N`, `HEUR_S`, `ID_CHANTIER`) VALUES (".$id.",'".$date_pointage."',".$heurN.",".$heurS.",".$idChantier.")";
			doQuery($req);
			doQuery('COMMIT');		
		}
	}
}

if ($action == "archiver_personnel"){
	$personnels = isset($_REQUEST['personnels']) && !empty($_REQUEST['personnels']) ? $_REQUEST['personnels'] : '';

	$req ="update personnels set status = 0 where ID = ".$personnels; 
	doQuery($req);
	doQuery('COMMIT');
	redirect('personnels.php?m=Salarie ou ouvrier est archivé avec succes');
}

if ($action == "a"){
	
	//Rendre les dates du format 11-30-2009 => 1235543267654
	$tab_dates = array(	"date",
					   "date_reglement",
					   "date_cheque",
					   "date_achat",
					   "date_vente",
					   "date_echange",
					   "date_paiment",
					   "date_facture"
						);
	
	foreach($tab_dates as $v){
		if (isset($_REQUEST[$v])){
			
			$tab_d = explode("/",$_REQUEST[$v]);
			
			$jour = $tab_d[0];
			$mois = $tab_d[1];
			$annee = $tab_d[2];
			
			$hr = date("G");
			$mint = date("i");
			$sec = date("s");
			
			if($mois!="")
				$_REQUEST[$v] = $annee."-".$mois."-".$jour;
		}
	}
	
	doQuery("BEGIN");
	 	
	for($i=0;$i<sizeof($tab_table);$i++){ 
	
		if($tab_table[$i]=="produits"){
			$_REQUEST['prix_echange']=$_REQUEST['prix_vente']*$_REQUEST['pourcentage_echange']/100;
			$_REQUEST['qte_stock']=0;
		}
		
		$var[$i]= Ajout($tab_table[$i],getNomChamps($tab_table[$i]),$_REQUEST);
		$identif=mysql_insert_id(); 
		$_REQUEST['id_'.$tab_table[$i]]=mysql_insert_id(); 
		
		if(isset($_FILES['photo']) and getChamp($tab_table[$i], "image")){
			$retour2 = upload_image($tab_table[$i],$_FILES['photo'],$identif);
			unset($_FILES);
			
			if($retour2){
				echo _UPLOAD_OK;
			}
			else
			{
				echo _UPLOAD_NOK;
			}
		}
		
	}
	
	for($i=0 ; $i<sizeof($var) ; $i++){ 
		if( !$var[$i] ){
			doQuery("ROLLBACK");
		$m=1;
		}	
	}
	
	if($m==1) $msg_err=_AJOUT_NOK;	
	else {
		$msg= _AJOUT_OK;
		
		
		
		doQuery("COMMIT");
	}	
		
	if(isset($_REQUEST['msg_retour'])){
		$msg = $_REQUEST['msg_retour'];
	}
	
	//if (isset($_REQUEST['parent'])) { $parent=$_REQUEST['parent']; }
	//redirect($page."?m=".$msg."&er=".$msg_err.$chaine_retour.$_REQUEST['ancre']);
}


//MODIFICATION
if ($action == "m"){
	$oldSalaire = "";
	if(isset($_REQUEST['table']) && !empty($_REQUEST['table'])){
		if($_REQUEST['table']=="personnels"){
			$oldSalaire = $_REQUEST['TYPE']=="Salarie"?getValeurChamp('SALAIRE_MENSUELLE','personnels','ID',$_REQUEST['personnels']):"";
		}
	}

	if(isset($_REQUEST['id_nom'])){
		$id_nom = $_REQUEST['id_nom'];
	}
	else 
	{
		$id_nom='ID';
	}
		
	//Rendre les dates du format 11-30-2009 => 1235543267654
	$tab_dates = array(	"date",
					   "date_cheque",
						"date_reglement",
					   "date_achat",
					   "date_vente",
					   "date_echange",
					   "date_paiment",
					   "date_facture"
						);
	
	foreach($tab_dates as $v){
		if (isset($_REQUEST[$v])){
			$tab_d = explode("/",$_REQUEST[$v]);

			$jour = $tab_d[0];
			$mois = $tab_d[1];
			$annee = $tab_d[2];

			$hr = date("G");
			$mint = date("i");
			$sec = date("s");

			if($mois!="")			
			$_REQUEST[$v] = $annee."-".$mois."-".$jour;
		}
	}
	
	doQuery("BEGIN");
	
	for($i=0;$i<sizeof($tab_table);$i++){ 
	 	$var[$i] = Modification($tab_table[$i],getNomChamps($tab_table[$i]),$_REQUEST,$id_nom,$id_valeur);
		
		if(isset($_FILES['photo'])){
			$retour2 = upload_image($tab_table[$i],$_FILES['photo'],$id_valeur);
			unset($_FILES);
			
			if($retour2)
			{
				echo _UPLOAD_OK;
			}
			else 
			{
				echo _UPLOAD_NOK;
			}
		}
		
		$id_nom='id_'.$tab_table[$i];	 
	}
	
	if (isset($_REQUEST['table_modification'])){
		$table_modification=$_REQUEST['table_modification'];
		Ajout($table_modification,getNomChamps($table_modification),$_REQUEST);	
	}	
	
	for($i=0 ; $i<sizeof($var) ; $i++){ 
		 if( !$var[$i] ){
			doQuery("ROLLBACK");
			$m=1;
		}			
	}
		
	if($m==1) $msg_err=_MODIFICATION_NOK;	
	else 
	{
		$msg= _MODIFICATION_OK;
		doQuery("COMMIT");
		if(isset($_REQUEST['table']) && !empty($_REQUEST['table'])){
			if($_REQUEST['table']=="personnels"){
				if(($oldSalaire!=$_REQUEST['SALAIRE_MENSUELLE'] && $_REQUEST['TYPE']=="Salarie") || ($oldSalaire!=$_REQUEST['TARIF_JOURNALIERS'] && $_REQUEST['TYPE']=="Ouvrier")){
					$nouveauSalaire = $_REQUEST['TYPE']=="Salarie" ? $_REQUEST['SALAIRE_MENSUELLE'] : $_REQUEST['TARIF_JOURNALIERS'];
					$req = "INSERT INTO `historique_salaire`(`ID_PERSONNEL`, `NOUEAU_SALAIRE`, `DATE_CHANGEMENT`) VALUES ('".$_REQUEST['id_valeurs_retour']."', '".$nouveauSalaire."','".date("Y-m-d")."')";
					doQuery($req);
					doQuery('COMMIT');
				}
			}
		}
	}
}

//SUPPRESSION
if ($action == "s"){
	//Pour la suppression physiques des fichiers
	if ($table == "mi_messages_pieces_jointes"){
		$fichier = 'files/'. getValeurChamp('fichier','mi_messages_pieces_jointes','id',$id_valeur);
	}
	
	$retour1=Suppression($table,$id_valeur);
	
	if ($table == "mi_messages_pieces_jointes"){
		unlink($fichier);
	}
	
	if($retour1){
		$msg= _SUPPRESSION_OK;
	}
	else $msg_err=_SUPPRESSION_NOK;
}


//DEVLOPPEZ PAR ACHAF GESTION DES PESONNELS
//VOIR L'ACTION a et m j'ai ajouter date_achat dans les liste des dates
if ($action == 'conexion')
{
	$login=$_POST['login'];
	$password=md5($_POST['password']);
	$sql="select * from users where login='".$login."' and password='".$password."'";
	$res=doQuery($sql);
	$nbr=mysql_num_rows($res);
	if($nbr==1)
	{
		$ligne = mysql_fetch_array($res) or die(mysql_error());
		$_SESSION['admin']="SOMLAKO";
		$_SESSION['user-cnx']=$ligne['PRENOM']." ".$ligne['NOM'];
		$_SESSION['email-cnx']=$ligne['EMAIL'];
		redirect("index.php");
	}
}

if(!isset($msg_err)){
	$msg_err ="";
}
redirect($page."?".$chaine_retour."&m=".$msg."&er=".$msg_err."#ancre");
?>	