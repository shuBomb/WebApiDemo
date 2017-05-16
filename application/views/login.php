<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php print_r($title); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="<?php echo base_url('css/bootstrap-theme.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('css/bootstrap.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?php echo base_url('js/bootstrap.min.js'); ?>"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
   <!--[if lt IE 9]><script src="<?php echo base_url('js/ie8-responsive-file-warning.js'); ?>"></script><![endif]-->
    <script src="<?php echo base_url('js/ie-emulation-modes-warning.js'); ?>"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript">
$(document).ready(function() {

  
    
    
	 
	 $('body').delegate('#forgot', 'click', function() {
	 	$('#msg').hide();
	 });
	 
	 $('body').delegate('#btn_send', 'click', function() {
		var email = $('#emailaddress').val().trim();
		if(email==''){
		    $('#msg').show();
		    $('#msg').addClass( "alert-danger" );
		    $('#getmsg').html("Please entered email address");
			return false;
		}
		$('#msg').show();
		$('#getmsg').html("");
        $.ajax({
  				type: "POST",
  				url: "<?php echo base_url().'admin/forgotpassword' ?>",
				dataType: 'html',
  				data: {email:email}
			}).done(function(msg) {
				
			if(msg==1){
					$('#getmsg').html("Password has been sent to your email id");
					$('#msg').addClass("alert-success");
					 $('#msg').removeClass( "alert-danger" );
				}else if(msg==2){
					$('#getmsg').html("Something went wrong");
					$('#msg').addClass("alert-danger");
					 $('#msg').removeClass( "alert-success" );
				}else if(msg==3){
					$('#getmsg').html("Email address not found in our database");
					$('#msg').addClass("alert-danger");
					 $( '#msg' ).removeClass( "alert-success" );
				}
				
				$('#msg').show();
				$('#emailaddress').val("");
				
			});
  		});
  	});
 </script> 
<script type="text/javascript">
function valid()
{
var flag=true;
var error='';
var username=document.getElementById("username").value;
var password=document.getElementById("password").value;
if(username=='')
{
flag=false;
error+='Please enter username\n';
}
if(password=='')
{
flag=false;
error+='Please enter password\n';
}
if(flag==false)
{
alert(error);
return flag;
}
}
</script>
</head>
<body class="login-bg">
	
<div id="loginModal" class="modal show logoin-page" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <h1 class="text-center">Login</h1>
      </div>
      
      <?php  
        if(isset($error)) { ?>
      <div class="alert alert-error">
  		<?php	echo "<center><b><font color='red'>".$error."</font></b></center>"; ?>
	 </div>
	    <?php  } ?>
      		 
      <div class="modal-body">
          <form action="<?php echo base_url(); ?>index.php/admin/check" method="post" onsubmit="return valid();">
            <div class="form-group">
              <input type="text" class="form-control" name="email" placeholder="Email" id="email">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" placeholder="Password" name="password" id="password" >
            </div>
            <div class="form-group text-right">
              <button class="btn btn-primary">Sign In</button>
            </div>
          </form>
      </div>
     <p class="text-right forgot-text"><a id="forgot" href="#" data-toggle="modal" data-target="#myModal">Forgot Password?</a></p>
     </div>
  </div>
</div>

</body>
</html>



<div class="container">
  <!-- Trigger the modal with a button -->
 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width: 52%">
    
      <!-- Modal content-->
      <div class="modal-content" id="modalcontent">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="panel panel-primary">
    		<div class="panel-heading">Forgot Your Password?</div>
       </div>

         <div class="alert" id="msg" style="display:none">
  			<strong id="getmsg"></strong>.
		</div>
  
  <hr>

  
  <form class="form-horizontal">
  <div class="form-group" >
    Email Address
    <div class="col-sm-6">
      <input type="text" class="form-control" id="emailaddress" oninvalid="this.setCustomValidity('Please Enter valid email')" placeholder="Enter Email Address" maxlength="100" required="required">
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10 ">
         <button id="btn_send" type="button" class="btn btn-primary">Send</button>
    </div>
  </div>
</form>

	
       </div>
        
      </div>
      
    </div>
  </div>
  
</div>



