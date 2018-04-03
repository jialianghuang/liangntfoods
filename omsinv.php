<?php include 'header.php';?>

<div class="contain">
<?php

$serverName = "win2003sql"; 
$connectionInfo = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$name = $_POST["name"];
$name = trim($name);
$flag1 = substr($name, -1);

if($flag1 == 'X'||$flag1 == 'x'){
    $name1 = substr($name,0,strlen($name)-1);
}
$flag2 = 0;
$conn4 = sqlsrv_connect($serverName, $connectionInfo);
if($conn4){
    $tsql4 = "SELECT ON_ORDER_QTY FROM dbo.inv_data WHERE PROD_CD = '".$name."'";
        $stmt4 = sqlsrv_query($conn4,$tsql4);
        if($stmt4 === false){
            echo "error";
            die(print_r(sqlsrv_errors(), true)); 
        }
        while($row4 = sqlsrv_fetch_array($stmt4,SQLSRV_FETCH_ASSOC)){
            $onorderqty = $row4['ON_ORDER_QTY'];
        }
        sqlsrv_free_stmt($stmt4);
        sqlsrv_close($conn4);

}
else{
    echo "connection error";
    die( print_r( sqlsrv_errors(), true)); 
}

if( $conn ) {
     $tsql = "SELECT *,dbo.inv.WIP_QTY,dbo.inv.IMAGE_NM FROM dbo.inv LEFT JOIN dbo.inv_data ON dbo.inv.PROD_CD = dbo.inv_data.PROD_CD WHERE dbo.inv.PROD_CD = '".$name."'" ;  
                $stmt = sqlsrv_query($conn, $tsql);  
                if ($stmt === false) {
                    echo "Error in query execution";  
                    echo "<br>";  
                    die(print_r(sqlsrv_errors(), true));  
                }
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $imageadd =$row['IMAGE_NM']; 
                    $imgposrev = strrev(trim($imageadd));
                    $imgposfinal = strlen($imgposrev)-strpos($imgposrev,'\\');
                    $imagepo = substr($imageadd,$imgposfinal);
                    echo '<img width="200px" alt="" src="images/'.trim($imagepo).'"><br>';                       
                    echo $row['PROD_CD'].'<br>'. $row['DESCRIP'] .'<br>'.$row['DESCRIP1'].'<br>'.$row['DESCRIP2'].'<br>';
                    echo '<table class="table"><tbody>';
                    echo '<tr><th>Status:</th><td>'.$row['CAT_NUM'].'</td></tr>'.'<tr><th>S/O Qty:</th><td>'.round($row['ORDER_QTY'],2).'</td></tr>'.'<tr><th>P/O Qty:</th><td>'.round($onorderqty,2).'</td></tr>';
                    echo '<tr><th>Qty/Case:</th><td>'.number_format($row['PC_CASE'],2).'</td></tr>';
                    echo '<tr><th>Qty/Pallet:</th><td>'.number_format($row['WIP_QTY'],2).'</td></tr>';
                    echo '<tr><th>CASH:</th><td>$'.round($row['RETAIL_PRS'],2).'</td></tr><tr><th>CREDIT CARD:</th><td>$'.round($row['CORP_PRS'],2).'</td></tr>'.'<tr><th>Wholesale:</th><td>$'.round($row['WHOLE_PRS'],2).'</td></tr>'.'<tr><th>Location:</th><td>'.$row['INV_LOCT'].'</td></tr>';
                    $invbefore = $row['IN_STOCK'];
                    $invav = $row['ORDER_QTY'];
                    $instock = ($invbefore - $invav )/$row['PC_CASE'];
                }  
                sqlsrv_free_stmt($stmt);  
                sqlsrv_close( $conn); 

}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>
<?php   //LAST_ORDER_DATE
$conn5 = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn5 ) {
     $tsql5 = "SELECT TOP 1 LOG_DT FROM dbo.invt_log WHERE PROD_CD = '".$name."' ORDER BY LOG_DT DESC" ;  
                $stmt5 = sqlsrv_query($conn5, $tsql5);  
                if ($stmt5 === false) {
                    echo "Error in query execution";  
                    echo "<br>";  
                    die(print_r(sqlsrv_errors(), true));  
                }
                while($row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC)) {
                    $daysToEpoch1 = 61730;
                  $FromEpoch1=($row5['LOG_DT']-$daysToEpoch1)*86400;
                  
                  echo '<tr><th>Last Sale Date:</th><td>'.date("m/d/y",$FromEpoch1).'</td></tr>';
                   //echo $row5['SUM(ORDER_QTY)'];
                }  
               

                sqlsrv_free_stmt($stmt5);  
                sqlsrv_close( $conn5); 

}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>
<?php
$epoch = time()/86400;
$currentd = intval($epoch) + 61700;
$conn6 = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn6 ) {
     $tsql6 = "SELECT SUM(ORDER_QTY) as lastqty FROM dbo.invt_log WHERE PROD_CD = '".$name."' AND LOG_DT > '".$currentd."'" ;  
                $stmt6 = sqlsrv_query($conn6, $tsql6);  
                if ($stmt6 === false) {
                    echo "Error in query execution";  
                    echo "<br>";  
                    die(print_r(sqlsrv_errors(), true));  
                }
                while($row6 = sqlsrv_fetch_array($stmt6, SQLSRV_FETCH_ASSOC)) {
                   
                  echo '<tr><th>Last 30 Days Sale Qty:</th><td>'.round($row6['lastqty'],2).'</td></tr>';
                }  
               

                sqlsrv_free_stmt($stmt6);  
                sqlsrv_close( $conn6); 

}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>
<?php

$serverName1 = "win2003sql"; //serverName\instanceName
$connectionInfo1 = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$conn1 = sqlsrv_connect( $serverName1, $connectionInfo1);
$namea = $name . "X";
if( $conn1 ) {
     $tsql1 = "SELECT * FROM dbo.inv LEFT JOIN dbo.inv_data ON dbo.inv.PROD_CD = dbo.inv_data.PROD_CD WHERE dbo.inv.PROD_CD = '".$namea."'" ;  
                $stmt1 = sqlsrv_query($conn1, $tsql1);  
                if ($stmt1 === false) {
                    echo "Error in query execution";  
                    echo "<br>";  
                    die(print_r(sqlsrv_errors(), true));  
                }
                while($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                   $invafter = (float)$row1['IN_STOCK'];
                   $percase = (float)$row1['PC_CASE'];
                   if(trim($row1['CAT_NUM']) == 'Active'){
                   $xcase = $invafter / $percase;}
                   else{
                       $xcase = 0;
                   }
                   echo "<tr><th>IN STOCK:</th><td>";
                   echo $instock + $xcase.'</td></tr></tbody></table>';
                   $flag2=1;
                }  
                if($flag2 != 1){
                    if($flag1 == 'X'||$flag1 =='x'){
                        echo "</tbody></table>";
                    }
                        else{
                   echo "<tr><th>IN STOCK:</th><td>";
                   echo $instock .'</td></tr></tbody></table>'; }
                   
                }

                sqlsrv_free_stmt($stmt1);  
                sqlsrv_close( $conn1); 

}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>

<form action="omsinv.php" method="post" accept-charset="UTF-8">
<?php 
if($flag1 == 'X'||$flag1 == 'x')
{echo '<p style="display:none"><input type="text" name="name" value="'.$name1.'" readonly></p>';
echo '<p><input class="btn btn-link" type="submit" value="Please go to Item No.'.$name1.'"></p>';}
?>
</form>
<?php

$serverName2 = "win2003sql"; //serverName\instanceName
$connectionInfo2 = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$conn2 = sqlsrv_connect( $serverName2, $connectionInfo2);
if( $conn2 ) {
     $tsql2 = "SELECT * FROM dbo.inv_upc WHERE PROD_CD = '".$name."'" ;  
                $stmt2 = sqlsrv_query($conn2, $tsql2);  
                if ($stmt2 === false) {
                    echo "Error in query execution";  
                    echo "<br>";  
                    die(print_r(sqlsrv_errors(), true));  
                }
                while($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                   $upccd = $row2['UPC_CD'];
                   trim($upccd);
                   echo '<br>';
                   echo "UPC:";
                   echo $upccd.'<br>';
                   $temp=array('1','2','3','4','5','6','7','8','9','0'); 
    $result=''; 
    for($i=0;$i<strlen($upccd);$i++){ 
        if(in_array($upccd[$i],$temp)){ 
            $result.=$upccd[$i]; 
        } }
                   if($flag1 != 'X')
                   echo '<img src=\'https://barcode.tec-it.com/barcode.ashx?data=' . $result. '&code=UPCA&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=96&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&qunit=Mm&quiet=0\' alt=\'Barcode Generator TEC-IT\'/>';
                }  
                sqlsrv_free_stmt($stmt2);  
                sqlsrv_close( $conn2); 

}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>

<?php

$conn3 = sqlsrv_connect($serverName, $connectionInfo);
if( $conn3 ) {
     $tsql3 = "SELECT * FROM dbo.pur_ord LEFT JOIN dbo.plog ON dbo.pur_ord.PUR_NUM = dbo.plog.PUR_NUM AND dbo.plog.PROD_CD = '".$name."' WHERE dbo.pur_ord.PUR_NUM IN (SELECT dbo.plog.PUR_NUM FROM dbo.plog LEFT JOIN dbo.inv_data ON dbo.plog.PROD_CD = dbo.inv_data.PROD_CD WHERE dbo.plog.PUR_NUM IN (SELECT SUB.PUR_NUM FROM (SELECT PUR_NUM, COUNT(*) AS PQTY FROM dbo.plog WHERE PROD_CD = '".$name."' AND LOG_QTY>0 GROUP BY PUR_NUM) SUB WHERE PQTY = 1) AND dbo.inv_data.PROD_CD = '".$name."') ";
                $stmt3 = sqlsrv_query($conn3, $tsql3);  
                if ($stmt3 === false) {
                    echo "Error in query execution";  
                    echo "<br>";  
                    die(print_r(sqlsrv_errors(), true));  
                }
                if($onorderqty>0){
                
                echo "<table><thead><tr><th>P/O Date</th><th>Est Rev Dt</th><th>P/O No.</th><th>Vendor#</th><th> QTY</th></tr></thead><tbody>";
                }
                while($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
                  if($onorderqty > 0){
                  
                  $daysToEpoch = 61730;
                  $FromEpoch=($row3['PUR_DT']-$daysToEpoch)*86400;
                  
                  echo "<tr><td>".date("m/d/y",$FromEpoch)."</td><td>".date("m/d/y",$FromEpoch);
                  echo "</td><td>".$row3['PUR_NUM']."</td><td>".$row3['VEN_ID']."</td><td>".round($row3['LOG_QTY'],2)."</td></tr>";
                  

                  }
                } 
                echo "</tbody></table>"; 
                sqlsrv_free_stmt($stmt3);  
                sqlsrv_close( $conn3); 

}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>
</div>

<div class="links">
                    <p><a class="btn btn-primary btn-lg" href="/ntfoods/public/inventory">BACK</a></p>
                    
</div>

</body>
</html>