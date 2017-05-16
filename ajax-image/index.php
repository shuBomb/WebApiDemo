<!DOCTYPE html>
<html><head>
<script src="//code.jquery.com/jquery-latest.min.js"></script>
<script src="http://malsup.github.io/jquery.form.js"></script>
<title>Uploading An Image Using AJAX In jQuery With PHP Example</title>
</head><body>
<div id="content" style="margin-top:10px;">
<h2><u>Uploading An Image Using AJAX In jQuery With PHP</u></h2><br/>
<div style="height:80%;width:97%;border:2px solid black;padding:10px 8px;">
 <form action="upload.php" method="POST" id="uploadform">
  <input type="file" name="file"/>
  <input type="submit" value="Upload"/>
  <div id="loader" style="display:none;">
   <center><img src="load.gif" /></center>
  </div>
  <div>
   Message :
   <div id="onsuccessmsg" style="border:5px solid #CCC;padding:15px;"></div>
  </div>
 </form>
</div>
<script>
$(document).ready(function(){
 function onsuccess(response,status){
  $("#loader").hide();
  $("#onsuccessmsg").html("Status :<b>"+status+'</b><br><br>Response Data :<div id="msg" style="border:5px solid #CCC;padding:15px;">'+response+'</div>');
 }
 $("#uploadform").on('submit',function(){
  $("#loader").show();
  var options={
   url     : $(this).attr("action"),
   success : onsuccess
  };
  $(this).ajaxSubmit(options);
 return false;
 });
});
</script>
<!-- http://www.subinsb.com/2013/09/uploading-image-using-ajax-in-jquery.html -->
</body></html>
