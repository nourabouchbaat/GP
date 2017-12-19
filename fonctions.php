<?php require('params.php'); ?>
<?php 
//Fonctions
function connect () {

	$host = _BD_HOST;
	$user = _BD_USER;
	$pass = _BD_PASS;
	
	mysql_connect($host, $user, $pass) or die("Erreur de connexion au serveur (fonction.php)");
				
	selectDb ();
}	
	
function selectDb () {	

	$bd = _DB;
		
	mysql_select_db($bd) or die("Erreur de connexion a la base de donnees (fonction.php)");
}
				
function doQuery ($querystring) {	
		
	connect ();
	selectDb ();
	
	$query=$querystring ;
				
	$result = mysql_query ($query) or die(mysql_errno()) ;
	
		if(!$result) {
		
			$m = "Erreur Exe. SQL";
		
			return $msg;
		}
	
	return $result ;
					
}

function redirect($url){
?>
	<script language="javascript" type="text/javascript">
			document.location.href = "<?php echo $url ?>";
	</script>
<?php
}



function begin(){
	doQuery("BEGIN");
}

function commit(){
	doQuery("COMMIT");
}

function rollback(){
	doQuery("ROLLBACK");
}



function ifExist($table,$login){
	
	$sql = "
			select count(*) as nb 
			
			from ". $table ."
			
			where login = '". $login ."' 		
				
			";
				
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res) or die(mysql_error());
	
	$nb = $ligne['nb'];
	
	if ($nb == 0) return 0;
	if ($nb != 0) return 1;

}

function makeDateConn($id,$type,$date){
	
	if ($type == "sadmin") $type = "admin";
	
	$sql = "update ". $type ." set date_connexion = '". $date ."' where id = '". $id ."'";
	
	//echo $sql;
	$res = doQuery($sql);
}

function limiter_texte($texte,$limite){
	return substr($texte,0,$limite);
}


function genererRef($id){

//Generer une ref de type : 
//initial Type + initial Cat - Id Commercial - id Produit (Ex. LR-123-15)

//initial Type et initial Cat
$sql = "select * 
		
		from 
		
		categorie as c 
		join bien as b 
		on c.id = b.id_cat
		
		where b.id = '". $id ."'
		";

//echo $sql;

$res = doQuery($sql);
$ligne = mysql_fetch_array($res) or die(mysql_error());

if ($ligne['type'] == 1) $initial_type = "V";
if ($ligne['type'] == 2) $initial_type = "L";

$initial_cat = $ligne['initial'];

$id_commercial = $ligne['id_com'];

$id_produit = $id;

$ref = $initial_type . $initial_cat . "-" . $id_commercial . "-" . $id_produit;

return strtoupper($ref);

}

function dateVersBd($d){

	$date = explode("-",$d);
		
	return $date[2]."-".$date[0]."-".$date[1]; 
}

function dateVersSite($d){

	$date = explode("-",$d);
		
	return $date[1]."-".$date[2]."-".$date[0]; 
}

function lire_csv( $filename, $separateur){

	if ($FILE=fopen($filename,"r")){
	
		while ($ARRAY[]=fgetcsv($FILE,1024,$separateur));
		
			fclose($FILE) ;
			array_pop($ARRAY);
		
		return $ARRAY;
		
	}
}

//fonction de gestion
//cette fonction permet de supprimer un ou plusieurs enregistrement dans une table
function Suppression($table,$valeur){
	
	$sql = "delete from ".$table." where id = '". $valeur ."'";
	 
	$bool = doQuery($sql);
	 
	 //supression de l'image principale de l'element
	if ($bool){
		$tab_valeur=split(',',$valeur);
		
		foreach($tab_valeur as $val){
			$nom_image = $val."_".$table;
			foreach (glob("galerie/".$nom_image.".*") as $filename) {
				 unlink($filename);
				 
			}
		}	
		
	}	
	
	return $bool;
}

function formuler_retour($noms,$valeurs){

	if (($noms != "") and ($valeurs != "")){
		$tab_noms = explode(',',$noms);
		$tab_valeurs = explode(',',$valeurs);
		
		$chaine = "&";
		
		for($i=0;$i<sizeof($tab_noms);$i++){
			$chaine .= $tab_noms[$i] ."=". $tab_valeurs[$i] ."&";
		}
		
		$chaine = substr($chaine,0,strlen($chaine)-1);
	}
	else
	{
		$chaine;
	}
	
	return $chaine;
}

function formuler_where($champs,$valeurs){

	if (($champs != "") and ($valeurs != "")){
	
		$tab_champs = explode(',',$champs);
		$tab_valeurs = explode(',',$valeurs);
		
		$chaine = "";
		
		for($i=0;$i<sizeof($tab_champs);$i++){
			$chaine .= $tab_champs[$i] ."='". $tab_valeurs[$i] ."' and ";
		}
		
		$chaine = substr($chaine,0,strlen($chaine)-1);
	}
	else
	{
		$chaine;
	}
	
	return $chaine;
}

/*def:pour recuperer la valeur d'un champ a partie de l'identifiant de la table 
input: la table 
		l identifiant de l enregistrement
		et le champs 
output: la valeur du champs	*/	
function getValeurChamp3($table,$id,$champ){
	$sql="select ".$champ."  
			from ".$table."
			where ID=".$id."
			";
	$res= doQuery($sql); 
	if (mysql_num_rows($res) == 0){
		return "";
	}
	else
	{
		$ligne = mysql_fetch_array($res) or die(mysql_error());
		return $ligne[$champ];
	}
}
function getValeurChamp2($table,$id,$id_val,$champ){
	 $sql="select ".$champ."  
			from ".$table."
			where ".$id."=".$id_val."
			";
	$res= doQuery($sql); 
	$ligne = mysql_fetch_array($res) or die(mysql_error());
		
	return $ligne[$champ];
			
}

function getValeurChamp($champ1,$table,$champ2,$valeur){
	$where = formuler_where($champ2,$valeur);	
	  
	 $sql="select ". $champ1."  
			from ". $table."
			where ". $where ." 1=1";
	$res= doQuery($sql); 
	
	if(mysql_num_rows($res) != 0){
		$ligne = mysql_fetch_array($res) or die(mysql_error());
		return $ligne[$champ1];
	}
	else
		return "";
			
}
function getValeurChamp4($champ,$table,$champ1,$val1,$champ2,$val2){
	$sql="select ". $champ."  
			from ". $table."
			where `". $champ1 ."`=".$val1." and `". $champ2 ."`=".$val2;
	$res= doQuery($sql); 
	

	if(mysql_num_rows($res) != 0){
		$ligne = mysql_fetch_array($res) or die(mysql_error());
		return $ligne[$champ];
	}
	else
		return "";
			
}
//pour ajouter un enregistement a une table
//input: la table concerné
		//les champs de la table concerné par l ajout 
		//les valeurs de ces champs envoyer par le formulaire ($_REQUEST)
//output: un message de confirmation ou d'erreur		 
			
function Ajout($table,$tab_champs,$tab_requetes){
	
	$champs="";
	$valeurs="";
	
	foreach($tab_champs as $col){
	
	//$col= str_replace('_required','',$colonne);
		if(isset($tab_requetes[$col])){
			if ($champs==""){
				$champs=$col;
				$valeurs="'".addslashes($tab_requetes[$col])."'";
			}
			else
			{	
				$champs=$champs.','.$col;
				$valeurs=$valeurs.",'".addslashes($tab_requetes[$col])."'";	
			}
		}		
	}	
		
	echo $sql = "
				insert into 
				".$table."(".$champs.") 
				values(".$valeurs.")
			";
		
		$bool = doQuery($sql) or die("ERREUR AJOUT : ".mysql_error());
		
		/*if ($bool){
			//ajouter une ligne dans la table Historique_Connexion
			$table_modification = "historique_action";
			$_REQUEST['type_utilisateurs'] = $_SESSION['type'];
			$_REQUEST['id_utilisateurs'] = $_SESSION['utilisateurs'];
			$_REQUEST['date'] = time();
			Ajout($table_modification,getNomChamps($table_modification),$_REQUEST);
		}*/
		
	return $bool;
}

//pour modifier un enregistrement dans une table
//input: la table concerné
		//les champs de la table concerné par la modification
		//les valeurs de ces champs envoyer par le formulaire ($_REQUEST)
//output: un message de confirmation ou d'erreur	
function Modification($table,$tab_champs,$tab_requetes,$id_nom,$id_valeur){
	$champs_val="";
	foreach($tab_champs as $col){
		if(isset($tab_requetes[$col])){
			if ($champs_val==""){
				//$champs_val=$col."='".addslashes($tab_requetes[$col])."',";
				
				$v = $tab_requetes[$col];
				
				if ($col != "id"){
					if ($col == "password") 
						$champs_val=$col."='".addslashes(md5($v))."',";
					else
						$champs_val=$col."='".addslashes($v)."',";
				}
				
			}
			else{
				
				$champs_val=$champs_val.$col."='".addslashes($tab_requetes[$col])."',";	
			}
		}		
	}
	//pour eleminer la ',' à la fin de la chaine de caractere
	$champs_mod = substr($champs_val,0, -1);
	
	//préparation de la clause where
	$tab_id_nom = split(',',$id_nom);
	$tab_id_valeur = split(',',$id_valeur);
	
	for($i=0;$i<sizeof($tab_id_valeur);$i++){
		if ($where==""){
			$where=$tab_id_nom[$i]."='".addslashes($tab_id_valeur[$i])."'";
		}
		else{
			$where=$where." and ".$tab_id_nom[$i]."='".addslashes($tab_id_valeur[$i])."'";	
		}
	}	
		
	if($champs_mod!=''){
		$sql="update ".$table." set ".$champs_mod." where ".$where." ";
	}	
	return $bool = doQuery($sql) or die("ERREUR MODIFICATION CAT : ".mysql_error());
}

//cette fonction permet testé si une valeur existe ou non dans une table
function ExisteValeur($table,$champ,$valeur,$exep){
	//recuperé les valeurs existantes
	 $query = "select * from ".$table." where ".$champ."='".$valeur."' and ". $champ ." <> '". $exep ."' ";
	 $result = doQuery($query);
	 
	 //traitement de l'existance d'un enregistrement
	//echo mysql_num_rows($result);
	
	if (mysql_num_rows($result)!=0){
		return true;
	}
	else 
		return false;	
}

//cette fonction permet de récuperer le nom d'un seul champd'un table
function getChamp($table, $champ){
	//recuperer lesnom du cham d'une table
	 $query = "SHOW COLUMNS FROM " . $table ." LIKE '".$champ."'";
	 $result = doQuery($query);
	 
	 //traitement de l'existance d'un enregistrement
	if (mysql_num_rows($result)!=0){
		return true;
		}
	else return false;	
		
}
//cette fonction permet de récuperer les nom des champs d'un table
//input: une table sql
//output: un tableau avec les nom des champs

function getNomChamps($table){
	//recuperer tous les nom des champs d'une table
	 $query = 'SHOW COLUMNS FROM ' . $table ;
	 $result = doQuery($query);
	 
	 //mettre les nom des champs sous form de tableau
	 for($i=0;$i<mysql_num_rows($result);$i++)
		  {
			$cols[] = mysql_result($result, $i);
			
		  }
	return $cols;
}

//cette fonction permet d'avoir le tarif d'une chambre par saison
//input: id_chambre, id_saison, id_produit
//output: tarif

function getTarifChambre($id_chambres,$id_saisons,$id_produits){
	
	$sql = "
			select *  from tarifs
			where id_chambres = '".$id_chambres."' 
			and id_saisons = '". $id_saisons ."' 
			and id_produits = '". $id_produits ."' 
			";
	
	$res= doQuery($sql); 
	
	if (mysql_num_rows($res) == 0){
		return 0;
	}
	else
	{
		$ligne = mysql_fetch_array($res) or die(mysql_error());
		return $ligne['tarif_public'];	
	}
}

//cette fonction permet d'avoir le tarif d'une chambre par saison par type de chambre
//input: id_chambre, id_saison, id_produit,id_types_chambres
//output: tarif

function getTarifChambreByType($id_chambres,$id_types_chambres,$id_saisons,$id_produits){
	
		$sql = "
			select *  from tarifs
			where id_chambres = '".$id_chambres."' 
			and id_saisons = '". $id_saisons ."' 
			and id_produits = '". $id_produits ."' 
			and id_types_chambres = '". $id_types_chambres ."' 
			";
	
	$res= doQuery($sql); 
	
	if (mysql_num_rows($res) == 0){
		return 0;
	}
	else
	{
		$ligne = mysql_fetch_array($res) or die(mysql_error());
		return $ligne['tarif'];	
	}
}


function datediff($debut,$fin){
	$diff = $debut - $fin;
	
	return round($diff/(60*60*24));
}


/*fonction permet de modifier pour un ou plusieurs enregistrement la valeurs d un champs
	input: table
			le mon du champs que nous voulons modifier
			la nouvelle valeur
			le ou les enregistrements concernées
	output: un message de confirmation ou d'erreur		
*/
function ModifValChamps($table,$champ,$valeur,$ids){
	$sql = "update ".$table." set 
				  
			  ".$champ."='".$valeur."'			  
			  where id in ('".$ids."')";
	
	//echo $sql;
			  
	return $bool = doQuery($sql) or die("ERREUR MODIFICATION ETAT : ".mysql_error());
	
}
/*fonction pour uploader une image dans une table
	input : table
			valeur: le fichier envoyé souqs forme array
			id: l'identifiant de l'enregistrement conserné
	output : message de confirmation ou d'erreur		
*/ 

function upload_image($table,$valeur,$id){
	if ($valeur['tmp_name']!=''){
		$image_tmp=$valeur['tmp_name'];
		$extention=get_image_extention($image_tmp);
		$nom_image=$id.'_'.$table.$extention;
		move_uploaded_file($image_tmp,'galerie/'.$nom_image);
		return ModifValChamps($table,'image',$nom_image,$id);
	}	
	
}
/*function qui permet de recuperer l'extention d'un fuchier image
	input :nom image
	output: extention de l'image
*/
function get_image_extention($image){
	list($largeur, $hauteur, $type, $attr)=getimagesize($image);
	if($type==1){$extention=".gif";}
	if($type==2){$extention=".jpg";}
	if($type==3){$extention=".png";}
	return $extention;
}

/*fonction d'affichage de liste simple

*/
function get_list_simple($tableau,$nom_champs){
?>
	<select name="<?php echo $nom_champs ?>" id="<?php echo $nom_champs ?>">
	<option value="">- -  - - - -  - - _</option>
		<?php  foreach($tableau as $val){ ?>
		<option value="<?php echo $val ?>"><?php echo formater_texte($val) ?></option>
		<?php  } ?>
	</select>
<?php 
}	
/*foncion de liste
input table concerné
		champ qu"on veux listé
		condition la condition where ou cas ou nous voulons selectionner les enregistrement
*/
function get_list($table,$id_modif,$champ,$condition,$action){ 
	if ($action=='selection'){
		$act="onChange='frm.submit()'";
		$option_vide='<option value=""> - -  - - __ </option>';
	}else 
		$act='';
	if($condition!=''){
		$cond=" where ".$condition;
	}
	else {
		$cond="";
	}
	
	$sql = "select * from ".$table.$cond. " order by ".$champ;
	$res = doQuery($sql);
	
	?>
	<select name="id_<?php echo $table ?>" id="id_<?php echo $table ?>" <?php echo $act ?> >
		<?php echo $option_vide ?>
     <?php   
		$s = "";
			while($ligne = mysql_fetch_array($res)){
				if(isset($id_modif)){
					if ($id_modif== $ligne['id']){
						//return $ligne['id'];
						$s = 'selected="selected"';
					}
				}	
		?>	
		
		<option value="<?php echo $ligne['id']?>" <?php echo  $s ?>> 
			<?php echo formater_texte($ligne[$champ]) ?> 
		</option>
		
		<?php
		$s="";
		}
		?>
		</select>
        <?php 
		return mysql_num_rows($res);
}

//fonction test
function get_table_valeur($table,$champs,$criteres){
	if ($criteres!=''){
		$crit="where ".$criteres;
	}
	else
		$crit="where 1=1";

	 $sql = "select * from ".$table." ". $crit;

	$res = doQuery($sql);
	$i=0;
	while($ligne = mysql_fetch_array($res)){
	
				foreach($champs as $champ){
					$valeurs[$i][$champ]=$ligne[$champ];
				}
		$i++;
	}
	return $valeurs;
}

//fonction d'affichage du th d'un tableau
function affichage_th($champs){
	
	?>
	<th class="case"> <input type="checkbox" name="all" onClick="cocherTout()" /> </th>
				<?php 
				for($i=0;$i<sizeof($champs);$i++){
				//foreach($champs as $champ){
				?>
					<th><?php echo $champs[$i] ?></th>
	             <?php   
				}
				?>
					<th> <?php echo _OP ?></th>
	<?php 
}

//fonction affichage valeurs sous forme de tableau
function affiche_table($champs,$valeurs,$table,$page){
	if( count($valeurs)==0){
	?>
		<tr>
        	<td colspan="20">
			<?php echo _TAB_VIDE;?>
            </td>
         <tr>   
     <?php       
	}
	else{
		foreach($valeurs as $valeur){
			//coloration des lignes
			if ($j%2 == 0) $c = "c1";
				else $c = "c2";	
			$j++;
			$id_val=$valeur['id'];
		?>	
			<tr class=<?php echo $c?>>	
				<td> <input type="checkbox" name="checkbox" value="<?php echo $id_val?>" /> </td>
				  <!- - <td><a href="<?php// echo substr($table, 0, -1);?>.php?id=<?php//echo $valeur['id'] ?>" ><?php//  echo $valeur[next($champs)] ?></a></td>- - >
                  <td>
                  		<a href="<?php echo _CHEMIN_ADMIN_WEB.$page ?>?id=<?php echo $id_val ?>" >
				  			<?php  echo $valeur[next($champs)] ?>
                        </a>
                  </td>
					<?php 
					
					for($i=1;$i<sizeof($valeur)-1;$i++){
						$champ=next($champs);
						if ($champ=='date_contrat'){
							if($valeur[$champ]=="0000-00-00"){
							?>
								<td> 
									<span><img src="../img/0.gif"/></span>
								</td>
                                
                             <?php 
							 }
							 else{
							   ?>
								<td> 
									<span><img src="../img/1.gif"/></span>
								</td>
                                
                             <?php 
							 }
						}else if ( $champ=='etat'){
							if($table =='produits'){$page_retour='sec/produits.php';}
							if($table =='categories'){$page_retour='manager/categories.php';}
							if($table =='caracteristiques'){$page_retour='manager/caracteristiques.php';}
							if( $valeur[$champ]==0){
							?>
								<td> 
									<span><a href="<?php echo _CHEMIN_ADMIN_WEB ?>gestion.php?table=<?php echo $table ?>&page=<?php echo $page_retour ?>&act=etat&champ_modif=etat&id=<?php echo $id_val?>"><img src="../img/0.gif"/></a></span>
								</td>
                                
                             <?php 
							 }
							 else{
							   ?>
								<td> 
									<span><a href="<?php echo _CHEMIN_ADMIN_WEB ?>gestion.php?table=<?php echo $table ?>&page=<?php echo $page_retour ?>&act=etat&champ_modif=etat&id=<?php echo $id_val?>"><img src="../img/1.gif"/></a></span>
								</td>
                                
                             <?php 
							 }
						}
						else {
					?>
						<td> 
							<span><?php echo $valeur[$champ] ?></span>
						</td>
				<?php 	
					}
					}
					
						reset($champs);
						$tab=split('/',$page);
						$racine=$tab['0'];
						
					
				?>
				<td>
                    <a href="<?php echo _CHEMIN_ADMIN_WEB.$racine ?>/modifier_<?php echo $table?>.php?id=<?php echo $valeur['id'] ?>" title="'._MODIFIER.'">
                        <img src="../img/_nav_dashboard.gif"/>
                    </a>
                    <!- - <a href="#" title="'._ADDFAVORIS.'">
                        <img src="../img/icon_addtofav.gif"/>
                    </a>- - >
                    <a href="#" 
                 onclick="javascript:supprimer('<?php echo $table;?>','<?php echo $valeur['id'];?>','<?php echo $table.".php";?>')">
                        <img src="../img/btn_remove-selected_bg.gif" />
                    </a>
							
				</td>
			   </tr>
			   <?php 
		}
	}	 			
}

/*affichage des titres titre
input: l url de l icone 
		le texte du titre*/
		
function affichage_titre($url,$libelle){
	if ($url==''){
		$url='image_default.jpg';
	}
	?>
	<p id="titre">
    	<img src="<?php echo $url; ?> " />
         &nbsp; 
		 <?php echo $libelle; ?>
    </p>
    <?php
}
/*affichage des operations
input: le lien lien ver le quel dirige cette operation
		l action javascript onclick executer par l'operation
		l'url de l'icone de l'operation
		le libelle de l'operation
*/
function affichage_operation($lien,$action,$url,$libelle){
	if($lien==''){
		$lien='#';
	}
	if ($url==''){
		$url='image_default.jpg';
	}
	?>
	 <a href="<?php echo $lien; ?>" onclick="<?php echo $action;?>">
          <img src="<?php echo $url ?>" />
           &nbsp;
          <?php echo $libelle ?>
        </a>
     <?php 
}
//function verification de compte
function verification_compte($comptes){

	$tab_comptes = split(',',$comptes);
	
	$i=0;
	foreach($tab_comptes as $compte){
		if($_SESSION['type']==$compte){ 
			$i++;
		}	
	}
	
	if($i==0){
		if($_SESSION['type']=='partenaire'){ 
			redirect(_CHEMIN_ADMIN_WEB."partenaire/produit.php");
		}
		
		if($_SESSION['type']=='secretaire'){ 
			redirect(_CHEMIN_ADMIN_WEB."sec/produits.php");
		}
		
		if($_SESSION['type']=='admin'){ 
			redirect(_CHEMIN_ADMIN_WEB."manager/categories.php");
		}
		else 
		{ 
			redirect(_CHEMIN_ADMIN_WEB."index.php");
		}
	}
	
} 


//Tarifs pour un produit selon la saison
function getLowTarifBySaison($hotel){
	
	$time = time();
	
	$sql_saison = 
			"
			select id_saisons from 
			saisons join periodes
			on saisons.id = periodes.id_saisons
			
			where saisons.id_produits = '". $hotel ."' and 
			". $time ." between date_debut and date_fin";
				
	$res_saison = doQuery($sql_saison);
	
	if (mysql_num_rows($res_saison) == 0){
		
	}
	else
	{
		$ligne_saison = mysql_fetch_array($res_saison) or die(mysql_error());
		$id_saison_en_cours = $ligne_saison['id_saisons'];
		
		//Get Le min des tarifs pour cet hotel
		$sql_tarif = 
			"
			select min(tarif) as min 
			from tarifs
			where id_produits = '". $hotel ."' and id_saisons = '". $id_saison_en_cours ."' 
			and tarif <> 0
			";
		$res_tarif = doQuery($sql_tarif);
		
		if (mysql_num_rows($res_tarif) == 0){
		}
		else
		{
			$ligne_tarif = mysql_fetch_array($res_tarif) or die(mysql_error());
			return $ligne_tarif['min'];
		}
	}
	
	
}


//fonction galerie
function affichage_galerie($table,$id_nom,$id_valeur,$page,$noms_retour,$valeurs_retour){
?>
	          
	<form name="frm" method="post" enctype="multipart/form-data" action="gestion.php" onsubmit="return checkForm(document.frm)"> 
		
		<input type="hidden" name="act" value="a">
        
        <input type="hidden" name="id_noms_retour" value="id,parent">
        <input type="hidden" name="id_valeurs_retour" value="<?php echo $id_valeur ?>,<?php echo $_REQUEST['parent'] ?>">
		
		<input type="hidden" name="<?php echo $id_nom ?>" value=<?php echo $id_valeur ?>>
		<input type="hidden" name="table" value="<?php echo $table ?>">
		<input type="hidden" name="page" value="<?php echo $page ?>">
        
        <input type="hidden" name="id_noms_retour" value="<?php echo $noms_retour ?>">
        <input type="hidden" name="id_valeurs_retour" value="<?php echo $valeurs_retour ?>">
		
		<table>
			<tr>
				<td>
					<input type="file"  id="photo_required" name="photo" />
					<input type="submit" class="button"  value="<?php echo _AJOUTER ?>"/>
					
				</td>
			</tr>
		</table>
		
		<table>
			<tr>
			<td>
			<table border="0">
			    <tr>
			  	<?php 
				$sql= "select * from ".$table." where ".$id_nom."='". $id_valeur ."'";
			  	$res = doQuery($sql);
			  	$i=1;
				while ($ligne = mysql_fetch_array($res)){	
			 	?>
					<td width="210">
                           <?php $fichier = "galerie/".$ligne['image'] ?>
                           <?php echo resize_picture($fichier,200,150," class='cadre' ") ?>
					
						&nbsp;
                        
						<a href="#" 
                        onclick="javascript:supprimer(
                        							'<?php echo $table ?>',
                                                    '<?php echo $ligne['id'];?>',
                                                    '<?php echo $page ?>',
                                                    '<?php echo $noms_retour ?>',
                                                    '<?php echo $valeurs_retour ?>'
                                                    )" 
                        class="supprimer2" >
							&nbsp;
						</a>
					
					</td>
			<?php if ($i%3==0) 
				{
			?>
				</tr><tr>	 
			<?php } 
			$i++;
			}?>
												
			
			  </tr>
		</table>
	</td>
	</tr>
	</table>
	</form>
<?php

}

function getNb($table,$champ,$id){
	
	//Formuler champ=valeur and ....
	$tab_champs = explode(',',$champ);
	$tab_valeurs = explode(',',$id);
	
	$chaine = "";
	
	for($i=0;$i<sizeof($tab_champs);$i++){
		$chaine .= $tab_champs[$i] ."='".  $tab_valeurs[$i] ."' and ";
	}
	$chaine = substr($chaine,0,strlen($chaine)-5);
	
	//
	$sql_get = "
			select count(*) as nb 
			
			from ". $table ."
			
			where ". $chaine;
			
	$res_get = doQuery($sql_get);
	$ligne_get = mysql_fetch_array($res_get) or die(mysql_error());
	
	return $nb = $ligne_get['nb'];
}

function getSum($table,$colonne,$champ,$id){
	
	//Formuler champ=valeur and ....
	$tab_champs = explode(',',$champ);
	$tab_valeurs = explode(',',$id);
	
	$chaine = "";
	
	for($i=0;$i<sizeof($tab_champs);$i++){
		$chaine .= $tab_champs[$i] ."='".  $tab_valeurs[$i] ."' and ";
	}
	$chaine = substr($chaine,0,strlen($chaine)-5);
	
	//
	//echo "- -  - - - -  - - <br>";
	$sql_get = "
			select sum(". $colonne .") as total 
			
			from ". $table ."
			
			where ". $chaine;
	//echo "<br>- -  - - - -  - - <br><br>";
			
	$res_get = doQuery($sql_get);
	$ligne_get = mysql_fetch_array($res_get) or die(mysql_error());
	
	if ($ligne_get['total'] == "") return "0";
	return $ligne_get['total'];
}

function getSumByJours($table,$colonne,$champ,$id,$champ2,$debut,$fin){
	
	//Formuler champ=valeur and ....
	$tab_champs = explode(',',$champ);
	$tab_valeurs = explode(',',$id);
	
	$chaine = "";
	
	for($i=0;$i<sizeof($tab_champs);$i++){
		$chaine .= $tab_champs[$i] ."='".  $tab_valeurs[$i] ."' and ";
	}
	$chaine = substr($chaine,0,strlen($chaine)-5);
	
	//
	//echo "- -  - - - -  - - <br>";
	$sql_get = "
			select sum(". $colonne .") as total 
			
			from ". $table ."
			
			where ". $chaine ." 
			and ".$champ2." between ". $debut." and ". $fin;
	//echo "<br>- -  - - - -  - - <br><br>";
			
	$res_get = doQuery($sql_get);
	$ligne_get = mysql_fetch_array($res_get) or die(mysql_error());
	
	if ($ligne_get['total'] == "") return "0";
	return $ligne_get['total'];
}

function getNbPublie($table,$ville){
	
	$sql_get = "
			select count(*) as nb 
			
			from ". $table ."
			
			where ville = '". formater_texte($ville) ."' 
			
			and etat = 1 and etat_sup = 1";
				
	$res_get = doQuery($sql_get);
	$ligne_get = mysql_fetch_array($res_get) or die(mysql_error());
	
	return $nb = $ligne_get['nb'];
}


function getTimeByDate($date,$sep){
	
	$tab = explode($sep,$date);
			
	$annee = $tab[2];
	$mois = $tab[0];
	$jour = $tab[1];
	
	return mktime(0,0,0,$mois,$jour,$annee);
}


//fonction de pagination
//recuperer le nombre de page en fonction du nombre de message que nous avons défini

function getNbrPages($requete,$messagesParPage){
		connect ();
		selectDb ();
		$retour_total=doQuery($requete) or die("ERREUR : ".mysql_error()); 
		//Nous récupérons le contenu de la requête dans $retour_total
		$donnees_total=mysql_fetch_assoc($retour_total); //On range retour sous la forme d'un tableau.
		$total=$donnees_total['total']; //On récupère le total pour le placer dans la variable $total.
		//Nous allons maintenant compter le nombre de pages.
		$nombreDePages=ceil($total/$messagesParPage);
		return $nombreDePages;
}


//recuperer le numero de la page actuelle
function getPageActuelle($nombreDePages){
		if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
		{
			 $pageActuelle=intval($_GET['page']);
			 
			 if($pageActuelle>$nombreDePages) 
			 // Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $nombreDePages...
			 {
				  $pageActuelle=$nombreDePages;
			 }
		}
		else // Sinon
		{
			 $pageActuelle=1; // La page actuelle est la n°1    
		}
		return 	$pageActuelle;
}


//affichage du systeme de navigation
function AffichagePagination($page,$pageActuelle,$nombreDePages){
	echo '<p align="center">'; //Pour l'affichage, on centre la liste des pages
	//precedent
		if($pageActuelle>1) //Si il s'agit de la page actuelle...
				 {
					 $j=$pageActuelle-1;
					 echo ' <a href="'.$page.'page='.$j.'"><< '. formater_texte("Page Précédente") .'</a> ';
					 //echo ' <a href="'.$page.','.$j.'.html"><< '. formater_texte("Page Précédente") .'</a> ';
				 }	

		 
	//fin precedent	 
	 if ($nombreDePages!=1){
		for($i=1; $i<=$nombreDePages; $i++) //On fait notre boucle
		{
			 //On va faire notre condition
			 if($i==$pageActuelle) //Si il s'agit de la page actuelle...
				 {
					 echo ' [ '.$i.' ] '; 
				 }	
			 else //Sinon...
				 {
					  echo ' <a href="'.$page.'page='.$i.'">'.$i.'</a> ';
					  //echo ' <a href="'.$page.','.$i.'.html">'.$i.'</a> ';
				 }		 
		}
	}	
	//suivant
		if($pageActuelle<$nombreDePages) //Si il s'agit de la page actuelle...
				 {
					 $j=$pageActuelle+1;
					 echo ' <a href="'.$page.'page='.$j.'">'. formater_texte("Page Suivante") .' >></a> ';
					 //echo ' <a href="'.$page.','.$j.'.html">'. formater_texte("Page Suivante") .' >></a> ';
				 }	
		 
	//fin suivant	 
echo '</p>';
}

//fin fonction de pagination


//fonction pour inseré des image pour le nbr d'etoiles
function image_etoile($etoile){
	if ($etoile=="1") return "1.png";
	if ($etoile=="2") return "2.png";
	if ($etoile=="3") return "3.png";
	if ($etoile=="4") return "4.png";
	if ($etoile=="5") return "5.png";
	if ($etoile=="6") return "5.png";
	if ($etoile=="7") return "0.png";
}
//pour generer un mot de pass aliatoire
function newChaine( $chrs = "") {
		if( $chrs == "" ) $chrs = 8;
		$chaine = ""; 
		$list = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		mt_srand((double)microtime()*1000000);
		$newstring="";
		while( strlen( $newstring )< $chrs ) {
			$newstring .= $list[mt_rand(0, strlen($list)-1)];
		}
		return $newstring;
	}
	
//cette fonction permet de récuperer la date du jour suivant (yyyy/mm/jj) à une date donné	
function date_jour_suivant($date_a){
	$tab=split("/",$date_a);
	 $annee=intval($tab[0]);
	 $mois=intval($tab[1]);
	 $jour=intval($tab[2]);
	//recupererle nbr de jour du mois en cours
	if($mois==04 or $mois==06 or $mois==09 or $mois==11 ){
		$nbr_jour=30;
	} 
	else if( $mois==02 ){
			if($annee%4==0){
				$nbr_jour=29;
			}
			else {
				$nbr_jour=28;
			}	
	}
	else {
		$nbr_jour=31;
	}
	//echo $nbr_jour;
	$annee=sprintf("%02d", $annee);
	$mois=sprintf("%02d", $mois);
	if($jour<$nbr_jour){
		
		$jour_suivant=sprintf("%02d", $jour+1);
		$date_suivante=$annee."/".$mois."/".$jour_suivant;
	}
	else if($jour==$nbr_jour and $mois==12){
		$annee_suivante=sprintf("%02d", $annee+1);
		$date_suivante=$annee_suivante."/01/01";	
	}	 
	else{
		$mois_suivant=sprintf("%02d", $mois+1);
		$date_suivante=$annee."/".$mois_suivant."/01";
	}
	return $date_suivante;	
}
//fonction de test d'une reservation
function test_reservation($id,$date_jour){
 	$date_jour = str_replace('/','-',$date_jour);
	 $sql = 'SELECT sum(nbr_chambre_resa) as somme FROM `reservation` WHERE '
        . ' (date_arrive <=\''.$date_jour.'\' and date_depart >=\''.$date_jour.'\')and (id_chambres=\''.$id.'\' )';
	$res = doQuery($sql);  	
		
	$ligne = mysql_fetch_array($res);
	//echo "cfdf:".$ligne['somme'];
	$nbre_chambre_reserve=intval($ligne['somme']);
	$nbre_chambre=intval(getValeurChamp('chambres',$id,'nbr_chambre'));
	$nbr_chambre_dispo=$nbre_chambre-$nbre_chambre_reserve;
	if ($nbr_chambre_dispo<0){
		return 0;
	}
	else{
		return $nbr_chambre_dispo;
	}	
	
}

function formater_texte($t){
	//return htmlentities(stripslashes($t));
	return htmlentities(stripslashes($t));
}

function formater_texte2($t){
	return html_entity_decode(stripslashes($t));
}

function envoi_mail($dest,$sujet,$message,$page){
	
//envoyer un msg de confirmation par mail
$headers ='From: "World Rezervation"<contact@world-rezervation.com>'."\n";
$headers .='Reply-To: contact@world-rezervation.com'."\n"; 
$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n";
$headers .='Content-Transfer-Encoding: 8bit';

$msg ='

<html>
	<head>
		<title>'.$sujet.'</title>
	</head>
	
	<body>
		'. $message .'
	</body>
</html>';

if (mail($dest, $sujet, $message, $headers)){
	$msg = _ENVOI_OK;
}
else
{
	$msg_err = _ENVOI_NOK;
}

redirect($page."?msg=".$msg."&err=".$msg_err);
}


function getTabList($tab,$nom,$valeur,$change,$libelle){
?>
<div class="controls">
<select name = "<?php echo $nom ?>" <?php echo $change ?> id="<?php echo $libelle ?>_required">	
	
    <option value="">- -  - - _</option>
	
	<?php
    foreach($tab as $c => $v){
    
        $s = "";
        
        if ($valeur == $c){
            $s = "selected";
        }
    ?>
        <option value="<?php echo $c ?>" <?php echo $s ?>>
			<?php echo formater_texte($v) ?>
        </option>
        
    <?php
    }
	?>
	
    </select>
</div>    
<?php
}


function getPrixTtc($prix,$taux){
	return $prix + ($prix * ($taux / 100 )); 
}


function getTotalHtCommandesFournisseurs($commandes,$fournisseurs){
	
	/*////////////////////////////// Produits ////////////////////////////////*/
	$sql_prd_devis = "select * from fournisseurs_devis_produits 
					where id_fournisseurs = '". $fournisseurs ."' 
					and id_fournisseurs_devis = '". $commandes ."' 
					order by id desc";
	$res_prd_devis = doQuery($sql_prd_devis);
	
	$grand_total_ht = 0;
	
	if (mysql_num_rows($res_prd_devis) == 0){
	}
	else
	{
		while($ligne_prd_devis = mysql_fetch_array($res_prd_devis)){
				$prix_ht = $ligne_prd_devis['prix'];
				
				$quantite = $ligne_prd_devis['quantite'];
				
				$total_ht = $prix_ht * $quantite;
				$grand_total_ht += $total_ht;
		}
	}
	/*////////////////////////////// Produits ////////////////////////////////*/
	
	
	
	/*////////////////////////////// services ////////////////////////////////*/
	$sql_srv_devis = "select * from fournisseurs_devis_services 
					where id_fournisseurs = '". $fournisseurs ."' 
					and id_fournisseurs_devis = '". $commandes ."' 
					order by id desc";
	$res_srv_devis = doQuery($sql_srv_devis);
	
	$grand_total_ht_srv = 0;
	
	if (mysql_num_rows($res_srv_devis) == 0){
	}
	else
	{
		while($ligne_srv_devis = mysql_fetch_array($res_srv_devis)){
		
			$prix_ht_srv = $ligne_srv_devis['prix'];
			
			$quantite_srv = $ligne_srv_devis['quantite'];
			
			$total_ht_srv = $prix_ht_srv * $quantite_srv;
			$grand_total_ht_srv += $total_ht_srv;
		}
	}
	/*////////////////////////////// services ////////////////////////////////*/
	
	return ($grand_total_ht + $grand_total_ht_srv);
}


function getTotalTtcCommandesFournisseurs($commandes,$fournisseurs){
	/*////////////////////////////// Produits ////////////////////////////////*/
	$sql_prd_devis = "select * from fournisseurs_devis_produits 
					where id_fournisseurs = '". $fournisseurs ."' 
					and id_fournisseurs_devis = '". $commandes ."' 
					order by id desc";
	$res_prd_devis = doQuery($sql_prd_devis);
	
	$grand_total_ht = 0;
	$grand_total_ttc = 0;
	
	if (mysql_num_rows($res_prd_devis) == 0){
	}
	else
	{
		while($ligne_prd_devis = mysql_fetch_array($res_prd_devis)){
				$prix_ht = $ligne_prd_devis['prix'];
				
				$quantite = $ligne_prd_devis['quantite'];
				
				$total_ht = $prix_ht * $quantite;
				$grand_total_ht += $total_ht;
				
				$taux = $ligne_prd_devis['tva'];
				
				$total_ttc = getPrixTtc($total_ht,$taux);
				$grand_total_ttc += $total_ttc;
		}
	}
	/*////////////////////////////// Produits ////////////////////////////////*/
	
	
	/*////////////////////////////// Services ////////////////////////////////*/
	$sql_srv_devis = "select * from fournisseurs_devis_services 
					where id_fournisseurs = '". $fournisseurs ."' 
					and id_fournisseurs_devis = '". $commandes ."' 
					order by id desc";
	$res_srv_devis = doQuery($sql_srv_devis);
	
	$grand_total_ht_srv = 0;
	$grand_total_ttc_srv = 0;
	
	if (mysql_num_rows($res_srv_devis) == 0){
	}
	else
	{	
		while($ligne_srv_devis = mysql_fetch_array($res_srv_devis)){
			$prix_ht_srv = $ligne_srv_devis['prix'];
			
			$quantite_srv = $ligne_srv_devis['quantite'];
			
			$total_ht_srv = $prix_ht_srv * $quantite_srv;
			$grand_total_ht_srv += $total_ht_srv;
			
			$taux_srv = $ligne_srv_devis['tva'];
			
			$total_ttc_srv = getPrixTtc($total_ht_srv,$taux_srv);
			$grand_total_ttc_srv += $total_ttc_srv;
		}
	}
	
	/*////////////////////////////// Services ////////////////////////////////*/
	return ($grand_total_ttc + $grand_total_ttc_srv);
}


function getTableList($table,$nom,$valeur,$champ,$change,$where,$libelle){
	
	$sql = "select * from ". $table ." ". $where ." order by ". $champ;
	$res = doQuery($sql);
	?>
	<div class="controls">
	<select name="<?php echo $nom ?>" <?php echo $change ?> 
	id="<?php echo $libelle ?>_required">
		
		<option value="">- -  - - _</option>
		
	<?php
	while($ligne = mysql_fetch_array($res)){	
		
		$s = "";
		
		if ( $valeur == $ligne['ID']){
			$s = "selected";
		}
	?>
		<option value="<?php echo $ligne['ID'] ?>" <?php echo $s ?>>
			<?php echo $ligne[$champ] ?>
		</option>
	<?php
	}
	?>
	</select>
 </div>
<?php
}

function getTableList2($table,$nom,$valeur,$champs,$value,$change,$where,$libelle){
	
	$sql = "select * from ". $table ." ". $where ." order by ". $champs;
	$res = doQuery($sql);
	?>
	<div class="controls">
	<select name="<?php echo $nom ?>" <?php echo $change ?> 
	id="<?php echo $libelle ?>_required">
		
		<option value="">- -  - - _</option>
		
	<?php
	while($ligne = mysql_fetch_array($res)){	
		
		$s = "";
		
		if ($valeur == $ligne[$value]){
			$s = "selected";
		}
	?>
		<option value="<?php echo $ligne[$value] ?>" <?php echo $s ?>>
			<?php 
			$tab_champs = explode(',',$champs);
			
			$chn = '';
			foreach($tab_champs as $v){
				$chn .= $ligne[$v] ." => ";
			}
			echo $chn = substr($chn,0,strlen($chn)-4);
			?>
		</option>
	<?php
	}
	?>
	</select>
  </div>
<?php
}

function resize_picture($fichier,$maxWidth,$maxHeight,$extras){

	# Passage des parametres dans la table : imageinfo
	$imageinfo= getimagesize("$fichier");
	$iw=$imageinfo[0];
	$ih=$imageinfo[1];
	
	# Parametres : Largeur et Hauteur souhaiter $maxWidth, $maxHeight
	# Calcul des rapport de Largeur et de Hauteur
	$widthscale = $iw/$maxWidth;
	$heightscale = $ih/$maxHeight;
	$rapport = $ih/$widthscale;
	
	# Calul des rapports Largeur et Hauteur a afficher
	if($rapport < $maxHeight)
		{$nwidth = $maxWidth;}
	 else
		{$nwidth = $iw/$heightscale;}
	 if($rapport < $maxHeight)
		{$nheight = $rapport;}
	 else
		{$nheight = $maxHeight;}
	
	# Affichage
	echo " <img src=$fichier width=\"$nwidth\" height=\"$nheight\" $extras>";
}

//Les menus imbriqués
function afficher_menu($parent, $niveau, $array) {
				 
	$html = "";
	$niveau_precedent = 0;
	 
	if (!$niveau && !$niveau_precedent) $html .= "\n<ul id='style'>\n";
	 
	foreach ($array AS $noeud) {
	 	
		if ($parent == $noeud['parent_id']) {
	 
		if ($niveau_precedent < $niveau) $html .= "\n<ul>\n";
	 		
			$etat = "<img src='images/".$noeud['etat_categorie'].".png' width='11'>";
			
			$lien = $noeud['nom_categorie'];
			
			$html .= "<li>". $lien;
		 
			$niveau_precedent = $niveau;
		 
			$html .= afficher_menu($noeud['categorie_id'], ($niveau + 1), $array);
	 
		}
	}
	 
	if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) $html .= "</ul>\n</li>\n";
	else if ($niveau_precedent == $niveau) $html .= "</ul>\n";
	else $html .= "</li>\n";
	 
	return $html;
	 
}

//Les menus imbriqués
function afficher_menu2($parent, $niveau, $array) {
				 
	$html = "";
	$niveau_precedent = 0;
	 
	if (!$niveau && !$niveau_precedent) $html .= "\n<ul>\n";
	 
	foreach ($array AS $noeud) {
	 
		if ($parent == $noeud['parent_id']) {
	 
		if ($niveau_precedent < $niveau) $html .= "\n<ul>\n";
	 		
			$etat = "<img src='images/".$noeud['etat_categorie'].".png' width='11'>";
			
			$lien = "";
			
			if (getNb('articles','id_categories',$noeud['categorie_id']) != 0){
				$nb = "<span class='small'>". getNb('articles','id_categories',$noeud['categorie_id']) ."</span>";
				
				$lien = "<a href='articles.php?id=". $noeud['categorie_id']."'>". $noeud['nom_categorie'] ." (". $nb .")</a>";
			}
			else
				$lien = $noeud['nom_categorie'];
			
			$html .= "<li class='espace'>". $lien;
		 
			$niveau_precedent = $niveau;
		 
			$html .= afficher_menu2($noeud['categorie_id'], ($niveau + 1), $array);
	 
		}
	}
	 
	if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) $html .= "</ul>\n</li>\n";
	else if ($niveau_precedent == $niveau) $html .= "</ul>\n";
	else $html .= "</li>\n";
	 
	return $html;
	 
}

//Les menus imbriqués
function afficher_menu3($parent, $niveau, $array) {
				 
	$html = "";
	$niveau_precedent = 0;
	$classcss = "";
	
	if($niveau == 0)
		$idcss="smenu"; 
	else
		$idcss=""; 
	
	if (!$niveau && !$niveau_precedent){
		$html .= "\n<ul id='". $idcss ."' class='niveau1'>\n";
	}
	 
	foreach ($array AS $noeud) {
	 	
		if ($parent == $noeud['parent_id']) {
	 
		if ($niveau_precedent < $niveau) $html .= "\n<ul class='niveau". ($niveau+1) ."'>\n";
			
			$lien = $noeud['nom_categorie'];
			
			if (getNb('categories','parent',$noeud['categorie_id']) != 0){
					$lien = "<a href='categories.php?id=". $noeud['categorie_id']."'>". $noeud['nom_categorie'] ."</a>";
			}
			elseif (getNb('articles','id_categories',$noeud['categorie_id']) != 0){
					$lien = "<a href='categories.php?id=". $noeud['categorie_id']."'>". $noeud['nom_categorie'] ."</a>";
			}
			
			$html .= "<li>". $lien;
			
			$niveau_precedent = $niveau;
		 
			$html .= afficher_menu3($noeud['categorie_id'], ($niveau + 1), $array);
	 
		}
	}
	 
	if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) $html .= "</ul>\n</li>\n";
	else if ($niveau_precedent == $niveau) $html .= "</ul>\n";
	else $html .= "</li>\n";
	 
	return $html;
	 
}

//Les menus imbriqués
function afficher_menu4($parent, $niveau, $array) {
				 
	$html = "";
	$niveau_precedent = 0;
	
	if (!$niveau && !$niveau_precedent){
		$html .= "\n<ul>\n";
	}
	 
	foreach ($array AS $noeud) {
	 	
		if ($parent == $noeud['parent_id']) {
	 
		if ($niveau_precedent < $niveau) $html .= "\n<ul>\n";
			
			$lien = $noeud['nom_categorie'.$_SESSION['lang']];
						
			if (getNb('categories','parent',$noeud['categorie_id']) != 0){
					$lien = $noeud['nom_categorie'.$_SESSION['lang']];
			}
			elseif (getNb('articles','id_categories',$noeud['categorie_id']) != 0){
					
					$lien = $noeud['nom_categorie'.$_SESSION['lang']];
					$lien .= "<ul>";
					
					//Lister les articles
					$sql_artls2 = "SELECT * FROM articles where etat=1 and id_categories =".$noeud['categorie_id'];
					$res_artls2 = doQuery($sql_artls2);
					while($ligne_artls2 = mysql_fetch_array($res_artls2)){
						$lien .= "	<li>
										<a href='article.php?id=". $ligne_artls2['id']  ."'>"
											.$ligne_artls2['nom'.$_SESSION['lang']].
										"</a>
									</li>";
					}
					
					$lien .= "</ul>";
			}
			
			$html .= "<li>". $lien;
			
			$niveau_precedent = $niveau;
		 
			$html .= afficher_menu4($noeud['categorie_id'], ($niveau + 1), $array);
		}
	}
	 
	if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) $html .= "</ul>\n</li>\n";
	else if ($niveau_precedent == $niveau) $html .= "</ul>\n";
	else $html .= "</li>\n";
	 
	return $html;
}

//Les menus imbriqués
function afficher_menu5($parent, $niveau, $array) {
				 
	$html = "";
	$niveau_precedent = 0;
	 
	if (!$niveau && !$niveau_precedent) $html .= "\n<ul>\n";
	 
	foreach ($array AS $noeud) {
	 
		if ($parent == $noeud['parent_id']) {
	 
		if ($niveau_precedent < $niveau) $html .= "\n<ul>\n";
	 		
			$etat = "<img src='images/".$noeud['etat_categorie'].".png' width='11'>";
			
			$lien = "";
			$lien = $noeud['nom_categorie'];
			$lien = $lien . "&nbsp;<a href='modifier_appartenances.php?id=". $noeud['categorie_id'] ."' class='modifier'>&nbsp;</a>";
			
			$html .= "<li class='espace'>". $lien;
		 
			$niveau_precedent = $niveau;
		 
			$html .= afficher_menu5($noeud['categorie_id'], ($niveau + 1), $array);
	 
		}
	}
	 
	if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) $html .= "</ul>\n</li>\n";
	else if ($niveau_precedent == $niveau) $html .= "</ul>\n";
	else $html .= "</li>\n";
	 
	return $html;
	 
}

//Les menus imbriqués page accueil site public
function afficher_menu6($parent, $niveau, $array) {
				 
	$html = "";
	$niveau_precedent = 0;
	
	if (!$niveau && !$niveau_precedent){
		$html .= "\n<ul>\n";
	}
	 
	foreach ($array AS $noeud) {
	 	
		if ($parent == $noeud['parent_id']) {
	 
		if ($niveau_precedent < $niveau) $html .= "\n<ul>\n";
			
			$lien = "<a href='#'>". $noeud['nom_categorie'.$_SESSION['lang']] ."</a>";
			
			if (getNb('categories','parent',$noeud['categorie_id']) != 0){
					$lien = "<a href='#'>". $noeud['nom_categorie'.$_SESSION['lang']] ."</a>";
			}
			elseif (getNb('articles','id_categories',$noeud['categorie_id']) != 0){
					$lien = "<a href='#'>". $noeud['nom_categorie'.$_SESSION['lang']] ."</a>";
					
					$lien = "<a href='#'>". $noeud['nom_categorie'.$_SESSION['lang']] ."</a>";
					$lien .= "<ul>";
					
					//Lister les articles
					$sql_artls = "SELECT * FROM articles where etat=1 and id_categories =".$noeud['categorie_id'];
					$res_artls = doQuery($sql_artls);
					while($ligne_artls = mysql_fetch_array($res_artls)){
						$lien .= "	<li>
										<a href='article.php?id=". $ligne_artls['id']  ."'>"
											.$ligne_artls['nom'.$_SESSION['lang']].
										"</a>
									</li>";
					}
					
					$lien .= "</ul>";
			}
			
			$html .= "<li>". $lien;
			
			$niveau_precedent = $niveau;
		 
			$html .= afficher_menu6($noeud['categorie_id'], ($niveau + 1), $array);
	 
		}
	}
	 
	if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) $html .= " </ul>\n</li>\n";
	else if ($niveau_precedent == $niveau) $html .= " </ul>\n";
	else $html .= " </li>\n";
	 
	return $html;
	 
}

//La liste des parents
function afficher_cats_cats($valeur) {

	$sql = "select * from categories where id <> '". $_REQUEST['id'] ."' order by nom";		
	$res = doQuery($sql);
	
	if (mysql_num_rows($res) == 0){
		
	}
	else
	{
	?>
	<select name="parent">
			<option value="">- -  - - _</option>
	<?php
		while($ligne = mysql_fetch_array($res)){
			//if (getNb('categories','parent',$ligne['id']) != 0){
			
				$s = "";
				if ($valeur == $ligne['id']) $s = "selected";
			?>
				<option value="<?php echo $ligne['id'] ?>" <?php echo $s ?>> <?php echo $ligne['nom']; ?> </option>
			<?php
			//}
		}
	?>
	</select>
	<?php
	}
}

//La liste des parents
function afficher_cats_articles($valeur) {

	$sql = "select * from categories order by nom";		
	$res = doQuery($sql);
	
	if (mysql_num_rows($res) == 0){
		
	}
	else
	{
	?>
	<select name="id_categories" id="_required">
			<option value="">- -  - - _</option>
	<?php
		while($ligne = mysql_fetch_array($res)){
			if (getNb('categories','parent',$ligne['id']) == 0){
				
				$s = "";
				if ($valeur == $ligne['id']) $s = "selected";
			?>
				<option value="<?php echo $ligne['id'] ?>" <?php echo $s ?>>
					<?php echo $ligne['nom']; ?> 
				</option>
			<?php
			}
		}
	?>
	</select>
	<?php
	}
}

function getResultatsTest($id_tests,$id_matieres,$id_eleves_tests_entree){
	
	$sql = "	select note from 
					tests_entree_resultats 
				where 
					id_tests_matieres =  ".$id_matieres." 
				and 
					id_tests_entree = ".$id_tests." 
				and 
					id_eleves_tests_entree = ".$id_eleves_tests_entree ;		
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res); 
	
	if($ligne['note'] == 0)
		return 'null';
	
	else
		return $ligne['note'];
}

function getFreeOdre($table){
	
	$sql = "select max(ordre) as max_ordre from ". $table;		
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	
	return $ligne['max_ordre']+1;
}

function mois_scolarite($mois){

	$req ="select * from mois where id = ".$mois;
	$result = doQuery($req);
	$ligne = mysql_fetch_array($result);
	
	if($ligne['mois_scolarite']==1){
	?>
		<img src="images/val.GIF" title="<?php echo _MOIS ?> <?php echo _SCOLARITE ?>" />
	<?php 		
	}
}

function afficher_menus($menus){
 
//Afficher les menus
$sql_menus = "select * from menus where libelle = '". $menus ."' order by id";		
$res_menus = doQuery($sql_menus);

$nb_menus = mysql_num_rows($res_menus);
if( $nb_menus == 0){
	
}
else
{
	while ($ligne_menus = mysql_fetch_array($res_menus)){
	
		//Afficher les elements de chaque menus
		$sql_elements_menus = "	select * from menus_elements 
								where id_menus = '". $ligne_menus['id'] ."' and etat=1 order by id";
		$res_elements_menus = doQuery($sql_elements_menus);
		
		$nb_elements_menus = mysql_num_rows($res_elements_menus);
		if( $nb_elements_menus == 0){
			
		}
		else
		{
		 
			while ($ligne_elements_menus = mysql_fetch_array($res_elements_menus)){
			?>

			<div id="menu_left">
				<h2><?php echo formater_constantes($ligne_elements_menus['libelle']); ?></h2>
				<ul>
			
				<?php
				//Afficher les liens de chaque elements menus
				$sql_liens_elements_menus = "select * from menus_elements_liens where id_menus_elements = '". $ligne_elements_menus['id'] ."' and etat=1 order by ordre";		
				$res_liens_elements_menus = doQuery($sql_liens_elements_menus);
				
				$nb_liens_elements_menus = mysql_num_rows($res_liens_elements_menus);
				if( $nb_liens_elements_menus == 0){
					
				}
				else
				{
				
					while ($ligne_liens_elements_menus = mysql_fetch_array($res_liens_elements_menus)){
						
						$liens = $ligne_liens_elements_menus['id'];
						$lien_menu = $ligne_liens_elements_menus['url'];
						$libelle_menu = formater_constantes($ligne_liens_elements_menus['libelle']);
					?>
						<li>
						
						<?php 
						//Afficher les liens selon les droits
						echo afficherLiensSelonDroits($lien_menu,'','','',$libelle_menu);
						?>
						
						</li>
					<?php
					}//Fin while liens
				}
				?>
				
				</ul>
			</div>
				
			<?php
			}//Fin While elments
		}
		
	}//Fin while menus
}

}

function formater_constantes($const){
	
	$tab = explode(" ",$const);
	
	$chaine = "";
	foreach($tab as $c){
		$chaine .= constant($c) . " ";
	}
	
	return trim($chaine);
}



function getDroitUtilisateur($utilisateurs,$lien){
	
	$user_profil = getValeurChamp('id_profils','utilisateurs_profils','id_utilisateurs',$utilisateurs);
	
	$sql = "select * from profils_droits 
			where id_profils = '". $user_profil ."' and id_menus_elements_liens = '". $lien ."'";
	$res = doQuery($sql);
	
	if (mysql_num_rows($res) == 0){
		return "";	
	}
	else
	{
		$ligne = mysql_fetch_array($res);
		$droit = $ligne['etat'];
		
		return $droit;
	}
}

function getDroitProfil($profil,$lien){
	
	$sql = "select * from profils_droits where id_profils = '". $profil ."' and id_menus_elements_liens = '". $lien ."'";		
	$res = doQuery($sql);
	
	if (mysql_num_rows($res) == 0){
		return "";	
	}
	else
	{
		$ligne = mysql_fetch_array($res);
		$droit = $ligne['etat'];
		
		return $droit;
	}
}

function afficherLiensSelonDroits($lien,$params,$class,$titre,$libelle){
	
	//Ajouter le lien si n'existe pas bd
	$nb_lien = getNb('menus_elements_liens','url',$lien);
	if($nb_lien == 0){

		$table = 'menus_elements_liens';
			$_REQUEST['id_menus_elements'] = '49';
			$_REQUEST['url'] = $lien;
			$_REQUEST['libelle'] = $lien;
			$_REQUEST['definitions'] = $lien;
		Ajout($table,getNomChamps($table),$_REQUEST);
		
	}
	//Ajouter le lien si n'existe pas bd
	
	if($_SESSION['type'] == 'user'){

		$id_page = getValeurChamp('id','menus_elements_liens','url',$lien);
		$droit = getDroitUtilisateur($_SESSION['utilisateurs'],$id_page);
		if($droit == "") $droit = 0;
		
		if ($droit == 1){
		?>
			<a href="<?php echo $lien ?>?<?php echo $params ?>#ancre" 
            class="<?php echo $class ?>" title="<?php echo $titre ?>">
				<?php echo $libelle ?>
			</a>
		<?php 
		}
		else
		{
		?>
			<a href="#ancre" class="<?php echo $class ?>gele" 
            title="<?php echo $titre ?>"><?php echo $libelle ?></a>
		<?php
		}
	
	}
	else
	{
	?>
		<a href="<?php echo $lien ?>?<?php echo $params ?>#ancre" 
        class="<?php echo $class ?>" title="<?php echo $titre ?>">
			<?php echo $libelle ?>
		</a>
	<?php 
	}

}

function afficherLiensSupprimerSelonDroits($lien,$table,$id,$page,$noms_retour,$valeurs_retour,$titre){
	
	//Ajouter le lien si n'existe pas bd
	$nb_lien = getNb('menus_elements_liens','url',$lien);
	if($nb_lien == 0){

		$table = 'menus_elements_liens';
			$_REQUEST['id_menus_elements'] = '49';
			$_REQUEST['url'] = $lien;
			$_REQUEST['libelle'] = $lien;
			$_REQUEST['definitions'] = $lien;
		Ajout($table,getNomChamps($table),$_REQUEST);
		
	}
	//Ajouter le lien si n'existe pas bd
	
	if($_SESSION['type'] == 'user'){

		$id_page = getValeurChamp('id','menus_elements_liens','url',$lien);
		$droit = getDroitUtilisateur($_SESSION['utilisateurs'],$id_page);
		if($droit == "") $droit = 0;
		
		if ($droit == 1){
		?>
            <a href="#ancre" class="supprimer2" onclick="javascript:supprimer('<?php echo $table ?>','<?php echo $id ?>','<?php echo $page ?>','<?php echo $noms_retour ?>','<?php echo $valeurs_retour ?>')" title="<?php echo _SUPPRIMER ?> <?php echo $titre ?>">&nbsp;</a>
		<?php 
		}
		else
		{
		?>
			<a href="#ancre" class="supprimer2" title="<?php echo $titre ?>">&nbsp;</a>
		<?php
		}
	
	}
	else
	{
	?>
		<a href="#ancre" class="supprimer2" onclick="javascript:supprimer('<?php echo $table ?>','<?php echo $id ?>','<?php echo $page ?>','<?php echo $noms_retour ?>','<?php echo $valeurs_retour ?>')" title="<?php echo _SUPPRIMER ?> <?php echo $titre ?>">&nbsp;</a>
	<?php 
	}
}

function verifierDroitPage($libelle_page){
	
	if($_SESSION['type'] == 'user'){
		
		$exceptions = array(1=>'index.php');
		$var = array_search('aa.php',$exceptions) ;
		
		$page = getValeurChamp('id','menus_elements_liens','url',$libelle_page);
		
		$droit = getDroitUtilisateur($_SESSION['utilisateurs'],$page);
		if ($droit == "") $droit = 0;
		
		if ($droit == 0) 
			//redirect('index.php?er='. _ERREUR_DROITS);
			echo _ERREUR_DROITS;
	}
}

function dateToTime($date,$sep){
	$tab_d = explode($sep,$date);
			
	$annee = $tab_d[2];
	$jour = $tab_d[1];
	$mois = $tab_d[0];
	
	
	return mktime(0,0,1,$mois,$jour,$annee);
}

function verifierAnneeActive($exceptions,$page){
	
	//Traitement de la chaines pages
	$v = array_search($page, $exceptions);	
	
	//Verifier si l'annee scolaire est définie
	if (!$v){
		$id_annee_active = getValeurChamp('id','annees_scolaires','etat','1');
		if ($id_annee_active == ""){
			redirect('annees_scolaires.php?er='. _DEFINIR_ANNEE_ACTIVE);
			
		}
		
	}
}

//la somme des frais d inscription entre deux dates
function getTotalFraisIsncription($utilisateurs,$date_debut,$date_fin,$id_annees_scolaires){
			
		$req = "select * from inscription 
				where id_annees_scolaires = ".$id_annees_scolaires." 
				and date_reglement<=".$date_fin." and date_reglement>=".$date_debut." 
				and date_reglement <> '' 
				and id_utilisateurs = ".$utilisateurs;
		$res=doQuery($req);
		$total=0;
		
		while($ligne=mysql_fetch_array($res)){
		
          $niveaux = getValeurChamp('id_niveaux','groupes','id',$ligne['id_groupes']);
		  $cycles=getValeurChamp('id_cycles','niveaux','id',$niveaux);
			
		  $frais_inscription = getValeurChamp('frais_inscription','cycles','id',$cycles)-$ligne['reduction'];
		  $total+=$frais_inscription;
		}

		return $total;
}
//la somme des frais de scolarite entre deux date

function getTotalFraisScolarite($caisse,$date_debut,$date_fin,$id_annees_scolaires){


$liste_groupe = "select * from groupes where id_annees_scolaires = '".$id_annees_scolaires."' order by id_niveaux";
$resultat  = doQuery($liste_groupe) ;
$total_caisse=0;

 $mois_d=date('n',$date_debut);
 $mois_f=date('n',$date_fin);

while($groupe=mysql_fetch_array($resultat)){


		 	 $req = "SELECT count(*) as nb FROM 
			 		frais_scolarite fr,inscription ins 
					WHERE fr.id_eleves=ins.id_eleves and id_groupes =".$groupe['id']." 
					and fr.date_reglement <= ".$date_fin." and fr.date_reglement >=".$date_debut." 
					and fr.id_annees_scolaires=".$id_annees_scolaires." and fr.id_utilisateurs = ".$caisse;
			$resul = doQuery($req);
			$nbe = mysql_fetch_array($resul);
			$frais = getValeurChamp('frais_scolarite','niveaux','id',$groupe['id_niveaux']);
			
			/*
			$req="select sum(ele.reduction) as reduction 
				 from eleves_reduction_frais_scolarite ele,inscription ins,mois 
				 where ele.id_eleves=ins.id_eleves  and ordre = ele.mois 
				 and id_groupes = ".$groupe['id']." and mois.id<=".$mois_f." and mois.id >=".$mois_d." 
				 and ele.id_eleves in 
																		
									(select id_eleves 
									from frais_scolarite 
									where date_reglement <= ".$date_fin." and date_reglement >=".
									$date_debut." and id_utilisateurs = ".$caisse.")
									
				 	and ele.id_annees_scolaires=".$id_annees_scolaires;
			*/
			
			$req = "select sum(reduction) as reduction 
				 from inscription 
				 where id_groupes = ".$groupe['id']."  
				 and id_eleves in 
																		
									(select id_eleves 
									from frais_scolarite 
									where date_reglement <= ".$date_fin." and date_reglement >=".
									$date_debut." and id_utilisateurs = ".$caisse.")
									
				 	and id_annees_scolaires=".$id_annees_scolaires;
			
			$resu = doQuery($req);
			$somme = mysql_fetch_array($resu);
			
			//calcul du total entre les deux dates
			$total = $nbe['nb']*$frais-$somme['reduction'];
			$total_caisse+=$total;

}
return $total_caisse; 
}

//fonctions utiles pour la realisation de la recette

//calcul la somme des reductions  des eleves  inscrit dans un groupe et dans l annee encours

function getReductionGroupe($id_groupe,$mois,$annees_encours){
	
	$req="	select sum(reduction) as somme 
			
			from inscription 
			
			where id_groupes = ".$id_groupe."  
			and id_annees_scolaires=".$annees_encours;
	$resu= doQuery($req);
	$somme_reduction = mysql_fetch_array($resu);

	return $somme_reduction['somme'];
}

//calcul le nombre des eleves ayant paye les frais dans un groupe et dans l annee encours

function getNbElevesPayeGroupe($id_groupe,$mois_encours,$annees_encours){
	$req = "SELECT count(*) as nb 
			FROM frais_scolarite fr,inscription ins 
			
			WHERE fr.id_eleves=ins.id_eleves 
			and id_groupes =".$id_groupe." and mois = ".$mois_encours." and fr.id_annees_scolaires=".$annees_encours;
	$resultat = doQuery($req);
	$nbr = mysql_fetch_array($resultat);
	
	return $nbr['nb'];
}

//calcul la somme des reductions  des eleves ayant paye les frais dans un groupe et dans l annee encours
function getReductionElevesPaye($id_groupe,$mois_encours,$annees_encours){
	/*
	$req2 = "select sum(ele.reduction) as somme from eleves_reduction_frais_scolarite ele,inscription ins 
			where ele.id_eleves=ins.id_eleves and ins.id_groupes = ".$id_groupe." 
			and ele.mois =".$mois_encours." 
			and ele.id_eleves in 
							(select id_eleves from 
							 frais_scolarite 
							 where mois = ".$mois_encours.") 
			and ele.id_annees_scolaires=".$annees_encours;
	*/
	
	$req2 = "select sum(reduction) as somme from inscription  
			where id_groupes = ".$id_groupe." 
			and id_eleves in 
							(select id_eleves from 
							 frais_scolarite 
							 where mois = ".$mois_encours.") 
			and id_annees_scolaires=".$annees_encours;
	
	$resu= doQuery($req2);
	$somme_redu = mysql_fetch_array($resu);
	
	return $somme_redu['somme'];
}

function formater_date($date,$sep){
		
	$tab_d = explode($sep,$date);
	
	$annee = $tab_d[2];
	$mois = $tab_d[1];
	$jour = $tab_d[0];
	
	$hr = date("G");
	$mint = date("i");
	$sec = date("s");
	
	return mktime($hr,$mint,$sec,$mois,$jour,$annee);
}

function ajouter_trace($champ,$operation,$table,$type,$user,$date){
	
	//Trace
	$tb = $table;
	$_REQUEST['id_champs'] = $champ;
	$_REQUEST['operation'] = $operation;
	$_REQUEST['table_operation'] = $table;
	$_REQUEST['type_utilisateurs'] = $type;
	$_REQUEST['id_utilisateurs'] = $user;
	$_REQUEST['date'] = $date;
	
	Ajout($tb,getNomChamps($tb),$_REQUEST);
	//Fin Trace	
}

function getResteFi($eleves,$id_annee){
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$groupes=getValeurChamp('id_groupes','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$niveaux = getValeurChamp('id_niveaux','groupes','id',$groupes);
	$cycles = getValeurChamp('id_cycles','niveaux','id',$niveaux);
	
	$reduction_fi = getValeurChamp('reduction_fi','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	$total = getValeurChamp('frais_inscription','cycles','id',$cycles)  - $reduction_fi;
	
	$total_regle = getSum('reglements_frais_inscriptions','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total - $total_regle;
}

function getResteFs($eleves,$id_annee,$mois){
	
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	
	$total = getFsByEleves($eleves,$id_annee);
	
	$total_regle = getSum('reglements_frais_scolarite','montant','id_inscriptions,id_annees_scolaires,mois',$id_inscriptions.','.$id_annee.','.$mois);
	
	return $total - $total_regle;
}

function getTotalRegleFi($eleves,$id_annee){
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$groupes=getValeurChamp('id_groupes','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$niveaux = getValeurChamp('id_niveaux','groupes','id',$groupes);
	$cycles = getValeurChamp('id_cycles','niveaux','id',$niveaux);
	
	$total = getValeurChamp('frais_inscription','cycles','id',$cycles);
	
	$total_regle = getSum('reglements_frais_inscriptions','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total_regle;
}

function getTotalRegleAncienSolde($eleves,$id_annee){
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$total = getSoldeElevesAncienSolde($eleves,$id_annee);
	
	$total_regle = getSum('reglements_ancien_solde','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total_regle;
}

function getTotalRegleAssuranceTransports($eleves,$id_annee){
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$total = getValeurChamp('assurance','transports_frais','id_annees_scolaires',$id_annee);
	
	$total_regle = getSum('reglements_frais_assurance_transports','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total_regle;
}

function getTotalRegleFournitures($eleves,$id_annee){
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	
	$total_regle = getSum('reglements_frais_fournitures','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total_regle;
}

function getTotalRegleAssuranceCantines($eleves,$id_annee){
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$total = getValeurChamp('assurance','cantines_frais','id_annees_scolaires',$id_annee);
	
	$total_regle = getSum('reglements_frais_assurance_cantines','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total_regle;
}

function getTotalRegleAssuranceClubs($eleves,$id_annee){
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$total = getValeurChamp('assurance','clubs_frais','id_annees_scolaires',$id_annee);
	
	$total_regle = getSum('reglements_frais_assurance_clubs','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total_regle;
}

function getTotalRegleFs($eleves,$id_annee){
	
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$total_regle = getSum('reglements_frais_scolarite','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total_regle;
}

function getTotalRegleMensualiteTransports($eleves,$id_annee){
	
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$total_regle = getSum('reglements_frais_mensualite_transports','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total_regle;
}

function getTotalRegleMensualiteTransports1111($eleves,$id_annee,$mois){
	
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$total_regle = getSum('reglements_frais_mensualite_transports','montant','id_inscriptions,id_annees_scolaires,mois',$id_inscriptions.','.$id_annee.','.$mois);
	
	return $total_regle;
}


function getTotalRegleMensualiteCantines($eleves,$id_annee){
	
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$total_regle = getSum('reglements_frais_mensualite_cantines','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total_regle;
}

function getTotalRegleMensualiteClubs($eleves,$id_annee,$mois){
	
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$total_regle = getSum('reglements_frais_mensualite_clubs','montant','id_inscriptions,id_annees_scolaires',$id_inscriptions.','.$id_annee);
	
	return $total_regle;
}


function getFiByEleves($eleves,$id_annee){
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$groupes=getValeurChamp('id_groupes','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$niveaux = getValeurChamp('id_niveaux','groupes','id',$groupes);
	$cycles = getValeurChamp('id_cycles','niveaux','id',$niveaux);
	
	$total = getValeurChamp('frais_inscription','cycles','id',$cycles);
	
	return $total;
}

function getFsByEleves($eleves,$id_annee){
	$id_inscriptions=getValeurChamp('id','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$groupes=getValeurChamp('id_groupes','inscription','id_eleves,id_annees_scolaires',$eleves.','.$id_annee);
	
	$niveaux = getValeurChamp('id_niveaux','groupes','id',$groupes);
	
	return getValeurChamp('frais_scolarite','niveaux','id',$niveaux);
}


function getMoisAvant($mois){
	$ordre = getValeurChamp('ordre','mois','id',$mois);
	$req="	select * from mois 
			where mois_scolarite = 1 and obligatoire = 0 
			and ordre < ". $ordre ."   
			order by ordre desc limit 1";
	$res = doQuery($req);
	$ligne = mysql_fetch_array($res);
	
	
	return $ligne['id'];	
}

function getNbEleveByCycle($cycle,$id_annee_scolaire){
	
	$sql_groupes = "
			select 
			
			groupes.id as groupes_id
			
			from 
			
			groupes 
			join
			niveaux on groupes.id_niveaux = niveaux.id
			
			join 
			cycles on cycles.id = niveaux.id_cycles
			
			where id_cycles = '". $cycle ."'
			";
	
	$sql = "
		select count(*) as nb 
		from inscription where id_groupes in (
											  ". $sql_groupes ."
											  )
		and 
			date_depart = '' 
		and 
			id_annees_scolaires = '". $id_annee_scolaire ."'
		";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	
	
	return $ligne['nb'];
	
}

function getNbEleveReglesByCycle($cycle,$id_annee_scolaire){
	
	$sql_groupes = "
			select 
			
			groupes.id as groupes_id
			
			from 
			
			groupes 
			join
			niveaux on groupes.id_niveaux = niveaux.id
			
			join 
			cycles on cycles.id = niveaux.id_cycles
			
			where id_cycles = '". $cycle ."'
			";
	
	$sql = "
		select count(*) as nb 
		from reglements_frais_inscriptions where id_groupes in (
											  ". $sql_groupes ."
											  )
		and id_annees_scolaires = '". $id_annee_scolaire ."'
		";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	
	
	return $ligne['nb'];
	
}

function getSoldeEleves($eleves,$annee,$mois){
	//Total des alimentation - total de tout les reglements
	
	//Mois, Annee courante
	//$mois_en_cours = date("n",time());
	//$ordre = getValeurChamp('ordre','mois','id',$mois_en_cours);
	
	$ordre = getValeurChamp('ordre','mois','id',$mois);
	
	$total_fi = getFiByEleves($eleves,$annee);
	$total_fs = getFsByEleves($eleves,$annee);
	
	
	$reduction = getValeurChamp('reduction','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	//Les reglement FI
	$previs_fi = $total_fi;
	$effectiv_fi = getSum('reglements_frais_inscriptions','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	//Les reglement FS
	$previs_fs = ($total_fs - $reduction) * $ordre;
	$effectiv_fs = getSum('reglements_frais_scolarite','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$global = ($effectiv_fi - $previs_fi) + ($effectiv_fs - $previs_fs);
	if($global == "") $global = 0;
	
	return $global;
}

function getSoldeElevesFi($eleves,$annee){

	$total_fi = getFiByEleves($eleves,$annee);
	
	$reduction_fi = getValeurChamp('reduction_fi','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	//Les reglement FI
	$previs_fi = $total_fi - $reduction_fi;
	
	$effectiv_fi = getSum('reglements_frais_inscriptions','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$global = ($effectiv_fi - $previs_fi);
	if($global == "") $global = 0;
	
	return $global;
}

function getSoldeElevesFournitures($eleves,$annee){

	$total_fournitures = getFiByEleves($eleves,$annee);
	
	$id_groupes_eleves = getValeurChamp('id_groupes','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$id_niveaux_eleves = getValeurChamp('id_niveaux','groupes','id',$id_groupes_eleves);

	$total_fournitures = getValeurChamp('montant','fournitures_frais','id_niveaux',$id_niveaux_eleves);
	$reduction_fournitures = getValeurChamp('reduction_fourniture','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	//Les reglement FI
	$previs_fournitures = $total_fournitures - $reduction_fournitures;
	$effectiv_fournitures = getSum('reglements_frais_fournitures','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	
	$global = ($effectiv_fournitures - $previs_fournitures);
	if($global == "") $global = 0;
	
	return $global;
}

function getSoldeElevesAncienSolde($eleves,$annee){

	$total_ancien_solde = getValeurChamp('ancien_solde','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
		
	//Les reglement FI
	$previs_ancien_solde = $total_ancien_solde;
	$effectiv_ancien_solde = getSum('reglements_ancien_solde','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	
	$global = ($effectiv_ancien_solde - $previs_ancien_solde);
	if($global == "") $global = 0;
	
	return $global;
}

function getSoldeElevesAssuranceTransports($eleves,$annee){
	
	$reduction_assurance_transport = getValeurChamp('reduction_assurance_transport','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$previ_transport = getValeurChamp('assurance','transports_frais','id_annees_scolaires',$annee);
	
	$total = $previ_transport - $reduction_assurance_transport;
	
	//Les reglement FI
	$previs = $total;
	$effectiv = getSum('reglements_frais_assurance_transports','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	
	$global = ($effectiv - $previs);
	if($global == "") $global = 0;
	
	return $global;
}

function getSoldeElevesMensualitesTransports111111($eleves,$annee){

	$mensualite = getValeurChamp('mensualite','transports_frais','id_annees_scolaires',$annee);
	
	$reduction_transport = getValeurChamp('reduction_transport','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);

$supplement_transport = getValeurChamp('supplement_transport','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$nb_mois = getNb('eleves_transports_mois','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$total = ($mensualite + $supplement_transport - $reduction_transport) * $nb_mois;
	
	//Les reglement FI
	$previs = $total;
	$effectiv = getSum('reglements_frais_mensualite_transports','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	
	$global = ($effectiv - $previs);
	if($global == "") $global = 0;
	
	return $global;
}

function getNbMoisTransportsTillNow($eleves,$annee,$mois){
	$mois_en_cours = getValeurChamp('ordre','mois','id',$mois);
	//$mois_en_cours = getValeurChamp('ordre','mois','id',date("n",time()));
	
	$sql = "
		select count(*) as nb 
		from eleves_transports_mois 
		where 
		id_eleves = '". $eleves ."' 
			and 
		id_annees_scolaires = '". $annee ."' 
			and
		mois <= '". $mois_en_cours ."'";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	
	if($ligne['nb'] == '') 
		return 0;
	else 
		return $ligne['nb'];
}

function getSoldeElevesMensualitesTransports($eleves,$annee,$mois){

	$mensualite = getValeurChamp('mensualite','transports_frais','id_annees_scolaires',$annee);
	
	$reduction_transport = getValeurChamp('reduction_transport','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);

	$supplement_transport = getValeurChamp('supplement_transport','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	//$nb_mois = getNb('eleves_transports_mois','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$nb_mois = getNbMoisTransportsTillNow($eleves,$annee,$mois);
	
	$total = ($mensualite + $supplement_transport - $reduction_transport) * $nb_mois;
	
	//Les reglement FI
	$previs = $total;
	$effectiv = getSum('reglements_frais_mensualite_transports','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	
	$global = ($effectiv - $previs);
	if($global == "") $global = 0;
	
	return $global;
}

function getSoldeElevesAssuranceCantines($eleves,$annee){
	
	$reduction_assurance_cantine = getValeurChamp('reduction_assurance_cantine','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$previ_cantine = getValeurChamp('assurance','cantines_frais','id_annees_scolaires',$annee);
	
	$total = $previ_cantine - $reduction_assurance_cantine;
	
	//Les reglement FCT
	$previs = $total;
	$effectiv = getSum('reglements_frais_assurance_cantines','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	
	$global = ($effectiv - $previs);
	if($global == "") $global = 0;
	
	return $global;
}

function getNbMoisCantinesTillNow($eleves,$annee,$mois){
	$mois_en_cours = getValeurChamp('ordre','mois','id',$mois);
	//$mois_en_cours = getValeurChamp('ordre','mois','id',date("n",time()));
	$sql = "
		select count(*) as nb 
		from eleves_cantines_mois 
		where 
		id_eleves = '". $eleves ."' 
			and 
		id_annees_scolaires = '". $annee ."' 
			and
		mois <= '". $mois_en_cours ."'";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	
	if($ligne['nb'] == '') return 0;
	else return $ligne['nb'];
}

function getSoldeElevesMensualitesCantines($eleves,$annee,$mois){

	$mensualite = getValeurChamp('mensualite','cantines_frais','id_annees_scolaires',$annee);
	
	$reduction_cantines = getValeurChamp('reduction_cantine','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	//$nb_mois = getNb('eleves_transports_mois','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$nb_mois = getNbMoisCantinesTillNow($eleves,$annee,$mois);
	
	$total = ($mensualite - $reduction_cantines) * $nb_mois;
	
	//Les reglement FI
	$previs = $total;
	$effectiv = getSum('reglements_frais_mensualite_cantines','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	
	$global = ($effectiv - $previs);
	if($global == "") $global = 0;
	
	return $global;
}

function getSoldeElevesAssuranceClubs($eleves,$annee){
	
	$reduction_assurance_club = getValeurChamp('reduction_assurance_club','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$previ_club = getValeurChamp('assurance','clubs_frais','id_annees_scolaires',$annee);
	
	$total = $previ_club - $reduction_assurance_club;
	
	//Les reglement FI
	$previs = $total;
	$effectiv = getSum('reglements_frais_assurance_clubs','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	
	$global = ($effectiv - $previs);
	if($global == "") $global = 0;
	
	return $global;
}

function getNbMoisClubsTillNow($eleves,$annee,$mois){
	$mois_en_cours = getValeurChamp('ordre','mois','id',$mois);
	//$mois_en_cours = getValeurChamp('ordre','mois','id',date("n",time()));
	$sql = "
		select count(*) as nb 
		from eleves_clubs_mois 
		where 
		id_eleves = '". $eleves ."' 
			and 
		id_annees_scolaires = '". $annee ."' 
			and
		mois <= '". $mois_en_cours ."'";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	
	if($ligne['nb'] == '') return 0;
	else return $ligne['nb'];
}

function getSoldeElevesMensualitesClubs($eleves,$annee,$mois){

	$mensualite = getValeurChamp('mensualite','clubs_frais','id_annees_scolaires',$annee);
	
	$reduction_clubs = getValeurChamp('reduction_club','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	//$nb_mois = getNb('eleves_transports_mois','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$nb_mois = getNbMoisClubsTillNow($eleves,$annee,$mois);
	
	$total = ($mensualite - $reduction_clubs) * $nb_mois;
	
	//Les reglement FI
	$previs = $total;
	$effectiv = getSum('reglements_frais_mensualite_clubs','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	
	$global = ($effectiv - $previs);
	if($global == "") $global = 0;
	
	return $global;
}

function getNbMoisScolariteTillNow($eleves,$annee,$mois){
	
	$mois_en_cours = getValeurChamp('ordre','mois','id',$mois);
	//$mois_en_cours = getValeurChamp('ordre','mois','id',date("n",time()));
	
	$sql_oblig = "
		select count(*) as nb 
		from eleves_scolarite_mois  
		where 
			id_eleves = '". $eleves ."'  
		and
			mois in(
					 select ordre from mois where obligatoire = '1' 
					 )
		";
	
	$res_oblig = doQuery($sql_oblig);
	$ligne_oblig = mysql_fetch_array($res_oblig);
	$mois_oblig = $ligne_oblig['nb'];
	//$mois_oblig = getNb('mois','obligatoire','1');
	
	/*
	$sql = "
		select count(*) as nb 
		from mois  
		where 
			ordre <= '". $mois_en_cours ."'
		and
			ordre not in(
					 select ordre from mois where obligatoire = '1' 
					 )
		";
	*/
	
	$sql = "
		select count(*) as nb 
		from eleves_scolarite_mois  
		where 
			id_eleves = '". $eleves ."' 
		and
			mois <= '". $mois_en_cours ."' 
		and
			mois not in(
					 select ordre from mois where obligatoire = '1' 
					 )
		";
	
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	$mois_active = $ligne['nb'];
	
	$total = $mois_oblig + $mois_active;
	
	if($total == '') 
		return 0;
	else 
		return $total;
}


function getSoldeElevesFs($eleves,$annee,$mois){
	
	$total_fs = getFsByEleves($eleves,$annee);
	
	
	$reduction = getValeurChamp('reduction','inscription','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$nb_mois = getNbMoisScolariteTillNow($eleves,$annee,$mois);
	
	//Les reglement FS
	$previs_fs = ($total_fs - $reduction) * $nb_mois;
	$effectiv_fs = getSum('reglements_frais_scolarite','montant','id_eleves,id_annees_scolaires',$eleves.','.$annee);
	
	$global = ($effectiv_fs - $previs_fs);
	if($global == "") $global = 0;
	
	return $global;
}


function getGroupesByCycles($cycle){
	$sql = "
			select 
			
			groupes.id as groupes_id
			
			from 
			
			groupes 
			join
			niveaux on groupes.id_niveaux = niveaux.id
			
			join 
			cycles on cycles.id = niveaux.id_cycles
			
			where id_cycles = '". $cycle ."'
			";
	$res = doQuery($sql);
	
	$groupes = "";
	while ($ligne = mysql_fetch_array($res)){
		$groupes .= $ligne['groupes_id'] .',';
	}
	$groupes = substr($groupes,0,strlen($groupes)-1);
	
	return $groupes;
}

function getTotalProduitsCommandes($produit){
	$sql_get = "
			select sum(quantite) as total 
			
			from 
			
			fournisseurs_devis join fournisseurs_devis_produits 
			on fournisseurs_devis.id = fournisseurs_devis_produits.id_fournisseurs_devis  
			
			where fournisseurs_devis.date_commande <> '' 
			and id_produits = '". $produit ."'";
			
	$res_get = doQuery($sql_get);
	$ligne_get = mysql_fetch_array($res_get) or die(mysql_error());
	
	if ($ligne_get['total'] == "") return "0";
	return $ligne_get['total'];
}

function getTotalProduitsLivres($produit,$annee){
	$sql_get = "
			select sum(quantite) as total 
			
			from 
			
			fournisseurs_devis_livraisons_produits  
			
			where 
				id_annees_scolaires = '". $annee ."'
			and 
				id_produits = '". $produit ."'";
			
	$res_get = doQuery($sql_get);
	$ligne_get = mysql_fetch_array($res_get) or die(mysql_error());
	
	if ($ligne_get['total'] == "") return "0";
	return $ligne_get['total'];
}

function getNbMsgNonLus($user,$type){
	$sql = "select 
		
		mi_messages.id as id_messages, 
		mi_messages.titre as titre, 
		mi_messages.message as message, 
		mi_messages.utilisateurs as proprietaire, 
		mi_messages.datea as date_messages, 
		mi_messages_destinataires.etat as etat, 
		mi_messages_destinataires.utilisateurs as destinataires,
		mi_messages_destinataires.type_utilisateurs as type_destinataires
		
		from 
		mi_messages join mi_messages_destinataires 
		
		on 
		mi_messages.id = mi_messages_destinataires.id_messages 
		
		where 
		
		mi_messages_destinataires.utilisateurs = '". $user ."' 
		and 
		mi_messages_destinataires.type_utilisateurs = '". $type ."' 
		and 
		mi_messages_destinataires.etat = 0
		and 
		mi_messages.envoye = 1";

$res = doQuery($sql);

$nb = mysql_num_rows($res);

if($nb == 0) return 0;
else return $nb;
}


function getNbMsg($user,$type){
	$sql = "select 
		
		mi_messages.id as id_messages, 
		mi_messages.titre as titre, 
		mi_messages.message as message, 
		mi_messages.utilisateurs as proprietaire, 
		mi_messages.datea as date_messages, 
		mi_messages_destinataires.etat as etat, 
		mi_messages_destinataires.utilisateurs as destinataires,
		mi_messages_destinataires.type_utilisateurs as type_destinataires
		
		from 
		mi_messages join mi_messages_destinataires 
		
		on 
		mi_messages.id = mi_messages_destinataires.id_messages 
		
		where 
		
		mi_messages_destinataires.utilisateurs = '". $user ."' 
		and 
		mi_messages_destinataires.type_utilisateurs = '". $type ."' 
		and 
		mi_messages.envoye = 1";

$res = doQuery($sql);

$nb = mysql_num_rows($res);

if($nb == 0) return 0;
else return $nb;
}


function getSoldeGlobalEleve11111($eleve,$annee){
	$global = 0;
	
	$id_parents = getValeurChamp('id_parents','eleves_parents','id_eleves',$eleve);
	
	$solde_alimentations =getSum('eleves_alimentations','montant','id_parents,id_annees_scolaires',$id_parents.','.$annee);
	
	//$solde_alimentations =getSum('eleves_alimentations','montant','id_eleves,id_annees_scolaires',$eleve.','.$annee);
	
	$ancien_solde = getTotalRegleAncienSolde($eleve,$annee);
	
	$solde_fourniture = getTotalRegleFournitures($eleve,$annee);
	
	$solde_fi = getTotalRegleFi($eleve,$annee);
	
	$solde_fs = getTotalRegleFs($eleve,$annee,$mois);
	
	$solde_assurance_transports = getTotalRegleAssuranceTransports($eleve,$annee);
	$solde_mensualite_transports = getTotalRegleMensualiteTransports($eleve,$annee);
	
	$solde_assurance_cantines = getTotalRegleAssuranceCantines($eleve,$annee);
	$solde_mensualite_cantines = getTotalRegleMensualiteCantines($eleve,$annee);
	
	$solde_assurance_clubs = getTotalRegleAssuranceClubs($eleve,$annee);
	$solde_mensualite_clubs = getTotalRegleMensualiteClubs($eleve,$annee);
	
	$global = $solde_alimentations - 
						(
						 $ancien_solde 
						 + $solde_fourniture 
						 + $solde_fi 
						 + $solde_fs 
						 + $solde_assurance_transports 
						 + $solde_mensualite_transports 
						 + $solde_assurance_cantines 
						 + $solde_mensualite_cantines 
						 + $solde_assurance_clubs 
						 + $solde_mensualite_clubs
						 );
	
	
	return $global;
	
}

function getSoldeGlobalEleve($eleve,$annee){
	$global = 0;
	
	$id_parents = getValeurChamp('id_parents','eleves_parents','id_eleves',$eleve);
	
	$solde_alimentations =getSum('eleves_alimentations','montant','id_parents,id_annees_scolaires',$id_parents.','.$annee);
	
$sql_freres = "	select * from 
					eleves_parents 
				where 
					id_parents = '". $id_parents ."'";
$res_freres = doQuery($sql_freres);
$nb_freres = mysql_num_rows($res_freres);
if( $nb_freres==0){
}
else
{
	while ($ligne_freres = mysql_fetch_array($res_freres)){
		$ancien_solde += getTotalRegleAncienSolde($ligne_freres['id_eleves'],$annee);
		
		$solde_fourniture += getTotalRegleFournitures($ligne_freres['id_eleves'],$annee);
		
		$solde_fi += getTotalRegleFi($ligne_freres['id_eleves'],$annee);
		
		$solde_fs += getTotalRegleFs($ligne_freres['id_eleves'],$annee,$mois);
		
		$solde_assurance_transports += getTotalRegleAssuranceTransports($ligne_freres['id_eleves'],$annee);
		$solde_mensualite_transports += getTotalRegleMensualiteTransports($ligne_freres['id_eleves'],$annee);
		
		$solde_assurance_cantines += getTotalRegleAssuranceCantines($ligne_freres['id_eleves'],$annee);
		$solde_mensualite_cantines += getTotalRegleMensualiteCantines($ligne_freres['id_eleves'],$annee);
		
		$solde_assurance_clubs += getTotalRegleAssuranceClubs($ligne_freres['id_eleves'],$annee);
		$solde_mensualite_clubs += getTotalRegleMensualiteClubs($ligne_freres['id_eleves'],$annee);
	}
}
	
	$global = $solde_alimentations - 
						(
						 $ancien_solde 
						 + $solde_fourniture 
						 + $solde_fi 
						 + $solde_fs 
						 + $solde_assurance_transports 
						 + $solde_mensualite_transports 
						 + $solde_assurance_cantines 
						 + $solde_mensualite_cantines 
						 + $solde_assurance_clubs 
						 + $solde_mensualite_clubs
						 );
	
	
	return $global;
	
}

function getSoldeEleveApayer($eleve,$annee,$mois){
	
	$grand_total = 0;
	
	$solde_ancien_solde = getSoldeElevesAncienSolde($eleve,$annee);
	$grand_total += $solde_ancien_solde;
	
	$solde_fi = getSoldeElevesFi($eleve,$annee);
	$grand_total += $solde_fi;
	
	$solde_fs = getSoldeElevesFs($eleve,$annee,$mois);
	$grand_total += $solde_fs;
	
	$annulation_fourniture = getNb('eleves_fournitures','id_eleves,id_annees_scolaires',$eleve.','.$annee);
	if($annulation_fourniture == 0){
		$solde_fourniture = getSoldeElevesFournitures($eleve,$annee);
		$grand_total += $solde_fourniture;
	}
	
	$etat_transports = getNb('eleves_transports','id_eleves,id_annees_scolaires',$eleve.','.$annee);
	if($etat_transports == 1){
		$solde_assurance_transports = getSoldeElevesAssuranceTransports($eleve,$annee);
		$grand_total += $solde_assurance_transports;
		
		$solde_mensualite_transports = getSoldeElevesMensualitesTransports($eleve,$annee,$mois);
		$grand_total += $solde_mensualite_transports;
	}
	
	
	$etat_cantines = getNb('eleves_cantines','id_eleves,id_annees_scolaires',$eleve.','.$annee);
	if($etat_cantines == 1){
		$solde_assurance_cantines = getSoldeElevesAssuranceCantines($eleve,$annee);
		$grand_total += $solde_assurance_cantines;
		
		$solde_mensualite_cantines = getSoldeElevesMensualitesCantines($eleve,$annee,$mois);
		$grand_total += $solde_mensualite_cantines;
	}
	
	
	$etat_clubs = getNb('eleves_clubs','id_eleves,id_annees_scolaires',$eleve.','.$annee);
	if($etat_clubs == 1){
		$solde_assurance_clubs = getSoldeElevesAssuranceClubs($eleve,$annee);
		$grand_total += $solde_assurance_clubs;
		
		$solde_mensualite_clubs = getSoldeElevesMensualitesClubs($eleve,$annee,$mois);
		$grand_total += $solde_mensualite_clubs;
	}
	
	if($grand_total < 0)
		return $grand_total;
	else
		return 0;
}

function formater_montant($montant){
	$chaine = '';
	
	$tab_mnt = explode('.',$montant);
	
	for($i=1;$i<=strlen($tab_mnt[0]);$i++){
		
		$car = substr($tab_mnt[0],-$i,1);
		
		if($i%3 == 0)
			$chaine .= $car .' ';
		else
			$chaine .= $car;
	}
	
	for($j=1;$j<=strlen($chaine);$j++){
		$c .= substr($chaine,-$j,1);
	}
	
	$dec = $tab_mnt[1];
	if($tab_mnt[1] == '') $dec = "00";
	
	return $c .'.'. $dec;
}

function getNbDocumentsByEtat($annee,$etat){
	
	$sql = "select * from eleves_alimentations
			where id_annees_scolaires = '". $annee ."'
			and 
				id in (
						   select id_alimentations from remises_documents 
						   where id_annees_scolaires = '". $annee ."'
						   )
			and
				etat_encaissement = '". $etat ."'
			";
	$res = doQuery($sql);

	$nb = mysql_num_rows($res);
	
	return $nb;
}

function getSoldeCompte($compte,$annee){
	
	$total_alimentations =getSum('comptes_alimentations','montant','id_comptes,id_annees_scolaires',$compte.','.$annee);
	
	$total_reglements = getSum('eleves_alimentations','montant','id_comptes,id_annees_scolaires',$compte.','.$annee);
					
	$total_virements_sourses = getSum('comptes_virements','montant','id_comptes_source,id_annees_scolaires',$compte.','.$annee);
	
	$total_virements_destinations = getSum('comptes_virements','montant','id_comptes_destination,id_annees_scolaires',$compte.','.$annee);
	
	$total = ($total_alimentations + $total_reglements + $total_virements_destinations) - $total_virements_sourses;
	
}


function getStockProduit($produit,$annee){
	
//$entrees = getTotalProduitsLivres($produit,$annee);
$entrees = getSum('fournisseurs_devis_livraisons_produits','quantite','id_produits',$produit);

$sorties = getSum('produits_sorties','quantite','id_produits,id_annees_scolaires',$produit.','.$annee);

$initials = getSum('produits_stocks_initials','quantite','id_produits',$produit);

return $stock = ($entrees + $initials) - $sorties;

}

function eleveMoyenneTestEntree($eleve,$test,$annee){
	$sql = "
			select * from 
				tests_entree_resultats  
			where
				id_tests_entree = '". $test ."'
			and
				id_annees_scolaires = '". $annee ."' 
			and
				id_eleves = '". $eleve ."' 
			";
	$res = doQuery($sql);
	
	$total_note = 0;
	$total_coef = 0;
	
	while ($ligne = mysql_fetch_array($res)){
		$coef = getValeurChamp('coefficient','tests_entree_tests_matieres','id_tests_matieres,id_tests_entree',$ligne['id_tests_matieres'].','.$test);
		
		$total_coef += $coef;
		
		$note = $ligne['note'];
		$total_note +=  ($note * $coef);
	}
	
	return round(($total_note / $total_coef),2);
}

function cmp222($produit){
	$sql = "select 
				(sum(prix*(fournisseurs_devis_livraisons_produits.quantite)) 
				/ 
				sum(fournisseurs_devis_livraisons_produits.quantite)) as cmp 
			
			from 
				fournisseurs_devis_produits
				join 
				fournisseurs_devis_livraisons_produits 
				on
				fournisseurs_devis_produits.id_produits
				= 
				fournisseurs_devis_livraisons_produits.id_produits
			
			where 
				fournisseurs_devis_produits.id_annees_scolaires = '". $annee ."'
			and 
				fournisseurs_devis_produits.id_produits = '". $produit ."'";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	
	return round($ligne['cmp'],2);
}

function cmp($produit){
	$sql = "select 

(
	sum(fournisseurs_devis_produits.prix * fournisseurs_devis_livraisons_produits.quantite) 
	+
	sum(produits_stocks_initials.prix * produits_stocks_initials.quantite) 
)

/ 

(
	sum(fournisseurs_devis_livraisons_produits.quantite) 
	+
	sum(produits_stocks_initials.quantite)
) 

as cmp 
			
			from 
				fournisseurs_devis_produits 
				
				join 
				fournisseurs_devis_livraisons_produits 
				on
				fournisseurs_devis_produits.id_produits 
				= 
				fournisseurs_devis_livraisons_produits.id_produits 
				
				join 
				produits_stocks_initials 
				on
				fournisseurs_devis_produits.id_produits 
				= 
				produits_stocks_initials.id_produits 
			
			where 
				fournisseurs_devis_produits.id_produits = '". $produit ."' 
			or
				fournisseurs_devis_livraisons_produits.id_produits = '". $produit ."' 
			or
				produits_stocks_initials.id_produits = '". $produit ."' 
			";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	
	return round($ligne['cmp'],2);
}

function getDispoTestSalleCreneau1111($test,$salle,$creneau,$annee){

	$date_test = date('d/m/Y',getValeurChamp('date','tests_entree','id',$test));
			
	$sql_tec = 
	"
	select  * 
	
	from 
	
	tests_entree join tests_entree_tests_matieres 
	on 
	tests_entree.id = tests_entree_tests_matieres.id_tests_entree 
					
	where 
		id_creneaux = '". $creneau ."'  
	and 
		id_salles = '". $salle ."'  
	and 
		id_tests_entree <> '". $test ."'
	and
		tests_entree_tests_matieres.id_annees_scolaires = '". $annee ."'
	";

	//echo "<br><br>";

	$res_tec = doQuery($sql_tec);
	$nb_tec = mysql_num_rows($res_tec);
	if($nb_tec != 0){
		while ($ligne_tec = mysql_fetch_array($res_tec)){
			$date_test2 = date('d/m/Y',$ligne_tec['date']);
			if($date_test == $date_test2){
			?>
			
			<span class="rouge">
					
			<?php $niv =getValeurChamp('id_niveaux','tests_entree','id',$ligne_tec['id_tests_entree']) ?>
			
			<?php echo getValeurChamp('libelle','tests_matieres','id',$ligne_tec['id_tests_matieres']) ?>
			, 
			<?php echo getValeurChamp('libelle','niveaux','id',$niv) ?> 
			
			</span>
				
			<?php
			}
			else
			{
				$mat = getNb('tests_entree_tests_matieres','id_tests_entree,id_creneaux,id_salles',$test.','.$creneau.','.$salle);
				
				if($mat != 0){
				
					$id_tests_matieres = getValeurChamp('id_tests_matieres','tests_entree_tests_matieres','id_tests_entree,id_creneaux,id_salles',$test.','.$creneau.','.$salle);
				?>
				
				<span class="vert">
					<?php echo getValeurChamp('libelle','tests_matieres','id',$id_tests_matieres); ?>
				</span>
				
				<?php	
				}
				else
				{
				
				//afficherLiensSelonDroits($lien,$params,$class,$titre,$libelle)
				echo afficherLiensSelonDroits(	'ajouter_tests_entree_tests_matieres.php',
									'tests_entree='. $test .'&creneaux='. $creneau .'&salles='. $salle,
									'ajouter',
									_MATIERES,
									_MATIERES);
				
				}
				
			}
		}
	}
	else
	{
		$mat = getNb('tests_entree_tests_matieres','id_tests_entree,id_creneaux,id_salles',$test.','.$creneau.','.$salle);
				
		if($mat != 0){
		
			$id_tests_matieres = getValeurChamp('id_tests_matieres','tests_entree_tests_matieres','id_tests_entree,id_creneaux,id_salles',$test.','.$creneau.','.$salle);
		?>
				
		<span class="vert">
		<?php echo getValeurChamp('libelle','tests_matieres','id',$id_tests_matieres); ?> 
		
		&nbsp;
		
		<?php 
		$id_plan = getValeurChamp('id','tests_entree_tests_matieres','id_tests_entree,id_creneaux,id_salles',$test.','.$creneau.','.$salle);
		
		echo afficherLiensSupprimerSelonDroits(
										'supprimer_tests_creneaux_matieres.php',
										'tests_entree_tests_matieres',
										$id_plan,
										'tests_entree_creneaux.php',
										'tests_entree',
										$test,
										_PLANNING)
		?>
		</span>
		
		<?php	
		}
		else
		{
		
		//afficherLiensSelonDroits($lien,$params,$class,$titre,$libelle)
		echo afficherLiensSelonDroits('ajouter_tests_entree_tests_matieres.php',
							'tests_entree='. $test .'&creneaux='. $creneau .'&salles='. $salle,
							'ajouter',
							_MATIERES,
							_MATIERES);
		
		}
	}
	
}

function getDispoTestSalleCreneau($test,$salle,$creneau){

	$date_test = date('d/m/Y',getValeurChamp('date','tests_entree','id',$test));
			
	$sql_tec = 
	"
	select  * 
	
	from 
	
	tests_entree join tests_entree_tests_matieres 
	on 
	tests_entree.id = tests_entree_tests_matieres.id_tests_entree 
					
	where 
		id_creneaux = '". $creneau ."'  
	and 
		id_salles = '". $salle ."'  
	and 
		id_tests_entree <> '". $test ."'
	";

	//echo "<br><br>";

	$res_tec = doQuery($sql_tec);
	$nb_tec = mysql_num_rows($res_tec);
	
	if($nb_tec != 0){
		while ($ligne_tec = mysql_fetch_array($res_tec)){
			$date_test2 = date('d/m/Y',$ligne_tec['date']);
			if($date_test == $date_test2){
			?>
			
			<span class="rouge">
					
			<?php $niv =getValeurChamp('id_niveaux','tests_entree','id',$ligne_tec['id_tests_entree']) ?>
			
			<?php echo getValeurChamp('libelle','tests_matieres','id',$ligne_tec['id_tests_matieres']) ?>
			, 
			<?php echo getValeurChamp('libelle','niveaux','id',$niv) ?> 
			
			</span>
				
			<?php
			}
			else
			{
			?>
				
				<span class="vert">
					<?php echo getValeurChamp('libelle','tests_matieres','id',$id_tests_matieres); ?>
				</span>

			<?php
			}
		}
	}
	else
	{
		
		$mat2 = getNb('tests_entree_tests_matieres','id_tests_entree,id_creneaux,id_salles',$test.','.$creneau.','.$salle);
				
		if($mat2 != 0){
			
			$id_tests_matieres = getValeurChamp('id_tests_matieres','tests_entree_tests_matieres','id_tests_entree,id_creneaux,id_salles',$test.','.$creneau.','.$salle);
		?>
				
		<span class="vert">
		<?php echo getValeurChamp('libelle','tests_matieres','id',$id_tests_matieres); ?> 
		
		&nbsp;
		
		<?php 
		$id_plan = getValeurChamp('id','tests_entree_tests_matieres','id_tests_entree,id_creneaux,id_salles',$test.','.$creneau.','.$salle);
		
		echo afficherLiensSupprimerSelonDroits(
										'supprimer_tests_creneaux_matieres.php',
										'tests_entree_tests_matieres',
										$id_plan,
										'tests_entree_creneaux.php',
										'tests_entree',
										$test,
										_PLANNING)
		?>
		</span>
		
		<?php	
		}
		else
		{
			
				//afficherLiensSelonDroits($lien,$params,$class,$titre,$libelle)
				echo afficherLiensSelonDroits(
					'ajouter_tests_entree_tests_matieres.php',
					'tests_entree='. $test .'&creneaux='. $creneau .'&salles='. $salle,
					'ajouter',
					_MATIERES,
					_MATIERES);
			
		}
	}
	
	
}

function getNoteControleEleve($eleve,$disc,$controle,$annee){
	
	$note = getValeurChamp('note','registres_notes_controles_eleves','id_eleves,id_disciplines,id_controles,id_annees_scolaires',$eleve.','.$disc.','.$controle.','.$annee);
	
	if($note == '') $note = 'null';
	
	return $note;
}

function getNoteExamensEleve($eleve,$disc,$examen,$annee){
	$note = getValeurChamp('note','registres_notes_examens_eleves','id_eleves,id_disciplines,id_examens,id_annees_scolaires',$eleve.','.$disc.','.$examen.','.$annee);
	
	if($note == '') $note = 'null';
	
	return $note;
}

function getNoteControleEleveDiscipline($eleve,$controle,$discipline,$annee){
	
	$note = getValeurChamp('note','registres_notes_controles_eleves','id_eleves,id_disciplines,id_controles,id_annees_scolaires',$eleve.','.$discipline.','.$controle.','.$annee);
	
	if($note == '') $note = 'null';
	
	return $note;
	
}

function getNoteExamenEleveDiscipline($eleve,$examen,$discipline,$annee){
	
	$note = getValeurChamp('note','registres_notes_examens_eleves','id_eleves,id_disciplines,id_examens,id_annees_scolaires',$eleve.','.$discipline.','.$examen.','.$annee);
	
	if($note == '') $note = 'null';
	
	return $note;
	
}

function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

function no_accent($str_accent) {
   $pattern = Array("/é/", "/è/", "/ê/", "/ç/", "/à/", "/â/", "/î/", "/ï/", "/ù/", "/ô/");
   // notez bien les / avant et après les caractères
   $rep_pat = Array("e", "e", "e", "c", "a", "a", "i", "i", "u", "o");
   $str_noacc = preg_replace($pattern, $rep_pat, $str_accent);
   return $str_noacc;
}

function traiter_pj($subject) {
	$pattern     = '`[^[:alnum:]]`';
	$replacement = '_';
	
	echo preg_replace($pattern, $replacement, $subject);
}


function getCAcommercial($id_commercial) {
	$nbr=0;
					$sql="select id from commandes where id_commerciaux= '". $id_commercial  ."'";
					$res = doQuery($sql);
					while($ligne = mysql_fetch_array($res))
					{	$nbr+=total($ligne['id']);	}
					$comission=getValeurChamp("comission","commerciaux","id",$id_commercial);
					$ca=round(($nbr*$comission)/100,2);
					echo formater_montant($ca)." "._DEVISE; 
}

function CAcommercial($id_commercial) {
	$nbr=0;
					$sql="select id from commandes where id_commerciaux= '". $id_commercial  ."'";
					$res = doQuery($sql);
					while($ligne = mysql_fetch_array($res))
					{	$nbr+=total($ligne['id']);	}
					$comission=getValeurChamp("comission","commerciaux","id",$id_commercial);
					$ca=round(($nbr*$comission)/100,2);
					return $ca; 
}

function CAcommercial_commandes($id_commercial,$id_commandes) {
		$commandes=total($id_commandes);
		$comission=getValeurChamp("comission","commerciaux","id",$id_commerciaux);
		$ca=($commandes*$comission)/100;
		return $ca; 
}

function total($id_commandes)
{
		$sql = "select SUM(prix) from modules where id in (select id_modules from commandes_modules where id_commandes = '". $id_commandes."' and gratuit=0 and reduction=0)";		
		$res = doQuery($sql);
		if($ligne=mysql_fetch_row($res))
		{
			$reduction=getValeurChamp("reduction","commandes","id",$id_commandes);
			$total1=$ligne[0]-($ligne[0]*($reduction/100));
		}
		
		
		
		$sql1 = "select * from commandes_modules where id_commandes = '". $id_commandes."' and reduction!=0";		
		$res1 = doQuery($sql1);
		while($ligne1=mysql_fetch_array($res1))
		{
			$prix_modules=getValeurChamp("prix","modules","id",$ligne1['id_modules']);
			$reduction_modules=$prix_modules-(($prix_modules*$ligne1['reduction'])/100);
			$t+=$reduction_modules;
		}
		$reduction2=getValeurChamp("reduction","commandes","id",$id_commandes);
		$total2=$t-($t*$reduction2)/100;
		
		$total=$total1+$total2;
	return $total;
}

function total_sans_reduction($id_commandes)
{
		$sql = "select SUM(prix) from modules where id in (select id_modules from commandes_modules where id_commandes = '". $id_commandes."' and gratuit=0 and reduction=0)";		
		$res = doQuery($sql);
		if($ligne=mysql_fetch_row($res))
		{
			$reduction=getValeurChamp("reduction","commandes","id",$id_commandes);
			$total1=$ligne[0];
		}
		
		
		
		$sql1 = "select * from commandes_modules where id_commandes = '". $id_commandes."' and reduction!=0";		
		$res1 = doQuery($sql1);
		while($ligne1=mysql_fetch_array($res1))
		{
			$prix_modules=getValeurChamp("prix","modules","id",$ligne1['id_modules']);
			$reduction_modules=$prix_modules-(($prix_modules*$ligne1['reduction'])/100);
			$t+=$reduction_modules;
		}
		$total2=$t;
		
		$total=$total1+$total2;
	return $total;
}

function ttc($id_commandes)
{
$ht=total($id_commandes);
$tva=getValeurChamp("tva","commandes","id",$id_commandes);
$ttc=$ht+(($ht*$tva)/100);
return round($ttc,2);
}

function regler($id_commandes)
{
	return $regler=getSum("reglements","montant","id_commandes",$id_commandes);
}
function reste($id_commandes)
{
		$ttc=ttc($id_commandes);
		$regler=regler($id_commandes);
		$reste=$ttc-$regler;           	
		return $reste;

}

function getTimestamp($date,$sep){
	$tab_d = explode($sep,$date);
			
	$annee = $tab_d[2];
	$mois = $tab_d[1];
	$jour = $tab_d[0];
	
	return mktime(0,0,1,$mois,$jour,$annee);
}

function nombre_fonctionnaliter($id)
{
	$req = "SELECT count(id) from fonctionnalites where id_modules=".$id;
	$resul = doQuery($req);
	$ligne = mysql_fetch_array($resul);
	return $ligne[0];
			
}

function somme_type_suivis($id_type)
{
	$sql = "select SUM(cout) as somme from suivis where id_type=".$id_type;		
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	return $ligne['somme'];
}

function total_suivis()
{
	$sql = "select SUM(cout) as somme from suivis";		
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	return $ligne['somme'];
}


function total_commandes()
{
	$somme=0;
	$sql = "select distinct(id_commandes) from commandes_modules";		
	$res = doQuery($sql);
	while($ligne = mysql_fetch_array($res))
	{
		$somme+=ttc($ligne['id_commandes']);	
	}
	return $somme;
}


function total_devis($id_devis)
{
		$sql1 = "select SUM(prix) from modules where id in (select id_modules from devis_modules where id_devis = '". $id_devis."')";		
		$res1 = doQuery($sql1);
		if($ligne1=mysql_fetch_row($res1))
		{
			$reduction=getValeurChamp("reduction","devis","id",$id_devis);
			$total=$ligne1[0]-($ligne1[0]*($reduction/100));
		}
	return $total;
}

function total_sans_reduction_devis($id_devis)
{
		$sql1 = "select SUM(prix) from modules where id in (select id_modules from devis_modules where id_devis = '". $id_devis."')";		
		$res1 = doQuery($sql1);
		if($ligne1=mysql_fetch_row($res1))
		{
			$total=$ligne1[0];
		}
	return $total;
}

function ttc_devis($id_devis)
{
$ht=total_devis($id_devis);
$tva=getValeurChamp("tva","devis","id",$id_devis);
$ttc=$ht+(($ht*$tva)/100);
return round($ttc,2);
}

function getfonctionnalite($id_modules)
{
		$sql = "select * from fonctionnalites where id_modules=".$id_modules;		
		$res = doQuery($sql);
		?>
		<ul>
		<?php
		while ($ligne = mysql_fetch_array($res))
		{?>
        	<li style="display:inline-block"><?php echo $ligne['nom'] ?></li>
        <?php 
		}
}

function NBClients()
{
	$sql = "select distinct(id_clients) from commandes";		
	$res = doQuery($sql);
	$nb = mysql_num_rows($res);
	return $nb;
}

function NBProspects()
{
	$sql = "select count(id) as nb from clients where id not in (
								select distinct(id_clients) from commandes
																)";		
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	return $ligne['nb'];
}


function NBModules()
{
	$sql = "select count(id) as nb from modules";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	return $ligne['nb'];
}

function NBFonctionnalites()
{
	$sql = "select count(id) as nb from fonctionnalites";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	return $ligne['nb'];
}

function NBDevis()
{
	$sql = "select count(id) as nb from devis";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	return $ligne['nb'];
}

function NBCommandes()
{
	$sql = "select count(id) as nb from commandes";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	return $ligne['nb'];
}


function NBFactures()
{
	$sql = "select count(id) as nb from commandes where numero_facture!=''";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	return $ligne['nb'];
}

function NBDevisATraiter()
{
	$sql = "select count(id) as nb from devis where etat=3";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	return $ligne['nb'];
}

function NBCommandesAFacturer()
{
	$sql = "select count(id) as nb from commandes where  numero_facture='' and tva!=0";
	$res = doQuery($sql);
	$ligne = mysql_fetch_array($res);
	return $ligne['nb'];
}

function NBFactureClientsImpayeés()
{
	$nb=0;
	$sql = "select * from commandes";
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
    	 if(reste($ligne['id'])!=0)
		 {$nb++;}
	}
	return $nb;
}

function CA_Reg_mois($m,$a)
{
	$montant=0;
	$sql = "select * from reglements";
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$date=$ligne['date_reglement'];
    	$mois=date("m",$date);
		$year=date("Y",$date);
		if($mois==$m and $year==$a)
		{
				$montant+=$ligne['montant'];
		}
	}
	return $montant;
}

function CA_Suivis_mois($m,$a)
{
	$montant_suivis=0;
	$sql = "select * from suivis";
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$date=$ligne['date'];
    	$mois=date("m",$date);
    	$year=date("Y",$date);
		if($mois==$m and $year==$a)
		{
				$montant_suivis+=$ligne['cout'];
		}
	}
	
	$montant_reg=0;
	$sql = "select * from commerciaux_reglements";
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$date=$ligne['date_reglement'];
    	$mois=date("m",$date);
    	$year=date("Y",$date);
		if($mois==$m and $year==$a)
		{
				$montant_reg+=$ligne['montant'];
		}
	}
	
	$montant=$montant_reg+$montant_suivis;
	return $montant;
}
function CAPre()
{
	$total=0;
	
	$sql = "select * from commandes";
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$total+=ttc($ligne['id']);
	}
	return $total;
}

function CAReel()
{
	$reg=getSum("reglements","montant","1","1");
	$sui=getSum("suivis","cout","1","1");
	$ttc=$reg-$sui;
	return $ttc; 
}

function CAClientsReel($id_clients)
{
	$reg=0;
	$sui=0;
	
	$sql = "select * from commandes where id_clients=".$id_clients;
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$reg+=getSum("reglements","montant","id_commandes",$ligne['id']);
		$sui+=getSum("suivis","cout","id_commandes",$ligne['id']);
	}
	
	return $reg-$sui;
	
}

function CAClientsPre($id_clients)
{
	$total=0;
	$sql = "select * from commandes where id_clients=".$id_clients;
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$total+=ttc($ligne['id']);
	}
	return $total;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getMontantVente($id_vente){
	$total=0;
	$sql = "select * from  ligne_ventes where id_ventes=".$id_vente;
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$total+=(($ligne['a_retour']+$ligne['qte_vente']-$ligne['nbr_retour'])*$ligne['prix_vente']);
	}
	return $total;
}

function getMontantEchange($id_echange){
	$total=0;
	$sql = "select * from  ligne_echange where id_echanges=".$id_echange;
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$total+=($ligne['qte_echange']*$ligne['prix_echange']);
	}
	return $total;
}

function getLastId($table){
	$total=0;
	$sql = "select max(id) from  ".$table;
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$total+=$ligne[0];
	}
	return $total;	
}

function MAJStock($idProduit,$qte,$op)
{
	echo $sql = "update produits set qte_stock=qte_stock".$op.$qte." where id=".$idProduit;
	$res = doQuery($sql);
	doQuery("COMMIT");
}

function getMontantPaye($id_vente){
	$total=0;
	$sql = "select * from  paiements where id_clients=".$id_vente;
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$total+=$ligne['montant'];
	}
	return $total;
}

function getMontantReste($id_vente){
	return getMontantVente($id_vente)-getMontantPaye($id_vente);
}

function getData($id){
		$sql = "select * from ligne_echange where id_echanges=".$id." order by id";		
		$res = doQuery($sql);
		$data=array();
		$i=0;
		while ($ligne = mysql_fetch_array($res))
		{
			$data[$i][0]=getValeurChamp('designation','produits','id',$ligne['id_produits']);
			$data[$i][1]=$ligne['qte_echange'];
			$i++;
		}	
		return $data;	
}

function getMontantAchat($id_achat){
	$total=0;
	$sql = "select * from  ligne_achats where id_achats=".$id_achat;
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$total+=($ligne['qte_achat']*$ligne['prix_achat']);
	}
	return $total;
}


function PrixPiece($id){
$prix=getValeurChamp('prix_echange','produits','id',$id);
$unite=getValeurChamp('unite_carton','produits','id',$id)>0?getValeurChamp('unite_carton','produits','id',$id):1;	
	return ($prix/$unite);
}


function totalVente($id){
	
	return getMontantVente($id);
}

function versement($id)
{
		$dateVent=getValeurChamp('date_vente','ventes','id',$id);
		$client=getValeurChamp('id_clients','ventes','id',$id);
		
		$sql = "select * from  paiements where id_clients=".$clients." and date_paiment='".$dateVent."'";
		$res = doQuery($sql);
		while ($ligne = mysql_fetch_array($res))
		{
			$total=$ligne['montant'];
		}
		return $total;

}

function CreditClient($id){
	$totalVente=0;
	$totalPaye=0;
	$sql = "select * from  ventes where id_clients=".$id;
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$totalVente+=getMontantVente($ligne['id']);
	}
	$totalVente+=getTotalFirstCredit($id);
	$totalPaye=getSum("paiements","montant","id_clients",$id);		
	return $totalVente-$totalPaye;
}


function getTotalVenteClient($id){
	$totalVente=0;
	$totalPaye=0;
	$sql = "select * from  ventes where id_clients=".$id;
	$res = doQuery($sql);
	while ($ligne = mysql_fetch_array($res))
	{
		$totalVente+=getMontantVente($ligne['id']);
	}

	return $totalVente;
}
function getTotalFirstCredit($id){
	$sq="select * from credit_clients where id_clients=".$id;
	$result = doQuery($sq);
	$somme=0;
	while ($lignes = mysql_fetch_array($result))
	{
		$somme+=$lignes['montant'];
	}
	return $somme;
}
function getTotalReste($id){
	return (getTotalVenteClient($id)+getTotalFirstCredit($id))-getSum("paiements","montant","id_clients",$id);
}

function getNombreClientInterne(){
	$sql = "select * from  clients where type_client=1";
	$res = doQuery($sql);
	return mysql_num_rows($res);
}

function getNombreClientExterne(){
	$sql = "select * from  clients where type_client=2";
	$res = doQuery($sql);
	return mysql_num_rows($res);
}

function getNombreFournisseurs(){
	$sql = "select * from  fournisseurs";
	$res = doQuery($sql);
	return mysql_num_rows($res);
}

function getNombreProduits(){
	$sql = "select * from  produits";
	$res = doQuery($sql);
	return mysql_num_rows($res);
}

function getValeurStock(){
	$sql = "select * from  produits";
	$res = doQuery($sql);
	$totalVente=0;
	while ($ligne = mysql_fetch_array($res))
	{
		$totalVente+=$ligne['qte_stock']*$ligne['prix_vente'];
	}
	return $totalVente;

}

function getValeurAchats(){
	$sql = "select * from  ligne_achats";
	$res = doQuery($sql);
	$totalVente=0;
	while ($ligne = mysql_fetch_array($res))
	{
		$totalVente+=$ligne['qte_achat']*$ligne['prix_achat'];
	}
	return $totalVente;
}

function getValeurVentes(){
	$sql = "select * from  ligne_ventes";
	$res = doQuery($sql);
	$totalVente=0;
	while ($ligne = mysql_fetch_array($res))
	{
		$totalVente+=$ligne['qte_vente']*$ligne['prix_vente'];
	}
	return $totalVente;
}

function getValeurRetour(){
	$sql = "select * from  ligne_echange";
	$res = doQuery($sql);
	$totalVente=0;
	while ($ligne = mysql_fetch_array($res))
	{
		$totalVente+=$ligne['qte_echange']*$ligne['prix_echange'];
	}
	return $totalVente;
}

function getValeurCredit(){
	$sq="select * from credit_clients";
	$result = doQuery($sq);
	$somme=0;
	while ($lignes = mysql_fetch_array($result))
	{
		$somme+=$lignes['montant'];
	}
	$sql = "select * from paiements";
	$res = doQuery($sql);
	$totalVente=0;
	while ($ligne = mysql_fetch_array($res))
	{
		$totalVente+=$ligne['montant'];
	}
	return (getValeurVentes()+$somme)-$totalVente;
}

function getDataStock() {	
	$sql = "select * from produits_print ";		
		$res = doQuery($sql);
		$data=array();
		$i=0;
		
		$total_anc=0;
		$total_stock=0;
		$total_vente=0;
		$total_achat=0;		
		$total_chnage=0;								
		
		while ($ligne = mysql_fetch_array($res))
		{
			$data[$i][0]=$ligne['designation'];
			$data[$i][1]=$ligne['ancien_stock'];
			$data[$i][2]=getQteEntrerPrint($ligne['id']);
			$data[$i][3]=getQteSortiePrint($ligne['id']);
			$data[$i][4]=$ligne['qte_stock'];
			$data[$i][5]=getQteChangePrint($ligne['id']);	
			
			$total_anc=$total_anc+($ligne['ancien_stock']*$ligne['prix_vente']);
			$total_achat=$total_achat+(getQteEntrerPrint($ligne['id'])*$ligne['prix_achat']);						
			$total_vente=$total_vente+(getQteSortiePrint($ligne['id'])*$ligne['prix_vente']);
			$total_stock=$total_stock+($ligne['qte_stock']*$ligne['prix_vente']);									
			$total_change=$total_change+(getQteChangePrint($ligne['id'])*$ligne['prix_echange']);												
			
			$i++;
		}
		
		$data[$i][0]="- -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - ";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]="";
		$data[$i][5]="";		
		$i++;
		
		$data[$i][0]="Total";
		$data[$i][1]=$total_anc;
		$data[$i][2]=$total_achat;
		$data[$i][3]=$total_vente;
		$data[$i][4]=$total_stock;
		$data[$i][5]=$total_change;		
		
			
		return $data;
}

function getBonEchange($echange){
	$sql = "select * from ligne_echange where id_echanges=".$echange;			
	$res = doQuery($sql);
	$data=array();
	$i=0;
	while ($ligne = mysql_fetch_array($res))
		{
			$data[$i][0]=getValeurChamp('designation','produits','id',$ligne['id_produits']);
			$data[$i][1]=$ligne['qte_echange'];
			$i++;
		}
	return $data;
}


	function updateDateImpression($champ){
			$select="update parametres set ".$champ."='".date('Y-m-d')."'";
			doQuery($select);
			doQuery("COMMIT"); 		
	}
	
	function getQteEntrerPrint($id){
		$sql2 = "select date_impression from produits_print";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
			{
				$dateimpression=$ligne2['date_impression'];
			}
			
		$sql2 = "select sum(qte_achat) as qte from ligne_achats where id_produits=".$id." and id_achats in (select id from achats where date_achat>='".$dateimpression."')";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
			{
				$qte=$ligne2['qte'];
			}				
		return $qte;
	}
	function getQteEntrerAffichage($id){
		 $sql2 = "select impresssion_stock from parametres";
				$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
			{
				$dateimpression=$ligne2['impresssion_stock'];
			}
			
		$sql2 = "select sum(qte_achat) as qte from ligne_achats where id_produits=".$id." and id_achats in (select id from achats where date_achat>'".$dateimpression."')";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
			{
				$qte=$ligne2['qte'];
			}				
		return $qte;
	}


	function getQteSortiePrint($id){
		$sql2 = "select date_impression from produits_print";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
		{
				$dateimpression=$ligne2['date_impression'];
		}
			
		$sql2 = "select sum(qte_vente+a_retour-nbr_retour) as qte from ligne_ventes where id_produits=".$id." and id_ventes in (select id from ventes where date_vente>'".$dateimpression."')";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
		{
			$qte=$ligne2['qte'];
		}				
		return $qte;
	}

	function getQteSortieAffichage($id){
		$sql2 = "select impresssion_stock from parametres";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
		{
				$dateimpression=$ligne2['impresssion_stock'];
		}
			
		$sql2 = "select sum(qte_vente+a_retour-nbr_retour) as qte from ligne_ventes where id_produits=".$id." and id_ventes in (select id from ventes where date_vente>'".$dateimpression."')";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
		{
			$qte=$ligne2['qte'];
		}				
		return $qte;
	}


	function getQteChangePrint($id){
		$sql2 = "select * from produits_print";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
		{
			$dateimpression=$ligne2['date_impression'];
		}
			
		$sql2 = "select sum(qte_change) as qte from ligne_ventes where id_produits=".$id." and id_ventes in (select id from ventes where date_vente>'".$dateimpression."')";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
			{
				$qte=$ligne2['qte'];
			}				
		return $qte;	
}
	function getQteChangeAffichage($id){
		$sql2 = "select * from parametres";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
		{
			$dateimpression=$ligne2['impresssion_stock'];
		}
			
		$sql2 = "select sum(qte_change) as qte from ligne_ventes where id_produits=".$id." and id_ventes in (select id from ventes where date_vente>'".$dateimpression."')";		
		$res2 = doQuery($sql2);
	
		while ($ligne2 = mysql_fetch_array($res2))
			{
				$qte=$ligne2['qte'];
			}				
		return $qte;	
}

function getFactureVente($ventes){
	$sql = "select * from ligne_ventes where id_ventes=".$ventes;		
	$res = doQuery($sql);
	$data=array();
	$i=0;
	while ($ligne = mysql_fetch_array($res))
		{
			$mont=(($ligne['qte_vente']+ $ligne['a_retour'])-$ligne['nbr_retour'])*$ligne['prix_vente'];
			$montCharge=$ligne['qte_vente']*$ligne['prix_vente'];
			$montTot+=$mont;
			$montTotCharge+=$montCharge;
			$data[$i][0]=getValeurChamp('designation','produits','id',$ligne['id_produits']);
			$data[$i][1]=$ligne['a_retour'];
			$data[$i][2]=$ligne['qte_vente'];
			$data[$i][3]=$ligne['nbr_retour'];
			$data[$i][4]=$mont;
			$data[$i][5]=$ligne['qte_change'];
			$i++;
		}

		$data[$i][0]="- -  - - - -  - - - -  - - - -  - - - -  -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - -";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]="";
		$data[$i][5]="";
		$i++;
		
		$data[$i][0]="";
		$data[$i][1]="";
		$data[$i][2]=$montCharge;
		$data[$i][3]="";
		$data[$i][4]="";
		$data[$i][5]="";
		$i++;
		
		$data[$i][0]="- -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]="";
		$data[$i][5]="";
		$i++;

	$chnages_fcture="";
	$total_facture="";
	$versement_facture="";
	$reste_facture="";
	$map_facture="";
	$title_chnages_fcture="Changes";
	$title_total_facture="Total";
	$title_versement_facture="Versement";
	$title_reste_facture="Reste";
	$title_map_facture="Map";
		
	$sql2 = "select * from factures where id_ventes=".$ventes;		
	$res2 = doQuery($sql2);

	
	while ($ligne2 = mysql_fetch_array($res2))
		{
			$chnages_fcture=$ligne2['chnages'];
			$total_facture=$ligne2['totale'];
			$versement_facture=$ligne2['versement'];
			$reste_facture=$ligne2['reste'];
			$map_facture=$ligne2['map'];
		}


		$data[$i][0]="";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_chnages_fcture;
		$data[$i][5]=$chnages_fcture;
		$i++;

		$data[$i][0]="";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_total_facture;
		$data[$i][5]=$total_facture;
		$i++;
		
		$data[$i][0]="";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_versement_facture;
		$data[$i][5]=$versement_facture;
		$i++;
		
		$data[$i][0]="";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_reste_facture;
		$data[$i][5]=$reste_facture;
		$i++;
		
		$data[$i][0]="";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_map_facture;
		$data[$i][5]=$map_facture;
		$i++;
		
		return $data;
}
function getFactureVenteReduit($ventes){
	$sql = "select * from ligne_ventes where id_ventes=".$ventes;		
	$res = doQuery($sql);
	$data=array();
	$montTot=0;
	$montTotCharge=0;
	
	$i=0;
	while ($ligne = mysql_fetch_array($res))
		{
			$mont=(($ligne['qte_vente']+ $ligne['a_retour'])-$ligne['nbr_retour'])*$ligne['prix_vente'];
			$montCharge=$ligne['qte_vente']*$ligne['prix_vente'];
					
			$montTot+=$mont;
			$montTotCharge+=$montCharge;
			$totalAncRetour+=$ligne['a_retour']*$ligne['prix_vente'];
			$totalCharge+=$ligne['qte_vente']*$ligne['prix_vente'];
			$totalNouvelleRetour+=$ligne['nbr_retour']*$ligne['prix_vente'];
			$totalChange+=$ligne['qte_change']*PrixPiece($ligne['id_produits']);
			
			$data[$i][0]=getValeurChamp('designation','produits','id',$ligne['id_produits']);
			$data[$i][1]=$ligne['a_retour'];
			$data[$i][2]=$ligne['qte_vente'];
			$data[$i][3]=$ligne['nbr_retour'];
			$data[$i][4]=$mont;
			$data[$i][5]=$ligne['qte_change'];
			$i++;
		}

		$data[$i][0]="- -  - - - -  - - - -  - - - -  - - - -  -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - -";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]="";
		$data[$i][5]="";
		$i++;
		
		$data[$i][0]="";
		$data[$i][1]=$totalAncRetour;
		$data[$i][2]=$totalCharge;
		$data[$i][3]=$totalNouvelleRetour;
		$data[$i][4]=$montTot;
		$data[$i][5]=$totalChange;
		$i++;
		
		$data[$i][0]="- -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - _";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]="";
		$data[$i][5]="";
		$i++;

	$chnages_fcture="";
	$total_facture="";
	$versement_facture="";
	$reste_facture="";
	$map_facture="";
	$title_chnages_fcture="Changes";
	$title_total_facture="Total";
	$title_versement_facture="Versement";
	$title_reste_facture="Reste";
	$title_map_facture="Map";
		
	$sql2 = "select * from factures where id_ventes=".$ventes;		
	$res2 = doQuery($sql2);

	
	while ($ligne2 = mysql_fetch_array($res2))
		{
			$chnages_fcture=$ligne2['chnages'];
			$total_facture=$ligne2['totale'];
			$versement_facture=$ligne2['versement'];
			$reste_facture=$ligne2['reste'];
			$map_facture=$ligne2['map'];
		}


		$data[$i][0]="Depenses";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_total_facture;
		$data[$i][5]=$total_facture;
		$i++;

		$data[$i][0]=$title_chnages_fcture;
		$data[$i][1]=$chnages_fcture;
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_reste_facture;
		$data[$i][5]=$reste_facture;
		$i++;
		
		$data[$i][0]="";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_versement_facture;
		$data[$i][5]=$versement_facture;
		$i++;
			
		$data[$i][0]="";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_map_facture;
		$data[$i][5]=$map_facture;
		$i++;
		
		return $data;
}

function getFactureVenteGlobal($fg){
	$sql = "select * from ligne_ventes where id_ventes in (select id_ventes from factures where id_facture_global=".$fg.")";		
	$res = doQuery($sql);
	$data=array();
	$i=0;
	
	$tot_aretour=0;
	$tot_charge=0;
	$tot_charge=0;
	$tot_nretour=0;
	$tot_change=0;
	
	while ($ligne = mysql_fetch_array($res))
		{
			$mont=(($ligne['qte_vente']+ $ligne['a_retour'])-$ligne['nbr_retour'])*$ligne['prix_vente'];
			$montTot+=$mont;
			$data[$i][0]=getValeurChamp('designation','produits','id',$ligne['id_produits']);
			$data[$i][1]=$ligne['a_retour']; 
			$tot_aretour=$tot_aretour+($ligne['a_retour']*$ligne['prix_vente']);
			$data[$i][2]=$ligne['qte_vente'];
			$tot_charge=$tot_charge+($ligne['qte_vente']*$ligne['prix_vente']);
			$data[$i][3]=$ligne['nbr_retour'];
			$tot_nretour=$tot_nretour+($ligne['nbr_retour']*$ligne['prix_vente']);
			$data[$i][4]=$mont;
			$data[$i][5]=$ligne['qte_change'];
			$tot_change=$tot_change+($ligne['qte_change']*getValeurChamp('prix_echange','produits','id',$ligne['id_produits']));
			$i++;
		}

		$data[$i][0]="- -  - - - -  - - - -  - - - -  - - - -  -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - -";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]="";
		$data[$i][5]="";
		$i++;

		$data[$i][0]="Total : ";
		$data[$i][1]=$tot_aretour;
		$data[$i][2]=$tot_charge;
		$data[$i][3]=$tot_nretour;
		$data[$i][4]="";
		$data[$i][5]=$tot_change;
		$i++;

		$data[$i][0]="- -  - - - -  - - - -  - - - -  - - - - -  - - - - - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - - -  - - -";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]="";
		$data[$i][5]="";
		$i++;

	$chnages_fcture="";
	$total_facture="";
	$versement_facture="";
	$map_facture="";
	
	$title_depense_fcture="Dépense";
	$title_gasoil_fcture="Gasoil";
	$title_change_fcture="Change";
	$title_piece_fcture="Pieces";

	$title_total_facture="Total";
	$title_versement_facture="Versement";
	$title_retour_facture="Retour";
	$title_map_facture="Map";
	$title_percent_facture=getValeurChamp('percent1','facture_global','id',$fg)."%";
		
	$sql2 = "select * from facture_global where id=".$fg;		
	$res2 = doQuery($sql2);
	
	while ($ligne2 = mysql_fetch_array($res2))
		{
			$depart_km_facture=$ligne2['depart_km'];
			$arrivee_km_facture=$ligne2['arrivee_km'];
			
			$depart_date_heur_facture=$ligne2['depart_date_heur'];
			$depart_date_min_facture=$ligne2['depart_date_min'];
			$arrivee_date_heur_facture=$ligne2['arrivee_date_heur'];
			$arrivee_date_min_facture=$ligne2['arrivee_date_min'];
			
			
			$total_facture=$ligne2['total'];
			$pieces_facture=$ligne2['pieces'];
			$versement_facture=$ligne2['versement'];
			$gasoil_facture=$ligne2['gasoil'];
			$prix_gasoil_facture=$ligne2['prix_gasoil'];
			
			
			$retour_facture=$ligne2['retour'];
			$chnages_fcture=$ligne2['changes'];
			$map_facture=$ligne2['map'];
			$percent1_facture=$ligne2['percent1'];
			$percent2_facture=$ligne2['percent2'];												
			
			
		}

		$data[$i][0]=$title_depense_fcture;
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_total_facture;
		$data[$i][5]=$total_facture;
		$i++;

		$data[$i][0]=$title_piece_fcture;
		$data[$i][1]=$pieces_facture;
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_versement_facture;
		$data[$i][5]=$versement_facture;
		$i++;
		
		$data[$i][0]=$title_gasoil_fcture;
		$data[$i][1]=$gasoil_facture; 
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_retour_facture;
		$data[$i][5]=$retour_facture;
		$i++;
		
		$data[$i][0]=$title_change_fcture;
		$data[$i][1]=$chnages_fcture;
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$title_map_facture;
		$data[$i][5]=$map_facture;
		$i++;
		
		$data[$i][0]="";
		$data[$i][1]="";
		$data[$i][2]="";
		$data[$i][3]="";
		$data[$i][4]=$percent1_facture;
		$data[$i][5]=$percent2_facture;
		$i++;
		
		return $data;
}

function inserIdFactureGlobal($id_fg,$id_fr){
	$sql1 = "insert into facture_global(id) values(".$id_fg.")";
	$res1 = doQuery($sql1);
	
	$sql = "update factures set id_facture_global= ". $id_fg ." where id = '". $id_fr ."'";
	$res = doQuery($sql);
}

function getFacturClientExterne(){
		$reqClient="select * from clients where type_client=1 and id in (select id_clients from ventes)";
		$resClient=doQuery($reqClient);
		$dataClient=array();
		$k=0;
		while ($ligneClient = mysql_fetch_array($resClient)){
			 $dataClient[$k][0]=substr($ligneClient['nom'],0,6);
			 $dataClient[$k][1]=$ligneClient['id'];
			 $k++;
		}
	    $data=array();
	    
		$i=0;
		$data[$i][0]="";
	$varTire="---------------------------";
	for($kk=1,$ct=0;$kk<count($dataClient)*2;$kk++)
	{
		if($kk%2!=0) {
			$data[$i][$kk]=$dataClient[$ct][0];
			$ct++;
		}
		else
			$data[$i][$kk]=0;
		
	}
	$data[$i][$kk]=0;

for($i=0;$i<$ct;$i++) $varTire.="------------------------";
	$i++;
    $data[$i][0]=$varTire;
	
	$i++;
    $data[$i][0]="Description";

    for($kk=1;$kk<count($dataClient)*2;$kk++){
	  $data[$i][$kk]=$kk%2==0?"chn":"chr";  
    }
	$data[$i][$kk]="chn";  
	$i++;

	$data[$i][0]=$varTire;
	$i++;
    $reqProduits = "select * from produits ";      
    $resProduit = doQuery($reqProduits);
	$totalCharge=array();
	$totalChange=array();
	
   for($kk=1;$kk<=count($dataClient)*2;$kk++)
   {
			$totalCharge[$kk]=0;
			$totalChange[$kk]=0;
   }
   while ($lignePrd = mysql_fetch_array($resProduit))
   {
	   $data[$i][0]=$lignePrd['designation'];
	   for($kk=1;$kk<=count($dataClient)*2;$kk++)
	   {
			 if($kk%2!=0){
				$qte=SommeQteChargePrClient($dataClient[($kk-1)/2][1],$lignePrd['id']);
				$prix=getValeurChamp('prix_vente','produits','id',$lignePrd['id']); 
				$data[$i][$kk]=$qte;
				$data[0][$kk+1]=$data[0][$kk+1]+$qte;				
				$totalCharge[$kk]=$totalCharge[$kk]+$qte*$prix;
			 }
			else{
				$qteC=SommeQteChangePrClient($dataClient[($kk-1)/2][1],$lignePrd['id']);
				$prix=getValeurChamp('prix_echange','produits','id',$lignePrd['id']); 
				$data[$i][$kk]=$qteC;
				$totalChange[$kk]=$totalChange[$kk]+$qteC*$prix;
			}
	   }
	   $i++;
	}
	$data[$i][0]=$varTire;
   $i++;
   for($kk=1;$kk<=count($dataClient)*2;$kk++)						
	{
	 if($kk%2!=0){
	 $data[$i][$kk]=$totalCharge[$kk];
	 }
	 else
	 {
		$data[$i][$kk]=$totalChange[$kk];
	 }

	}
	return $data;
}

function SommeQteChargePrClient($id_clients,$id_produits){
	$sql = "select sum(qte_vente) as qte from ligne_ventes where id_produits=".$id_produits." and id_ventes in (select id from ventes where id_clients=".$id_clients.")";		
	$res = doQuery($sql);
    while ($ligne = mysql_fetch_array($res))
	{
			return $ligne["qte"];
	}
}

function SommeQteChangePrClient($id_clients,$id_produits){
	$sql = "select sum(qte_change) as qte from ligne_ventes where id_produits=".$id_produits." and id_ventes in (select id from ventes where id_clients=".$id_clients.")";		
	$res = doQuery($sql);
    while ($ligne = mysql_fetch_array($res))
	{
			return $ligne["qte"];
	}
}

function getTotalVersement($id){
 $sql = "select sum(versement) as qte from factures where id_facture_global=".$id;		
	$res = doQuery($sql);
    while ($ligne = mysql_fetch_array($res))
	{
			return $ligne["qte"];
	}	
}

function getDepartKm($id){
	$dd=getvaleurChamp('depart_km','facture_global','id',$id);
	if($dd!="") return $dd;
	else {
	$sql = "select min(depart_km) as ar from ventes where id in (select id_ventes from factures where id_facture_global=".$id.")";		
	$res = doQuery($sql);
    while ($ligne = mysql_fetch_array($res))
	{
			return $ligne["ar"];
	}	
	}
}

function getArriveKm($id){
	$sql = "select max(arrivee_km) as ar from ventes where id in (select id_ventes from factures where id_facture_global=".$id.")";		
	$res = doQuery($sql);
    while ($ligne = mysql_fetch_array($res))
	{
			return $ligne["ar"];
	}	
}
?>
