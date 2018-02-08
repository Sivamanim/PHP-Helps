<!Doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>On Time</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,700i,800,800i" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/app.css" rel="stylesheet" type="text/css" />
</head>
<body class="installer-page">
	<div id="main-wrapper">
		<div class="content">
			<div class="installer-wrap">
				<div class="container">
					<div class="installer-box">
						<div class="row">
							<div class="col-sm-12">
								<h4 class="installer-title">Thanks for purchasing On Time</h4>
							</div>
						</div>
						<?php
							 $hostname   = "";
							$db_username = "";
							$db_password = "";
							$db_name     = "";
							if(!isset($_GET['step']))
							{
								$_GET['step'] = 1;
							}
							$error_message = $this->session->userdata('error_message');    
							if($error_message!='') 
							   { ?>
							<div class="alert alert-danger">
								<strong>Info!</strong> <?php echo $error_message; ?> .
							</div>
							<?php 
							$hostname       = $this->session->userdata("hostname");
							$db_username    = $this->session->userdata("db_username");
							$db_password    = $this->session->userdata("db_password");
							$database_name  = $this->session->userdata("db_name");
							$this->session->set_userdata(array('error_message'=>'','hostname'=>'','db_username'=>'','db_password'=>'','db_name'=>''));
							} ?>
						<div id="installerwizard" class="wizard-box">
							<ul  class="bwizard-steps">
								<li class="<?php if($_GET['step']==1){ echo"active"; }?>"> <?php if($_GET['step']==1) { ?> <a href="#" data-toggle="tab" aria-expanded="true"> <?php } ?>  <span class="label">1</span> System Settings <?php if($_GET['step']==1) { ?></a> <?php } ?>  </li>
								<li class="<?php if($_GET['step']==2){ echo"active"; }?>"> <?php if($_GET['step']==2) { ?> <a href="#" data-toggle="tab" aria-expanded="true"> <?php } ?>  <span class="label">2</span> Database Settings<?php if($_GET['step']==2) { ?></a> <?php } ?> </li>								
								<li class="<?php if($_GET['step']==3){ echo"active"; }?>"> <?php if($_GET['step']==3) { ?> <a href="#" data-toggle="tab" aria-expanded="true"> <?php } ?>  <span class="label">3</span> Basic Settings <?php if($_GET['step']==3) { ?> </a> <?php } ?> </li>
								
							</ul>
							<div class="tab-content">
											<?php
												$step1_completed = $this->session->userdata('step1_completed');
											if($step1_completed=="true") { ?>
                                            <div class="tab-pane wizard-content  <?php if($_GET['step']==1) { ?> active <?php } ?>" id="system_check">
                                                  <form class="form-horizontal" id="database_settings" role="form" action="<?php echo base_url()."installer/move_next" ?>" method="post" autocomplete="off">                 
                                                            <?php
                                                        $config_file = "./application/config/config.php";
                                                        $database_file = "./application/config/database.php";
                                                        $autoload_file = "./application/config/autoload.php";
                                                        $route_file = "./application/config/routes.php";
                                                        $htaccess_file = ".htaccess";
                                                        $error = FALSE;
                                                        $php_version_compatibility = TRUE ; 
                                                        if(phpversion() > "7.2"){ $error = TRUE; $php_version_compatibility = FALSE;
                                                        echo "<div class='alert alert-danger'>Your PHP version is ".phpversion()."! PHP 7.2 or Lower required!</div>"; }else{
                                                        echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> You are running PHP ".phpversion()."</div>";
                                                        }


                                                        if(!extension_loaded('mysqli')){$error = TRUE; echo "<div class='alert alert-danger'>Mysqli PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Mysqli PHP extension loaded!</div>";}
                                                        if(!is_writeable($database_file)){$error = TRUE; echo "<div class='alert alert-danger'>Database File (application/config/database.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Database file is writeable!</div>";}
                                                        if(!is_writeable($config_file)){$error = TRUE; echo "<div class='alert alert-danger'>Config File (application/config/config.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Config file is writeable!</div>";}
                                                        if(!is_writeable($route_file)){$error = TRUE; echo "<div class='alert alert-danger'>Route File (application/config/routes.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Routes file is writeable!</div>";}
                                                        if(!is_writeable("./assets/temp_files")){echo "<div class='alert alert-danger'><i class='fa fa-times'></i> /resource/tmp folder is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> /assets/temp_files folder is writeable!</div>";}

                                                        ?>    
                                                      <ul class="pager wizard">																		
                                    <?php if($php_version_compatibility) { ?>		<li class="next"><button type="submit">Next</button></li>   <?php } ?>
                                                      </ul>
                                                      </form>   
                                            </div>
                                            <?php } ?>     

								<div class="tab-pane wizard-content <?php if($_GET['step']==2) { ?> active <?php } ?>" id="db_settings">
								<?php $step2_completed = $this->session->userdata('step2_completed'); 								 
								if($step2_completed=="true") { ?>
								
									<form class="form-horizontal" id="database_settings" role="form" action="<?php echo base_url()."installer/db_installation" ?>" method="post" autocomplete="off"> 
										<div class="installer-inputs">																	
											<div class="form-group clearfix">
												<label class="col-sm-4 control-label">Database Host</label>
												<div class="col-sm-8">
													<input class="form-control" type="text" required="required" name="hostname" value="<?php if(isset($hostname)) { echo $hostname;  } ?>" >
												</div>
											</div>
											<div class="form-group clearfix">
												<label class="col-sm-4 control-label">Database Name</label>
												<div class="col-sm-8">
													<input class="form-control" type="text" required="required" name="db_name" value="<?php if(isset($database_name)) { echo $database_name;  } ?>" >
												</div>
											</div>
											<div class="form-group clearfix">
												<label class="col-sm-4 control-label">Database Username</label>
												<div class="col-sm-8">
													<input class="form-control" type="text" name="db_username" required="required" value="<?php if(isset($db_username)) { echo $db_username;  } ?>"  />
												</div>
											</div>
											<div class="form-group clearfix">
												<label class="col-sm-4 control-label">Database Password</label>
												<div class="col-sm-8">
													<input class="form-control" type="password" name="db_password" value="<?php if(isset($db_password)) { echo $db_password;  } ?>"  />
												</div>
											</div>
										</div>
										<ul class="pager wizard">																		
											<li class="next"><button type="submit">Next</button></li>
										</ul>
									</form>
									<?php } ?> 
								</div>
								  
								<?php $step3_completed = $this->session->userdata('step3_completed');
											if($step3_completed=="true") { ?>
					
										<div class="tab-pane wizard-content <?php if($_GET['step']==3) { ?> active <?php } ?> " id="basic_settings">
										<form class="form-horizontal" id="admin_basic_details" role="form" action="<?php echo base_url()."installer/admin_details" ?>" method="post" autocomplete="off">                                                                            
										<?php                                                                                
											$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
											$base_url .= "://".$_SERVER['HTTP_HOST'];
											$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
										?>
										<div class="installer-inputs">
											<div class="form-group">
											admin_details	<label class="col-sm-4 control-label">Admin Username</label>
												<div class="col-sm-8">
													<input class="form-control" required="required" type="text" name="admin_username">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Admin Password</label>
												<div class="col-sm-8">
													<input class="form-control" required="required" type="password" name="admin_password">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Confirm Password</label>
												<div class="col-sm-8">
													<input class="form-control" required="required" type="password" name="admin_confirm_password">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-4 control-label">Admin Email</label>
												<div class="col-sm-8">
													<input class="form-control" required="required" type="email" name="admin_email">
												</div>
											</div>
										</div>
										<ul class="pager wizard">																		
											<li class="next"><button type="submit">Finish</button></li>
										</ul>
									</form>
								  </div>

								 <?php } ?>  
								 </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="<?php echo base_url() ?>assets/js/jquery.min.js"></script>
	<script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/bootstrapValidator.min.js"></script>
        
<script>
    $(document).ready(function() {
    $(".numonly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>