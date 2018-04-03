@extends('layouts.app')

@section('content')

<?php
$epoch = time()/86400;
$currentd = intval($epoch) + 61729;
$serverName = "win2003sql"; 
$connectionInfo = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn ) {
    $tsql = "SELECT dbo.inv.CLASS_CD,dbo.inv.DEPT_NUM,dbo.inv.WHOLE_PRS2,dbo.inv.WHOLE_PRS3,dbo.plog.INVS_NUM,dbo.plog.LOG_DATE,dbo.plog.PROD_CD,dbo.plog.BASE_COST,dbo.plog.UT_DESC,dbo.inv_data.AVG_COST,dbo.inv_data.PRICE_BASE,dbo.inv.RETAIL_PRS,dbo.inv.CORP_PRS,dbo.inv.WHOLE_PRS FROM dbo.plog  LEFT JOIN dbo.inv ON dbo.plog.PROD_CD = dbo.inv.PROD_CD LEFT JOIN dbo.inv_data ON dbo.plog.PROD_CD = dbo.inv_data.PROD_CD WHERE dbo.plog.INVS_NUM <> '0'  AND dbo.plog.BASE_COST <> dbo.inv_data.PRICE_BASE AND dbo.plog.LOG_DATE > '".$currentd."' ORDER BY dbo.plog.PROD_CD" ;  
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
                   $object[$x]['BASE_COST'] = $row['PRICE_BASE'];
                   $object[$x]['UT_DESC'] = $row['UT_DESC'];
                   $object[$x]['AVG_COST'] = $row['AVG_COST'];
                   $object[$x]['RETAIL_PRS']=$row['RETAIL_PRS'];
                   $object[$x]['CORP_PRS']=$row['CORP_PRS'];
                   $object[$x]['WHOLE_PRS']=$row['WHOLE_PRS'];
                   $object[$x]['WHOLE_PRS2']=$row['WHOLE_PRS2'];
                   $object[$x]['WHOLE_PRS3']=$row['WHOLE_PRS3'];
                   $object[$x]['CLASS']=$row['CLASS_CD'];
                   $object[$x]['DEPT']=$row['DEPT_NUM'];
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
$sql = "SELECT * FROM pdfactor";
$result = $conn1->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row1 = $result->fetch_assoc()) {
        $object1[$z]['PROD_CD'] = $row1['PROD_CD'];
        $object1[$z]['FACTOR1'] = $row1['FACTOR1'];
        $object1[$z]['FACTOR2'] = $row1['FACTOR2'];
        $object1[$z]['FACTOR3'] = $row1['FACTOR3'];
        $object1[$z]['FACTOR4'] = $row1['FACTOR4'];
        $object1[$z]['FACTOR5'] = $row1['FACTOR5'];
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
            $object[$m]['FACTOR4'] = $object1[$n]['FACTOR4'];
            $object[$m]['FACTOR5'] = $object1[$n]['FACTOR5'];
        }
    }
   
}
?>



<div class="container">
<table class = "sortable table">
<thead>
<tr>
<th scope = "col" style="cursor:pointer">Item#</th>
<th scope = "col">Descrip</th>
<th scope = "col" style="cursor:pointer">CLASS</th>
<th scope = "col" style="cursor:pointer">DEPT</th>
<th scope = "col">Price1</th>
<th scope = "col">Price2</th>
<th scope = "col">Price3</th>
<th scope = "col">Price4</th>
<th scope = "col">Price5</th>
<th scope = "col">Unit Cost</th>
<th scope = "col">Avg. Cost</th>
<!-- <th scope = "col">x1</th>
<th scope = "col">x1</th>
<th scope = "col">x1</th> -->
</tr>
</thead>
<tbody>
<?php 

for($j=0;$j<$x;$j++){
    if(!empty($object[$j]['FACTOR1'])){    
    echo '<tr><th scope="row">'.$object[$j]['PROD_CD'].'</th>';
    echo '<td>'.$object[$j]['UT_DESC'].'</td><td>'.$object[$j]['CLASS'].'</td><td>'.$object[$j]['DEPT'].'</td>';
    echo '<td>'.number_format($object[$j]['RETAIL_PRS'],2).'</td><td>'.number_format($object[$j]['CORP_PRS'],2).'</td><td>'.number_format($object[$j]['WHOLE_PRS'],2).'</td><td>'.number_format($object[$j]['WHOLE_PRS2'],2).'</td><td>'.number_format($object[$j]['WHOLE_PRS3'],2).'</td></tr>';
    //new prices
    echo '<tr class="table-active font-weight-bold"><th scope="row">'.$object[$j]['PROD_CD'].'</th>';
    echo '<td>'.$object[$j]['UT_DESC'].'</td><td>'.$object[$j]['CLASS'].'</td><td>'.$object[$j]['DEPT'].'</td>';
    if($object[$j]['AVG_COST']>$object[$j]['BASE_COST']){
         
    echo '<td>'.number_format($object[$j]['AVG_COST']*$object[$j]['FACTOR1'],2).'</td>';
    echo '<td>'.number_format($object[$j]['AVG_COST']*$object[$j]['FACTOR2'],2).'</td>';
    echo '<td>'.number_format($object[$j]['AVG_COST']*$object[$j]['FACTOR3'],2).'</td>';
    echo '<td>'.number_format($object[$j]['AVG_COST']*$object[$j]['FACTOR4'],2).'</td>';
    echo '<td>'.number_format($object[$j]['AVG_COST']*$object[$j]['FACTOR5'],2).'</td>';
    
        
    }
    else{
    echo '<td>'.number_format($object[$j]['BASE_COST']*$object[$j]['FACTOR1'],2).'</td>';
    echo '<td>'.number_format($object[$j]['BASE_COST']*$object[$j]['FACTOR2'],2).'</td>';
    echo '<td>'.number_format($object[$j]['BASE_COST']*$object[$j]['FACTOR3'],2).'</td>';
    echo '<td>'.number_format($object[$j]['BASE_COST']*$object[$j]['FACTOR4'],2).'</td>';
    echo '<td>'.number_format($object[$j]['BASE_COST']*$object[$j]['FACTOR5'],2).'</td>';
    
    }
    echo '<td>'.number_format($object[$j]['BASE_COST'],3).'</td>';
    echo '<td>'.number_format($object[$j]['AVG_COST'],3).'</td>';
    echo '</tr>';
    //echo '<td>'.number_format($object[$j]['FACTOR1'],2).'</td><td>'.number_format($object[$j]['FACTOR2'],2).'</td><td>'.number_format($object[$j]['FACTOR3'],2).'</td></tr>';
}
else{
    echo '<tr><th scope="row">'.$object[$j]['PROD_CD'].'</th>';
    echo '<td>'.$object[$j]['UT_DESC'].'</td>';
    echo '<td>Error:No factor</td></tr>';
}
}

?>
</tbody>
</table>



<?php 
if($j==0){
    echo '<p>No price update</p>';
}
?>
<?php $user = \Auth::user()->id;
if($user == 4)
{   echo '<form class="form-inline" action="prices.store" method="POST">'.csrf_field().'<div class="form-group mx-sm-3 mb-2">
<label class="col-form-label">Search Item: &nbsp</label>
<input class="form-control" name="itemno">
</div>
<button type="submit" class="btn btn-primary mb-2">Search</button>
</form>';}

use Illuminate\Support\Facades\DB;

if($_POST){
$productid = $_POST["itemno"];

$factors = DB::table('pdfactor')->where('PROD_CD',$productid)->get();
foreach($factors as $factor){
    echo '<br><form action="prices.factor" method="POST">'.csrf_field().'<div class="form-row">';
    echo '<div class="form-group col-md-1"><label>'.$factor->PROD_CD.'</label></div>';
    echo '<input type="hidden" name="id" value="'.$factor->PROD_CD.'">';
    echo '<div class="form-group col-md-1"><input name="FACTOR1" class="form-control" value="'.$factor->FACTOR1.'"></div>';
    echo '<div class="form-group col-md-1"><input name="FACTOR2" class="form-control" value="'.$factor->FACTOR2.'"></div>';
    echo '<div class="form-group col-md-1"><input name="FACTOR3" class="form-control" value="'.$factor->FACTOR3.'"></div>';
    echo '<div class="form-group col-md-1"><input name="FACTOR4" class="form-control" value="'.$factor->FACTOR4.'"></div>';
    echo '<div class="form-group col-md-1"><input name="FACTOR5" class="form-control" value="'.$factor->FACTOR5.'"></div>';
    echo '<button type="reset" class="btn btn-primary ml-2 mb-3">Reset</button><button type="submit" class="btn btn-primary ml-2 mb-3">Edit</button></div></form>';
}
}
?>

</div>

@endsection