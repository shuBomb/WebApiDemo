$(document).ready(function() {
	
	$(".btn_viewdetail").click(function() { 
		var id = $(this).val();
		var baseurl = "<?=  site_url(); ?>";
        window.location.replace(baseurl + '/admin/profileview/'+id);
	});
	
	$("#filter_by").change(function() { 
		var filter_by = $( "#filter_by" ).val();
		if(filter_by==''){
			return false;
		} else if(filter_by=='1'){
		
		$('#filter1').show();
		$('#filter2').hide();
		$('#filter3').hide();

	    var base_url = "<?php echo base_url(); ?>";
			$.ajax({
  				type: "POST",
  				url: base_url+"admin/patientlist",
  				data: {filter_by:filter_by}
				}).done(function(msg) {
  					window.location.href=base_url+"admin/patientlist";
				});
		}else if(filter_by=='2'){
			$('#filter1').hide();
			$('#filter2').show();
			$('#filter3').hide();
			
			var filter = $( "#filter2" ).val();
			
			var base_url = "<?php echo base_url(); ?>";
			$.ajax({
  				type: "POST",
  				url: base_url+"admin/filterpatient",
  				data: {filter:filter,filterby:1} // filterby 1 for HCP and 2 for organisation
				}).done(function(msg) {
				     
  					//window.location.href=base_url+"admin/patientlist";
				});
				
		}else if(filter_by=='3'){
			$('#filter1').hide();
			$('#filter2').hide();
			$('#filter3').show();
		}
		});
		
		$("#clear").click(function() {  
			$("#search_key").val("");
		});
	
	
 });