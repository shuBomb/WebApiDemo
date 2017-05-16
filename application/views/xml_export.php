<?php
 $response = '';
 $response .= '<?xml version="1.0" encoding="UTF-8"?>';
 if(isset($xmldata) && count($xmldata)>0){ 
     //$response .= '<status>TRUE</status>';
     $response .=  '<response>';
     foreach($xmldata as $respo){
        
        if($respo->FkHCPStatusId == 1){
        $approve = "Enable";
        }else  if($respo->FkHCPStatusId == 1){
         $approve = "Disable";
        }else{
         $approve = "Block";
        } 
          $response .= '<hcpid>'.$respo->pkHCPId.'</hcpid>
                        <firstName>'.$respo->FirstName.'</firstName>
                        <surname>'.$respo->Surname.'</surname>
                        <date_of_birth>'.$respo->DOB.'</date_of_birth>
                        <mobileNo>'.$respo->MobileNo.'</mobileNo>
                        <email>'.$respo->Email.'</email>
                        <status>'.$approve.'</status>
                        <description>Transaction Detail</description>';
          
     }
      $response .=  '</response>';
 }else{
     $response .=  '<response>';
     $response .= '<status>False</status>';
     $response .=  '</response>';
 }
 	header("Content-type: application/x-msdownload");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$filename.xml");
	header("Pragma: no-cache");
	header("Expires: 0");
 echo $response; die;
?>
