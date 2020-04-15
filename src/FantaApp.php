<?php
require_once("Utils.php");
class FantaApp  {
      private static $instances = [];
      private $_fantabase;
      private $isLogged = false;
      public $userInfo = null;
      public $userInfoAgg = null;
      public $nomeApp = "MRF_Mimmo";
    protected function __construct() {
    	  $this->RestoreLogin();
          if (!isset($this->userInfo))
          	$this->TokenLogin();
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
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <webmaster@cantirsi.com>' . "\r\n";
            mail($to,$subject,$message,$headers);
            return true;
        }
        return false;
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
              $obj = (object)array("ID"=>$esiste[0]["ID"],"session"=>$session);
              $this->Sql()->EditRecord("Profili",$obj);
              $userInfo["session"] = $session;
			  $agg  = $this->Sql()->Fetch("ProfiliAggiuntiva","ID='".$esiste[0]["ID"]."'");
			  if (count($agg)==1)
				$this->userInfoAgg = $agg[0];
              setcookie("fanta_session", $session, time() + (86400 * 10));
            }
            $this->isLogged = true;
            $this->userInfo = $esiste[0];
            return true;
        }
        return false;
    }
    function TokenLogin(){
    	$token = Utils::getBearerToken($_SERVER);
        /*
        if(!isset($token)){
            http_response_code(403);
            exit(0);
        }
        */
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
        /*
        if (!isset($this->userInfo)){
            http_response_code(403);
            exit(0);
        }
        */
    }
    function RestoreLogin(){
    	if(isset($_COOKIE["fanta_session"])){
        	$session = 	$_COOKIE["fanta_session"];
            $esiste  = $this->Sql()->Fetch("Profili","session='".$session."'");
            if (count($esiste)==1){
              $this->isLogged = true;
              $this->userInfo = $esiste[0];
              $esiste2  = $this->Sql()->Fetch("ProfiliAggiuntiva","ID='".$esiste[0]["ID"]."'");
              if (count($esiste2)==1)
              	$this->userInfoAgg = $esiste2[0];
            }
        }
    }
    function UserSignOut(){
    	if(isset($_COOKIE["fanta_session"])){
			setcookie("fanta_session", " ");
            $obj = (object)array("ID"=>$userInfo["ID"],"session"=>$userInfo["session"]);
			$agg  = $this->Sql()->Fetch("ProfiliAggiuntiva","ID='".$esiste[0]["ID"]."'");
            $this->Sql()->EditRecord("Profili",$obj);
			unset($this->userInfo);
			unset($this->userInfoAgg);
        }
    }
}
class FantaBase{
	private  $showQuery;
    function __construct($sq){
    	$this->showQuery = $sq;
    }
    
	function Fetch($table,$where){
  		$conn = $this->connect();
        $sql = 'SELECT * FROM '.$table.' ';
        if (isset($where)){
        	$sql = $sql . "WHERE " . $where;
        }
		$result = $conn->query($sql);
        $return = array();
        if ($this->showQuery)
        	echo $sql;
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                array_push($return,$row);
            }
        } 
        $conn->close();
        return $return;
    }
    function DeleteRecord($table,$where){
    	$conn = $this->connect();
        $sql = "DELETE FROM ".$table." WHERE ".$where;
        if ($this->showQuery)
        	echo $sql;
		$result = $conn->query($sql);
         
        $conn->close();
        return $return;
    }
    function AddRecord($table,$obj){
    	$conn = $this->connect();
        $sql = "INSERT INTO ".$table."(";
        foreach ($obj as $key => $value) {
           $sql = $sql . $key . ',';
       	}
        $sql = substr($sql, 0, -1);
        $sql = $sql . ") VALUES (";
        foreach ($obj as $key => $value) {
           $sql = $sql . "'". $value . "',";
       	}
        $sql = substr($sql, 0, -1);
        $sql = $sql . ")";
        if ($this->showQuery)
        	echo $sql;
		$result = $conn->query($sql);
         
        $ID =  $result->insert_id;
        $conn->close();
        return $ID;
    }
    function EditRecord($table,$obj){
    	$conn = $this->connect();
        $id = $obj->ID;
        unset($obj->ID);
        $sql = "UPDATE ".$table." SET ";
        foreach ($obj as $key => $value) {
           $sql = $sql . $key . "= '".$value."',";
       	}
        $sql = substr($sql, 0, -1);
        $sql = $sql . " WHERE ID = ".$id;
        if ($this->showQuery)
        	echo $sql;
		$result = $conn->query($sql);
        $conn->close();
    }
    function connect() {
    	$json_config = Utils::loadtext("fantapp.config");
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