<?php
/*
echo "<pre>";
print_r($filter_by_hcp);
//foreach($data as $row){ 
//echo $row['device_token'];
//}
die; 
  */ 
?>
<!doctype html>
<html>
 <head>
 <title>Manage Banners</title>
 <link href="<?php echo base_url().'css/style.css'; ?>" rel="stylesheet" />
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
 <link rel="stylesheet" href="<?php echo base_url('css/bootstrap-theme.min.css'); ?>">
 <link rel="stylesheet" href="<?php echo base_url('css/bootstrap.min.css'); ?>">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
 <script src="<?php echo base_url('js/bootstrap.min.js'); ?>"></script>
 <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
 <link rel="icon" href="<?php echo base_url().'img/favicon.ico'; ?>" type="image/x-icon">
 <script type="text/javascript">
$(document).ready(function() {
	$("#clear").click(function() {  
			$("#search_key").val("");
			var BASE_URL = $("#baseurl").val(); 
	      window.location.replace(BASE_URL + 'admin/');
	});
	  $('body').delegate('.delete', 'click', function() {
			var getid = $(this).attr('data-row'); // Get Event Id
			var returnVal = confirm("Are you sure, you want to delete?");
			if(returnVal==true){
            	$.ajax({
  						type: "POST",
  						url: "<?php echo base_url().'admin/deletebanner' ?>",
						dataType: 'html',
  						data: {getid:getid}
					}).done(function(msg) {
					     $('#tbl_row_'+getid).animate( {backgroundColor:'#BF3719'}, 1000).fadeOut(1000,function() {
   						 $('#tbl_row_'+getid).remove();
   						});
  				 	});
            }else{
            	return false;
            }
	});
	
	$('body').delegate('.edit', 'click', function() {
    	
    	var getid = $(this).attr('data-row'); // Get Banner Id
    	var patientId = $('#patientid').val();
    	var type = $(this).attr('data-type'); // 1 for view and 2 for edit
    	if(type==1){
    	   $('#campaignname').attr('readonly', true);
    	   $('#url').attr('readonly', true);
    	   $('#getstatus').attr('readonly', true);
    	   $('#showstatus').attr('readonly', true);
    	   $('#showstatusdiv').show();
    	   $('#statusradio').hide();
    	   $('#change_image').hide();
    	   $('#btn_update').hide();
    	}else{
    	    $('#campaignname').attr('readonly', false);
    	    $('#url').attr('readonly', false);
    	    $('#getstatus').attr('readonly', false);
    	    $('#showstatus').attr('readonly', false);
    		$('#statusradio').show();
    		$('#showstatusdiv').hide();
    		$('#change_image').show();
    		$('#btn_update').show();
    		
    	}
    	
        $.ajax({
  			type: "POST",
  			url: "<?php echo base_url().'admin/getbannerdata' ?>",
			dataType: 'html',
  			data: {getid:getid,patientId:patientId}
			}).done(function(msg) {
				var data = JSON.parse(msg);
				if(data.status== "true"){
					if(data.getstatus==1){
					 var getstatus = "Active";
					 $('#active').prop('checked',true);
					}else{
					  var getstatus = "InActive";
					  $('#inactive').prop('checked',true);
					}
					$('#showstatus').val(getstatus); 
					$('#campaignname').val(data.campaign_name); 
					$('#url').val(data.url);
					$('#getstatus').val(getstatus); 
					$('#pkBannerId').val(getid);  //  // set seizure type id 
   					$("#img_banner").attr('src',data.ad_picture );	
   					$('#imgname').val(data.ads_picture_name);  
				}else{
					alert("Something Went Wrong");
				}
				
			});
  	});
  	
  	function validateURL(textval) {
     var urlregex = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/;
     return urlregex.test(textval);
     }
  	
  	
  	$('#btn_update').click(function(){
      //Some code
      var campaignname = $('#campaignname').val().trim();
	  var url = $('#url').val().trim();
	  var pkBannerId = $('#pkBannerId').val(); 
	  var imgname =  $('#imgname').val();
	  var getstatus = $(".getstatus:checked").val();
	  var BASE_URL = $("#baseurl").val(); 
	  
	  
	  
	  if(campaignname == ''){
	    alert('Please entered campaign name');
		return false;
	  }
	  if(validateURL(url)==false){
	     alert('Please enter valid URL');
		return false;
	  }
	  $.ajax({
  		type: "POST",
  		url: "<?php echo base_url().'admin/updatebanner' ?>",
		dataType: 'html',
  		data: {campaignname:campaignname,url:url,pkBannerId:pkBannerId,getstatus:getstatus,imgname:imgname}
		}).done(function(msg) {
		
		    var display_ads_image = "<img width='50' src="+BASE_URL+'upload/'+imgname+">";
		
		    $("#successmsg").show().delay(5000).fadeOut();
		    $( "#tbl_row_"+pkBannerId+" td:eq(1)" ).text(campaignname);
		    $( "#tbl_row_"+pkBannerId+" td:eq(2)" ).html(display_ads_image);
	  		$( "#tbl_row_"+pkBannerId+" td:eq(3)" ).text(url);
	  		if(getstatus==0){
	  			$( "#tbl_row_"+pkBannerId+" td:eq(6)" ).text("InActive");
	  		}else{
	  			$( "#tbl_row_"+pkBannerId+" td:eq(6)" ).text("Active");
	  		}
	  		setTimeout(function(){ $('#editbanner').modal('hide') }, 1000);
			setTimeout(function(){ 
				  	 	$('#tbl_row_'+pkBannerId).animate( {backgroundColor:'#00FF80'}, 3000).fadeIn(1500,function() {
	  			  	 	$('#tbl_row_'+pkBannerId).css('background-color', '#fff');
	  			  	 });
				  }, 2500); 
	 });
 });
 
 
 /* Add Profile image */
    $("body").on("change", ".upload-image-file", function() {
        var obj = $(this);
        var BASE_URL = $("#baseurl").val(); 
        var allowedExtension = ["jpg", "jpeg", "gif", "png"];
        var fileExtension = $(this).val().split('.').pop().toLowerCase();
        var isValidFile = false;
        for (var index in allowedExtension) {
            if (fileExtension === allowedExtension[index]) {
                isValidFile = true;
                break;
            }
        }
        if (!isValidFile) {
            bootbox.alert("Invalid file");
            return false;
        }
        $(obj).prop('disabled', true);
        var file_data = $(".upload-image-file").prop("files")[0];   // Getting the properties of file from file field
        var form_data = new FormData();                  // Creating object of FormData class
        form_data.append("profile-image", file_data);              // Appending parameter named file with properties of file_field to form_data
        $(".loader").show();
        $.ajax({
            url: BASE_URL+"admin/uploadfile",
            cache: false,
            contentType: false,
            processData: false,
            data: form_data, // Setting the data attribute of ajax with file_data
            type: 'post',
            success: function(response) {
                $(".loader").hide();
                $(obj).prop('disabled', false);
                var jsonResult = jQuery.parseJSON(response);
                if (jsonResult.status == true && jsonResult.image_name != '') {
                    jQuery("#img_banner").attr("src", jsonResult.image_url) ;
                    $('#imgname').val(jsonResult.image_name);
                } else {
                   alert("Something went wrong");
                }
            },
        });
    });
});
 </script>
 </head>
 <body>
<div class="header-bg">
<div class="head-section">
<div class="col-md-2"><a href="<?php echo base_url().'admin/bannerlist/' ?>"><img src="<?php echo base_url().'img/appicon.png'; ?>" /></a></div>
<div class="nav-section col-md-10">
   <ul  class="nav nav-pills pull-right">
    <li><a href="<?php echo base_url().'admin/bannerlist/' ?>">Home</a></li>
    <li><a href="<?php echo base_url().'admin/configuration/' ?>">Configuration</a></li>
    <li><a href="<?php echo base_url().'admin/logout/' ?>">Logout</a></li>
  </ul>
  </div>
  </div>
 </div>
<div class="right col-md-12">
   <div class="panel panel-primary">
    <div class="panel-heading">Banner List</div>
  </div>
   <form method='post' action=<?php echo base_url().'admin/searchBanner/' ?>>
    <div class=" col-md-12 p-l0 p-r0">
       <div class="pull-left margin15">
        <label class="control-label pull-left search-by-text">Search:</label>
        <input type="text" id="search_key" value="<?php if(!empty($search_key)) echo $search_key; ?>" maxlength="50"  name="search_key"  class="form-control input-group-lg reg_name pull-left input-search" required="required"/>
        <button  class="btn btn-primary" type="submit" title="Search" >Search</button>
        <a id="clear" class="btn btn-primary" title="Clear">Clear</a> </div>
       <div class="p-r0 pull-right"><a href="<?php echo base_url().'admin/addbanner/' ?>" class="btn btn-primary" title="Add New Banner">Add New Banner</a></div>
     </div>
  </form>
   <table id="tbl_list" class="table product-list" cellpadding="0" cellspacing="0" >
    <thead>
       <tr >
        <th nowrap="nowrap"><label>S. No.</label></th>
        <th><label>Campaign Name</label></th>
        <th><label>Ad Picture</label></th>
        <th><label>URL</label></th>
        <th><label>No. of Clicks</label></th>
        <th><label>No. of Views</label></th>
        <th><label>Status</label></th>
        <th><label>Action</label></th>
      </tr>
     </thead>
    <tbody >
       <?php if($data) {
             	$page =$page+1;
                foreach($data as $row){ ?>
         			<tr id="tbl_row_<?= $row['ads_id_PK'] ?>" class="tbl_row">
        			<td><?php echo $page ?></td>
        			<td><?php echo $row['campaign_name']; ?></td>
        			<td><img class="img_ads" width="50" src="<?php echo $row['ad_picture']; ?>" /></td>
        			<td><?php echo $row['url']; ?></td>
        			<td><?php echo $row['number_click']; ?></td>
        			<td><?php echo $row['number_views']; ?></td>
        			<td><?php 
                    	if($row['status']==1){
                        	echo "Active";
                        }else{
                        	echo "InActive";
                        }
                        ?></td>
        <td class="action-btn"><button data-toggle="modal" data-target="#editbanner" class="btn_viewdetail edit" data-row="<?= $row['ads_id_PK']; ?>" data-type="1"><i class="glyphicon glyphicon-search"></i></button>
           <button data-toggle="modal" data-target="#editbanner" class="btn_viewdetail edit" data-row="<?= $row['ads_id_PK']; ?>" data-type="2"><i class="glyphicon glyphicon-edit"></i></button>
           <button  class="btn_viewdetail delete" data-row="<?= $row['ads_id_PK']; ?>" ><i class="glyphicon glyphicon-trash"></i></button></td>
      </tr>
       <?php $page++; }
                         
                         ?>
       <?php
                          
                          } else { ?>
       <tr>
         <td colspan="10"><div class="alert alert-info" > <strong><?php echo " No record found "; ?></strong> </div></td>
       </tr>
       <?php }?>
   </tbody>
   </table>
   <div class="pagination">
    <?php
						  if($links){ ?>
    <?php echo $links;
                          }
                           ?> </div>
   <div class="clr"></div>
 </div>
<div class="modal fade" id="editbanner" role="dialog">
   <div class="modal-dialog modal-md"> 
    <!-- Modal content-->
    <div class="modal-content container-fluid">
       <div class="heading">Banner Detail<button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
       <div class="panel-body p-rl0 p-t0">
        <div class="alert alert-success" id="successmsg" style="display:none"> <strong>Successfully updated.</strong>. </div>
        <div class="col-md-12 p-l0 p-r0">
           <div class="form-group">
            <label>Campaign Name:</label>
            <input type="text" class="form-control" id="campaignname" placeholder="Campaign Name:" maxlength="200" >
          </div>
           <div class="form-group">
            <label>URL: </label>
            <input type="text" class="form-control" id="url" placeholder="URL" maxlength="200" >
          </div>
           <div class="form-group">
            <label>Banner Image: </label>
            <img id="img_banner" width="100" hieght="80" /> </div>
           <div class="form-group" id="change_image">
            <label>Change Image: </label>
            <form method="POST" class="uploadform" enctype="multipart/form-data">
               <input type="file" name="profile-image" class="upload-image-file" />
             </form>
            <div class="loader" style="display:none;">
               <center>
                <img src="<?php echo base_url()."img/load.gif" ?>" />
              </center>
             </div>
          </div>
           <div class="form-group" id="showstatusdiv" style="display:none">
            <label>Status: </label>
            <input type="text" class="form-control" id="showstatus" placeholder="Status" maxlength="12" >
          </div>
           <div class="form-group" id="statusradio">
            <label>Status: </label>
           
               <input id="active" type="radio" class="radio_event_type getstatus" name="optradio" value="1">
                <label class="v-align-mid">Active</label>
           
               <input id="inactive" type="radio" class="radio_event_type getstatus" name="optradio" value="0">
               <label class="v-align-mid">InActive</label>
          </div>
           <input type="hidden" id="pkBannerId">
           <input type="hidden" id="imgname">
           <input type="hidden" id="baseurl" value="<?php echo base_url(); ?>">
           <div class="col-md-12 p-r0 text-right">
            <button type="button" id="btn_update" class="btn btn-primary">Update</button>
          </div>
         </div>
        <!--/.col-md-6-->
        <div class="col-md-6 p-r0"> </div>
        <!--/.col-md-6--> 
        <i class="clearfix"></i> </div>
     </div>
  </div>
 </div>
<!--/.addevent-->

</body>
</html>
