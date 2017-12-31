<?php session_start(); ?>
<?php //error_reporting(0) ?>

<link href="style.css" rel="stylesheet" type="text/css" />

<?php require_once('lang.php'); ?>
<?php require_once('fonctions.php'); ?>

<?php
echo "<center><h2>" . _REDIRECT . "</h2></center>";
//print_r($_REQUEST);
//echo "<br><br>";
connect();
//detection de la table et des champs concerné
$tablees = isset($_REQUEST['table']) && !empty($_REQUEST['table']) ? $_REQUEST['table'] : "";
$tab_table = !empty($tablees) ? split(',', $_REQUEST['table']) : array();
$table = count($tab_table) > 0 ? $tab_table[0] : "";
$action = isset($_REQUEST['act']) && !empty($_REQUEST['act']) ? $_REQUEST['act'] : "";
$id_valeur = isset($_REQUEST['id_valeur']) && !empty($_REQUEST['id_valeur']) ? $_REQUEST['id_valeur'] : "";
$page = isset($_REQUEST['page']) && !empty($_REQUEST['page']) ? $_REQUEST['page'] : "";
$id_retour = isset($_REQUEST['id_retour']) && !empty($_REQUEST['id_retour']) ? $_REQUEST['id_retour'] : "";
$id_noms_retour = isset($_REQUEST['id_noms_retour']) && !empty($_REQUEST['id_noms_retour']) ? $_REQUEST['id_noms_retour'] : "";
$id_valeurs_retour = isset($_REQUEST['id_valeurs_retour']) && !empty($_REQUEST['id_valeurs_retour']) ? $_REQUEST['id_valeurs_retour'] : "";
$champ_modif = isset($_REQUEST['champ_modif']) && !empty($_REQUEST['champ_modif']) ? $_REQUEST['champ_modif'] : "";
$valeur_modif = isset($_REQUEST['valeur_modif']) && !empty($_REQUEST['valeur_modif']) ? $_REQUEST['valeur_modif'] : "";
$chaine_retour = formuler_retour($id_noms_retour, $id_valeurs_retour);

if ($action == "a") {
    //Rendre les dates du format 11-30-2009 => 1235543267654
    $tab_dates = array("date",
        "date_reglement",
        "date_cheque",
        "date_achat",
        "date_vente",
        "date_echange",
        "date_paiment",
        "date_facture"
    );

    foreach ($tab_dates as $v) {
        if (isset($_REQUEST[$v])) {

            $tab_d = explode("/", $_REQUEST[$v]);

            $jour = $tab_d[0];
            $mois = $tab_d[1];
            $annee = $tab_d[2];

            $hr = date("G");
            $mint = date("i");
            $sec = date("s");

            if ($mois != "")
                $_REQUEST[$v] = $annee . "-" . $mois . "-" . $jour;
        }
    }

    doQuery("BEGIN");

    for ($i = 0; $i < sizeof($tab_table); $i++) {

        if ($tab_table[$i] == "produits") {
            $_REQUEST['prix_echange'] = $_REQUEST['prix_vente'] * $_REQUEST['pourcentage_echange'] / 100;
            $_REQUEST['qte_stock'] = 0;
        }

        $var[$i] = Ajout($tab_table[$i], getNomChamps($tab_table[$i]), $_REQUEST);
        $identif = mysql_insert_id();
        $_REQUEST['id_' . $tab_table[$i]] = mysql_insert_id();

        if (isset($_FILES['photo']) and getChamp($tab_table[$i], "image")) {
            $retour2 = upload_image($tab_table[$i], $_FILES['photo'], $identif);
            unset($_FILES);

            if ($retour2) {
                echo _UPLOAD_OK;
            } else {
                echo _UPLOAD_NOK;
            }
        }
    }

    for ($i = 0; $i < sizeof($var); $i++) {
        if (!$var[$i]) {
            doQuery("ROLLBACK");
            $m = 1;
        }
    }

    if ($m == 1)
        $msg_err = _AJOUT_NOK;
    else {
        $msg = _AJOUT_OK;



        doQuery("COMMIT");
    }

    if (isset($_REQUEST['msg_retour'])) {
        $msg = $_REQUEST['msg_retour'];
    }

    //if (isset($_REQUEST['parent'])) { $parent=$_REQUEST['parent']; }
    //redirect($page."?m=".$msg."&er=".$msg_err.$chaine_retour.$_REQUEST['ancre']);
}


//MODIFICATION
if ($action == "m") {
    $oldSalaire = "";
    if (isset($_REQUEST['table']) && !empty($_REQUEST['table'])) {
        if ($_REQUEST['table'] == "personnels") {
            $oldSalaire = $_REQUEST['TYPE'] == "Salarie" ? getValeurChamp('SALAIRE_MENSUELLE', 'personnels', 'ID', $_REQUEST['personnels']) : "";
        }
    }

    if (isset($_REQUEST['id_nom'])) {
        $id_nom = $_REQUEST['id_nom'];
    } else {
        $id_nom = 'ID';
    }

    //Rendre les dates du format 11-30-2009 => 1235543267654
    $tab_dates = array("date",
        "date_cheque",
        "date_reglement",
        "date_achat",
        "date_vente",
        "date_echange",
        "date_paiment",
        "date_facture"
    );

    foreach ($tab_dates as $v) {
        if (isset($_REQUEST[$v])) {
            $tab_d = explode("/", $_REQUEST[$v]);

            $jour = $tab_d[0];
            $mois = $tab_d[1];
            $annee = $tab_d[2];

            $hr = date("G");
            $mint = date("i");
            $sec = date("s");

            if ($mois != "")
                $_REQUEST[$v] = $annee . "-" . $mois . "-" . $jour;
        }
    }

    doQuery("BEGIN");

    for ($i = 0; $i < sizeof($tab_table); $i++) {
        $var[$i] = Modification($tab_table[$i], getNomChamps($tab_table[$i]), $_REQUEST, $id_nom, $id_valeur);

        if (isset($_FILES['photo'])) {
            $retour2 = upload_image($tab_table[$i], $_FILES['photo'], $id_valeur);
            unset($_FILES);

            if ($retour2) {
                echo _UPLOAD_OK;
            } else {
                echo _UPLOAD_NOK;
            }
        }

        $id_nom = 'id_' . $tab_table[$i];
    }

    if (isset($_REQUEST['table_modification'])) {
        $table_modification = $_REQUEST['table_modification'];
        Ajout($table_modification, getNomChamps($table_modification), $_REQUEST);
    }

    for ($i = 0; $i < sizeof($var); $i++) {
        if (!$var[$i]) {
            doQuery("ROLLBACK");
            $m = 1;
        }
    }

    if ($m == 1)
        $msg_err = _MODIFICATION_NOK;
    else {
        $msg = _MODIFICATION_OK;
        doQuery("COMMIT");
        if (isset($_REQUEST['table']) && !empty($_REQUEST['table'])) {
            if ($_REQUEST['table'] == "personnels") {
                if (($oldSalaire != $_REQUEST['SALAIRE_MENSUELLE'] && $_REQUEST['TYPE'] == "Salarie") || ($oldSalaire != $_REQUEST['TARIF_JOURNALIERS'] && $_REQUEST['TYPE'] == "Ouvrier")) {
                    $nouveauSalaire = $_REQUEST['TYPE'] == "Salarie" ? $_REQUEST['SALAIRE_MENSUELLE'] : $_REQUEST['TARIF_JOURNALIERS'];
                    $req = "INSERT INTO `historique_salaire`(`ID_PERSONNEL`, `NOUEAU_SALAIRE`, `DATE_CHANGEMENT`) VALUES ('" . $_REQUEST['id_valeurs_retour'] . "', '" . $nouveauSalaire . "','" . date("Y-m-d") . "')";
                    doQuery($req);
                    doQuery('COMMIT');
                }
            }
        }
    }
}

//SUPPRESSION
if ($action == "s") {
    //Pour la suppression physiques des fichiers
    if ($table == "mi_messages_pieces_jointes") {
        $fichier = 'files/' . getValeurChamp('fichier', 'mi_messages_pieces_jointes', 'id', $id_valeur);
    }

    $retour1 = Suppression($table, $id_valeur);

    if ($table == "mi_messages_pieces_jointes") {
        unlink($fichier);
    }

    if ($retour1) {
        $msg = _SUPPRESSION_OK;
    } else
        $msg_err = _SUPPRESSION_NOK;
}


//DEVLOPPEZ PAR ACHAF GESTION DES PESONNELS
//VOIR L'ACTION a et m j'ai ajouter date_achat dans les liste des dates
if ($action == 'conexion') {
    $login = $_POST['login'];
    $password = md5($_POST['password']);
    $sql = "select * from users where login='" . $login . "' and password='" . $password . "'";
    $res = doQuery($sql);
    $nbr = mysql_num_rows($res);
    if ($nbr == 1) {
        $ligne = mysql_fetch_array($res) or die(mysql_error());
        $_SESSION['admin'] = "SOMLAKO";
        $_SESSION['user'] = $ligne['ID'];
        $_SESSION['user-cnx'] = $ligne['PRENOM'] . " " . $ligne['NOM'];
        $_SESSION['email-cnx'] = $ligne['EMAIL'];
        redirect("index.php");
    }
}

//AJOUT
if ($action == "exporter_database") {
    exportDatabase();
   // envoi_mail("a.mareshal@gmail.com", "database somlako", "Exportation de la base de donne somlako", "database.php");
    $msg = "Votre base de données est sauvgardés avec succes";
}

if ($action == "importer_database") {
    exportDatabase();
    $msg = importerDatabase($_REQUEST['files']);
    redirect($page . "?m=" . $msg);
}

if ($action == "addPersonnelChantier") {
    $personnels = isset($_REQUEST['personnels']) && !empty($_REQUEST['personnels']) ? $_REQUEST['personnels'] : '';
    $chantiers = isset($_REQUEST['chantiers']) && !empty($_REQUEST['chantiers']) ? $_REQUEST['chantiers'] : '';
    $marches = isset($_REQUEST['marches']) && !empty($_REQUEST['marches']) ? $_REQUEST['marches'] : '';

    $req = "INSERT INTO `personnels_chantiers`(`ID_PERSONNELS`, `ID_CHNATIERS`, `DATE_AFFECTATION`) VALUES (" . $personnels . "," . $chantiers . ",'" . date('Y-m-d') . "')";
    doQuery($req);
    doQuery('COMMIT');
    redirect('ajouter_personnels_chantiers.php?marches=' . $marches . '&chantiers=' . $chantiers . '&m=Ajout est effectue avec succes');
}

if ($action == "removePersonnelChantier") {
    $personnels = isset($_REQUEST['personnels']) && !empty($_REQUEST['personnels']) ? $_REQUEST['personnels'] : '';
    $chantiers = isset($_REQUEST['chantiers']) && !empty($_REQUEST['chantiers']) ? $_REQUEST['chantiers'] : '';
    $marches = isset($_REQUEST['marches']) && !empty($_REQUEST['marches']) ? $_REQUEST['marches'] : '';
    $date =  isset($_REQUEST['DATE_SORTIE']) && !empty($_REQUEST['DATE_SORTIE']) ? $_REQUEST['DATE_SORTIE'] : '';
    $ID = isset($_REQUEST['ID']) && !empty($_REQUEST['ID']) ? $_REQUEST['ID'] : '';

    $req = "update personnels_chantiers set DATE_SORTIE='" . $date . "' where ID = " . $ID;
    doQuery($req);
    doQuery('COMMIT');
    redirect('personnels_chantiers.php?marches=' . $marches . '&chantiers=' . $chantiers . '&m=Ouvrier a quitter le chantier');
}

if ($action == "addPersonnelChantier") {
    $chantiers = isset($_REQUEST['chantiers']) && !empty($_REQUEST['chantiers']) ? $_REQUEST['chantiers'] : '';
    $personnels = isset($_REQUEST['personnels']) && !empty($_REQUEST['personnels']) ? $_REQUEST['personnels'] : '';
    $date =  isset($_REQUEST['DATE_START']) && !empty($_REQUEST['DATE_START']) ? $_REQUEST['DATE_START'] : '';
    

    $req = "INSERT INTO `personnels_chantiers` (`ID_PERSONNELS`, `ID_CHNATIERS`, `DATE_AFFECTATION`) 
    VALUES (" . $personnels . "," . $chantiers . ",'" . $date . "')";
    ;
    doQuery($req);
    doQuery('COMMIT');
    redirect('personnels.php?m=Salarie ou ouvrier est archivé avec succes');
}

if ($action == "ajouter_avance") {

    $nb = isset($_REQUEST['nb_personnage']) && !empty($_REQUEST['nb_personnage']) ? $_REQUEST['nb_personnage'] : 0;
    for ($i = 0; $i < $nb; $i++) {
        $idPersonne = isset($_REQUEST['id_' . $i]) && !empty($_REQUEST['id_' . $i]) ? $_REQUEST['id_' . $i] : 0;
        $datePaiement = date("Y-m-d");
        $montant = isset($_REQUEST['avance_' . $i]) && !empty($_REQUEST['avance_' . $i]) ? $_REQUEST['avance_' . $i] : 0;
        $type = isset($_REQUEST['type_' . $i]) && !empty($_REQUEST['type_' . $i]) ? $_REQUEST['type_' . $i] : 0;
        if ($montant > 0) {
            $req = "INSERT INTO `avances`(`ID_PERSONNELS`, `DATE_EMPREINTE`, `MONTANT`,`type`) VALUES(" . $idPersonne . ",'" . $datePaiement . "'," . $montant . ",'" . $type . "')";
            doQuery($req);
            doQuery('COMMIT');
        }
    }

    redirect("ajouter_avance.php?m=Ajout d'empreint pour : " . $_REQUEST['txtrechercher'] . " est validé");
}

if ($action == "ajouter_paiement") {
    $start = isset($_REQUEST['DATE_POINTAGE_START']) && !empty($_REQUEST['DATE_POINTAGE_START']) ? $_REQUEST['DATE_POINTAGE_START'] : "";
    $end = isset($_REQUEST['DATE_POINTAGE_END']) && !empty($_REQUEST['DATE_POINTAGE_END']) ? $_REQUEST['DATE_POINTAGE_END'] : "";
    $datePaiement = date("Y-m-d");
    $sql=  isset($_REQUEST['query']) && !empty($_REQUEST['query']) ? $_REQUEST['query'] : "";
    $res = doQuery($sql);

    $nb = mysql_num_rows($res);
    if ($nb == 0) {
        echo _VIDE;
    } else {
        while ($ligne = mysql_fetch_array($res)) {
            $idPersonne = $ligne["ID"];
            $sommeHeurN = getSommeHeurN($idPersonne, $start, $end);
            $sommeHeurS = getSommeHeurS($idPersonne, $start, $end);
            $datePointageStart = $start;
            $datePointageEnd = $end;
            $montant = getMontant($idPersonne, $start, $end);
            $credit = getSommeCredit($idPersonne, $start, $end);
            $avance = getSommeAvance($idPersonne, $start, $end);
            $netapayer = getNetAPayer($idPersonne, $start, $end);
            $req = "INSERT INTO `paiements`( `ID_PERSONNELS`, `DATE_PAIEMENT`, `SOMME_HEUR_N`, `SOMME_HEUR_S`, 
                `DATE_POINTAGE_START`, `DATE_POINTAGE_END`, `MONTANT`, `AVANCE`, `CREDIT`, `NETAPAYER`) 
VALUES(" . $idPersonne . ",'" . $datePaiement . "'," . $sommeHeurN . "," . $sommeHeurS . ",'" . $datePointageStart . "','" . $datePointageEnd . "'," . $montant . "," . $avance . "," . $credit . "," . $netapayer . ")";
            doQuery($req);
            doQuery('COMMIT');            
        }
    }
    genererFichPaiement($start,$end,$datePaiement,$sql);
    redirect("ajouter_paiement.php?dateDebut=" . $_REQUEST['dateDebut'] . "&dateFin=" . $_REQUEST['dateFin'] . "&m=Ajout du paiement de " . $_REQUEST['txtrechercher'] . " est validé");
}

if ($action == "ajouter_pointage") {
    $nb = isset($_REQUEST['nb_personnage']) && !empty($_REQUEST['nb_personnage']) ? $_REQUEST['nb_personnage'] : 0;
    $date_pointage = isset($_REQUEST['DATE_POINTAGE']) && !empty($_REQUEST['DATE_POINTAGE']) ? $_REQUEST['DATE_POINTAGE'] : "";
    $idChantier = isset($_REQUEST['ID_CHANTIER']) && !empty($_REQUEST['ID_CHANTIER']) ? $_REQUEST['ID_CHANTIER'] : "";
    $idMarche = isset($_REQUEST['ID_MARCHE']) && !empty($_REQUEST['ID_MARCHE']) ? $_REQUEST['ID_MARCHE'] : "";
    $admin = isset($_REQUEST['ADMINISTRATION']) && !empty($_REQUEST['ADMINISTRATION']) ? $_REQUEST['ADMINISTRATION'] : "";
    if (empty($idChantier) && empty($idMarche) && empty($admin)) {
        redirect("ajouter_pointage.php?m=veuillez choisir un chantier ou un marche");
    } else {
        for ($i = 0; $i < $nb; $i++) {
            $id = isset($_REQUEST['id_' . $i]) && !empty($_REQUEST['id_' . $i]) ? $_REQUEST['id_' . $i] : "";
            $heurN = isset($_REQUEST['HEUR_N_' . $i]) ? $_REQUEST['HEUR_N_' . $i] : "";
            $heurS = isset($_REQUEST['HEUR_S_' . $i]) ? $_REQUEST['HEUR_S_' . $i] : "";
            $idChantier = getIdChantier($id, $idChantier, $idMarche);
            (!empty($heurN) && $heurN > 0) || (!empty($heurS) && $heurS > 0);
            if ((!empty($heurN) && $heurN > 0) || (!empty($heurS) && $heurS > 0)) {
                $req = "INSERT INTO `pointages`(`ID_PERSONNELS`, `DATE_POINTAGE`, `HEUR_N`, `HEUR_S`, `ID_CHANTIER`) VALUES (" . $id . ",'" . $date_pointage . "'," . $heurN . "," . $heurS . "," . $idChantier . ")";
                doQuery($req);
                doQuery('COMMIT');
                $msg = "L'Ajout a été effectue avec success";
            }
        }
    }
}

if ($action == "archiver_personnel") {
    $personnels = isset($_REQUEST['personnels']) && !empty($_REQUEST['personnels']) ? $_REQUEST['personnels'] : '';

    $req = "update personnels set status = 0 where ID = " . $personnels;
    doQuery($req);
    doQuery('COMMIT');
    redirect('personnels.php?m=Salarie ou ouvrier est archivé avec succes');
}

if ($action == "desarchiver_personnel") {
    $personnels = isset($_REQUEST['personnels']) && !empty($_REQUEST['personnels']) ? $_REQUEST['personnels'] : '';

    $req = "update personnels set status = 1 where ID = " . $personnels;
    doQuery($req);
    doQuery('COMMIT');
    redirect('personnels_archiver.php?m=Salarie ou ouvrier est desarchivé avec succes');
}

if ($action == "update_profil") {
    $NOM = isset($_REQUEST['NOM']) && !empty($_REQUEST['NOM']) ? $_REQUEST['NOM'] : '';
    $PRENOM = isset($_REQUEST['PRENOM']) && !empty($_REQUEST['PRENOM']) ? $_REQUEST['PRENOM'] : '';
    $EMAIL = isset($_REQUEST['EMAIL']) && !empty($_REQUEST['EMAIL']) ? $_REQUEST['EMAIL'] : '';
    $PASSWORD = isset($_REQUEST['PASSWORD']) && !empty($_REQUEST['PASSWORD']) ? $_REQUEST['PASSWORD'] : '';
    $CONFIRMER_PASSWORD = isset($_REQUEST['CONFIRMER_PASSWORD']) && !empty($_REQUEST['CONFIRMER_PASSWORD']) ? $_REQUEST['CONFIRMER_PASSWORD'] : '';
    if (!empty($PASSWORD)) {
        if ($PASSWORD == $CONFIRMER_PASSWORD) {
            $req = "update users set NOM='" . $NOM . "',PRENOM='" . $PRENOM . "',EMAIL='" . $EMAIL . "',PASSWORD='" . md5($PASSWORD) . "'";
        } else {
            redirect('profil.php?m=Les deux mot de pass ne sont pas identique');
        }
    } else {
        $req = "update users set NOM='" . $NOM . "',PRENOM='" . $PRENOM . "',EMAIL='" . $EMAIL . "'";
    }
    doQuery($req);
    doQuery('COMMIT');
    redirect('index.php?m=mise a jour du profil est reussie');
}

if (!isset($msg_err)) {
    $msg_err = "";
}
redirect($page . "?" . $chaine_retour . "&m=" . $msg . "&er=" . $msg_err . "#ancre");
?>	