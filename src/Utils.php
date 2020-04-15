<?php
class Utils {
	public static function ploatTable($array,$header,$settings){
		echo '<div class="container vh-100">';
        if (isset($settings->add)){
        	if (gettype($settings->add)=='boolean')
              echo '
                      <button type="button" class="float-right mr-1 mb-1 btn btn-md btn-primary">Aggiungi</button>
                  ';
             else
             	echo $settings->add;
        }
        echo '		<table class="table table-light">
        				<thead><tr>';
        if (isset($settings->edit) || isset($settings->delete)){
        	echo '
                	<th class="w-25" scope="col"></th>
                '; 
        }
        foreach($header as $obj){
        	echo '<th scope="col">'.$obj.'</th>';
        }
        echo '</tr></thead><tbody>';
    	foreach($array as $obj){
        	Utils::ploatRow($obj,$header,$settings);
        }
        echo '</tbody></table></div>';
    }
    public static function ploatRow($row,$header,$settings){
    	echo '<tr>';
        if (isset($settings->edit) || isset($settings->delete)){
            echo '<td>';
            if (gettype($settings->edit)=='boolean')
            	echo 'Edit ';
            else{
        		$content = str_replace("%ID%",$row["ID"],$settings->edit);
            	echo $content;
            }    
            if (gettype($settings->delete)=='boolean')
            	echo 'Delete ';
            else{
        		$content = str_replace("%ID%",$row["ID"],$settings->delete);
            	echo $content;
            }
            echo '</td>';
        }
    	foreach($header as $obj){
        	echo '<td>'.$row[$obj].'</td>';
        }
        echo '</tr>';
    }
    function loadtext($filename){
    	$myfile = fopen($filename, "r") or die("Unable to open file ".$filename);
      	$content =  fread($myfile,filesize($filename));
        
      	
        fclose($myfile);
    	return $content;
    }
    function dump_to_file($obj){
      ob_flush();
      ob_start();
      var_dump($obj);
      file_put_contents("dump.txt", ob_get_flush());
    }
    function RequestAuthorization($app){
    	if (!isset($app->userInfo)){	
        	
        	header('HTTP/1.0 403 Forbidden');
            require("403.html");
            exit(0);
        }
    }
    function getJwt($fields = array()) { 
    	$json_config = Utils::loadtext("fantapp.config");
        $obj = json_decode($json_config);
    	$secretkey = $obj->tokenSecret;
        $encoded_header = base64_encode('{"alg": "HS256","typ": "JWT"}'); 
        $encoded_payload = base64_encode(json_encode($fields)); 
        $header_payload = $encoded_header . '.' . $encoded_payload; 
        $signature = base64_encode(hash_hmac('sha256', $header_payload, $secretkey, true)); 
        $jwt_token = $header_payload . '.' . $signature; 
        return $jwt_token; 
    }
    function checkJwt($token = NULL) { 
    	$json_config = Utils::loadtext("fantapp.config");
        $obj = json_decode($json_config);
    	$secretkey = $obj->tokenSecret;
        $jwt_values = explode('.', $token); 
        $recieved_signature = $jwt_values[2]; 
        $recievedHeaderAndPayload = $jwt_values[0] . '.' . $jwt_values[1]; 
        $payload = $jwt_values[1];
        $payjson = base64_decode($payload);
        $objpay = json_decode($payjson);
        if ($objpay->iat < time()-(86400*7))
        	return NULL;
        $resultedsignature = base64_encode(hash_hmac('sha256', $recievedHeaderAndPayload, $secretkey, true));
        if ($resultedsignature == $recieved_signature) 
            return($objpay);
        else 
            return(NULL); 
    }  
    function getAuthorizationHeader($server){
        $headers = null;
        if (isset($server['Authorization'])) {
            $headers = trim($server["Authorization"]);
        }
        else if (isset($server['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($server["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    function getBearerToken($server) {
        $headers = Utils::getAuthorizationHeader($server);
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
    function GetObjectFromBody(){
    	$body = file_get_contents('php://input');
        $obj = json_decode ($body);
        if ($obj == NULL){
            http_response_code (400);//["HTTP/1.1 400 Bad Request"];
            exit(0);
        }
        return $obj;
    }
    function RenderContent($content,$obj) {
    	$ciao  = $content;
    	foreach($obj as $campo=>$valore){
        	$ciao = str_replace("{{".$campo."}}",$valore,$ciao);
        }
        return $ciao;
    }
    function GetTabellaMondo($app,$nome){
    	$user = $app->userInfoAgg;
        $prefi = str_pad($user["idmondo"], 3, '0', STR_PAD_LEFT);
        if($user["idmondo"] == "0")
            echo "{error:true;message:\"You didn't choose a world!}";
        return "MRF_Mondo".$prefi."_".$nome;
	}
}
?>