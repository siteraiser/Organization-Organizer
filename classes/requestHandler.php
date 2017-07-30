<?php 
include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

abstract class helpers{

	public function routes($rows,$path){

		foreach($rows as $key => $value){	
			$routes[]=['pattern' => $key,'controller' => $value];
		}
		unset($value);

		function match($pattern,$urlSegs){
			$i = 0;$match = false;$urlcount=count($urlSegs);
			foreach($pattern as $value){				
				if($value != '(:any)'){
					if($urlSegs[$i] == $value){
						$match = true;
					}else{
						return false;
					}
				}else{
					if(isset($urlSegs[$i])){
						$any[$i]=$urlSegs[$i];
					}
				}
				$i++;
				if($urlcount-- == 0){return false;}
			}
			if(!isset($any) && $match==true){
				$any=true;
			}
			return $any;
		}

		$i = 0;
		foreach($routes as $value){	
			$pattern=explode('/',$value['pattern']);
			$urlSegs = explode('/', $path,count($pattern));
			$results = match($pattern,$urlSegs);
			if($results){
				break;
			}
			$i++;
		}

		if($results !==true && !empty($results)){
			foreach($results as $value){
				$trans[]=$value;
			}
		}
	$controller_method=[];
		if(isset($routes[$i]['controller'])){			
			$first = strpos($routes[$i]['controller'], '$');
			if($first==0){
			$first = strlen($routes[$i]['controller']);
			}
			$controller_method = substr($routes[$i]['controller'], 0, $first); 
			$controller_method = explode('/',$controller_method);
		}
		if(isset($trans)){
			
			$args = substr($routes[$i]['controller'],  $first, strlen($routes[$i]['controller']));
				$argsOrder = explode('/',$args);
				$i=0;
				foreach($argsOrder as $value){
					$combined[] = @$trans[substr($value,1) - 1]; 
				}
			$controller_method[2]=$combined;
		}
		return $controller_method;
	}
	
	public function get_include_contents($filename,$data,$instant_view=false) {
		foreach($data as $key => $value){
			$$key = $value;
		}
		if (is_file('views/'.$filename.'.php')) {
			if($instant_view === false){ob_start();}
	
			include 'views/'.$filename.'.php';
			
			if($instant_view === false){	return ob_get_clean(); }
			
			
		}else{
			echo $filename . ' is not a valid file!';			
		}
		return false;
	}	
	
	public function addView($view,$data) {	
		//sets output in controller's var
		if(!isset($this->output)){$this->output='';}
		$this->output.=$this->get_include_contents($view,$data);		
	}	
	
	
	
	
	/*
	
	public function loadPDO($path = 'db.inc.php') {

			//add pdo 
			include('secure/'.$path);//include once fails due to unsetting of dynpages controller				
			
			if(!is_object($this->pdo)){
				try {
					$this->pdo = new PDO('mysql:host=localhost;dbname='.$database, $username, $password);    					
				} catch (PDOException $e) {
					print "Error!: " . $e->getMessage() . "<br/>";
					return'';
					//die();
				} 		
				
			}else{
				return'';
			}
		return $this->pdo;		
	}	
	
	public function loadNEO4J($timeout = 15) {
		
	/*	$options = [
			CURLOPT_CONNECTTIMEOUT => $timeout, // The number of seconds to wait while trying to connect.
			CURLOPT_SSL_VERIFYPEER => false // Stop cURL from verifying the peer's certificate
		];
		$httpClient = new Client(null, null, $options);

		$config = \GraphAware\Neo4j\Client\HttpDriver\Configuration::create($httpClient);
	*/	
		
		
	//	if(!is_object($this->client)){			
		
		/*$this->client = ClientBuilder::create()
			->addConnection('default', 'http://neo4j:password@localhost:7474', $config)
			->build();
		
			$this->client =$client = ClientBuilder::create()->addConnection('default', 'http://neo4j:admin@localhost:7474')->setDefaultTimeout($timeout)->build(); // Example for HTTP connection configuration (port is optional)	
		}
	//	return $this->client;				
	}	*/

	
	
	public function loadModel($path,$args=[]) {
		$ep = explode('/',$path);
		$name =	end($ep);
		$loadname = 'models/'.$path.'.php';
		try {

		if (!file_exists($loadname ))
		  throw new Exception ($loadname.' does not exist');
		else		
		  	include_once($loadname);
			$this->$name = new $name($args);	
			$this->add_props($this->$name);		
		}
		catch(Exception $e) {    
		  echo "Message : " . $e->getMessage();
		  echo "Code : " . $e->getCode();
		}
		
	
	}	
	
	
	
		
	
	
	
	/*
	public function loadModel($path,$args=[]) {
		$var=explode('/',$path);
		$name =	end($var);
		$loadname = 'models/'.$path.'.php';
		if(file_exists($loadname) AND !isset($this->$name)){
			include_once($loadname);
			$this->$name = new $name($args);		

			
			if(!is_object($this->client)){
			
			$this->client =$client = ClientBuilder::create()->addConnection('default', 'http://neo4j:admin@localhost:7474')->build(); // Example for HTTP connection configuration (port is optional)	
			}
			$this->$name->client=$this->client;
			

			//add pdo 
			include_once('secure/db.inc.php');		
			if(in_array)crawl
			
			if(!is_object($this->pdo)){
				try {
					$this->pdo = new PDO('mysql:host=localhost;dbname='.$database, $username, $password);    					
				} catch (PDOException $e) {
					print "Error!: " . $e->getMessage() . "<br/>";
					die();
				} 				
			}
			$this->$name->pdo=$this->pdo;		
			$this->add_props($this->$name);			
		}
	}	
	
	*/
	
	public function add_props($object){
	//controller or model
		$object->url_segments=$this->url_segments;	
		$object->doc_root=$this->doc_root;
		$object->path=$this->path;	
		$object->req_url=$this->req_url;
		$object->base_url=$this->base_url;
		$object->pdo=self::pdo(); 
		$object->client=self::client(); 
	}
	public function doc_root() {
		return $this->doc_root;
	}
	public function url_segments() {//no need in controller or view or model
		return $this->url_segments;
	}

	
	/*****extras ***********/
	public function urlSlug ($string) {
		$string = utf8_encode($string);
		$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);   
		$string = preg_replace('/^a-z0-9\-\_]/i', '', $string);
		$string = str_replace(' ', '-', $string);
		$string = str_replace('(', '', $string);
		$string = str_replace(')', '', $string);
		$string = trim($string, '-');
		$string = strtolower($string);

		if (empty($string)) {
			return '';
		}

		return $string;
	}
	public function imgSlug ($string) {
		$string = str_replace('?', '', $string);
		$string = str_replace(' ', '_', $string);
		return $string;
	}
	public function selectedOption($fields,$selected=''){
		$allOut = "";	
		foreach($fields as $field){
			$out = '<option ';
				if(@$selected == $field){
					$out.="selected";
				}
			$out.=' value="'.$field.'">';
			$out.=$field . '</option>';  
			$allOut.= $out;			
		 }
		 return $allOut.'</select>';
	}	
	
	public function getSelectFields($array,$col_name){
		foreach(@$array as $value){
			$ret[]=$value[$col_name];
		}		
		return $ret;
	}
	
	private static $connections = array();
  	public static $pdo=0;	
	public static $client=0; // neo4j	
	
	public static function client($timeout = 30,$password=''){	
		if(!isset (self::$connections['neo4j'])){
			self::$connections['neo4j'] = ClientBuilder::create()->addConnection('default', 'http://neo4j:'.$password.'@localhost:7474')->setDefaultTimeout($timeout)->build();// ClientBuilder::create()->addConnection('default', 'bolt://neo4j:'.$password.'@localhost:7687')
		}
		return self::$connections['neo4j'];
	}
	
	public static function pdo($path = ''){//	db.inc.php 
		if(!isset (self::$connections['pdo'])){ 
			//add pdo 
			include('secure/'.$path);//include once fails due to unsetting of dynpages controller	
			try{
				self::$connections['pdo'] =  new PDO('mysql:host=localhost;dbname='.$database, $username, $password);    					
			} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				return'';
				//die();
			} 			

		}
		if(isset (self::$connections['pdo'])){ 
			return self::$connections['pdo'];	
		}else{return 0;}
	}
}

class requestHandler extends helpers{

	public $base_url;
	public $url_segments;
	public $req_url;
	public $path;	
	public $controllername;
	public $doc_root;
	
	public function getContent(){
		//Determine if local or on local network, otherwise exit program
		$host = substr($_SERVER['HTTP_HOST'], 0, 5);
		if (in_array($host, array('local', '127.0'))){
		 $this->base_url    =  'http://localhost/'; 
		} else if (in_array($host, array('192.1'))){
		 $this->base_url    = 'http://192.168.254.42/';
		}else{
			die();
		}	
		
		$this->doc_root    = $_SERVER['DOCUMENT_ROOT'].'/';		
		$this->req_url     = parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH);
		$this->output="";	//set default out html		
		$this->path = trim($this->req_url, '/');    // Trim leading slash(es)
	
		if($this->path == null || $this->path == 'index.php'){   // No path url_segments means home
			$this->path='home';
			$this->url_segments[0] = 'home';//home controller
		
		}else
		{
			$this->url_segments = explode('/', urldecode($this->path));			
			$this->path=parse_url($this->path, PHP_URL_PATH);	//echo path here		
		}
		$this->controllername=$this->url_segments[0];	
		if(isset($this->url_segments[1])){
			$method=$this->url_segments[1];
		}
		//Routing		
		$rows['sitemap.xml'] = 'sitemap';
		$rows['link1/(:any)'] = 'controller/method/$1';
		$rows['link2/(:any)'] = 'controllera/methoda/$1';
		$rows['link3/(:any)/links/more/(:any)'] = 'controllera/methoda/$1/$2';
		
		$controller_method=$this->routes($rows,$this->path);
		unset($rows);
		if(isset($controller_method[0])){
			$this->controllername = $controller_method[0];
		}
		
		if(isset($controller_method[1])){
			$method = $controller_method[1];
		}
		$params =[];
		if(isset($controller_method[2])){	
			$params = $controller_method[2];
		}
		array_unshift($params, $this->path);
		
		$controllerpath='controllers/'.$this->controllername.'.php';

		if(file_exists($controllerpath)){
			include_once($controllerpath);
			$controller = new $this->controllername();	
			if(isset($method) AND method_exists($controller, $method)){
				$function = $method;
				//call function set view data
				$this->add_props($controller);				
				call_user_func_array(array($controller,$function), $params);
				$this->output=$controller->output;
				
			} else if(method_exists($controller, 'index')){
				//call function set view data
				$this->add_props($controller);
				call_user_func_array(array($controller,'index'), $params);
				$this->output=$controller->output;
			}else{//maybe add fallback controller for db etc:))
				$notfound=true;
			}			
		}else{
			$notfound=true;
	   }
	   if(isset($notfound)){$this->notFound();}
	}
	
	
	public function notFound(){
		header('HTTP/1.1 404 Not Found');
		print' Not Found! <a href="/">Home</a>';exit();   // Show404Error();$this->output=$this->path.
	}

}
