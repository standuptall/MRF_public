<?php
include_once("FantaApp.php");
include_once("Utils.php");
include_once("../FantaApp.php");
include_once("../Utils.php");
class MenuLib
{
	function GetMealByDay($day)
    {
    	$app = FantaApp::GetSingleTon();
        $ret = $app->Sql()->Fetch_L("Menu_Pasto","data='".$day->format("Y-m-d")."'","pranzocena");
        $pasti = array();
        foreach($ret as $obj)
        {
        	$pasto = new Pasto();
            $pasto->ID = $obj["ID"];
            $pasto->idricetta = $obj["idricetta"];
            $pasto->nomericetta = $obj["nomericetta"];
            $pasto->data = $obj["data"];
            $pasto->pranzocena = $obj["pranzocena"]==1;
            $ret23 = $app->Sql()->Fetch_L("Menu_Ricetta","ID='".$pasto->idricetta."'")[0];
            $pasto->descrizionericetta = $ret23["descrizione"];
            $ret2 = $app->Sql()->Fetch_L("Menu_PastoIngredienti","idpasto='".$pasto->ID."'");
            $pasto->ingredientipresenti = "red";
            $ct = 0;
            foreach($ret2 as $obj2)
            {
            	$ingrediente = new Ingrediente();
                $ingrediente->ID = $obj2["ID"];
                $ingrediente->idpasto = $pasto->ID;
                $ingrediente->nome = $obj2["ingrediente"];
                $ingrediente->presente = $obj2["presente"] == 1;
                if ($ingrediente->presente)
                	$ct++;
                array_push($pasto->ingredienti, $ingrediente);
            }
            if ($ct == count($ret2))
            	$pasto->ingredientipresenti = "badge badge-success";
            if ($ct < count($ret2))
            	$pasto->ingredientipresenti = "badge badge-warning";
            if ($ct == 0)
            	$pasto->ingredientipresenti = "badge badge-danger";
            array_push($pasti,$pasto);
        }
        return $pasti;
    }
    
    function SaveMeal($date,$obj)
    {
    	//trovo prima il pasto(pranzo o cena), se esiste lo cancello, poi inserisco il nuovo
    	$app = FantaApp::GetSingleTon();
        $ret = $app->Sql()->Fetch_L("Menu_Pasto","data='".$date->format("Y-m-d")."' AND pranzocena=".($obj->pranzocena ? "1" : "0"));
        if (count($ret)>0)
        {
        	$app->Sql()->DeleteRecord_L("Menu_Pasto","ID=".$ret[0]["ID"]);
            $app->Sql()->DeleteRecord_L("Menu_PastoIngredienti","idpasto=".$ret[0]["ID"]);
        }
        $pasto["idricetta"] = $obj->idricetta;
        $pasto["nomericetta"] = $obj->nomericetta;
        $pasto["data"] = $date->format("Y-m-d");
        $pasto["pranzocena"] = $obj->pranzocena ? 1 : 0;
        $app->Sql()->AddRecord_L("Menu_Pasto",$pasto);
        $ret = $app->Sql()->Fetch_L("Menu_Pasto","data='".$date->format("Y-m-d")."' AND pranzocena=".($obj->pranzocena ? "1" : "0"));
        foreach($obj->ingredienti as $ingr)
        {
        	$ingrediente["idpasto"] = $ret[0]["ID"];
        	$ingrediente["ingrediente"] = $ingr->nome;
        	$ingrediente["presente"] = $ingr->presente ? 1 : 0;
        	$app->Sql()->AddRecord_L("Menu_PastoIngredienti",$ingrediente);
        }
        $this->LogOperation("Aggiunto ".$obj->nomericetta." alla data ".$pasto["data"]." come ".($pasto["pranzocena"]==1 ? "cena":"pranzo"));
    }
    function DeleteMeal($date,$obj)
    {
    	$app = FantaApp::GetSingleTon();
        $ret = $app->Sql()->Fetch_L("Menu_Pasto","data='".$date->format("Y-m-d")."' AND pranzocena=".($obj->pranzocena ? "1" : "0"));
        if (count($ret)>0)
        {
        	$app->Sql()->DeleteRecord_L("Menu_Pasto","ID=".$ret[0]["ID"]);
            $app->Sql()->DeleteRecord_L("Menu_PastoIngredienti","idpasto=".$ret[0]["ID"]);
        $this->LogOperation("Eliminato ".$ret[0]["nomericetta"]." dalla data ".$ret[0]["data"]." come ".($ret[0]["pranzocena"]==1 ? "cena":"pranzo"));
        }
    }
    function GetIngredientiToBuy()
    {
    	$date = new DateTime("now",new DateTimeZone("Europe/Rome"));
        $app = FantaApp::GetSingleTon();
        $ingredlist = array();
        $pasti = $app->Sql()->Fetch_L("Menu_Pasto","data>='".$date->format("Y-m-d")."'");
        foreach($pasti as $pasto){
        	$ingred = $app->Sql()->Fetch_L("Menu_PastoIngredienti","idpasto=".$pasto["ID"]." AND presente=0");
            foreach($ingred as $ing){
            	$elem = array();
                $elem["ingrediente"] = $ing["ingrediente"];
                if (!in_array($elem,$ingredlist))
            		array_push($ingredlist,$elem);
            }
        }
        sort($ingredlist);
        return $ingredlist;
    }
    function GetIngredientiDispensaDaControllare()
    {
    	$date = new DateTime("now",new DateTimeZone("Europe/Rome"));
    	$datetoday = new DateTime("now",new DateTimeZone("Europe/Rome"));
        $app = FantaApp::GetSingleTon();
        $date->sub(new DateInterval('P7D'));  //ultimi 7 giorni
        $dispensa = $app->Sql()->Fetch_L("Menu_Dispensa");
        $pasti = $app->Sql()->Fetch_L("Menu_Pasto","data>='".$date->format("Y-m-d")."' and data <='".$datetoday->format("Y-m-d")."'");
        $ingredlist = array();
        foreach($pasti as $pasto){
        	$ingred = $app->Sql()->Fetch_L("Menu_PastoIngredienti","idpasto=".$pasto["ID"]);
            foreach($ingred as $ing){
            	array_push($ingredlist,$ing["ingrediente"]);
            }
        }
        $controllalist = array();
        foreach($dispensa as $ingrdispensa)
        {
        	$nome = strtolower(trim($ingrdispensa["ingrediente"]));
            foreach($ingredlist as $ing){
            	$nome2 = strtolower(trim($ing));
                if ($nome == $nome2)
                {
                	$elem = array();
                    $elem["ingrediente"] = trim($ingrdispensa["ingrediente"]);
                    $elem["ID"] = trim($ingrdispensa["ID"]);
                    if (!in_array($elem,$controllalist))
            			array_push($controllalist,$elem);
                }
            }
        }
        sort($controllalist);
        return $controllalist;
    }
    function GetProposte()
    {
    	$date = new DateTime("now",new DateTimeZone("Europe/Rome"));
        $app = FantaApp::GetSingleTon();
        $dispensa = $app->Sql()->Fetch_L("Menu_Dispensa");
        $ricette = $app->Sql()->Fetch_L("Menu_Ricetta");
        $ret = array();
        foreach($ricette as $ricetta){
        	$ingredlist  = $app->Sql()->Fetch_L("Menu_RicettaIngredienti","idricetta=".$ricetta["ID"]);
            $numingred = count($ingredlist);
            $ingredcount = 0;
            foreach($ingredlist as $ingred){
            	$nomeing = trim(strtolower($ingred["nome"]));
                foreach($dispensa as $disp)
                {
                	$nomedis = trim(strtolower($disp["ingrediente"]));
                    if ($nomedis==$nomeing)
                    	$ingredcount++;
                }
            }
            $elem = array();
            $elem["idricetta"] = $ricetta["idricetta"];
            $elem["nomericetta"] = $ricetta["nome"];
            $elem["descrizionericetta"] = $ricetta["descrizione"];
            $elem["ingredientipresenticount"] = $ingredcount;
            $elem["ingredientimancanti"] = $numingred  - $ingredcount;
            if ($ingredcount == $numingred)
            	$elem["ingredientipresenti"] = "list-group-item-success";
            if ($ingredcount < $numingred)
            	$elem["ingredientipresenti"] = "list-group-item-warning";
            if ($ingredcount == 0 && $numingred)
            	$elem["ingredientipresenti"] = "list-group-item-danger";
            array_push($ret,$elem);
        }
        usort($ret, array('MenuLib','cmp'));
        return $ret;
    }
    function GetCostoStimato($tipo){
    	$ret = new CostoIngredienti();
        $app = FantaApp::GetSingleTon();
    	switch($tipo){
        	case "dispensa":
            	$dispensa = $app->Sql()->Fetch_L("Menu_Dispensa");
                foreach($dispensa as $disp)
                {
                	$ing = strtolower(trim($disp["ingrediente"]));
                	$record = $app->Sql()->Fetch("Menu_IngredientiCosto","LOWER(ingrediente)='".$ing."'");
                    if (count($record)>0)
                    	$ret->costo += $record[0]["costo"];
                    else
                   		array_push($ret->ingredientimancanti,$disp["ingrediente"]);
                }
            	break;
            case "listaspesa":
            	$dispensa = $app->Sql()->Fetch_L("Menu_ListaSpesa");
                foreach($dispensa as $disp)
                {
                	$ing = strtolower(trim($disp["ingrediente"]));
                	$record = $app->Sql()->Fetch("Menu_IngredientiCosto","ingrediente='".$ing."'");
                    if (count($record)>0)
                    	$ret->costo += $record[0]["costo"];
                    else
                   		array_push($ret->ingredientimancanti,$disp["ingrediente"]);
                }
            	break;
            case "dacomprare":
            	$dispensa = $this->GetIngredientiToBuy();
                foreach($dispensa as $disp)
                {
                	$ing = strtolower(trim($disp["ingrediente"]));
                	$record = $app->Sql()->Fetch("Menu_IngredientiCosto","ingrediente='".$ing."'");
                    if (count($record)>0)
                    	$ret->costo += $record[0]["costo"];
                    else
                   		array_push($ret->ingredientimancanti,$disp["ingrediente"]);
                }
            	break;
        }
        return $ret;
    }
    function RefreshPastoIngredients()
    {
        $date = new DateTime("now",new DateTimeZone("Europe/Rome"));
        $app = FantaApp::GetSingleTon();
        $dispensa = $app->Sql()->Fetch_L("Menu_Dispensa");
        $pasti = $app->Sql()->Fetch_L("Menu_Pasto","data>='".$date->format("Y-m-d")."'");
        foreach($pasti as $pasto){
        	$ingred = $app->Sql()->Fetch_L("Menu_PastoIngredienti","idpasto=".$pasto["ID"]." AND presente=0");
            foreach($ingred as $ing){
            	foreach($dispensa as $ingrdispensa)
        		{
            		if (trim(strtolower($ing["ingrediente"]))==trim(strtolower($ingrdispensa["ingrediente"])))
                    {
                    	$ing["presente"] = 1;
                    	$app->Sql()->EditRecord("Menu_PastoIngredienti",(object)$ing);
                    }
                }
            }
        }
    }
    function LogOperation($descrizione){
        $app = FantaApp::GetSingleTon();
        $elem = array();
        $elem["descrizione"] = $descrizione;
    	$date = new DateTime("now",new DateTimeZone("Europe/Rome"));
        $elem["data"] = $date->format("Y-m-d");
        $app->Sql()->AddRecord_L("Menu_Log",$elem);
    }
    private static function cmp($a, $b)
    {
        if ($a["ingredientimancanti"] == $b["ingredientimancanti"]) {
            return 0;
        }
        return ($a["ingredientimancanti"] < $b["ingredientimancanti"]) ? -1 : 1;
    }




    
    
}
class Pasto
{
	public $ID = 0;
	public $idricetta = 0;
    public $nomericetta = "";
    public $descrizionericetta = "";
    public $data;
    public $pranzocena = false;
    public $ingredientipresenti = "";
    public $ingredienti = array();
}
class Ingrediente 
{
	public $ID = 0;
	public $idpasto = 0;
	public $nome = "";
    public $presente = false;
}
class ProposteMenu
{
	public $idricetta = 0;
    public $nomericetta = "";
    public $descrizionericetta = "";
    public $ingredientipresenti = "";
    public $ingredientimancanti = 0;
}
class CostoIngredienti
{
    public $costo = 0;
    public $ingredientimancanti = array();
}
?>

