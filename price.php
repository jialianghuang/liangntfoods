@extends('layouts.app')

@section('content')

<?php
$epoch = time()/86400;
$currentd = intval($epoch) + 61730;
$serverName = "win2003sql"; 
$connectionInfo = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn ) {
    $tsql = "SELECT dbo.plog.INVS_NUM,dbo.plog.LOG_DATE,dbo.plog.PROD_CD,dbo.plog.BASE_COST,dbo.plog.UT_DESC,dbo.inv_data.AVG_COST,dbo.inv_data.PRICE_BASE,dbo.inv.RETAIL_PRS,dbo.inv.CORP_PRS,dbo.inv.WHOLE_PRS FROM dbo.plog  LEFT JOIN dbo.inv ON dbo.plog.PROD_CD = dbo.inv.PROD_CD LEFT JOIN dbo.inv_data ON dbo.plog.PROD_CD = dbo.inv_data.PROD_CD WHERE dbo.plog.INVS_NUM <> '0'  AND dbo.plog.BASE_COST <> dbo.inv_data.PRICE_BASE AND dbo.plog.LOG_DATE = '".$currentd."' ORDER BY dbo.plog.PROD_CD" ;  
               $stmt = sqlsrv_query($conn, $tsql);

               $x = 0;  
               if ($stmt === false) {
                   echo "Error in query execution";  
                   echo "<br>";  
                   die(print_r(sqlsrv_errors(), true));  
               }
               while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                   $object[$x]['LOG_DATE'] = $row['LOG_DATE'];
                   $object[$x]['PROD_CD'] = $row['PROD_CD'];
                   $object[$x]['BASE_COST'] = $row['BASE_COST'];
                   $object[$x]['UT_DESC'] = $row['UT_DESC'];
                   $object[$x]['AVG_COST'] = $row['AVG_COST'];
                   $object[$x]['RETAIL_PRS']=$row['RETAIL_PRS'];
                   $object[$x]['CORP_PRS']=$row['CORP_PRS'];
                   $object[$x]['WHOLE_PRS']=$row['WHOLE_PRS'];
                   $x++;
                   
            }
            sqlsrv_free_stmt($stmt);  
                sqlsrv_close( $conn); 

}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>
<?php
$servername1 = "localhost";
$username1 = "root";
$password1 = "";
$dbname = "mysql";
$z = 0;
// Create connection
$conn1 = new mysqli($servername1, $username1, $password1, $dbname);
// Check connection
if ($conn1->connect_error) {
    die("Connection failed: " . $conn1->connect_error);}
$sql = "SELECT * FROM pfactor";
$result = $conn1->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row1 = $result->fetch_assoc()) {
        $object1[$z]['PROD_CD'] = $row1['PROD_CD'];
        $object1[$z]['FACTOR1'] = $row1['FACTOR1'];
        $object1[$z]['FACTOR2'] = $row1['FACTOR2'];
        $object1[$z]['FACTOR3'] = $row1['FACTOR3'];
        $z++;
    }
} else {
    echo "0 results";
}
$conn1->close();
$m = 0;
$n = 0;
for($m=0;$m<$x;$m++){
    for($n=0;$n<$z;$n++){
        if(trim($object[$m]['PROD_CD'])==trim($object1[$n]['PROD_CD']))
        {
            $object[$m]['FACTOR1'] = $object1[$n]['FACTOR1'];
            $object[$m]['FACTOR2'] = $object1[$n]['FACTOR2'];
            $object[$m]['FACTOR3'] = $object1[$n]['FACTOR3'];
        }
    }
   
}
?>



<div class="container">
<table class = "table">
<thead>
<tr>
<th scope = "col">Item#</th>
<th scope = "col">Descrip</th>
<th scope = "col">Price1</th>
<th scope = "col">Price2</th>
<th scope = "col">Price3</th>
<th scope = "col">Price4</th>
<th scope = "col">Price5</th>
<th scope = "col">Unit Cost</th>
<th scope = "col">Avg. Cost</th>
<th scope = "col">x1</th>
<th scope = "col">x1</th>
<th scope = "col">x1</th>
</tr>
</thead>
<tbody>
<?php 

for($j=0;$j<$x;$j++){
    if($object[$j]['FACTOR1']==0){  $object[$j]['FACTOR1']=$object[$j]['RETAIL_PRS']/$object[$j]['AVG_COST'];
        $object[$j]['FACTOR2']=$object[$j]['CORP_PRS']/$object[$j]['AVG_COST'];
        $object[$j]['FACTOR3']=$object[$j]['WHOLE_PRS']/$object[$j]['AVG_COST'];}
    echo '<tr><th scope="row">'.$object[$j]['PROD_CD'].'</th>';
    echo '<td>'.$object[$j]['UT_DESC'].'</td>';
    if($object[$j]['AVG_COST']>$object[$j]['BASE_COST']){
         
    echo '<td>'.number_format($object[$j]['AVG_COST']*$object[$j]['FACTOR1'],2).'</td>';
    echo '<td>'.number_format($object[$j]['AVG_COST']*$object[$j]['FACTOR2'],2).'</td>';
    echo '<td>'.number_format($object[$j]['AVG_COST']*$object[$j]['FACTOR3'],2).'</td>';
    echo '<td>0</td><td>0</td>';
        
    }
    else{
    echo '<td>'.number_format($object[$j]['BASE_COST']*$object[$j]['FACTOR1'],2).'</td>';
    echo '<td>'.number_format($object[$j]['BASE_COST']*$object[$j]['FACTOR2'],2).'</td>';
    echo '<td>'.number_format($object[$j]['BASE_COST']*$object[$j]['FACTOR3'],2).'</td>';
    echo '<td>0</td><td>0</td>';
    }
    echo '<td>'.number_format($object[$j]['BASE_COST'],3).'</td>';
    echo '<td>'.number_format($object[$j]['AVG_COST'],3).'</td>';
    echo '<td>'.number_format($object[$j]['FACTOR1'],2).'</td><td>'.number_format($object[$j]['FACTOR2'],2).'</td><td>'.number_format($object[$j]['FACTOR3'],2).'</td></tr>';
}

?>
</tbody>
</table>
<?php 
if($j==0){
    echo '<p>No price update</p>';
}
?>
<form class="form-inline" action="" method="post">
<div class="form-group">
<h4>Search Item</h4>
<input class="form-control" name="itemno">
</div>
<button type="submit" class="btn btn-primary mb-2">Search</button>
</form>
<?php
use Illuminate\Support\Facades\DB;

if($_POST){
$productid = $_POST["itemno"];

$factors = DB::table('pfactor')->where('PROD_CD',$productid)->get();
foreach($factors as $factor){
    echo $factor->PROD_CD;
    echo $factor->FACTOR1;
    echo $factor->FACTOR2;
    echo $factor->FACTOR3;
    echo $factor->FACTOR4;
    echo $factor->FACTOR5;
}
}
?>
</div>

@endsection