<!doctype html>
<html>
      <head>
      <title>Add New Banner</title>
      <link href="<?php echo base_url().'css/style.css'; ?>" rel="stylesheet" />
      <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
      <link rel="stylesheet" href="<?php echo base_url('css/bootstrap-theme.min.css'); ?>">
      <link rel="stylesheet" href="<?php echo base_url('css/bootstrap.min.css'); ?>">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <script src="<?php echo base_url('js/bootstrap.min.js'); ?>">
      </script>
      <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
      
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
<form action="<?php echo base_url(); ?>admin/savebanner" method="POST" enctype="multipart/form-data" >
        <div class="panel-primary col-md-12">
    <div class="panel-heading"> Add New Banner</div>
   
    <div class="panel-body add-banner-form">
            <div class="row navbar-form p-l0 m-b-14">
            
            <?php if($msg != ""){ ?>
            <div class="row navbar-form p-l0 m-b-14">
            <div class="col-md-12 p-r0 p-l0" style="color:red" >
               The filetype you are attempting to upload is not allowed. 
           </div>
           </div>
           <?php } ?>
           
            <?php if($errormsg != ""){ ?>
            <div class="row navbar-form p-l0 m-b-14">
            <div class="col-md-12 p-r0 p-l0" style="color:red" >
               <?php echo $errormsg; ?>
           </div>
           </div>
           <?php } ?>
           
        <div class="col-md-12 p-r0 p-l0">
                <label for="inputEmail" class="control-label">Campaign Name<span class="error-stare">*</span></label>
                <input type="text" name="campaignname" id="campaignname" required="required" placeholder="Campaign Name" maxlength="300" class="form-control" />
              </div>
      </div>
            <div class="row navbar-form p-l0 m-b-14">
        <div class="col-md-12 p-r0 p-l0">
                <label for="inputEmail" class="control-label">Upload Ads<span class="error-stare">*</span></label>
                <input type="file" name="banner_image" id="banner_image" required="required" class="form-control" />
              </div>
      </div>
            <div class="row navbar-form p-l0 m-b-14">
        <div class="col-md-12 p-r0 p-l0">
                <label for="inputEmail" class="control-label">URL</label>
                <input type="url" name="url"  maxlength="100" id="url" placeholder="URL (optional)" class="form-control"/>
              </div>
      </div>
            <div class="row navbar-form p-l0 m-b-14">
        <div class="col-md-12 p-r0 p-l0" >
                <label>&nbsp;</label>
                <button id="btn_submit" class="btn btn-primary m-l-5" type="submit" title="Save">Save</button>
              </div>
        <?php
   				 echo form_close();
   			  ?>
      </div>
          </div>
  </div>
      </form>
</body>
</html>
