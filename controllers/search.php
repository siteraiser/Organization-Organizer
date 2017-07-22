<?php 
class search extends requestHandler{



	public function index(){
	
	

	$get_var='page';//page number var
	$results_per_page=5;
	$page = 1;		
	if(!empty($_GET['page'])) {
		$page = $_GET[$get_var];
	}
		
	$page--;
	$skip = $page * $results_per_page;
	$search = '';
	if(!empty($_GET['search'])) {
		$search = $_GET['search'];
	}
	if(isset($_GET['field'])) {
		$parts = explode('_',$_GET['field']);
		foreach($parts AS &$value){
			$value=ucfirst($value);
		}
	echo	$label = implode('_',$parts);
	}else{
		$label = 'Organization';
		
	}	
	
	
	require_once $this->doc_root().'classes/labels.php';
	$this->loadModel('search_model',$Labels);
	$this->loadModel('labeltable_model',$Labels);	
	
	
	if(!empty($_GET['search_property'])){ // maybe move down to get records area (even add to query?)
		
		$data['count'] = $this->search_model->countRecords($label,$search,$_GET['search_property']);
		echo 'here';
	}
	
	$this->search_model->getProperties($label);

	
	$data['fields']=$this->search_model->getFields();//Labels	
	
	$data['properties']=$this->search_model->properties;
	
	$this->search_model->init();
	if($this->search_model->sameAsLast('page')){
		$_GET['sort'] = $this->search_model->swapSort($_GET['sort']);//$this->search_model->sort
	}
	$data['addon'] = "&amp;search={$_GET['search']}&amp;field={$_GET['field']}&amp;search_property={$_GET['search_property']}&amp;sort=".$_GET['sort'];
	
	
	
	$this->labeltable_model->setGETParams('?page='.($page + 1).$data['addon']);//Labels	
	
	
	$out='';
	if(!empty($_GET['search_property'])){
		$results = $this->search_model->getRecords($label,$_GET['search_property'],$search,$skip,$results_per_page);
	
		$this->labeltable_model->setTHead($label);
		
		foreach ($results->getRecords() as $record) {
			$this->labeltable_model->setTRow($label,$record);
		}
		$out.=$this->labeltable_model->render($attr='');;
	}
	
	//Table adds sortfields for itelf, now add them for paging 
	$data['addon'].="&amp;sortfield={$_GET['sortfield']}";
	
	
	$data['out'] = $out;
	
	//$data['hidden_inputs'] = '<input type="hidden" name="field" value="'.$_GET['field'].'"/>';
	
	
	$data['page'] = $page;
	$data['results_per_page'] = $results_per_page;
	
	
	$this->addView('search',$data);
	
	
	}
	
	
function hiddenInputs($fields,$pageNumber){
//exclude unesessary hidden fields which are submitted by the form, maybe make an exclude array to check for custom filtering
		//$key != 'search' && $key != 'searchfield' && $key != 'resultsppg' && $key != 'startdate' && $key != 'enddate'
	$hidden_inputs='';
	parse_str(parse_url(urldecode(@$_SERVER['HTTP_REFERER']), PHP_URL_QUERY), $q_arry);
	$q_arry['page'] = $pageNumber;
	$q_arry = array_unique($q_arry);
	foreach( $q_arry as $key => $value){
		
		if(in_array($key,$fields) || $key == 'page'){
			$hidden_inputs.='<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
		}
	}

	return $hidden_inputs;	
}
	
	
}	