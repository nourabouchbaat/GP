<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Paiement des personnels et des ouvriers";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Paiement";
$_SESSION['breadcrumb_nav3'] = "Ajout des paiements";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "paiements.php";
$_SESSION['link_nav3'] = "ajouter_paiement.php";
$_SESSION['link_nav4'] = "";
$_SESSION['breadcrumb_nav4'] = "";
?>
<?php require_once('menu.php'); ?>
<div id="page-inner"> 
    <div class="row">
        <div class="col-12">
            <?php if (isset($_REQUEST['m'])) { ?>
                <div class="alert alert-info">
                    <?php echo $_REQUEST['m'] ?>
                    <a href="#" data-dismiss="alert" class="close">x</a>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <form name="frm1" action="" method="post" >
                            <div class="col-lg-4">	
                                <div class="form-group">
                                    <label class="control-label"><?php echo _RECHERCHER ?>:</label>
                                    <div class="controls">
                                        <input type="text" name="txtrechercher" value="<?php if (isset($_REQUEST['txtrechercher'])) echo $_REQUEST['txtrechercher']; ?>" class="form-control input-small-recherche" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php $checked = isset($_REQUEST['admin']) && !empty($_REQUEST['admin']) ? "checked='true'" : "" ?>
                                    <label class="control-label"><input type="checkbox" name="admin"  onchange="this.form.submit()" <?php echo $checked ?>/>&nbsp;&nbsp;Pointage d'administration:

                                    </label>
                                </div>

                            </div>
                            <div class="col-lg-4">	
                                <div class="form-group">
                                    <?php
                                    $marches = isset($_REQUEST['marches']) && !empty($_REQUEST['marches']) ? $_REQUEST['marches'] : "";
                                    $change = "onchange='this.form.submit()'";
                                    ?>
                                    <label class="control-label"  style="width:100% !important">Marché <?php echo getTableList('marches', 'marches', $marches, 'NUM_MARCHE', $change, $where, $libelle) ?>

                                    </label>
                                </div>
                                <div class="form-group">
                                    <?php
                                    $chantiers = isset($_REQUEST['chantiers']) && !empty($_REQUEST['chantiers']) ? $_REQUEST['chantiers'] : "";
                                    $change2 = "onchange='this.form.submit()'";
                                    $whereCh = $marches != "" ? " where ID_MARCHE=" . $_REQUEST['marches'] : "";
                                    ?>
                                    <label class="control-label" style="width:100% !important">Chantier <?php echo getTableList('chantiers', 'chantiers', $chantiers, 'CODE', $change2, $whereCh, $libelle) ?></label>
                                </div>
                            </div>
                            <div class="col-lg-4">	
                                <div class="form-group">
                                    <label class="control-label">Date pointage entre:</label>
                                    <div class="controls">
                                        <input type="date" id="cal_required" name="dateDebut"  value="<?php if (isset($_REQUEST['dateDebut'])) echo $_REQUEST['dateDebut']; ?>" class="form-control input-small" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Et :</label>
                                    <div class="controls">
                                        <input type="date" id="cal_required" name="dateFin"  value="<?php if (isset($_REQUEST['dateFin'])) echo $_REQUEST['dateFin']; ?>"   class="form-control input-small" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-actions">
                                    <input type="submit" name="v" class="btn btn-primary" value="<?php echo _RECHERCHE . "r" ?>" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>						
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Advanced Tables -->
            <div class="panel panel-default">

                <div class="panel-body">

                    <div class="widget-content nopadding">
                        <?php
                        $where1 = "";
                        $dateFull = 0;
                        if (isset($_REQUEST['txtrechercher']) and ! empty($_REQUEST['txtrechercher']))
                            $where1 .= "and (NOM like '%" . $_REQUEST['txtrechercher'] . "%' or PRENOM like '%" . $_REQUEST['txtrechercher'] . "%' or CIN like '%" . $_REQUEST['txtrechercher'] . "%' or TELEPHONE like '%" . $_REQUEST['txtrechercher'] . "%' or CNSS like '%" . $_REQUEST['txtrechercher'] . "%' or RIB like '%" . $_REQUEST['txtrechercher'] . "%' or 	DATE_EMBAUCHE like '%" . $_REQUEST['txtrechercher'] . "%' or CODE like '%" . $_REQUEST['txtrechercher'] . "%' or ADRESSE like '%" . $_REQUEST['txtrechercher'] . "%') ";
                        if (isset($_REQUEST['dateDebut']) && !empty($_REQUEST['dateDebut'])) {
                            $where1 .= " and ID IN (SELECT ID_PERSONNELS FROM pointages where DATE_POINTAGE >= DATE_FORMAT('" . $_REQUEST['dateDebut'] . "', '%Y-%m-%d'))";
                            $dateFull = $dateFull + 1;
                        }

                        if (isset($_REQUEST['dateFin']) && !empty($_REQUEST['dateFin'])) {
                            $where1 .= " and ID IN (SELECT ID_PERSONNELS FROM pointages where DATE_POINTAGE < DATE_FORMAT('" . $_REQUEST['dateFin'] . "', '%Y-%m-%d'))";
                            $dateFull = $dateFull + 1;
                        }

                        if (isset($_REQUEST['chantiers']) && !empty($_REQUEST['chantiers']))
                            $where1 .= " and ID IN (SELECT ID_PERSONNELS FROM personnels_chantiers where ID_CHNATIERS=" . $_REQUEST['chantiers'] . ")";
                        else if (isset($_REQUEST['marches']) && !empty($_REQUEST['marches']))
                            $where1 .= " and ID IN (SELECT ID_PERSONNELS FROM personnels_chantiers where ID_CHNATIERS IN(select ID from chantiers where ID_MARCHE=" . $_REQUEST['marches'] . "))";


                        if (isset($_REQUEST['admin']) && !empty($_REQUEST['admin'])) {
                            $where1 .= " and admin=1";
                        }

                        echo $sql = "select * from personnels where STATUS=1 " . $where1 . " order by ID";
                        $res = doQuery($sql);

                        $nb = mysql_num_rows($res);
                        if ($nb == 0) {
                            echo _VIDE;
                        } else {
                            ?>
                            <form action="gestion.php" name="frm" method="post" 
                                  onsubmit="return checkForm(document.frm);" >
                                <input type="hidden" name="act" value="ajouter_paiement"/>
                                <input type="hidden" name="DATE_POINTAGE_START" value="<?php echo $_REQUEST['dateStart'] ?>"/>
                                <input type="hidden" name="DATE_POINTAGE_END" value="<?php echo $_REQUEST['dateFin'] ?>"/>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <thead>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Somme heurs N</th>
                                    <th>Somme Heurs S</th>
                                    <th>Tarif journaliere / Salaire</th>
                                    <th>Montant</th>
                                    <th>Avance</th>
                                    <th>Credit</th>
                                    <th>Net à payer</th>
                                    <th>Valider</th>

                                    </thead>	
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        while ($ligne = mysql_fetch_array($res)) {

                                            if ($i % 2 == 0)
                                                $c = "c";
                                            else
                                                $c = "";
                                            ?>
                                            <?php
                                            if ($dateFull != 2) {
                                                $dis = "class='disable'";
                                                $title = "Veuillez choisir l'interballe des date de pointage";
                                            } else {
                                                $title = "Valider le paiement";
                                            }
                                            ?>
                                            <tr class="<?php echo $c ?>">
                                        <input type="hidden" name="id_<?php echo $i ?>" value="<?php echo $ligne['ID'] ?>"/>
                                        <td><?php echo $ligne['CODE'] ?></td>
                                        <td><?php echo $ligne['NOM'] . " " . $ligne['PRENOM'] ?></td>
                                        <td><?php echo getSommeHeurN($ligne['ID'], $_REQUEST['dateDebut'], $_REQUEST['dateFin']) ?></td>
                                        <td><?php echo getSommeHeurS($ligne['ID'], $_REQUEST['dateDebut'], $_REQUEST['dateFin']) ?></td>
                                        <td><?php echo $ligne['TYPE'] == "Salarie" ? $ligne['SALAIRE_MENSUELLE'] : $ligne['TARIF_JOURNALIERS'] ?></td>
                                        <td><?php echo getMontant($ligne['ID'], $_REQUEST['dateDebut'], $_REQUEST['dateFin']) ?></td>
                                        <td><?php echo getSommeAvance($ligne['ID'], $_REQUEST['dateDebut'], $_REQUEST['dateFin']) ?></td>
                                        <td><?php echo getSommeCredit($ligne['ID'], $_REQUEST['dateDebut'], $_REQUEST['dateFin']) ?></td>
                                        <td><?php echo getNetAPayer($ligne['ID'], $_REQUEST['dateDebut'], $_REQUEST['dateFin']) ?></td>
                                        <td <?php echo $dis ?>>
                                            <a href="gestion.php?act=valider_paiement&personnels=<?php echo $ligne['ID'] ?>&dateDebut=<?php echo $_REQUEST['dateDebut'] ?>&dateFin=<?php echo $_REQUEST['dateFin'] ?>"
                                               class="supprimer2"
                                               title="<?php echo $title ?>">
                                                <i class="glyphicon glyphicon-ok"></i> 
                                            </a>
                                        </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php
                            } //Fin If
                            ?>
                    </div>
                </div>
            </div>
            <!--End Advanced Tables -->
        </div>
    </div>
</div>
<!-- /. PAGE INNER  -->			 
<?php // }   ?>
<?php require_once('foot.php'); ?>