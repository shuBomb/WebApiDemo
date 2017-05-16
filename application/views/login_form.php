<?php
/*
	Created By: MUDASSIR SIPL
	Created Date: 30-Nov-2012
	login form for admin section
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
   <title>Admin</title>
    <link href="<?php echo base_url('css/admin_style.css'); ?>" rel="stylesheet" type="text/css" >
    <link href="<?php echo base_url('css/admin-bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" >
  <!--  <script src="<?php echo base_url();?>js/jquery-1.8.3.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url();?>js/jquery.validate.js" type="text/javascript" charset="utf-8"></script>-->
		<script src="<?php echo base_url('js/bootstrap.min.js'); ?>"></script>
	    <!--[if lt IE 9]><script src="<?php echo base_url('js/ie8-responsive-file-warning.js'); ?>"></script><![endif]-->
    <script src="<?php echo base_url('js/ie-emulation-modes-warning.js'); ?>"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-page">
<div class="login_form container login-container">
	<a title="My Meds Manager" class="brandname" href="#"><img width="337" height="215" alt="" src="http://52.19.64.202/images/logo.png" class="img-responsive"></a>
	<div id="login_form_inner" class="form-signin">	
    	<div id="login_form_inner_bottom">
        	<div id="login_form_inner_mid">
		<?php 
                if(isset($msg)) echo '<p class="login_top_error">'.$msg.'</p>'; 
                $loginAttributes = array("method"=>"post", "name"=>"admin_login", "id"=>"admin_login");
                echo form_open('/manager/validateCredentials',$loginAttributes);
                ?>
                <div class="inputemail">
                <input type="text" name="username" id="username" value="shubham@gmail.com" class="required form-control femail" />
				<span class="error error-un"><?php echo form_error('username'); ?></span>
                </div>
                <div class="inputpw">
                <input type="password" name="password" id="password" value="123456" class="required form-control" />
				<span class="error"><?php echo form_error('password'); ?></span>
                </div>
                <input type="submit" class="btn btn-lg btn-primary btn-block" title="Login" name="submit" id="login" value="Login" />
                <?php echo form_close(); ?>            	
            </div>
        </div>
    </div>	
</div><!-- end login_form-->