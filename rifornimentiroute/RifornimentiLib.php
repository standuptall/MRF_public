<?php
include_once("FantaApp.php");
include_once("Utils.php");
include_once("../FantaApp.php");
include_once("../Utils.php");
class RifornimentiLib
{
	function GetRifornimenti($argument,$range=false)
    {
    	$app = FantaApp::GetSingleTon();
        
        $order = "data";
        $where = ($argument>0 ? " 1=1 AND  ID=".$argument : "1");
        if ($range)
        {
          $first = $app->Sql()->Fetch_L("Rifornimenti","primo=1","data")[0];
          $last = $app->Sql()->Fetch_L("Rifornimenti","ultimo=1","data")[0];
          $ret1 = $app->Sql()->Fetch_L("Rifornimenti","ID>=".$first["ID"]." AND ID<=".$last["ID"],"data");
        }
        else
        	$ret1 = $app->Sql()->Fetch_L("Rifornimenti",$where,$order);
        $ret2 = array();
        foreach($ret1 as $sin)
        {
          $sin["litri"] = strval($sin["importo"]/$sin["costo"]);
          $sin["stato"] = $sin["primo"]==1 ? "→" : ($sin["ultimo"]==1 ? "←" : "") ;
          array_push($ret2,$sin);
        }
        return $ret2;
    }
    function GetCosto(){
    	$app = FantaApp::GetSingleTon();
        $ret1 = $this->GetRifornimenti(0,true);
        $costo = array();
        foreach($ret1 as $rif){
        	array_push($costo,floatval($rif["importo"]));
        }
        return round(array_sum($costo),2);
    }
    function GetCostoOgniCentoChilometri(){
    	$app = FantaApp::GetSingleTon();
        $ret1 = $this->GetRifornimenti(0,true);
        $costo = array();
        foreach($ret1 as $rif){
        	array_push($costo,floatval($rif["costo"]));
        }
        $medio = array_sum($costo)/count($costo);
        return round($medio * $this->GetConsumoMedio(),2);
    }
    function GetConsumoMedio(){
    	$app = FantaApp::GetSingleTon();
        $ret1 = $this->GetRifornimenti(0,true);
        $litri = array();
        foreach($ret1 as $rif){
        	array_push($litri,floatval($rif["litri"]));
        }
        $seriesy = $this->GetYSeries();
        $prog = $this->GetStepSeries();
        $cnt = count($litri);
        $arr_cons = array();
        $nominatore_ponderata = 0;
        for($i=0;$i<$cnt-1;$i++){
        	$cons  = $litri[$i]*100/$prog[$i];
        	//echo $litri[$i] . '*100/'.$prog[$i].'='.$cons.'<br/>';
            $nominatore_ponderata += ($cons*$prog[$i]);
        }
        //echo $nominatore_ponderata;
        $ponderata= $nominatore_ponderata/array_sum($prog);
        /*$deltay = $seriesy[count($seriesy)-1] - $seriesy[0];
        $deltax =  array_sum($litri) - $litri[count($seriesy)-1];
        return round($deltax/$deltay*100,2);*/
        return round($ponderata,2);
    }
    function GetVarianza(){
    	$series = $this->GetXSeries();
        $average = array_sum($series)/count($series);
        $vararray = array();
        foreach($series as $rif){
        	array_push($vararray,($rif-$average)*($rif-$average));
        }
        return array_sum($vararray)/(count($vararray) - 1);
    }
    function GetChilometriMensili(){
    	$varianza = $this->GetVarianza();
    	$covarianza = $this->GetCovarianza();
        $beta = $covarianza/$varianza;
        $km = $beta * 30;
        return round($km,2);
    }
    function GetCovarianza(){
    	$seriesx = $this->GetXSeries();
    	$seriesy = $this->GetYSeries();
        $averagex = array_sum($seriesx)/count($seriesx);
        $averagey = array_sum($seriesy)/count($seriesy);
        $vararray = array();
        $acnt = count($seriesx);
        for($i=0;$i<$acnt;$i++){
        	$ix = $seriesx[$i];
            $iy = $seriesy[$i];
        	array_push($vararray,($ix-$averagex)*($iy-$averagey));
        }
        return array_sum($vararray)/(count($vararray) - 1);
    }
    function GetYSeries(){
    	$app = FantaApp::GetSingleTon();
        $ret1 = $this->GetRifornimenti(0,true);
        $series = array();
        foreach($ret1 as $rif){
        	array_push($series,floatval($rif["tachimetro"]));
        }
        return $series;
    }
    function GetStepSeries(){
    	$app = FantaApp::GetSingleTon();
        $ret1 = $this->GetRifornimenti(0,true);
        $series = array();
        $cnt = count($ret1);
        for($i=1;$i<$cnt;$i++){
       	 	$act = $ret1[$i];
            $prec = $ret1[$i-1];
        	array_push($series,floatval($act["tachimetro"])-floatval($prec["tachimetro"])+floatval($act["residuo"]));
        }
        return $series;
    }
    function GetXSeries(){
    	$app = FantaApp::GetSingleTon();
        $ret1 = $this->GetRifornimenti(0,true);
        $min = new DateTime('2030-10-11');
        $max = new DateTime('2009-10-11');
        foreach($ret1 as $rif){
            $dcomp = DateTime::createFromFormat('Y-m-d',$rif["data"]); 
        	if ($dcomp < $min)
            	$min = $dcomp;
        	if ($dcomp > $max)
            	$max = $dcomp;
        }
        $series = array();
        foreach($ret1 as $rif){
            $dcomp = DateTime::createFromFormat('Y-m-d', $rif["data"]);
        	array_push($series,date_diff($dcomp,$min)->days);
        }
        return $series;
    }
    
    function SetFirst($id){
    	$app = FantaApp::GetSingleTon();
        $ret1 = $app->Sql()->DoQuery("UPDATE Rifornimenti SET primo = 0");
        $ret1 = $app->Sql()->DoQuery("UPDATE Rifornimenti SET primo = 1 WHERE ID=".$id);
    }
    function SetLast($id){
    	$app = FantaApp::GetSingleTon();
        $ret1 = $app->Sql()->DoQuery("UPDATE Rifornimenti SET ultimo = 0");
        $ret1 = $app->Sql()->DoQuery("UPDATE Rifornimenti SET ultimo = 1 WHERE ID=".$id);
    }
    
    private static function cmp($a, $b)
    {
        if ($a["ingredientimancanti"] == $b["ingredientimancanti"]) {
            return 0;
        }
        return ($a["ingredientimancanti"] < $b["ingredientimancanti"]) ? -1 : 1;
    }
}
?>

