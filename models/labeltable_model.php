<?php 
//CREATE INDEX ON :User(name)
//CREATE INDEX ON :User(name)

//use Neoxygen\NeoClient\ClientBuilder;





class labeltable_model extends requestHandler{
	
	public $labels =""; 
	public $properties =""; 
	public $head ="";
	public $rows ="";
	
	public $GETParams='';
	
	public function __construct($Labels){			
		$this->labels=$Labels;
	}
	/* also in search */
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
	
	function setGETParams($params){
		$this->GETParams = $params;
	}
	
	
	function setHead($headarray,$html=""){
		
		foreach($headarray as $value){
			$html.="<th>$value</th>";		
		}
		$this->head="<tr>$html</tr>";
	}

	function addRow($str='',$rowarray){
		$html="$str";
		if(is_array($rowarray)){
			foreach($rowarray as $value){
				$html.="<td>$value</td>";		
			}
		}else{
			$html='<td></td>';
		}
		$this->rows.="<tr>$html</tr>";
	}

	function render($attr=''){
		$table='<table '.$attr.'><thead>';	
		$table.=$this->head.'</thead><tbody>';	
		$table.=$this->rows;
		return $table.="</tbody></table>";
	}
	
		
	/// SET Table Head	
	public function setTHead($label){
		$this->head='';
		
		if(empty($this->properties))
		$this->getProperties($label);
		$th=[];
		$th[] = $this->makeHeadLink('oid');	
		foreach($this->properties AS $property){
			$th[] = $this->makeHeadLink($property);			
		}
		$this->setHead($th);
	}	
	
	
	/// GET Links	
	public function setTRow($label,$record){	
		//$this->rows='';
		$td=[];
		$td[] = $this->makeDataLink('oid',$record);
		foreach($this->properties AS $key => $property){
			$td[] = $record->value('n.'.$property);			
		}
		$this->addRow('',$td);
	}	
	
	function makeDataLink($label,$record){ 	
		$out='';
		
		if($label == 'Person'){
			$out.='<a href="/manage/update?field='.$_GET['field'].
			'&amp;'.$_GET['field'].'id='.$record->value('n.oid').
			'&amp;editid='.$record->value('n.oid').
			'&amp;update=1">'
			.($record->value('n.first_name') == ''? 'null' : $record->value('n.first_name'))
			.'</a>'; 
		}else
		if($label == 'oid'){
			$out.='<a href="/manage/update?field='.$_GET['field'].
			'&amp;'.$_GET['field'].'id='.$record->value('n.oid').
			'&amp;editid='.$record->value('n.oid').
			'&amp;update=1">Edit ID: '
			.($record->value('n.oid') == ''? 'null' : $record->value('n.oid'))
			.'</a>'; 
		}else{
				
			$out.='<a href="/manage/update?field='.$_GET['field'].
			'&amp;'.$_GET['field'].'id='.$record->value('n.oid').
			'&amp;editid='.$record->value('n.oid').
			'&amp;update=1">'
			.($record->value('n.name') == ''? 'null' : $record->value('n.name'))
			.'</a>'; 			
		
		}		
		return $out;	
	}

	function makeHeadLink($property){  
			
			$out='<a class="';
			if(@$_GET['sortfield'] == $property){
				$out.="selectedsort";
			}
			$out.='" href="';	
			$out.=$this->GETParams;	
			$out.='&amp;sortfield='.$property.'">';
			$out.=strtoupper($property).'</a>';
			

		return $out;
	}  
	
	
}
?>