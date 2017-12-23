<?php session_start(); ?>
<?php error_reporting(0) ?>

<link href="style.css" rel="stylesheet" type="text/css" />

<?php require_once('lang.php'); ?>
<?php require_once('fonctions.php'); ?>

<?php 
echo "<center><h2>"._REDIRECT."</h2></center>";
connect ();
//detection de la table et des champs concerné
$tab_table = split(',',$_REQUEST['table']);
$table=$tab_table[0];

$action = $_REQUEST['act'];

$id_valeur = $_REQUEST['id_valeur'];
$id_retour = $_REQUEST['id_retour'];
$id_noms_retour = $_REQUEST['id_noms_retour'];
$id_valeurs_retour = $_REQUEST['id_valeurs_retour'];

$chaine_retour = formuler_retour($id_noms_retour,$id_valeurs_retour);
$page = $_REQUEST['page'];
$champ_modif=$_REQUEST['champ_modif'];
$valeur_modif=$_REQUEST['valeur_modif'];

//AJOUT
if($action== "exporter_database"){
print_r($_REQUEST);
	exportDatabase();
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

if ($action == "admin_password"){
$sql="select * from administration where password='".md5($_REQUEST['password0'])."'";
$res=DoQuery($sql);
$nbr=mysql_num_rows($res);
if($nbr==0){
	$msg_err = "Verifiez votre ancien mot de passe !";
}
else
{
	if($_REQUEST['password']==$_REQUEST['password2'])
	{
		$ligne=mysql_fetch_array($res);
		echo $_REQUEST['password']."<br>";
		echo $_REQUEST['email']=$ligne['email'];
		
		$_REQUEST['password']=md5($_REQUEST['password']);
		if(isset($_REQUEST['id_nom'])){
			$id_nom = $_REQUEST['id_nom'];
		}
		else 
		{
			$id_nom='id';
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
		}
	}
	else
	{
		$msg_err="Verifiez Votre mot de passe !";	
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

//Ordre des categories
if ($action == "ordre_liens"){

	$sql = "select * from menus_elements_liens where id_menus_elements = '". $_REQUEST['menus_elements'] ."'";
	$res = doQuery($sql);
	$nb = mysql_num_rows($res);
	
	while($ligne = mysql_fetch_array($res)){
		$sql_ordre = "
				update menus_elements_liens 
				set ordre = '".$_REQUEST[$ligne['id']]."' where id = '". $ligne['id'] ."'";
		$res_ordre = doQuery($sql_ordre);
		
		if($res_ordre)	$i++;
	}
	
	if ($nb != $i){
		$msg_err = _MODIFICATION_NOK;	
		
	}
	else 
	{
		$msg = _MODIFICATION_OK;
		
	}
		
}

if ($action == "ordre_menus"){

	$sql = "select * from menus";
	$res = doQuery($sql);
	$nb = mysql_num_rows($res);
	
	while($ligne = mysql_fetch_array($res)){
		$sql_ordre = "
				update menus 
				set ordre = '".$_REQUEST[$ligne['id']]."' where id = '". $ligne['id'] ."'";
		$res_ordre = doQuery($sql_ordre);
		
		if($res_ordre)	$i++;
	}
	
	if ($nb != $i){
		$msg_err = _MODIFICATION_NOK;	
		
	}
	else 
	{
		$msg = _MODIFICATION_OK;
		
	}
	
}

if ($action == "ordre_creneaux"){

	$sql = "select * from ". $_REQUEST['table'] ." 
			where ". $_REQUEST['id_nom'] ."=". $_REQUEST['id_valeur'];
	$res = doQuery($sql);
	$nb = mysql_num_rows($res);
	
	while($ligne = mysql_fetch_array($res)){
		$sql_ordre = "
				update ". $_REQUEST['table'] ." 
				set ordre = '".$_REQUEST[$ligne['id']]."' where id = '". $ligne['id'] ."'";
		$res_ordre = doQuery($sql_ordre);
		
		if($res_ordre)	$i++;
	}
	
	if ($nb != $i){
		$msg_err = _MODIFICATION_NOK;	
		
	}
	else 
	{
		$msg = _MODIFICATION_OK;
		
	}
	
}

//ordre
if ($action == "ordre"){

	$sql = "select * from ". $table;
	$res = doQuery($sql);
	$nb = mysql_num_rows($res);
	
	while($ligne = mysql_fetch_array($res)){
		$sql_ordre = "
				update ". $table ." 
				set ordre = '".$_REQUEST[$ligne['id']]."' where id = '". $ligne['id'] ."'";
		$res_ordre = doQuery($sql_ordre);
		
		if($res_ordre)	$i++;
	}
	
	if ($nb != $i){
		$msg_err = _MODIFICATION_NOK;	
		
	}
	else 
	{
		$msg = _MODIFICATION_OK;
		
	}
		
}

//Ordre faq
if ($action == "order_faq"){

	$sql = "select * from faq";
	$res = doQuery($sql);
	$nb = mysql_num_rows($res);
	
	while($ligne = mysql_fetch_array($res)){
		$sql_ordre = "
				update faq  
				set ordre = '".$_REQUEST[$ligne['id']]."' where id = '". $ligne['id'] ."'";
		$res_ordre = doQuery($sql_ordre);
		
		if($res_ordre)	$i++;
	}
	
	if ($nb != $i){
		$msg_err = _MODIFICATION_NOK;	
		
	}
	else 
	{
		$msg = _MODIFICATION_OK;
		
	}
		
}

//MODIFICATION : MODIFIER LE VALEUR D4UN CHAMPS
if ($action == "etat"){
	ModifValChamps($table,$champ_modif,$valeur_modif,$id_valeur);
	
}

if ($action == "etat_annees"){
	
	$req = 'update annees_scolaires set etat = 0';
	$result= doQuery($req);
	
	if ($result) ModifValChamps('annees_scolaires','etat',1,$_REQUEST['annees']);

}

if ($action == "etat_unique"){
	
	$req = 'update '. $_REQUEST['table'] .' set '. $_REQUEST['champ_modif'] .' = 0';
	$result= doQuery($req);
	
	if ($result){
		ModifValChamps($_REQUEST['table'],$_REQUEST['champ_modif'],$_REQUEST['valeur_modif'],$_REQUEST['id_valeur']);
	}
}

if ($action == "appartenances"){
	
	if ($_REQUEST['parent'] != ""){
		$sql = "update categories set parent = '". $_REQUEST['parent'] ."' where id = '". $_REQUEST['id'] ."'";
	}
	else
	{
		$sql = "update categories set parent = null where id = '". $_REQUEST['id'] ."'";
	}
	$bool = doQuery($sql);
	
	if ($bool)
		$msg = _MODIFICATION_OK;
	else
		$msg_err = _MODIFICATION_NOK;
}

if ($action == "resultats"){

	$req= "select * from tests_entree_tests_matieres where id_tests_entree = ".$_REQUEST['id_tests_entree'] ;
	$result = doQuery($req);

		while ($ligne = mysql_fetch_array($result)){
						
			$note=getResultatsTest($_REQUEST['id_tests_entree'],$ligne['id_tests_matieres'],$_REQUEST['id_eleves_tests_entree']);
							
			if($note=='null')
			{
				$sql = "insert into 	
						resultats(id_eleves,id_tests_entree,id_eleves_tests_entree,id_annees_scolaires,id_tests_matieres,id_preinscription,note) 
						values(". $_REQUEST['id_eleves'] .",".$_REQUEST['id_tests_entree'].",".$_REQUEST['id_eleves_tests_entree'].",".$_REQUEST['id_annees_scolaires'].",".$ligne['id_tests_matieres'].",".getValeurChamp('id','preinscription','id_eleves',$_REQUEST['id_eleves']).",".$_REQUEST['note'.$ligne['id_tests_matieres']].")";
			}
			else
			{
				$sql = "update resultats_tests_entree set note = ".$_REQUEST['note'.$ligne['id_tests_matieres']]."
						 where id_eleves_tests_entree = ".$_REQUEST['id_eleves_tests_entree']." and id_tests_matieres = ".$ligne['id_tests_matieres']." and id_tests_entree = ".$_REQUEST['id_tests_entree'] ;
			}
			//echo $sql."<br />";
			$res=doQuery($sql);
			
			
	}

}


if ($action == "resultats_global"){

	$sql = "select * from 
				eleves_tests_entree 
			where 
				id_tests_entree =".$_REQUEST['id_tests_entree']." 
			and
				id_annees_scolaires = '". $_REQUEST['id_annees_scolaires'] ."'
			order by id desc";
	$result = doQuery($sql);

	while ($ligne = mysql_fetch_array($result)){
		$sql_mat1 = "
				select * from 
					tests_entree_tests_matieres 
				where
					id_tests_entree = '". $_REQUEST['id_tests_entree'] ."'
				and
					id_annees_scolaires = '". $_REQUEST['id_annees_scolaires'] ."'
				";
		$res_mat1 = doQuery($sql_mat1);
		while ($ligne_mat1 = mysql_fetch_array($res_mat1)){
			
			$note = getResultatsTest($_REQUEST['id_tests_entree'],$ligne_mat1['id_tests_matieres'],$ligne['id']);
			
			if($note=='null'){
				
				$id_pre = getValeurChamp('id','preinscription','id_eleves,id_annees_scolaires',$ligne['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
				
				$sql = "insert into 
				tests_entree_resultats(	id_eleves,
										id_tests_entree,
										id_eleves_tests_entree,
										id_annees_scolaires,
										id_tests_matieres,
										id_preinscription,
										note
									) 
				values(". 
				$ligne['id_eleves'] .",".
				$_REQUEST['id_tests_entree'] .",". 
				$ligne['id'] .",". 
				$_REQUEST['id_annees_scolaires'] .",". 
				$ligne_mat1['id_tests_matieres'] .",". 
				$id_pre .",".
				$_REQUEST['note'.$ligne['id_eleves'].'_'.$ligne_mat1['id_tests_matieres']]
					.")";
						
			}
			else
			{
			
				$sql = "
update tests_entree_resultats 
	set note = ".$_REQUEST['note'.$ligne['id_eleves'].'_'.$ligne_mat1['id_tests_matieres']]."
where 
	id_eleves_tests_entree = ".$ligne['id']." 
and 
	id_tests_matieres = ".$ligne_mat1['id_tests_matieres']." 
and 
	id_tests_entree = ".$_REQUEST['id_tests_entree'];
						 
			}
			
			$res = doQuery($sql);
			
		}
	}
		
}


//MAJ des droits
if ($action == "profils_droits"){

	$droit = getDroitProfil($_REQUEST['profils'],$_REQUEST['liens']);
	
	if ($droit == ""){
		$sql = "insert into profils_droits(id_profils,id_menus_elements_liens,etat)
				values('". $_REQUEST['profils'] ."','". $_REQUEST['liens'] ."','". $_REQUEST['etat'] ."')";
	}
	else
	{
		$sql = "update profils_droits set etat=".$_REQUEST['etat']."
				where id_menus_elements_liens=".$_REQUEST['liens']." and id_profils=".$_REQUEST['profils'];
	}
	
	$bool = doQuery($sql);
}

//MAJ des droits pour menus
if ($action == "profils_droits_elements_menus"){
	
	$sql= "	select * from menus_elements_liens  
			where id_menus_elements = '". $_REQUEST['elements_menus'] ."'";		
	$res = doQuery($sql);
	
	$nb = mysql_num_rows($res);
	if( $nb == 0){
	}
	else
	{
	 
		while ($ligne = mysql_fetch_array($res)){
	
			$droit = getDroitProfil($_REQUEST['profils'],$ligne['id']);
			
			if ($droit == ""){
				$sql = "insert into profils_droits(id_profils,id_menus_elements_liens,etat)
						values('". 	$_REQUEST['profils'] ."','". 
									$ligne['id'] ."','". 
									$_REQUEST['etat'] ."')";
			}
			else
			{
				$sql = "update profils_droits set etat=".$_REQUEST['etat']."
						where id_menus_elements_liens=".$ligne['id']." 
						and id_profils=".$_REQUEST['profils'];
			}
			
			$bool = doQuery($sql);
		}
	}
}

//APPLIQUER DES REDUCTIONS
if ($action == "reductions"){

	$req= "select * from mois where mois_scolarite = 1 ";
	$result = doQuery($req);

		while ($ligne = mysql_fetch_array($result)){
					$sql= "select * from eleves_reduction_frais_scolarite where mois = ".$ligne['ordre']." and id_eleves =".$_REQUEST['id_eleves']." and id_annees_scolaires = ".$_REQUEST['id_annees_scolaires'];		
					$res = doQuery($sql);
					$nb = mysql_num_rows($res);
					if ($nb==0){
						if (isset($_REQUEST['reduction'.$ligne['ordre']])and isset($_REQUEST['motif'.$ligne['ordre']]))	
						$sql2='insert into eleves_reduction_frais_scolarite(id_eleves,id_inscriptions,id_annees_scolaires,mois,reduction,motif) values ('.$_REQUEST['id_eleves'].','.getValeurChamp('id','inscription','id_eleves',$_REQUEST['id_eleves']).','.$_REQUEST['id_annees_scolaires'].','.$ligne['ordre'].','.$_REQUEST['reduction'.$ligne['ordre']].',\''.$_REQUEST['motif'.$ligne['ordre']].'\')';
					}
					else{
						if (isset($_REQUEST['reduction'.$ligne['ordre']])and isset($_REQUEST['motif'.$ligne['ordre']]))
						$sql2='update eleves_reduction_frais_scolarite set reduction='.$_REQUEST['reduction'.$ligne['ordre']].',motif = \''.$_REQUEST['motif'.$ligne['ordre']].'\'  where id_eleves='.$_REQUEST['id_eleves'].' and id_annees_scolaires = '.$_REQUEST['id_annees_scolaires'].' and mois = '.$ligne['ordre'];
						
					}
		//echo $sql2;
				doQuery($sql2);
		}

}

if ($action == "cne_obligatoire"){
	
$req = 'update cycles set cne_obligatoire = 0';
$result= doQuery($req);

if ($result)
	ModifValChamps('cycles','cne_obligatoire',1,$_REQUEST['cycles']);

}

//MODIFICATION PASSWORD
if ($action == "password"){

	if(isset($_REQUEST['id_nom'])) {
		$id_nom = $_REQUEST['id_nom'];
	}
	else 
	{
		$id_nom = 'id';
	}
	doQuery("BEGIN");
	
	if ($_REQUEST['password2'] == $_REQUEST['password']){
		
		for($i=0;$i<sizeof($tab_table);$i++){ 
			
			$var[$i] = Modification($tab_table[$i],getNomChamps($tab_table[$i]),$_REQUEST,$id_nom,$id_valeur);
			
			$id_nom = 'id';	 
		}
	
		for($i=0 ; $i<sizeof($var) ; $i++){ 
			if( !$var[$i] ){
				doQuery("ROLLBACK");
				$m=1;
			}		
		}
	}
	else
	{
		$m=1;
	}
	
	if($m==1) 
		$msg_err = _MODIFICATION_NOK;	
	else 
	{
		$msg = _MODIFICATION_OK;
		doQuery("COMMIT");
	}
	
}

if ($action == "associer_niveau_en_attente"){
//____________________
	$id_annee_active = getValeurChamp('id','annees_scolaires','etat','1');
	echo $sql = "	select * from eleves 
				where id not in(
								select id_eleves from preinscription 
								where id_annees_scolaires = '". $id_annee_active ."' 
								)
				and id not in(
					 select id_eleves from inscription 
					 where id_annees_scolaires = '". $id_annee_active ."' 
					 )";		
	$res = doQuery($sql);
	
	$nb = mysql_num_rows($res);
	if( $nb==0){
		echo _VIDE;
	}
	else
	{
		while ($ligne = mysql_fetch_array($res)){
			
			$_REQUEST['id_annees_scolaires'] = $id_annee_active;
			$_REQUEST['id_eleves'] = $ligne['id'];
			$_REQUEST['date'] = time();
			
			$table = "preinscription";
			Ajout($table,getNomChamps($table),$_REQUEST);
		}
	}
//____________________
}

if ($action == "associer_groupe_en_attente"){
//____________________
	$id_annee_active = getValeurChamp('id','annees_scolaires','etat','1');
	echo $sql = "select * from preinscription 
			where id_annees_scolaires = '".$id_annee_active."' 
			and id_eleves not in (
								select id_eleves from inscription where id_annees_scolaires = '". $id_annee_active ."'
								)";	
	$res = doQuery($sql);
	
	$nb = mysql_num_rows($res);
	if( $nb==0){
		echo _VIDE;
	}
	else
	{
		while ($ligne = mysql_fetch_array($res)){
			$id_annee_active = getValeurChamp('id','annees_scolaires','etat','1');
			$_REQUEST['id_annees_scolaires'] = $id_annee_active;
			$_REQUEST['id_eleves'] = $ligne['id_eleves'];
			$_REQUEST['date'] = time();
			
			$table = "inscription";
			Ajout($table,getNomChamps($table),$_REQUEST);
		}
	}
//____________________
}

if ($action == "messagerieeeeee"){
	
	doQuery("BEGIN");
	
	//Expéditeur
	$_REQUEST['utilisateurs'] = $_SESSION['utilisateurs'];
	$_REQUEST['type_utilisateurs'] = $_SESSION['type'];
	$_REQUEST['datea'] = time();
	
	
	$table = "mi_messages";
	$bool1 = Ajout($table,getNomChamps($table),$_REQUEST);
	
	if($bool1){
		$_REQUEST['id_messages'] = mysql_insert_id();
		
		//Destinataire
		$tab_id_user = explode(',',$_REQUEST['id_utilisateurs']);
		$_REQUEST['utilisateurs'] = $tab_id_user[0];
		$_REQUEST['type_utilisateurs'] = $tab_id_user[1];
		
		$table = "mi_messages_destinataires";
		$bool2 = Ajout($table,getNomChamps($table),$_REQUEST);
		
		if($bool2){
			doQuery("COMMIT");
			$page = "mi_ajouter_messages2.php";
		}
		else
		{
			doQuery("ROLLBACK");
			$page = "mi_messages.php";
			$msg_err = _AJOUT_NOK;
		}
		
	}//bool1
}

if ($action == "messagerie"){
	
	doQuery("BEGIN");
	
	//Expéditeur
	$_REQUEST['utilisateurs'] = $_SESSION['utilisateurs'];
	$_REQUEST['type_utilisateurs'] = $_SESSION['type'];
	$_REQUEST['datea'] = time();
	
	
	$table = "mi_messages";
	$bool1 = Ajout($table,getNomChamps($table),$_REQUEST);
		
	if($bool1){
		$chaine_retour = '&messages='.mysql_insert_id();
		$page = "mi_ajouter_messages2.php";
		
		doQuery("COMMIT");
	}
	else
	{
		doQuery("ROLLBACK");
		$page = "mi_messages.php";
		$msg_err = _AJOUT_NOK;
	}
	
}

if ($action == "destinataires"){
	
	doQuery("BEGIN");
	
	$selection = explode(',',$_REQUEST['selection']);
	
	$i = 0;
	$j = sizeof($selection);
	
	foreach($selection as $c){
	
		//Destinataire
		$tab_id_user = explode(';',$c);
		$_REQUEST['utilisateurs'] = $tab_id_user[0];
		$_REQUEST['type_utilisateurs'] = $tab_id_user[1];
		
		$table = "mi_messages_destinataires";
		$bool2 = Ajout($table,getNomChamps($table),$_REQUEST);
		
		if($bool2){
			$i++;
		}
	
	}//Fin foreach
	
	if($i == $j){
		doQuery("COMMIT");
	}
	else
	{
		doQuery("ROLLBACK");
	}
	
}

if ($action == "messagerie_reponses"){
	
	doQuery("BEGIN");
	
	//Expéditeur
	$_REQUEST['utilisateurs'] = $_SESSION['utilisateurs'];
	$_REQUEST['type_utilisateurs'] = $_SESSION['type'];
	$_REQUEST['datea'] = time();
	
	
	$table = "mi_messages_reponses";
	$bool1 = Ajout($table,getNomChamps($table),$_REQUEST);
		
	if($bool1){
		$chaine_retour ='&messages='.$_REQUEST['id_messages'].'&reponses='.mysql_insert_id();
		$page = "mi_ajouter_messages_reponses2.php";
		
		doQuery("COMMIT");
	}
	else
	{
		doQuery("ROLLBACK");
		$page = "mi_details_messages.php?messages=". $_REQUEST['id_messages'];
		$msg_err = _AJOUT_NOK;
	}
	
}

if ($action == "fournitures_frais"){
	
	$sql_supr = "	delete from fournitures_frais  
					where id_annees_scolaires = '". $_REQUEST['id_annees_scolaires'] ."'";
	$bool_supr = doQuery($sql_supr);
	
	if($bool_supr){
		
		//Afficher les cycles
		$sql_cycles = "select * from cycles 
						where id_annees_scolaires = '". $_REQUEST['id_annees_scolaires'] ."' 
						order by id";		
		
		$res_cycles = doQuery($sql_cycles);
		
		$nb_cycles = mysql_num_rows($res_cycles);
		if( $nb_cycles == 0){
		}
		else
		{
			while ($ligne_cycles = mysql_fetch_array($res_cycles)){
				
				//Afficher les niveaux de chaque cycle
				$sql_niveaux = "select * from niveaux where id_cycles = '". $ligne_cycles['id'] ."' order by id";		
				$res_niveaux = doQuery($sql_niveaux);
				
				$nb_niveaux = mysql_num_rows($res_niveaux);
				if( $nb_niveaux == 0){
					
				}
				else
				{
					while ($ligne_niveaux = mysql_fetch_array($res_niveaux)){
						
						$tab = 'fournitures_frais';
							$_REQUEST['id_niveaux'] = $ligne_niveaux['id'];
							$_REQUEST['etat'] = $_REQUEST['etat_'.$ligne_niveaux['id']];
							$_REQUEST['montant'] = $_REQUEST[$ligne_niveaux['id']];
						Ajout($tab,getNomChamps($tab),$_REQUEST);
						
					}
				}
			}
		}
	
	}
}

if ($action == "reglements_mensualites_transports"){
	
	doQuery("BEGIN");
	
	$id_transports	= getValeurChamp('id','eleves_transports','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);

	$frais	= getValeurChamp('mensualite','transports_frais','id_annees_scolaires',$_REQUEST['id_annees_scolaires']);
	
	$reduction_transport = getValeurChamp('reduction_transport','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$supplement_transport = getValeurChamp('supplement_transport','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$id_inscriptions	= getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$id_groupes_eleves = getValeurChamp('id_groupes','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$total = $frais + $supplement_transport - $reduction_transport;
	
	$i=0;
	$selection = explode(',',$_REQUEST['selection']);
	foreach($selection as $c){
		$id_mois = getValeurChamp('id','mois','ordre',$c);
		
		$id_eleves_transports_mois = getValeurChamp('id','eleves_transports_mois','id_eleves,id_annees_scolaires,mois,id_transports',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires'].','.$c.','.$id_transports);
		
		$tab = 'reglements_frais_mensualite_transports';
			$_REQUEST['id_transports'] = $id_transports;
			$_REQUEST['id_inscriptions'] = $id_inscriptions;
			$_REQUEST['id_groupes'] = $id_groupes_eleves;
			$_REQUEST['id_eleves_transports_mois'] = $id_eleves_transports_mois;
			$_REQUEST['mois'] = $c;
			$_REQUEST['montant'] = $total;
			$_REQUEST['utilisateurs'] = $_SESSION['utilisateurs'];
			$_REQUEST['type_utilisateurs'] = $_SESSION['type'];
		$bool = Ajout($tab,getNomChamps($tab),$_REQUEST);
		
		if($bool){
			$i++;	
		}
	}
	
	$_REQUEST['ancre'] = 'reglements';
	$chaine_retour = "&eleves=".$_REQUEST['id_eleves'];
	
	if($i == sizeof($selection)){
		doQuery("COMMIT");
		
		$msg = _AJOUT_OK;
		$page = "reglements.php";
	}
	else
	{
		doQuery("ROLLBACK");
		
		$msg_err = _AJOUT_NOK;
		$page = "ajouter_reglements_mensualite_transports.php";
	}
}

if ($action == "reglements_mensualites_cantines"){
	
	doQuery("BEGIN");
	
	$id_cantines	= getValeurChamp('id','eleves_cantines','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);

	$frais	= getValeurChamp('mensualite','cantines_frais','id_annees_scolaires',$_REQUEST['id_annees_scolaires']);
	
	$reduction_cantines = getValeurChamp('reduction_cantine','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$id_inscriptions	= getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$id_groupes_eleves = getValeurChamp('id_groupes','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$total = $frais - $reduction_cantines;
	
	$i=0;
	$selection = explode(',',$_REQUEST['selection']);
	foreach($selection as $c){
		$id_mois = getValeurChamp('id','mois','ordre',$c);
		
		$id_eleves_cantines_mois = getValeurChamp('id','eleves_cantines_mois','id_eleves,id_annees_scolaires,mois,id_cantines',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires'].','.$c.','.$id_cantines);
		
		$tab = 'reglements_frais_mensualite_cantines';
			$_REQUEST['id_cantines'] = $id_cantines;
			$_REQUEST['id_inscriptions'] = $id_inscriptions;
			$_REQUEST['id_groupes'] = $id_groupes_eleves;
			$_REQUEST['id_eleves_cantines_mois'] = $id_eleves_cantines_mois;
			$_REQUEST['mois'] = $c;
			$_REQUEST['montant'] = $total;
			$_REQUEST['utilisateurs'] = $_SESSION['utilisateurs'];
			$_REQUEST['type_utilisateurs'] = $_SESSION['type'];
		$bool = Ajout($tab,getNomChamps($tab),$_REQUEST);
		
		if($bool){
			$i++;	
		}
	}
	
	$_REQUEST['ancre'] = 'reglements';
	$chaine_retour = "&eleves=".$_REQUEST['id_eleves'];
	
	if($i == sizeof($selection)){
		doQuery("COMMIT");
		
		$msg = _AJOUT_OK;
		$page = "reglements.php";
	}
	else
	{
		doQuery("ROLLBACK");
		
		$msg_err = _AJOUT_NOK;
		$page = "ajouter_reglements_mensualite_cantines.php";
	}
}

if ($action == "reglements_mensualites_clubs"){
	
	doQuery("BEGIN");
	
	$id_clubs	= getValeurChamp('id','eleves_clubs','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);

	$frais	= getValeurChamp('mensualite','clubs_frais','id_annees_scolaires',$_REQUEST['id_annees_scolaires']);
	
	$reduction_clubs = getValeurChamp('reduction_club','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$id_inscriptions	= getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$id_groupes_eleves = getValeurChamp('id_groupes','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$total = $frais - $reduction_clubs;
	
	$i=0;
	$selection = explode(',',$_REQUEST['selection']);
	foreach($selection as $c){
		$id_mois = getValeurChamp('id','mois','ordre',$c);
		
		$id_eleves_clubs_mois = getValeurChamp('id','eleves_clubs_mois','id_eleves,id_annees_scolaires,mois,id_clubs',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires'].','.$c.','.$id_clubs);
		
		$tab = 'reglements_frais_mensualite_clubs';
			$_REQUEST['id_clubs'] = $id_clubs;
			$_REQUEST['id_inscriptions'] = $id_inscriptions;
			$_REQUEST['id_groupes'] = $id_groupes_eleves;
			$_REQUEST['id_eleves_clubs_mois'] = $id_eleves_clubs_mois;
			$_REQUEST['mois'] = $c;
			$_REQUEST['montant'] = $total;
			$_REQUEST['utilisateurs'] = $_SESSION['utilisateurs'];
			$_REQUEST['type_utilisateurs'] = $_SESSION['type'];
		$bool = Ajout($tab,getNomChamps($tab),$_REQUEST);
		
		if($bool){
			$i++;	
		}
	}
	
	$_REQUEST['ancre'] = 'reglements';
	$chaine_retour = "&eleves=".$_REQUEST['id_eleves'];
	
	if($i == sizeof($selection)){
		doQuery("COMMIT");
		
		$msg = _AJOUT_OK;
		$page = "reglements.php";
	}
	else
	{
		doQuery("ROLLBACK");
		
		$msg_err = _AJOUT_NOK;
		$page = "ajouter_reglements_mensualite_clubs.php";
	}
}

if ($action == "reglements_mensualites_scolarite"){
	
	doQuery("BEGIN");

	$frais = getFsByEleves($_REQUEST['id_eleves'],$_REQUEST['id_annees_scolaires']);
	
	$reduction = getValeurChamp('reduction','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$id_inscriptions	= getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$id_groupes_eleves = getValeurChamp('id_groupes','inscription','id_eleves,id_annees_scolaires',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires']);
	
	$total = $frais - $reduction;
	
	$i=0;
	$selection = explode(',',$_REQUEST['selection']);
	foreach($selection as $c){
		$id_mois = getValeurChamp('id','mois','ordre',$c);
		
		$id_eleves_scolarite_mois = getValeurChamp('id','eleves_scolarite_mois','id_eleves,id_annees_scolaires,mois,id_inscriptions',$_REQUEST['id_eleves'].','.$_REQUEST['id_annees_scolaires'].','.$c.','.$id_inscriptions);
		
		$tab = 'reglements_frais_scolarite';
			$_REQUEST['id_inscriptions'] = $id_inscriptions;
			$_REQUEST['id_groupes'] = $id_groupes_eleves;
			$_REQUEST['id_eleves_scolarite_mois'] = $id_eleves_scolarite_mois;
			$_REQUEST['mois'] = $c;
			$_REQUEST['montant'] = $total;
			$_REQUEST['utilisateurs'] = $_SESSION['utilisateurs'];
			$_REQUEST['type_utilisateurs'] = $_SESSION['type'];
		$bool = Ajout($tab,getNomChamps($tab),$_REQUEST);
		
		if($bool){
			$i++;	
		}
	}
	
	$_REQUEST['ancre'] = 'reglements';
	$chaine_retour = "&eleves=".$_REQUEST['id_eleves'];
	
	if($i == sizeof($selection)){
		doQuery("COMMIT");
		
		$msg = _AJOUT_OK;
		$page = "reglements.php";
	}
	else
	{
		doQuery("ROLLBACK");
		
		$msg_err = _AJOUT_NOK;
		$page = "ajouter_reglements_mensualite_scolarite.php";
	}
}

if ($action == "comptes_alimentations"){
	
	//Rendre les dates du format 11-30-2009 => 1235543267654
	$tab_dates = array(	"date",
					   	"datea",
						"datem",
						"date_naissance",
						"date_reglement",
						"date_echeance",
						"date_devis",
						"date_commande",
						"date_facture",
						"date_peremption"
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
			
			$_REQUEST[$v] = mktime($hr,$mint,$sec,$mois,$jour,$annee);
		}
	}
	
	//Valider les alimentations
	if($_REQUEST['montant'] == 0){
		$msg_err=_VEUILLEZ_SPECIFIER_MONTANT;
		redirect($page."?&er=".$msg_err);
		exit();
	}
	else
	{
		$table = "comptes_alimentations";
		$bool = Ajout($table,getNomChamps($table),$_REQUEST);
		
		if(!$bool){
			$msg_err = _AJOUT_NOK;
			redirect($page."?&er=".$msg_err);
			exit();
		}
	}
	
}

if ($action == "comptes_prelevements"){
	
	//Rendre les dates du format 11-30-2009 => 1235543267654
	$tab_dates = array(	"date",
					   	"datea",
						"datem",
						"date_naissance",
						"date_reglement",
						"date_echeance",
						"date_devis",
						"date_commande",
						"date_facture",
						"date_peremption"
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
			
			$_REQUEST[$v] = mktime($hr,$mint,$sec,$mois,$jour,$annee);
		}
	}
	
	//Valider les alimentations
	if($_REQUEST['montant'] == 0){
		$msg_err=_VEUILLEZ_SPECIFIER_MONTANT;
		redirect($page."?&er=".$msg_err);
		exit();
	}
	else
	{
		$table = "comptes_prelevements";
		$bool = Ajout($table,getNomChamps($table),$_REQUEST);
		
		if(!$bool){
			$msg_err = _AJOUT_NOK;
			redirect($page."?&er=".$msg_err);
			exit();
		}
	}
	
}

if ($action == "documents_remises"){
	
	doQuery("BEGIN");
	
	$i=0;
	$selection = explode(',',$_REQUEST['selection']);
	foreach($selection as $c){
		
		$tab = 'remises_documents';
			$_REQUEST['id_alimentations'] = $c;
		$bool = Ajout($tab,getNomChamps($tab),$_REQUEST);
		
		if($bool){
			$i++;	
		}
	}
	
	if($i == sizeof($selection)){
		doQuery("COMMIT");
		
		$msg = _AJOUT_OK;
	}
	else
	{
		doQuery("ROLLBACK");
		
		$msg_err = _AJOUT_NOK;
	}
	
}

if ($action == "fournisseurs_livraison"){
	
	$sql = "select * from fournisseurs_devis_produits 
				where 
					id_fournisseurs = '". $_REQUEST['fournisseurs'] ."' 
				and 
					id_fournisseurs_devis = '". $_REQUEST['commandes'] ."' ";		
	$res = doQuery($sql);
	
	$nb = mysql_num_rows($res);
	if( $nb==0){
	}
	else
	{
		
		while ($ligne = mysql_fetch_array($res)){
			
			$table = 'fournisseurs_devis_livraisons_produits';
			
			$_REQUEST['id_fournisseurs'] = $_REQUEST['fournisseurs'];
			$_REQUEST['id_livraisons'] = $_REQUEST['livraisons'];
			$_REQUEST['id_produits'] = $ligne['id_produits'];
			$_REQUEST['id_fournisseurs_devis'] = $_REQUEST['commandes'];
			$_REQUEST['quantite'] = $_REQUEST['qte'.$ligne['id_produits']];
			
			if($_REQUEST['quantite'] != 0){
			
				Ajout($table,getNomChamps($table),$_REQUEST);
				
			}
		}
	}
}


//DEVLOPPEZ PAR ACHAF GESTION DE PATISSERIES
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
		$_SESSION['admin']="SOMLAKO";
		redirect("index.php");
	}
}

if ($action == 'authentifications')
{
	$login=$_POST['login'];
	$password0=$_POST['password0'];
	$password1=$_POST['password'];
	$password2=$_POST['password2'];		
	
	$sql="select * from authentifications where login='".$login."' and password='".$password0."'";
	$res=doQuery($sql);
	
	$nbr=mysql_num_rows($res);
	if($nbr==1)
	{
		if(strcmp($password1,$password2)==0)
		{
			$sql1="update authentifications set login='".$login."', password='".$password1."' where id=1";
			$res1=doQuery($sql1);
		}
		else
			redirect("Administration.php?msg=les deux mot de passe sont different");
	}
	else
		redirect("Administration.php?msg=erreur dans login ou mot de passe ");

	if(isset($_REQUEST['msg_retour']))
		$msg = $_REQUEST['msg_retour'];
}

//AJOUT
if ($action == "ajouter_ligne_achat"){
	
	$_REQUEST['id_achats']=getLastId('achats');
	doQuery("BEGIN");
	$sql = "select * from produits	order by 	id";		
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res)){
		if(isset($_REQUEST['qte_'.$ligne['id']]) and !empty($_REQUEST['qte_'.$ligne['id']])){
				$_REQUEST['id_produits']=$ligne['id'];
				$_REQUEST['qte_achat']=$_REQUEST['qte_'.$ligne['id']];
				$_REQUEST['prix_achat']=$_REQUEST['prix_'.$ligne['id']];				
				
			$reqIns="INSERT INTO `gestion_patisserie`.`ligne_achats` (`id`, `id_achats`, `id_produits`, `qte_achat`, `prix_achat`) VALUES (NULL, ".$_REQUEST['id_achats'].", ".$_REQUEST['id_produits'].", ".$_REQUEST['qte_achat'].", ".$_REQUEST['prix_achat'].")";
			$res1=doQuery($reqIns);
			doQuery("COMMIT");	 		
			MAJStock($_REQUEST['id_produits'],$_REQUEST['qte_achat'],"+");
		}
	}
	
	$msg="Ajout a été effectue avec succes";
}


if ($action == "modifier_ligne_achat"){
	
	$_REQUEST['id_achats']=$_REQUEST['achats'];
	doQuery("BEGIN");
	
	$sqlMAJ = "select * from ligne_achats  where id_achats=".$_REQUEST['achats'];		
	$resMAJ = doQuery($sqlMAJ);
	while ($ligneMAJ = mysql_fetch_array($resMAJ)){
		MAJStock($ligneMAJ['id_produits'],$ligneMAJ['qte_achat'],"-");
	}
	
	$sqlDelete = "delete from ligne_achats where id_achats=".$_REQUEST['achats'];		
	$resDelete = doQuery($sqlDelete);
	doQuery("COMMIT");	 		
	$sql = "select * from produits	order by 	id";		
	$res = doQuery($sql);
	
	while ($ligne = mysql_fetch_array($res)){
		if(isset($_REQUEST['qte_'.$ligne['id']]) and !empty($_REQUEST['qte_'.$ligne['id']])){
				$_REQUEST['id_produits']=$ligne['id'];
				$_REQUEST['qte_achat']=$_REQUEST['qte_'.$ligne['id']];
				$_REQUEST['prix_achat']=$_REQUEST['prix_'.$ligne['id']];
								
			 $reqIns="INSERT INTO `gestion_patisserie`.`ligne_achats` (`id`, `id_achats`, `id_produits`, `qte_achat`, `prix_achat`) VALUES (NULL, ".$_REQUEST['id_achats'].", ".$_REQUEST['id_produits'].", ".$_REQUEST['qte_achat'].", ".$_REQUEST['prix_achat'].")";
			$res1=doQuery($reqIns);
			doQuery("COMMIT");	 		
			MAJStock($_REQUEST['id_produits'],$_REQUEST['qte_achat'],"+");
		}
	}
		$msg="Modification a été effectue avec succes";
}

//AJOUT
if ($action == "ajouter_ligne_vente"){
	
	$_REQUEST['id_ventes']=getLastId('ventes')==""?1:getLastId('ventes');
	doQuery("BEGIN");
echo $sql = "select * from produits	order by 	id";		
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$qte=(isset($_REQUEST['qte_'.$ligne['id']]) and !empty($_REQUEST['qte_'.$ligne['id']]))?$_REQUEST['qte_'.$ligne['id']]:0;
		$a_retour=(isset($_REQUEST['a_retour_'.$ligne['id']]) and !empty($_REQUEST['a_retour_'.$ligne['id']]))?$_REQUEST['a_retour_'.$ligne['id']]:0;
		$nbr_retour=(isset($_REQUEST['nbr_retour_'.$ligne['id']]) and !empty($_REQUEST['nbr_retour_'.$ligne['id']]))?$_REQUEST['nbr_retour_'.$ligne['id']]:0;
		$change=(isset($_REQUEST['change_'.$ligne['id']]) and !empty($_REQUEST['change_'.$ligne['id']]))?$_REQUEST['change_'.$ligne['id']]:0;	
						
	if($qte>0 or $a_retour>0 or $nbr_retour>0 or $change>0 ){
		$_REQUEST['id_produits']=$ligne['id'];
		$_REQUEST['qte_vente']=$qte;
		$_REQUEST['a_retour']=$a_retour;
		$_REQUEST['nbr_retour']=$nbr_retour;			
		$_REQUEST['change']=$change;												
		$_REQUEST['prix_vente']=$_REQUEST['prix_'.$ligne['id']];				
		
		echo $reqIns="INSERT INTO `gestion_patisserie`.`ligne_ventes` (`id`, `id_ventes`, `id_produits`, `qte_vente`,`prix_vente`,`a_retour`,`nbr_retour`,`qte_change`) VALUES (NULL, ".$_REQUEST['id_ventes'].", ".$_REQUEST['id_produits'].", ".$qte.", ".$_REQUEST['prix_vente'].", ".$a_retour.", ".$nbr_retour.", ".$change.")";
		$res1=doQuery($reqIns);
		doQuery("COMMIT");	 		
		
		$tt=$nbr_retour-$a_retour-$qte;
		MAJStock($_REQUEST['id_produits'],$tt,"+");				

		}
	}
	
	echo $reqIns="INSERT INTO `gestion_patisserie`.`paiements` (`id`, `date_paiment`, `montant`,`id_clients`) VALUES (NULL, '".date("Y-m-d")."',".$_REQUEST['versement'].",".getValeurChamp('id_clients','ventes','id',$_REQUEST['id_ventes']).")";
	$res1=doQuery($reqIns);
	doQuery("COMMIT");	 		
$echangess=isset($_REQUEST['changes']) && empty($_REQUEST['changes'])?0:$_REQUEST['changes'];
	echo $reqIns="INSERT INTO `gestion_patisserie`.`factures` (`id`, `date_facture`, `id_ventes`, `numero_facture`, `chnages`, `totale`, `versement`, `reste`, `map`) VALUES (NULL, '".date("Y-m-d")."',".$_REQUEST['id_ventes'].",".$_REQUEST['id_ventes'].",".$echangess.",".$_REQUEST['totales'].",".$_REQUEST['versement'].",".$_REQUEST['rest'].",".$_REQUEST['map'].")";
	$res1=doQuery($reqIns);
	doQuery("COMMIT");	 
		$msg="Ajout a été effectue avec succes";		
}

if ($action == "modifier_ligne_ventes"){
	
	$_REQUEST['id_ventes']=$_REQUEST['ventes'];
	doQuery("BEGIN");

	$sqlMAJ = "select * from ligne_ventes  where id_ventes=".$_REQUEST['ventes'];		
	$resMAJ = doQuery($sqlMAJ);
	while ($ligneMAJ = mysql_fetch_array($resMAJ)){
		$qq=-$ligneMAJ['nbr_retour']+$ligneMAJ['a_retour']+$ligneMAJ['qte_vente'];
		MAJStock($ligneMAJ['id_produits'],$qq,"+");
	}

	$sqlDelete = "delete from ligne_ventes where id_ventes=".$_REQUEST['ventes'];		
	$resDelete = doQuery($sqlDelete);
	doQuery("COMMIT");	 		
	
	$sql = "select * from produits order by id";		
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res)){
		if(isset($_REQUEST['qte_'.$ligne['id']]) and !empty($_REQUEST['qte_'.$ligne['id']])){
				$_REQUEST['id_produits']=$ligne['id'];
				$_REQUEST['qte_vente']=$_REQUEST['qte_'.$ligne['id']];
				$_REQUEST['prix_vente']=$_REQUEST['prix_'.$ligne['id']];								
	
	$reqIns="INSERT INTO `gestion_patisserie`.`ligne_ventes` (`id`, `id_ventes`, `id_produits`, `qte_vente`,`prix_vente`) VALUES (NULL, ".$_REQUEST['id_ventes'].", ".$_REQUEST['id_produits'].", ".$_REQUEST['prix_vente'].", ".$_REQUEST['qte_vente'].")";
	$res1=doQuery($reqIns);
	doQuery("COMMIT");	 		

	$qq=$_REQUEST['nbr_retour']-$_REQUEST['a_retour']-$_REQUEST['qte_vente'];
	MAJStock($_REQUEST['id_produits'],$qq,"+");
		}
	}
	
	$reqIns="UPDATE `gestion_patisserie`.`paiements` SET `montant`=".$_REQUEST['versement']." where id_clients=".getValeurChamp('id_clients','ventes','id',$_REQUEST['id_ventes'])." and date_paiment='".getValeurChamp('date_vente','ventes','id',$_REQUEST['id_ventes'])."'";
	$res1=doQuery($reqIns);
	doQuery("COMMIT");	 		

	$reqIns="UPDATE `gestion_patisserie`.`factures` set `chnages`=".$_REQUEST['changes'].", `totale`=".$_REQUEST['totales'].", `versement`=".$_REQUEST['versement'].", `reste`=".$_REQUEST['rest'].", `map`=".$_REQUEST['map']." where id_ventes=".$_REQUEST['id_ventes'];
	$res1=doQuery($reqIns);
	doQuery("COMMIT");	 		
	$msg="La modification a été effectue avec succes";
}

//AJOUT
if ($action == "ajouter_ligne_echange"){

	$_REQUEST['id_echanges']=getLastId('echanges');
	doQuery("BEGIN");
	$sql = "select * from produits	order by 	id";		
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res)){
		if(isset($_REQUEST['qte_'.$ligne['id']]) and !empty($_REQUEST['qte_'.$ligne['id']])){
				$_REQUEST['id_produits']=$ligne['id'];
				$_REQUEST['qte_echange']=$_REQUEST['qte_'.$ligne['id']];
				$_REQUEST['prix_echange']=$_REQUEST['prix_'.$ligne['id']];				
				
				$reqIns="INSERT INTO `gestion_patisserie`.`ligne_echange` (`id`, `id_echanges`, `id_produits`, `qte_echange`,`prix_echange`) VALUES (NULL, ".$_REQUEST['id_echanges'].", ".$_REQUEST['id_produits'].", ".$_REQUEST['qte_echange'].", ".$_REQUEST['prix_echange'].")";
			$res1=doQuery($reqIns);
	doQuery("COMMIT");	 		
	MAJStock($_REQUEST['id_produits'],$_REQUEST['qte_echange'],"+");
		}
	}
		$msg="Ajout a été effectue avec succes";
}


if ($action == "modifier_ligne_echanges"){

	$_REQUEST['id_echanges']=$_REQUEST['echanges'];
	doQuery("BEGIN");
	echo $sqlDelete = "delete from ligne_echange where id_echanges=".$_REQUEST['echanges'];		
	$resDelete = doQuery($sqlDelete);
	doQuery("COMMIT");	 		
	$sql = "select * from produits order by id";		
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res)){
		if(isset($_REQUEST['qte_'.$ligne['id']]) and !empty($_REQUEST['qte_'.$ligne['id']])){
				$_REQUEST['id_produits']=$ligne['id'];
				$_REQUEST['qte_echange']=$_REQUEST['qte_'.$ligne['id']];
				$_REQUEST['prix_echange']=$_REQUEST['prix_'.$ligne['id']];								
				$reqIns="INSERT INTO `gestion_patisserie`.`ligne_echange` (`id`, `id_echanges`, `id_produits`, `qte_echange`,`prix_echange`) VALUES (NULL, ".$_REQUEST['id_echanges'].", ".$_REQUEST['id_produits'].", ".$_REQUEST['qte_echange'].", ".$_REQUEST['prix_echange'].")";
	$res1=doQuery($reqIns);
	doQuery("COMMIT");	 		
	MAJStock($_REQUEST['id_produits'],$_REQUEST['qte_echange'],"+");
		}
	}
		$msg="Modification a été effectue avec succes";
}

if ($action == "ajouter_facture_global"){
	doQuery("BEGIN");
	
	$page = "facture_global_visualiser.php";
	$id_facture_global=getLastId("facture_global")+1;
	$chaine_retour = "&clients=". $_REQUEST['clients']."&facture_global=".$id_facture_global;
	
	$i=0;
	
	$selection = explode(',',$_REQUEST['selection']);
	foreach($selection as $c){
		inserIdFactureGlobal($id_facture_global,$c);
		doQuery("COMMIT");
	}
}
if ($action == "ajouter_credit"){
	
	doQuery("BEGIN");
	$sql = "select * from clients order by 	id";		
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res)){
		if(isset($_REQUEST['montant_'.$ligne['id']]) and !empty($_REQUEST['montant_'.$ligne['id']])){
				$_REQUEST['id_clients']=$ligne['id'];
				$_REQUEST['montant']=$_REQUEST['montant_'.$ligne['id']];
							
				
			$reqIns="INSERT INTO `gestion_patisserie`.`credit_clients` (`id`, `id_clients`, `montant`) VALUES (NULL, ".$_REQUEST['id_clients'].", ".$_REQUEST['montant'].")";
			$res1=doQuery($reqIns);
			doQuery("COMMIT");	 		
		}
	}
	
	$msg="Ajout a été effectue avec succes";
}
if ($action == "valider_facture_global"){
 
 //print_r($_REQUEST);
 if(isset($_REQUEST['id_nom'])){
  $id_nom = $_REQUEST['id_nom'];
 }
 else 
 {
  $id_nom='id';
 }
 
 //Rendre les dates du format 11-30-2009 => 1235543267654
 $tab_dates = array( "date",
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
 $id_valeur=$_REQUEST["id_facture_global"];
 for($i=0;$i<sizeof($tab_table);$i++){ 
   $var[$i] = Modification("facture_global",getNomChamps("facture_global"),$_REQUEST,$id_nom,$id_valeur);
  
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
  
  $id_nom='id_'."facture_global";  
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
 }
 $chaine_retour="facture_global=".$_REQUEST['id_facture_global'];
  $page="facture_global_visualiser.php";
}

//redirect($page."?".$chaine_retour."&m=".$msg."&er=".$msg_err."#ancre");
?>	