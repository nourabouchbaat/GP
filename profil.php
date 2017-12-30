
<?php require_once('head.php'); ?>
<?php 
	$_SESSION['titre'] ="Gestion de profil";
	$_SESSION['link_nav1'] ="index.php";
	$_SESSION['link_nav2'] ="profil.php";
	$_SESSION['link_nav3'] ="";
	$_SESSION['breadcrumb_nav1'] ="Accueil";
	$_SESSION['breadcrumb_nav2'] ="Profil";
	$_SESSION['breadcrumb_nav3'] ="";
	$_SESSION['link_nav4'] ="";
	$_SESSION['breadcrumb_nav4'] ="";
	$userId = $_SESSION['user'];
?>
<?php require_once('menu.php'); ?>	 
			<div id="page-inner"> 
							<div class="row">
				<div class="col-lg-12">
					<?php if(isset($_REQUEST['m'])) {?>
							<div class="alert alert-success">
								<?php echo $_REQUEST['m']?>
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
                            	<div class="col-lg-12">	
									<form action="gestion.php" name="frm" method="post" onsubmit="return checkForm(document.frm);" >
										<input type="hidden" name="act" value="update_profil"/>

										<div class="form-group">
											<label class="control-label"><?php echo "Nom" ?>  : </label>
											<input type="text" id="<?php echo "Nom" ?>__required" 
													name="NOM"  class="form-control" value="<?php echo getValeurChamp('NOM','users','ID',$userId); ?>"/>
									    </div>
									    <div class="form-group">
											<label class="control-label"><?php echo "Prenom" ?> : </label>
											<input type="text" id="<?php echo "Prenom" ?>__required" 
													name="PRENOM"  class="form-control" value="<?php echo getValeurChamp('PRENOM','users','ID',$userId); ?>"/>
									    </div>
									    <div class="form-group">
											<label class="control-label"><?php echo "Email" ?> : </label>
											<input type="text" id="<?php echo "Email" ?>__required" 
													name="EMAIL"  class="form-control" value="<?php echo getValeurChamp('EMAIL','users','ID',$userId); ?>"/>
									    </div>
									    <div class="form-group">
											<label class="control-label"><?php echo "Login" ?> : </label>
											<input type="text" id="<?php echo "Login" ?>__required" 
													name="LOGIN"  class="form-control" value="<?php echo getValeurChamp('LOGIN','users','ID',$userId); ?>"/>
									    </div>

									    <div class="form-group">
											<label class="control-label"><?php echo "Password" ?> : </label>
											<input type="password" id="<?php echo "Password" ?>__required" 
													name="PASSWORD"  class="form-control" />
									    </div>	

									    <div class="form-group">
											<label class="control-label"><?php echo "Confirmer Password" ?> : </label>
											<input type="password" id="<?php echo "CONFIRMER_PASSWORD" ?>__required" 
													name="CONFIRMER_PASSWORD"  class="form-control" />
									    </div>									    
									    <div class="form-group">
										<button type="submit" class="btn btn-default">
											<?php echo _MODIFIER ?>
										</button>
										ou <a class="text-danger" href="postes.php">Annuler</a>
                                            
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