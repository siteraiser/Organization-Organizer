<?php 

class search_model extends requestHandler{
	public $labels='';
	public $fields=[];	
	public $properties=[];
	
	//public $query_addon='';
	public $sortfield='';	
	public $sort='desc';	
	
	
	public function __construct($Labels){			
		$this->labels=$Labels;	
	}

	
	
	///SORTING
			
	function init(){
	//	if(isset($_GET['datefield'])){
	//		$this->datefield=$_GET['datefield'];//maybe add security check here!!!
	//	}
	
	
		//set fake default GET vars so sorting works, GET vars are used later to toggle sort etc.
		//if the sorting field and the page number are the same as last time, then set GET to the default vars. 
		if( $this->sameAsLast('page') ){
			if(!isset($_GET['sort']) && isset($_GET['sortfield'])){
				$_GET['sort']=$this->sort;//default sort direction
			}		
		}

		if(isset($_GET['sortfield'])){
			$this->sortfield = $_GET['sortfield'];
		}	
		//Set both to defaults
		if( $this->sameAsLast('page') || !isset($_GET['sortfield'])){
			if(!isset($_GET['sort']) && !isset($_GET['sortfield'])){
				$_GET['sort']=$this->sort;
				$_GET['sortfield']=$this->sortfield;
			}			
		}		

	}
	
	
	function swapSort($sort){ 

		if($sort == strtolower('asc')){
			$sort='desc';
		}else if($sort == strtolower('desc')){
			$sort='asc';
		}

		return $sort;
	}	
	
	public function sortParams(){	
		//Query addon passed into init 
	//	if($this->query_addon !=''){
	//		$add.=' AND '.$this->query_addon;
	//	}
	
		if($_GET['sortfield']==strtolower('oid')){//is numeric
			$add=' ORDER BY toFloat(n.'. $this->sortfield .")";
		}else{
			$add=' ORDER BY LOWER(n.'. $this->sortfield .")";
		}
		
		if($_GET['sort']==strtolower('asc')){
			$add.=" ASC ";
		}else if($_GET['sort']==strtolower('desc')){
			$add.=" DESC ";
		}else{
			$add.=" " . $this->sort . " ";
		}
		
		return $add;
	}
	
//Check if the last request str had the same sort field and pageno's
 function sameAsLast($get_var = 'page'){
  
  $last='';
	$query_str = parse_url(urldecode(@$_SERVER['HTTP_REFERER']), PHP_URL_QUERY);
	parse_str($query_str, $last);
  
	if(isset($last['sortfield']) ){
	  
		if($last['sortfield'] == @$_GET['sortfield'] && @$last[$get_var] == @$_GET[$get_var]){
	
			return 1;
		 }else{
			return 0;
		 }
	 } else{
		return 0;	
	 }
 }

	

	
	
	
	
	
	
	public function getFields(){
		$array = $this->labels;
		//add to form out array
		foreach($array AS $key => $value){			
			$this->fields[] = $key;			
			$fields[] = strtolower($key);
		}	
		return $fields;
		//echo '<pre>';
	//	var_dump($this->properties);
	//	echo '</pre>';
	}
	
	public function getProperties($label){
		$array = $this->labels->$label->Properties;
		//add to form out array
		foreach($array AS $key => $property){
			
			$this->properties[] = $key;
			
		}	
		//echo '<pre>';
	//	var_dump($this->properties);
	//	echo '</pre>';
	}
	
	
	
	public function countRecords($label,$search,$property){
		
		
		$query = "	
		MATCH (n:$label)
		WHERE (n.$property =~ {search}) 
		RETURN count(DISTINCT n)";
		
		$result1 = $this->client->run($query,["search"=>"(?i).*$search.*"]);
			
		foreach ($result1->getRecords() as $record1) {
			$count = $record1->value('count(DISTINCT n)');
		}
		return $count;
	}
	
	
	public function getRecords($label,$property = 'name',$search,$skip,$results_per_page){	
		
		$list = $this->getPropertiesList();
		echo $order_by = ($this->sortfield !='' && $this->sort !='' ? $this->sortParams():'ORDER BY toFloat(n.oid) DESC');//Order by
		$query = "
		MATCH (n:$label)
		WHERE (n.$property =~ {search}) 
		
		
		RETURN DISTINCT n,$list,n.oid
		$order_by	//	ORDER BY n.oid DESC
		SKIP {skip}
		LIMIT {rpp}";
			
		return $this->client->run($query,["search"=>"(?i).*$search.*","skip"=>$skip,"rpp"=>$results_per_page]);//'(?i).*
		
	}
	
	public function getPropertiesList(){
		$insert_array=[];
		foreach($this->properties AS $property){
			$insert_array[]= 'n.'.$property;
			
		}
		$list=implode(',',$insert_array);
		return $list;
	}
	
	
}

?>