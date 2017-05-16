<div class="row navbar-form">
	<div class="col-xs-6">
    </div>
    <div class="col-xs-2" style="padding-left: 124px;">
    	<p> Signed in as: </p>
    </div>
    <div class="col-xs-3">
    <?php echo $this->session->userdata('username'); ?>
    </div>
    <div class="col-xs-1">
    	<form method='post' class="fr" action=<?php echo base_url().'admin/logout/' ?>>
    		<button  type="submit" class="btn btn-primary">Logout</button>
    	</form>	
    </div>
 </div>   

     
     

<header id="header" style="background-color:#549CE2">
		<h1 class="fl"><a href="<?php echo site_url("admin/patientlist"); ?>"> <img src="<?php echo base_url().'images/inner-screenbg.jpg' ?>" alt="Hopchek" />
		</a>
		</h1> 
	<div class="clr"></div>
	<div ><b><a href="<?php echo site_url("admin/patientlist"); ?>" style="cursor:pointer;color:white">Home</a></b></div>
</header>

	
	