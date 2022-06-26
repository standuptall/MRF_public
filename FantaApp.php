<?php
require_once("Utils.php");
class FantaApp  {
      private static $instances = [];
      private $_fantabase;
      private $isLogged = false;
      public $userInfo = null;
      public $userInfoAgg = null;
      public $nomeApp = "SmartMenu";
      public $pagina = "";
    protected function __construct() {
    	  $this->RestoreLogin();
          
          if (!isset($this->userInfo)){
          
          	$this->TokenLogin();
             }
    }
    function LoadCss(){
        //mimmo
    	$content = Utils::loadtext("includecss.html");
        echo $content;
    }
    function LoadJs(){
    	$content = Utils::loadtext("includejs.html");
        echo $content;
    }
    function LoadHeader($userInfo,$userInfoAgg){
        include("./Layout/Header.php");
        RenderHeader($this->userInfo,$this->userInfoAgg);
    }
    function ValidKey($key){
    	$obj = $this->Sql()->Fetch("Users","key='".$key."'");
        return $obj;
    }
    function Init($dirname){
          echo '
  <html>
  <head>
    <meta charset="utf-8">
  ';
      $this->LoadCss();
      $this->LoadJs();
      if (file_exists($dirname."/style.css"))
      	echo '<style type="text/css">'.Utils::loadtext($dirname.'/style.css').'</style>';
      if (file_exists($dirname."/script.js"))
      	echo '<script>'.Utils::loadtext($dirname.'/script.js').'</script>';
      echo '
      <title>FantaApp</title>
  </head>
  <body>
  ';
      $this->LoadHeader($this->userInfo,$this->userInfoAgg);
    }
    function Close(){
    	echo '
            </div>
        </body>
        </html>';
    }
	public static function GetSingleTon()
    {
    	$cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }
        return self::$instances[$cls];
    }
    function Sql($val){
    	return new FantaBase($val);
    }
    function RegisterUser($email,$username,$password){
    	$passwordmd = md5($password);
        $esiste  = $this->Sql()->Fetch("Profili","email='".$email."'");
        if (count($esiste)==0){
        	$id = uniqid("fantapp",true);
            $link = 'http://cantirsi.altervista.org/FantaApp/email_confirm?id='.$id;
            $username = strtolower($username);
            $obj = (object)array("nomeutente"=>$username,
                                 "password"=>$passwordmd,
                                 "email"=>$email,
                                 "stato"=>"pending",
                                 "url_confirm"=>$link,
                                 "key_confirm"=>$id,
                                 "datareg"=>date("Y-m-d H:i:s")
                                 );
        	$this->Sql()->AddRecord("Profili",$obj);
            $to = $email;
            $subject = "Conferma registrazione FantaApp";
            $message = '
            <html>
            <head>
            <title>FantaApp</title>
            </head>
            <body>
            <h1>Grazie per esserti registrato a FantaApp!</h1>
            <p>Segui il seguente link:</p><br>
            <p><a href="'.$link.'">'.$link.'</a></p>
            
            </body>
            </html>
            ';
            
            $this->SendMail($to,$subject,$message);
            return true;
        }
        return false;
    }
    function SendMail($to,$subject,$message)
    {
    	$headers = "MIME-Version: 1.0" . "\r\n";
      	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      	$headers .= 'From: <webmaster@cantirsi.com>' . "\r\n";
      	mail($to,$subject,$message,$headers);
    }
    function ValidRegistration($key){
        $esiste  = $this->Sql()->Fetch("Profili","key_confirm='".$key."'");
        if (count($esiste)==1){
        	if ($esiste[0]["stato"]="pending"){
              $obj = (object)array("ID"=>$esiste[0]["ID"],"stato"=>"attivo");
              $this->Sql()->EditRecord("Profili",$obj);
              return true;
            }
            else return false;
        }
        return false;
    }
    function UserLogin($user,$password,$donotsetcookie) {
    	$pw = md5($password);
        $user = strtolower($user);
        $esiste  = $this->Sql()->Fetch("Profili","nomeutente='".$user."' AND password='".$pw."'");
        
        if (count($esiste)==1){
        	if ($esiste[0]["stato"]!="attivo")
            	return false;
        	if(!$donotsetcookie){
              $session = uniqid("fantapp",true);
              $obj = (object)array("nomeutente"=>$esiste[0]["nomeutente"],"data"=>date("Y-m-d h:i:s"),"session"=>$session);
              $this->Sql()->AddRecord("ProfiliSessioni",$obj);
             
              $userInfo["session"] = $session;
			  $agg  = $this->Sql()->Fetch("ProfiliAggiuntiva","ID='".$esiste[0]["ID"]."'");
			  if (count($agg)==1)
				$this->userInfoAgg = $agg[0];
              setcookie("fanta_session", $session, time() + (86400 * 30));
            }
            $this->isLogged = true;
            $this->userInfo = $esiste[0];
            return true;
        }
        return false;
    }
    function TokenLogin(){
    	$token = Utils::getBearerToken($_SERVER);
    	$fields = Utils::checkJwt($token);
        if (isset($fields)){
        	$esiste  = $this->Sql()->Fetch("Profili","email='".$fields->email."'");
        	if ($esiste[0]["stato"]!="attivo")
            {
            	echo '{message: "not authorized"}';
            	http_response_code(403);
                exit(0);
            }
            $this->isLogged = true;
            $this->userInfo = $esiste[0];
            $agg  = $this->Sql()->Fetch("ProfiliAggiuntiva","ID='".$esiste[0]["ID"]."'");
			if (count($agg)==1)
				$this->userInfoAgg = $agg[0];
        }
        if (!isset($this->userInfo) && !empty($token))
        {
        	echo "Invalid token";
            http_response_code(403);
            exit(0);
        }
    }
    function RestoreLogin(){
    	if(isset($_COOKIE["fanta_session"])){
        	$session = 	$_COOKIE["fanta_session"];
            $esistesess  = $this->Sql()->Fetch("ProfiliSessioni","session='".$session."'");
            if (count($esistesess)==1){
              $data = new DateTime($esistesess["data"]);
              $datanow = $date = new DateTime("now",new DateTimeZone("Europe/Rome"));
              $data->add(new DateInterval('P1M'));
              if ($data->diff($datanow)>=0)
              {
                $esiste  = $this->Sql()->Fetch("Profili","nomeutente='".$esistesess[0]["nomeutente"]."'");
                $this->isLogged = true;
                $this->userInfo = $esiste[0];
                $esiste2  = $this->Sql()->Fetch("ProfiliAggiuntiva","ID='".$esiste[0]["ID"]."'");
                if (count($esiste2)==1)
                  $this->userInfoAgg = $esiste2[0];
              }
              else {
            	setcookie("fanta_session", "", time() - 3600);
                $obj = (object)array("ID"=>$esistesess[0]["ID"]);
                $this->Sql()->DeleteRecord("ProfiliSessioni",$obj);
                }
            }
            else {
            	setcookie("fanta_session", "", time() - 3600);
            }
        }
    }
    function UserSignOut(){
    	if(isset($_COOKIE["fanta_session"])){
			setcookie("fanta_session", " ");
            $esistesess  = $this->Sql()->Fetch("ProfiliSessioni","session='".$userInfo["session"]."'");
            $obj = array("ID"=>$esistesess[0]["ID"]);
			//$agg  = $this->Sql()->Fetch("ProfiliAggiuntiva","ID='".$esiste[0]["ID"]."'");
            $this->Sql()->DeleteRecord("ProfiliSessioni",$obj);
			unset($this->userInfo);
			unset($this->userInfoAgg);
        }
    }
    
    function FallBackIfNotAuthenticated(){
    	if (!isset($this->userInfo)){
        	header("location: /FantaApp");
            
        }
    }
    function lb($key){
    	$cdlingua = "EN";
    	if (!isset($this->userInfo)){
        }
        else
        	$cdlingua = $this->userInfo["cdlingua"];
        $lb = $this->Sql()->Fetch("Dictionary","cdlingua='".$cdlingua."' AND key_='".$key."'");
        if (count($lb)>0)
        	return $lb[0]["value"];
        return '['.$key.']';
    }
    function GetAuth($route){
    	$lb = $this->Sql()->Fetch("Routes3","route='".$route."'");
        if (count($lb)>0)
        	return $lb[0];
        else{
        	$obj = (object)array(
            	"route" => $route,
                "enabled" => "1",
                "get" => "1",
                "post" => "0",
                "put" => "0",
                "delete" => "0"
            );
            $this->Sql()->AddRecord("Routes3",$obj);
            return $this->Sql()->Fetch("Routes3","route='".$route."'")[0];
        }
    }
}
class FantaBase{
	private  $showQuery;
    function __construct($sq){
    	$this->showQuery = $sq;
    }
    
	function Fetch($table,$where,$order){
  		$conn = $this->connect();
        $sql = 'SELECT * FROM '.$table.' ';
        if (isset($where)){
        	$sql = $sql . "WHERE " . $where;
        }
        if (isset($order)){
        	$sql = $sql . " ORDER BY " . $order;
        }
		$result = $conn->query($sql);
        $return = array();
        if ($this->showQuery)
        	echo $sql;
        if (!$result) {
        	$this->handleError($conn,$sql);
        }
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                array_push($return,$row);
            }
        } 
        $conn->close();
        return $return;
    }
    function Fetch_L($table,$where,$order)
    {
    	$app = FantaApp::getSingleTon();
    	if (strlen($table)>=4)
        	//if (substr($table, 0, 4)=="Menu")
            	if (isset($where))
                	$where .= " AND idutente=".$app->userInfo["menu_idutente"];
                else	
                	$where .= " idutente=".$app->userInfo["menu_idutente"];
        return $this->Fetch($table,$where,$order);
    }
    function DeleteRecord($table,$where){
    	$conn = $this->connect();
        $sql = "DELETE FROM ".$table." WHERE ".$where;
        if ($this->showQuery)
        	echo $sql;
		$result = $conn->query($sql);
        if (!$result) {
        	$this->handleError($conn,$sql);
        }
         
        $conn->close();
        return $return;
    }
    function DeleteRecord_L($table,$where)
    {
    	$app = FantaApp::getSingleTon();
    	if (strlen($table)>=4)
        	if (substr($table, 0, 4)=="Menu")
            	if (isset($where))
                	$where .= " AND idutente=".$app->userInfo["menu_idutente"];
                else	
                	$where .= " idutente=".$app->userInfo["menu_idutente"];
        return $this->DeleteRecord($table,$where);
    }
    function AddRecord($table,$obj){
    	$conn = $this->connect();
        $sql = "INSERT INTO ".$table."(";
        foreach ($obj as $key => $value) {
           $sql = $sql . '`'.$key .'`'. ',';
       	}
        $sql = substr($sql, 0, -1);
        $sql = $sql . ") VALUES (";
        foreach ($obj as $key => $value) {
           if(gettype($value)=="integer")
           		$coda = $value;
           if(gettype($value)=="string")
           		$coda = "'". $value . "'";
           $sql = $sql . $coda . ",";
       	}
        $sql = substr($sql, 0, -1);
        $sql = $sql . ")";
        if ($this->showQuery)
        	echo $sql;
		$result = $conn->query($sql);
         
        if (!$result) {
        	$this->handleError($conn,$sql);
        }
        $result = $conn->query("SELECT MAX(ID) as ID FROM ".$table);
        $row = mysqli_fetch_assoc($result);
        $ID = $row["ID"];
        $conn->close();
        return $ID;
    }
    function AddRecord_L($table,$obj)
    {
    	$app = FantaApp::getSingleTon();
    	if (strlen($table)>=4)
        {
          $obj["idutente"] = $app->userInfo["menu_idutente"];
          $obj["datains"] = date("Y-m-d h:i:s");
        }
        return $this->AddRecord($table,$obj);
    }
    function EditRecord($table,$obj){
    	$conn = $this->connect();
        $id = $obj->ID;
        unset($obj->ID);
        $sql = "UPDATE ".$table." SET ";
        foreach ($obj as $key => $value) {
           if(gettype($value)=="integer")
           		$coda = $value;
           if(gettype($value)=="string")
           		$coda = "'". $value . "'";
           $sql = $sql . $key . "= ".$coda.",";
       	}
        $sql = substr($sql, 0, -1);
        $sql = $sql . " WHERE ID = ".$id;
        if ($this->showQuery)
        	echo $sql;
		$result = $conn->query($sql);
        if (!$result) {
        	$this->handleError($conn,$sql);
        }
        $conn->close();
    }
    function handleError($mysqli,$sql)
    {
    	echo "MySQL error ".$mysqli->error." Query: ".$sql." ". $msqli->errno;   
    }
    function DoQuery($query){
    	$conn = $this->connect();
    	if ($this->showQuery)
        	echo $query;
		$result = $conn->query($query);
        if (!$result) {
        	$this->handleError($conn,$query);
        }
        $return = array();
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                array_push($return,$row);
            }
        } 
        $conn->close();
        return $return;
    }
    function connect() {
    	$json_config = Utils::loadtext(ROOTPATH."/fantapp.config");
        $obj = json_decode($json_config);
    	// Create connection
        $conn = new mysqli($obj->server, $obj->user, $obj->password,$obj->databasename);
        
        // Check connection
        if (!$conn) {
            echo("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>