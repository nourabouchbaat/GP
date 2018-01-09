
<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion des pointages";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Pointages";
$_SESSION['breadcrumb_nav3'] = "Modifier un pointage";

$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "pointages.php";
$_SESSION['link_nav3'] = "modifier_pointage.php?pointages=" . $_REQUEST['pointages'];
$_SESSION['link_nav4'] = "";
$_SESSION['breadcrumb_nav4'] = "";
?>
<?php require_once('menu.php'); ?>
<div id="page-inner"> 
    <div class="row">
        <form action="gestion.php" name="frm" method="post" 
            onsubmit="return checkForm(document.frm);" >
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-body">
                        <input type="hidden" name="act" value="m"/>
                        <input type="hidden" name="table" value="pointages"/>
                        <input type="hidden" name="page" value="pointages.php"/>

                        <input type="hidden" name="id_nom" value="ID"/>
                        <input type="hidden" name="id_valeur" value="<?php echo $_REQUEST['pointages'] ?>"/>	

                        <input type="hidden" name="id_noms_retour" value="pointages"/>
                        <input type="hidden" name="id_valeurs_retour" value="<?php echo $_REQUEST['pointages'] ?>"/>	


                        <div class="form-group">
                            <label class="control-label"><?php echo "Nom" ?> : </label>
                            <div class="controls">
                                <?php echo getValeurChamp('NOM', 'personnels', 'ID', getValeurChamp('ID_PERSONNELS', 'pointages', 'ID', $_REQUEST['pointages'])) ?> <?php echo getValeurChamp('PRENOM', 'personnels', 'ID', getValeurChamp('ID_PERSONNELS', 'pointages', 'ID', $_REQUEST['pointages'])) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label"><?php echo "Code" ?> : </label>
                            <div class="controls">
                                <?php echo getValeurChamp('CODE', 'personnels', 'ID', getValeurChamp('ID_PERSONNELS', 'pointages', 'ID', $_REQUEST['pointages'])) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Code marche : </label>
                            <?php echo getValeurChamp('NUM_MARCHE', 'marches', 'ID', getValeurChamp('ID_MARCHE', 'pointages', 'ID', $_REQUEST['pointages'])) ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Code chantier : </label>
                            <?php echo getValeurChamp('CODE', 'chantiers', 'ID', getValeurChamp('ID_CHANTIER', 'pointages', 'ID', $_REQUEST['pointages'])) ?>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php echo "Date pointage" ?> : </label>
                            <div class="controls">
                                <input type="date" id="cal_required"  class="form-control input-small"  value="<?php echo getValeurChamp('DATE_POINTAGE', 'pointages', 'ID', $_REQUEST['pointages']); ?>"
                                       name="DATE_POINTAGE"  />

                            </div>
                        </div>
                    </div>
                        </div>
                    </div>


                        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label"><?php echo "Matin : Heur d'entrée" ?> : </label>
                            <div class="controls">
                                <input type="number" id="<?php echo "M_H_EN" ?>_required"  value="<?php echo getValeurChamp('M_H_EN', 'pointages', 'ID', $_REQUEST['pointages']); ?>"
                                       name="M_H_EN"  class="form-control input-small"/>
                            </div>
                        </div>
            </div>
            <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label"><?php echo "Matin : Heur de sortie" ?> : </label>
                            <div class="controls">
                                <input type="number" id="<?php echo "M_H_SOR" ?>_required"  value="<?php echo getValeurChamp('M_H_SOR', 'pointages', 'ID', $_REQUEST['pointages']); ?>"
                                       name="M_H_SOR"  class="form-control input-small" />
                            </div>
                        </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
            
                        <div class="form-group">
                            <label class="control-label"><?php echo "Soir : Heur d'entrée" ?> : </label>
                            <div class="controls">
                                <input type="number" id="<?php echo "S_H_EN" ?>_required"  value="<?php echo getValeurChamp('S_H_EN', 'pointages', 'ID', $_REQUEST['pointages']); ?>"
                                       name="S_H_EN"  class="form-control input-small"/>
                            </div>
                        </div>
            </div>
            <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label"><?php echo "Soir : Heur de sortie" ?> : </label>
                            <div class="controls">
                                <input type="number" id="<?php echo "S_H_SOR" ?>_required"  value="<?php echo getValeurChamp('S_H_SOR', 'pointages', 'ID', $_REQUEST['pointages']); ?>"
                                       name="S_H_SOR"  class="form-control input-small" />
                            </div>
                        </div>
            </div>
        </div>
                        <div class="form-group">
                            <label class="control-label">Remarque : </label>
                            <div class="controls">
                                <textarea style="width:100%" rows="3" name="REMARQUE"  id="<?php echo "REMARQUE" ?>_required" ><?php echo getValeurChamp('REMARQUE', 'pointages', 'ID', $_REQUEST['pointages']); ?></textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" class="btn btn-primary" value="<?php echo _MODIFIER ?>" /> ou <a class="text-danger" href="pointages.php">Annuler</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>	

<?php require_once('foot.php'); ?>