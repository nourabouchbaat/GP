<?php require_once('head.php'); ?>
<?php
$_SESSION['titre'] = "Gestion des version de base de donné";
$_SESSION['breadcrumb_nav1'] = "Accueil";
$_SESSION['breadcrumb_nav2'] = "Base de donné";
$_SESSION['breadcrumb_nav3'] = "";
$_SESSION['link_nav1'] = "index.php";
$_SESSION['link_nav2'] = "database.php";
$_SESSION['link_nav3'] = "";
$_SESSION['link_nav4'] = "";
$_SESSION['breadcrumb_nav4'] = "";
?>
<?php require_once('menu.php'); ?>

<div id="page-inner"> 
    <div class="row">
        <div class="col-lg-12">
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
                       	<form action="gestion.php" name="frm" method="post" 
                              onsubmit="return checkForm(document.frm);" >
                            <input type="hidden" name="act" value="exporter_database"/>
                            <input type="hidden" name="page" value="database.php"/>
                            <div class="col-lg-12">	
                                <div class="form-group">
                                    <label class="control-label">Exporter la version actuelle de la base de donneé</label>						
                                    <div class="form-actions">
                                        <input type="submit" name="v" class="btn btn-primary" value="<?php echo "Exporter" ?>" />								
                                    </div>
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
                    <div class="table-responsive">

                        <?php
                        $files = scandir("backup");
                        ?>								
                        <table class="table table-striped table-bordered table-hover" >
                            <tr>
                                <th>Archives</th>
                                <th>Valider</th>
                            </tr>
                            <?php
                            for ($i = 2; $i < count($files); $i++) {
                                ?>

                                <tr>
                                    <td><?php echo getDateExport($files[$i]); ?></td>
                                    <td><a href="gestion.php?act=importer_database&page=database.php&files=backup/<?php echo $files[$i] ?>">Importer</a></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php require_once('foot.php'); ?>