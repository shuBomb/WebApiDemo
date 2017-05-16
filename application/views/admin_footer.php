</div>
<!-- wrapper end -->
</div>
<!-- main wrapper strat -->
</body>
  <p align="center" class="admin_footer"> <font color="#FFFFFF" size="2">Created by Systematix Infotech Pvt. Ltd.</font> </p>
</html>




<div class="container">
  <!-- Trigger the modal with a button -->
 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width: 85%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="panel panel-primary">
    		<div class="panel-heading">Seizures Types</div>
        	<div class="panel-body"> 
        
        <div class="alert alert-success" id="msg_add_seizure_type">
  			<strong>Successfully Added</strong>.
		</div>

        <div class="modal-body">
          <table class="table" id="tbl_seizuretype">
    		<thead>
      			<tr>
        			<th>Name</th>
       				<th>Description</th>
       				<th>Edit</th>
       				<th>Delete</th>
      			</tr>
    		</thead>
    <tbody>
    <?php if($seizure_type){
    		foreach($seizure_type as $seizuretyperow){ ?>
    		 <tr id="tbl_typerow_<?= $seizuretyperow['pkSeizureTypeId']; ?>" class="tbl_seizuretype_row">
        		<td><?= $seizuretyperow['SeizureType']; ?></td>
        		<td><?= $seizuretyperow['Description']; ?></td>
        		<td><img src="<?php echo base_url().'upload/'?>edicticon.jpg" data-row="<?= $seizuretyperow['pkSeizureTypeId']; ?>"  class="edit_siezure_type" alt="Delete" width="30" height="30"></span></td>
        		<td><img src="<?php echo base_url().'upload/'?>cancel.png" data-row="<?= $seizuretyperow['pkSeizureTypeId']; ?>" class="delete_siezure_type" alt="Edit" width="30" height="30"></span></td>

      		</tr>
    	<?php 	}
    	}else{
    	echo "No Data found";
    	}
    	 ?>
    </tbody>
  </table>
  </div>
  </div>
  </div>
  
  <hr>
  
  <form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">Seizure Name:</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="seizuretype" placeholder="Seizure Name" maxlength="100">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-3 control-label">Seizure Description:</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="seizuredescription" placeholder="Seizure Description" maxlength="250">
    </div>
  </div>
 
 
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10 ">
   		 <input type="hidden" value="1" id="input_for_edit_type" />
      	 <input type="hidden" value="0" id="pkSeizureTypeId" />
         <button id="btn_add_siezure_type" type="button" class="btn btn-primary">Add</button>
         <a id="btn_typecancel" style="display:none;cursor:pointer">Cancel</a>
    </div>
  </div>
</form>

		<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        
        
  
        </div>
        
      </div>
      
    </div>
  </div>
  
</div>



