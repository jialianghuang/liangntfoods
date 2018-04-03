@extends('layouts.app')

@section('content')

<!-- start -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/bootstrap-select.css">

  <style>
    body {
      padding-top: 70px;
    }
  </style>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="dist/js/bootstrap-select.js"></script>
<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
<form class="form-inline" action="pricekey.store" method="POST">
@csrf
<div class="form-group mx-sm-3 mb-2">
<label class="col-form-label">Search Item# from: &nbsp</label>
<input class="form-control" name="itemno">
<label class="col-form-label">to: &nbsp</label>
<input class="form-control" name="itemnoto">
</div>
CLASS:
<select name= "classname[]" class="selectpicker" multiple>
  <option value="BC">BC</option>
  <option value="BF">BF</option>
  <option value="BV">BV</option>
  <option value="BX">BX</option>
  <option value="BY">BY</option>
  <option value="CD">CD</option>
  <option value="CK">CK</option>
  <option value="CL">CL</option>
  <option value="CN">CN</option>
  <option value="CO">CO</option>
  <option value="CR">CR</option>
  <option value="CS">CS</option>
  <option value="DISC">DISC</option>
  <option value="DISP">DISP</option>
  <option value="DL">DL</option>
  <option value="DP">DP</option>
  <option value="DS">DS</option>
  <option value="DU">DU</option>
  <option value="DY">DY</option>
  <option value="EQ">EQ</option>
  <option value="FE">FE</option>
  <option value="FN">FN</option>
  <option value="GF">GF</option>
  <option value="KI">KI</option>
  <option value="LD">LD</option>
  <option value="LM">LM</option>
  <option value="LQ">LQ</option>
  <option value="PL">PL</option>
  <option value="PO">PO</option>
  <option value="PR">PR</option>
  <option value="RA">RA</option>
  <option value="RF">RF</option>
  <option value="RX">RX</option>
  <option value="SF">SF</option>
  <option value="SN">SN</option>
  <option value="ST">ST</option>
  <option value="WR">WR</option>
</select>
&nbsp DEPT:<select name = "deptname[]" class="selectpicker" multiple>
<option value="BC">BC</option>
<option value="BD">BD</option>
<option value="BL">BL</option>
<option value="BOBA">BOBA</option>
<option value="BR">BR</option>
<option value="CH">CH</option>
<option value="CN">CN</option>
<option value="CO">CO</option>
<option value="CP">CP</option>
<option value="CR">CR</option>
<option value="CT">CT</option>
<option value="DE">DE</option>
<option value="DM">DM</option>
<option value="DN">DN</option>
<option value="DR">DR</option>
<option value="EQ">EQ</option>
<option value="ER">ER</option>
<option value="FL">FL</option>
<option value="FN">FN</option>
<option value="FO">FO</option>
<option value="FR">FR</option>
<option value="FS">FS</option>
<option value="FZ">FZ</option>
<option value="G">G</option>
<option value="GL">GL</option>
<option value="GT">GT</option>
<option value="JP">JP</option>
<option value="KO">KO</option>
<option value="LQ">LQ</option>
<option value="MIX">MIX</option>
<option value="MS">MS</option>
<option value="MT">MT</option>
<option value="MX">MX</option>
<option value="NS">NS</option>
<option value="PL">PL</option>
<option value="PP">PP</option>
<option value="R">R</option>
<option value="RF">RF</option>
<option value="SC">SC</option>
<option value="SD">SD</option>
<option value="SF">SF</option>
<option value="SG">SG</option>
<option value="SL">SL</option>
<option value="SN">SN</option>
<optoin value="SP">SP</option>
<option value="ST">ST</option>
<option value="SW">SW</option>
<option value="SY">SY</option>
<optoin value="SYP">SYP</option>
<option value="TEA">TEA</option>
<option value="UT">UT</option>
<option value="WE">WE</option>
<option value="WN">WN</option>
<option value="WR">WR</option>
</select>
&nbsp<button type="submit" class="btn btn-primary mb-2">Search</button>
</form>
<?php
use Illuminate\Support\Facades\DB;
if($_POST){
$productid = $_POST["itemno"];
$productidto = $_POST['itemnoto'];
$classids = $_POST['classname'];
$deptids = $_POST['deptname'];
//OMS DATA
$serverName = "win2003sql"; 
$connectionInfo = array( "Database"=>"omsdata",  "Uid"=>"emaster", "PWD"=>"emaster", "CharacterSet"=>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn ) {
    $tsql = "SELECT dbo.inv_data.FRT_CUS,dbo.inv_data.HANDL_FEE,dbo.inv.PROD_CD,dbo.inv.DESCRIP,dbo.inv.CLASS_CD,dbo.inv.DEPT_NUM,dbo.inv.WHOLE_PRS2,dbo.inv.WHOLE_PRS3,dbo.inv_data.AVG_COST,dbo.inv_data.PRICE_BASE,dbo.inv.RETAIL_PRS,dbo.inv.CORP_PRS,dbo.inv.WHOLE_PRS FROM dbo.inv LEFT JOIN dbo.inv_data ON dbo.inv.PROD_CD = dbo.inv_data.PROD_CD WHERE dbo.inv.PROD_CD >= '".$productid."' AND dbo.inv.PROD_CD < '".$productidto."' ORDER BY dbo.inv.PROD_CD" ;  
               $stmt = sqlsrv_query($conn, $tsql);

               $x = 0;  
               if ($stmt === false) {
                   echo "Error in query execution";  
                   echo "<br>";  
                   die(print_r(sqlsrv_errors(), true));  
               }
               while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $object[$x]['PROD_CD'] = $row['PROD_CD'];
                $object[$x]['BASE_COST'] = $row['PRICE_BASE']+$row['FRT_CUS']+$row['HANDL_FEE'];
                $object[$x]['UT_DESC'] = $row['DESCRIP'];
                $object[$x]['AVG_COST'] = $row['AVG_COST']+$row['FRT_CUS']+$row['HANDL_FEE'];
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
echo '<label style="padding-left:15px;padding-right:15px">Item#</label><label style="padding-left:72px;padding-right:15px">Price1</label><label style="padding-left:70px;padding-right:15px">Price2</label><label style="padding-left:70px;padding-right:15px">Price3</label><label style="padding-left:70px;padding-right:15px">Price4</label><label style="padding-left:70px;padding-right:15px">Price5</label><label style="padding-left:35px;padding-right:15px">Unit Cost</label><label style="padding-left:60px;padding-right:15px">Avg Cost</label><br>';
$factors = DB::table('pdfactor')->where([['PROD_CD','>=',$productid],['PROD_CD','<',$productidto]])->get();
foreach($factors as $factor){
    for($xx=0;$xx<$x;$xx++)
    {if(trim($object[$xx]['PROD_CD'])==trim($factor->PROD_CD)){
    echo '<div class="form-group col-md-1"><label>'.$object[$xx]['PROD_CD'].'</label></div>';
    echo '<div class="form-group col-md-1"><label>'.number_format($object[$xx]['RETAIL_PRS'],2).'</label></div>';
    echo '<div class="form-group col-md-1"><label>'.number_format($object[$xx]['CORP_PRS'],2).'</label></div>';
    echo '<div class="form-group col-md-1"><label>'.number_format($object[$xx]['WHOLE_PRS'],2).'</label></div>';
    echo '<div class="form-group col-md-1"><label>'.number_format($object[$xx]['WHOLE_PRS2'],2).'</label></div>';
    echo '<div class="form-group col-md-1"><label>'.number_format($object[$xx]['WHOLE_PRS3'],2).'</label></div>';
    echo '<div class="form-group col-md-1"><label>'.$object[$xx]['BASE_COST'].'</label></div>';
    echo '<div class="form-group col-md-1"><label>'.$object[$xx]['AVG_COST'].'</label></div>';
    echo '<br>';
  }}
    echo '<br><form action="pricekey.factor" method="POST">'.csrf_field().'<div class="form-row">';
    echo '<div class="form-group col-md-1"><label style="font-weight:400;padding-left:10px">Factors</label></div>';
    echo '<input type="hidden" name="id" value="'.$factor->PROD_CD.'">';
    echo '<div class="form-group col-md-1"><input name="FACTOR1" class="form-control" value="'.$factor->FACTOR1.'"></div>';
    echo '<div class="form-group col-md-1"><input name="FACTOR2" class="form-control" value="'.$factor->FACTOR2.'"></div>';
    echo '<div class="form-group col-md-1"><input name="FACTOR3" class="form-control" value="'.$factor->FACTOR3.'"></div>';
    echo '<div class="form-group col-md-1"><input name="FACTOR4" class="form-control" value="'.$factor->FACTOR4.'"></div>';
    echo '<div class="form-group col-md-1"><input name="FACTOR5" class="form-control" value="'.$factor->FACTOR5.'"></div>';
    echo '<button type="reset" class="btn btn-primary ml-2 mb-3">Reset</button><button type="submit" class="btn btn-primary ml-2 mb-3">Apply</button></div></form>';
}
}
?>
@endsection