// JavaScript Document

function cocherTout(){
		
	if( document.frm.all.checked){
		var bool = true;
	}
	else{
		var bool = false;
	}
	

	for( var i=0; i< document.frm.checkbox.length; i++){
		document.frm.checkbox[i].checked = bool;
	}
	
	if( document.frm.checkbox.length == undefined){
		document.frm.checkbox.checked = bool;
	}

}
/*
function supprimer(table,id,page){
	var rep =window.confirm("Veuillez confirmer la suppression !"); 
	
	if (rep){ 
		document.location.href="../gestion.php?table="+table+"&page="+page+"&id="+id+"&act=s"
	}
}
*/

function supprimer(table,id,page,id_noms_retour,id_valeurs_retour){
	var rep =window.confirm("Veuillez confirmer la suppression !"); 
	
	if (rep){ 
	
	document.location.href="gestion.php?table="+table+"&page="+page+"&id_valeur="+id+"&id="+id+"&act=s&id_noms_retour="+id_noms_retour+"&id_valeurs_retour="+id_valeurs_retour
	}
	
}

function supprimer2(table,id,page,id_noms_retour,id_valeurs_retour){
	var rep =window.confirm("Veuillez confirmer la suppression !"); 
	
	if (rep){ 
		document.location.href="gestion.php?table="+table+"&page="+page+"&id_valeur="+id+"&id="+id+"&act=etat&champ_modif=etat_sup&valeur_modif=0&id_noms_retour="+id_noms_retour+"&id_valeurs_retour="+id_valeurs_retour
	}
}


function ChoixMasse(params){
	
	var tab = new Array();
	var j = 0;
	for( var i=0; i< document.frm.checkbox.length; i++){
		if(document.frm.checkbox[i].checked){
			tab[j] = document.frm.checkbox[i].value;
			j = j + 1;
		}
	}
	
	if( document.frm.checkbox.length == undefined){
		if(document.frm.checkbox.checked){
			tab[0] = document.frm.checkbox.value;
		}
	}
	
	if(tab.length == 0){
		alert(" Votre selection est vide !");
	}
	else{

	var rep = window.confirm(" Veuillez confirmer votre selection !");
		if (rep){ 
			document.location.href = "gestion.php?"+params+"&selection="+tab;
		}	
	}		
}