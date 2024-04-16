<?php
   						
    define ('hostnameorservername',"localhost");	 // Server Name or host Name 
    define ('serverusername','root'); // database Username 
    define ('serverpassword',''); // database Password 
    define ('databasename','svlelectricals'); // database Name 



    $project = "SVL Electricals";
    $slogan = "Trust our experience";
    $officename = "Kolhapur";
    $officename1 = "SVL";
    global $connection;
    $connection = @mysqli_connect(hostnameorservername,serverusername,serverpassword,databasename) or die('Connection could not be made to the SQL Server. Please contact report this system error at <font color="blue">7588171304</font>');
   

?>
