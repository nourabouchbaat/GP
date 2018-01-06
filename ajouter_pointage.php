<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Pointage des personnels";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Pointage";
$_SESSION['breadcrumb_nav3'] = "Ajout des pointages";
$_SESSION['link_nav4'] = "";
$_SESSION['breadcrumb_nav4'] = "";

$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "pointages.php";
$_SESSION['link_nav3'] = "ajouter_pointage.php";
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
                            <div class="col-lg-3">	
                                <div class="form-group">
                                    <?php $checked = isset($_REQUEST['admin']) && !empty($_REQUEST['admin']) ? "checked='true'" : "" ?>
                                    <label class="control-label"><input type="checkbox" name="admin"  onchange="this.form.submit()" <?php echo $checked ?>/>&nbsp;&nbsp;Pointage d'administration:

                                    </label>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" name="v" class="btn btn-primary" value="<?php echo _RECHERCHE . "r" ?>" />
                                </div>
                            </div>
                            <div class="col-lg-3">	
                                <div class="form-group">
                                    <?php
                                    $marches = isset($_REQUEST['marches']) && !empty($_REQUEST['marches']) ? $_REQUEST['marches'] : "";
                                    $change = "onchange='this.form.submit()'";
                                    ?>
                                    <label class="control-label">Marché <?php echo getTableList('marches', 'marches', $marches, 'NUM_MARCHE', $change, $where, $libelle) ?>

                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3">	
                                <div class="form-group">
                                    <?php
                                    $chantiers = isset($_REQUEST['chantiers']) && !empty($_REQUEST['chantiers']) ? $_REQUEST['chantiers'] : "";
                                    $change2 = "onchange='this.form.submit()'";
                                    $whereCh = $marches != "" ? " where ID_MARCHE=" . $_REQUEST['marches'] : "";
                                    ?>
                                    <label class="control-label">Chantier <?php echo getTableList('chantiers', 'chantiers', $chantiers, 'CODE', $change2, $whereCh, $libelle) ?></label>
                                </div>
                            </div>
                            <div class="col-lg-3">	
                                <div class="form-group">
                                    <?php $date = isset($_REQUEST['date_pointage']) && !empty($_REQUEST['date_pointage']) ? $_REQUEST['date_pointage'] : date('Y-m-d'); ?>
                                    <label class="control-label">Date pointage <input type="date"  onchange="this.form.submit()"
                                                                                      name="date_pointage"  class="form-control input-small" value="<?php echo $date ?>" /></label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>						
            </div>
        </div>
    </div>

        <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <form name="frm1" action="" method="post" >
                            <table border="1" style="border:1px solid grey;width: 98%;margin: 10px">
                            	<tr>
                            		<th style="padding: 10px">Chantier  :</th>
                            		<td colspan="3"><?php echo !empty($chantiers)?getValeurChamp("CODE","chantiers","ID",$chantiers):""; ?></td>
                            	</tr>
                            	<tr>
                            		<th style="width:100px; padding: 10px">Chef chantier  :</th>
                            		<td style="width: 25%;">Nom<br><?php $ID_CHEF = !empty($chantiers)?getValeurChamp("ID_CHEF","chantiers","ID",$chantiers):"";echo !empty($ID_CHEF)?getValeurChamp("PRENOM","personnels","ID",$ID_CHEF)." ".getValeurChamp("NOM","personnels","ID",$ID_CHEF):""; ?></td>
                            		<td style="width: 25%;">Code<br><?php echo !empty($ID_CHEF)?getValeurChamp("CODE","personnels","ID",$ID_CHEF):""; ?></td>
                            		<td style="width:200px">Signature<br>&nbsp;</td>
                            	</tr>

                            	<tr>
                            		<th style="width:100px; padding: 10px">Responsable  :</th>
                            		<td style="width: 25%;">Nom<br><?php $ID_RESP = !empty($chantiers)?getValeurChamp("ID_RESP","chantiers","ID",$chantiers):"";echo !empty($ID_RESP)?getValeurChamp("PRENOM","personnels","ID",$ID_RESP)." ".getValeurChamp("NOM","personnels","ID",$ID_RESP):""; ?></td>
                            		<td style="width: 25%;">Code<br><?php echo !empty($ID_RESP)?getValeurChamp("CODE","personnels","ID",$ID_RESP):""; ?></td>
                            		<td style="width:200px">Signature<br>&nbsp;</td>
                            	</tr>
                            	<tr>
                            		<th style="padding: 10px">Date  :</th>
                            		<td colspan="3"><?php echo $date; ?></td>
                            	</tr>
                            </table>
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
                        if (isset($_POST['admin']) and ! empty($_REQUEST['admin']))
                            $where1 = "and admin=1";
                        else if (isset($_POST['chantiers']) and ! empty($_REQUEST['chantiers'])) {
                            $where1 = " and ID in (select ID_PERSONNELS from personnels_chantiers where ID_CHNATIERS=" . $_REQUEST['chantiers'] . ")";
                        } else if (isset($_POST['marches']) and ! empty($_REQUEST['marches'])) {
                            $where1 = " and ID in (select ID_PERSONNELS from personnels_chantiers where ID_CHNATIERS IN(select ID from chantiers where ID_MARCHE=" . $_REQUEST['marches'] . "))";
                        }


                        $sql = "select * from personnels where status=1 " . $where1 . " order by id";
                        $res = doQuery($sql);

                        $nb = mysql_num_rows($res);
                        if ($nb == 0) {
                            echo _VIDE;
                        } else {
                            ?>
                            <form action="gestion.php" name="frm" method="post" 
                                  onsubmit="return checkForm(document.frm);" >
                                <input type="hidden" name="act" value="ajouter_pointage"/>
                                <input type="hidden" name="table" value="pointages"/>
                                <input type="hidden" name="page" value="pointages.php"/>
                                <input type="hidden" name="DATE_POINTAGE" value="<?php echo $date ?>"/>
                                <input type="hidden" name="ID_CHANTIER" value="<?php echo $_REQUEST['chantiers'] ?>"/>
                                <input type="hidden" name="ID_MARCHE" value="<?php echo $_REQUEST['marches'] ?>"/>
                                <input type="hidden" name="ADMINISTRATION" value="<?php echo $_REQUEST['admin'] ?>"/>
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr> 
                                    <th rowspan="2">Code</th>
                                    <th rowspan="2">Nom</th>
                                    <th colspan="2">Matin</th>
                                    <th colspan="2">Soir</th>
                                    <th rowspan="2">TRAVAUX EFFECTUE + REMARQUE</th>
                                    
                                    </tr>
                                    <tr>
                                    <th>H.EN</th>
                                    <th>H.SOR</th>
                                    <th>H.EN</th>
                                    <th>H.SOR</th>
                                    </tr>
                                    </thead>	
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $nb = mysql_num_rows($res);
                                        while ($ligne = mysql_fetch_array($res)) {

                                            if ($i % 2 == 0)
                                                $c = "c";
                                            else
                                                $c = "";
                                            ?>
                                            <tr class="<?php echo $c ?>">
                                        <input type="hidden" name="id_<?php echo $i ?>" value="<?php echo $ligne['ID'] ?>"/>
                                        <td><?php echo $ligne['CODE'] ?></td>
                                        <td><?php echo $ligne['NOM'] . " " . $ligne['PRENOM'] ?></td>
                                        <td><input type="number" id="<?php echo "M_H_EN" ?>__required" name="M_H_EN_<?php echo $i ?>"  value="0" class="form-control input-small" style="width: 100px"/></td>
                                        <td><input type="number"  style="width: 100px" id="<?php echo "M_H_SOR" ?>__required" name="M_H_SOR_<?php echo $i ?>"  value="0"  class="form-control input-small"/></td>
                                        <td><input type="number"  style="width: 100px" id="<?php echo "S_H_EN" ?>__required" name="S_H_EN_<?php echo $i ?>"  value="0" class="form-control input-small"/></td>
                                        <td><input type="number"  style="width: 100px" id="<?php echo "S_H_SOR" ?>__required" name="S_H_SOR_<?php echo $i ?>"  value="0"  class="form-control input-small"/></td>
                                        <td><textarea class="form-control input-small" name="REMARQUE" style="width: 200px"></textarea></td>
                                        
                                        <input type="hidden" name="nb_personnage" value="<?php echo $nb ?>"/>
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
                            <div class="form-actions">
                                <input type="submit" class="btn btn-primary" value="<?php echo _VALIDER ?>" /> ou <a class="text-danger" href="personnels.php">Annuler</a>
                            </div>
                        </form>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
        <?php require_once('foot.php'); ?>