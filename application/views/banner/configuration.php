<!doctype html>
<html>
 <head>
 <title>Manage Configuration</title>
 <link href="<?php echo base_url().'css/style.css'; ?>" rel="stylesheet" />
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
 <link rel="stylesheet" href="<?php echo base_url('css/bootstrap-theme.min.css'); ?>">
 <link rel="stylesheet" href="<?php echo base_url('css/bootstrap.min.css'); ?>">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
 <script src="
        <?php echo base_url('js/bootstrap.min.js'); ?>">
      </script>
 <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
 <script type="text/javascript">
$(document).ready(function() {
	
	
  	$('#btn_submit').click(function(){
      //Some code
      
      var config_id = $('#config_id').val().trim();
      var no_of_ads = $('#no_of_ads').val().trim();
	  var shuffle_interval = $('#shuffle_interval').val().trim();
	  
	  if(no_of_ads == ''){
	    alert('Please entered value of ads');
		return false;
	  }
	  if(shuffle_interval == ''){
	    alert('Please entered value of shuffle interval');
		return false;
	  }
	  
	  if(no_of_ads < 1){
	    alert('Please entered non zero value');
		return false;
	  }
	  
	  if(shuffle_interval<5){
	    alert('Please entered shuffle interval time above 5 sec');
		return false;
	  }
	  $.ajax({
  		type: "POST",
  		url: "<?php echo base_url().'admin/saveconfig' ?>",
		dataType: 'html',
  		data: {no_of_ads:no_of_ads,shuffle_interval:shuffle_interval,config_id:config_id}
		}).done(function(msg) {
		
		    $("#successmsg").show().delay(5000).fadeOut();
       });
 });
 
  	
  	
});
 </script>
 </head>
 <body>
<div class="header-bg">
   <div class="head-section">
    <div class="col-md-2">&nbsp;</div>
    <div class="nav-section col-md-10">
       <ul  class="nav nav-pills pull-right">
        <li><a href="<?php echo base_url().'admin/bannerlist/' ?>">Home</a></li>
        <li><a href="<?php echo base_url().'admin/configuration/' ?>">Configuration</a></li>
        <li><a href="<?php echo base_url().'admin/logout/' ?>">Logout</a></li>
      </ul>
     </div>
  </div>
 </div>
<div id="msg"></div>
<div class="panel-primary col-md-12">
   <div class="panel-heading"> Configuration</div>
   <div class="panel-body add-banner-form configur-form">
    <div class="alert alert-success" id="successmsg" style="display:none"> <strong>Successfully Updated</strong>. </div>
    <div class="row navbar-form p-l0 m-b-14">
       <div class="col-md-12 p-r0 p-l0 ">
        <label for="inputEmail" class="control-label">No. of Ads</label>
        <input type="number" name="no_of_ads" id="no_of_ads" value="<?= $data['no_of_ads']; ?>" required="required" placeholder="No. of Ads" maxlength="2" class="form-control" />
      </div>
      </div>
      <div class="row navbar-form p-l0 m-b-14">
      <div class="col-md-12 p-r0 p-l0">
        <label for="inputEmail" class="control-label">Add Shuffle Interval(s)</label>
         <input type="number" name="shuffle_interval" value="<?= $data['shuffle_interval']; ?>" required="required"  maxlength="5" id="shuffle_interval" placeholder="Add Shuffle Interval(s)" class="form-control"/>
      </div>
     </div>
  
    <input type="hidden" id="config_id" value="<?= $data['config_id_PK']; ?>" />
    <div class="row navbar-form p-l0 m-b-14">
       <div class="col-md-12 p-r0 p-l0" ><label>&nbsp;</label><button id="btn_submit" class="btn btn-primary m-l-5" type="submit" title="save" >Save</button> </div>
       
     </div>
  </div>
 </div>
</body>
</html>
