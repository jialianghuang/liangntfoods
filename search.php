<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>NTFOODS</title>
        <link rel="stylesheet" href="/ntfoods/public/css/app.css">
        
    </head>
    <body>
<?php

$serverName = "win2003sql"; //serverName\instanceName

// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
$connectionInfo = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$search = $_POST["search"];

if( $conn ) {
     echo "";
     $tsql = "SELECT *,dbo.inv.IMAGE_NM FROM dbo.inv LEFT JOIN dbo.inv_data ON dbo.inv.PROD_CD = dbo.inv_data.PROD_CD WHERE dbo.inv.DESCRIP LIKE '".'%'.$search.'%'."' OR dbo.inv.PROD_CD like '".'%'.$search.'%'."'" ;  
                $stmt = sqlsrv_query($conn, $tsql);  
                if ($stmt === false) {
                    echo "Error in query execution";  
                    echo "<br>";  
                    die(print_r(sqlsrv_errors(), true));  
                }
                echo '<h2>RESULTS</h2>';
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo '<form action="omsinv.php" method="post" accept-charset="UTF-8">'; 
                    $imageadd =$row['IMAGE_NM']; 
                    $imgposrev = strrev(trim($imageadd));
                    $imgposfinal = strlen($imgposrev)-strpos($imgposrev,'\\');
                    $imagepo = substr($imageadd,$imgposfinal);
                    
                    echo '<p style="display:none"><input type="text" name="name" value="'.$row['PROD_CD'].'" readonly></p>';    
                    
                    echo '<p><button class="btn btn-outline-secondary" type="submit" value=""><img width="30px" height="30px" class="rounded float-left" alt="" src="images/'.trim($imagepo).'">'.$row['PROD_CD']. $row['DESCRIP'].'</button></p>';
                    
                    echo '</form>';

                }  
                sqlsrv_free_stmt($stmt);  
                sqlsrv_close( $conn); 
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>
</body>
</html>