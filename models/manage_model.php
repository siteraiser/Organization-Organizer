<?php 
//CREATE INDEX ON :User(name)
//CREATE INDEX ON :User(name)

//use Neoxygen\NeoClient\ClientBuilder;





class manage_model extends requestHandler{
	
	
	
	
	public $labels =""; 
	public $Organization_array=[];
	public $Location_array=[];
	public $Website_array=[];
	public $Web_Account_array=[];
	public $Login_Details_array=[];
	public $Person_array=[];
	
	public function __construct($Labels){	
		
		$this->labels=$Labels;
	}

	function idIsTaken($id,$label){
      /*  $query = "MATCH (n:$label{oid:{id}}) RETURN count(n.oid) as count";
        $result = $this->client->run($query,['id'=>$id]);
		$record = $result->getRecord();
		echo '<pre>';
			var_dump($record);
			echo '</pre>';
        if($record->value('count')!=0 ){
		echo 'count:'.$record->value('count');
            return true;
        }else{
			echo 'count:'.$record->value('count');
            return false;
        }
		*/
    }	

	public function createID($label = 'Organization'){			
	/*
		 do {
          $id = UUID::v4();
        } while ($this->idIsTaken($id,$label));
		return $id;
		*/
		$query = "MATCH (n:$label) RETURN n,toFloat(n.oid) AS ord ORDER BY ord DESC LIMIT 1";
		$result = $this->client->run($query);
		$id=-1;
		foreach ($result->getRecords() AS $record) {
			echo '<br>'.$label.' highest id found: '. $id =$record->value('ord');
		}	
		return strval($id + 1);
	
		
	}
	
	
	public function createLink($aid,$bid,$labela,$labelb,$link_label){	
	
		$query = "
		MATCH (a:$labela {oid:{a}}),(b:$labelb {oid:{b}})
		//WHERE not ((a)-[:blocked]-(b))     
		CREATE UNIQUE (a)-[:$link_label]->(b)
		";
		$result = $this->client->run($query,["a"=>$aid,"b"=>$bid]);
	}
	
	public function removeLink($aid,$bid,$labela,$labelb,$link_label){	
	
		$query = "
		MATCH (a:$labela {oid:{a}})-[r:$link_label]-(b:$labelb {oid:{b}})
		  
		DELETE r
		";
		$result = $this->client->run($query,["a"=>$aid,"b"=>$bid]);
	}	
		
	public function makeForms($type='Organization'){

		$array = $this->labels->$type->Properties;
		//add to form out array
		foreach($array AS $key => $property){
			$name = $type.'-'.$key;
			
			
			$input_type = $property['type'];
			
			//get select list or load exertnal list maybe
			$select_list = '';
			if($input_type == 'select'){
				$select_list = $property['list'];
			}
				
			$input='';
			if($input_type == 'checkbox'){
				$input = "<input name='$name' type='hidden' value='0'>".
						" <input type='checkbox' name='$name' value='1'>";
			}else if($input_type == 'select'){
					
				 $input = '<select name="'.$name.'">
				 <option value=""></option>'.
				 $this->selectedOption($select_list, []).						
				'</select>';
			}else{
				$input = "<input type='text' name='$name' value=''>";
			}
			
			
			
			$element = "$name: $input";//.($property['type'] == 'checkbox' ? "<input  name='$name' type='hidden' value='0'>":"")." <input type='{$property['type']}' name='$name' ".($property['type'] == 'checkbox' ? "value='1'":"").">";			// <
			$this->form_array[$type][] = $element ;
		}		
	//	echo '<pre>';
		//var_dump($this->form_array);
		//echo '</pre>';
	}	
		
	/// GET INFO	
	public function filledForms($type='Organization'){

		
		$type_array_name = $type.'_array';
		$id = $this->$type_array_name['oid'];
		
		//Get organization
		$array = $this->labels->$type->Properties;
		
		$properties_array=[];
	
		//add to form out array
		foreach($array AS $key =>$property){
			$properties_array[]='n.'.$key;
		}
		$insert=implode(',',$properties_array);		
		//HERE!
		$query="
			MATCH (n:$type{oid:{id}}) RETURN $insert";
			$result = $this->client->run($query,["id"=>$id]);
		
			foreach ($result->getRecords() AS $record) {
				
				foreach($properties_array AS $prop){
					foreach($array AS $key3 =>$props){
						if($key3 == substr($prop,2)){
							$name = $type.'-'.$key3;
							$input_type = $props['type'];
							
							//get select list or load exertnal list maybe
							$select_list = '';
							if($input_type == 'select'){
								$select_list = $props['list'];
							}
						}							
					}
					$input='';
					if($input_type == 'checkbox'){
						$input = "<input name='$name' type='hidden' ".($record->value($prop) == 1? 'checked':'')." value='0'>".
								" <input type='checkbox' name='$name' ".( $record->value($prop) == 1 ? "checked":"")." value='1'>";
					}else if($input_type == 'select'){
						
						 $input = '<select name="'.$name.'">
						 <option value=""></option>'.
						 $this->selectedOption($select_list, @$record->value($prop)).						
						'</select>';
					}else{
						$input = "<input type='text' name='$name' value='{$record->value($prop)}'>";
					}
					
					
					$element = "$name: $input";		
					
					
					$this->form_array[$type][] = $element ;
				}				
			}
	
	}	

	///INSERT	---------------
	public function insertForms(){

	
		//add to form out array
		foreach($_POST AS $name => $value){
	
			if($value !='Add'){
				list($type,$field) = explode('-',$name);
				$label_type='insert'.$type;
				$insert_array[$label_type][$field] = $value;//($value == '' ? '0' : $value);
				if(strtolower($type)== strtolower($_GET['field'])){
					$property = $type.'_array';
					$this->$property[$field] = $value;
				}
			}
			
		}		
	/*	echo '<pre>';
		var_dump($this->Organization_array);
		echo '</pre>';
		echo '<pre>';
		var_dump($insert_array);
		echo '</pre>';
		*/
		
		foreach($insert_array AS $name => $value){
			$this->$name($value);
		}
		
		
	}	



	
	public function getForms(){
		return $this->form_array;	
	}	

	public function buildQuery($data){
		$insert_array=[];
		$fields=[];
		foreach($data AS $field => $value){
			$insert_array[]=$field.':{'.$field.'}';
			$fields[$field]=$value;
		}
		$insert=implode(',',$insert_array);
		return (object)["insert"=>$insert,"fields"=>$fields];
	}
	

	public function insertOrganization($data=[]){			
	
		$queryObject=$this->buildQuery($data);	
		
		$id = $this->createID('Organization');
		$this->Organization_array['oid'] = $id;
		
		$query = "CREATE (biz:Organization {oid:'$id',".$queryObject->insert."})";
		$result = $this->client->run($query,$queryObject->fields);

	}


	
	
	public function insertLocation($data=[]){			

		$queryObject=$this->buildQuery($data);		
		
		$id = $this->createID('Location');
		$this->Location_array['oid'] = $id;
		
		
		$query = "
		MATCH (O:Organization {oid:'".$this->Organization_array['oid']."'})
		CREATE (O)<-[:Location_Of]-(:Location {oid:'$id',".$queryObject->insert."})
		";
		$result = $this->client->run($query,$queryObject->fields);

	}

	public function insertWebsite($data=[]){			

		$queryObject=$this->buildQuery($data);		
		
		$id = $this->createID('Website');
		$this->Website_array['oid'] = $id;
		 if(isset($this->Location_array['oid'])){
			$parent_label="Location";
			$parent_id=$this->Location_array['oid'];
		 }else{
			$parent_label="Organization";
			$parent_id=$this->Organization_array['oid'];
		 }
		
		$query = "
		MATCH (N2:$parent_label {oid:'".$parent_id."'})
		CREATE (N2)<-[:Website_Of]-(:Website {oid:'$id',".$queryObject->insert."})
		";
		$result = $this->client->run($query,$queryObject->fields);

	}	
	
	public function insertPerson($data=[]){			

		$queryObject=$this->buildQuery($data);		
		
		$id = $this->createID('Person');
		$this->Person_array['oid'] = $id;
		
		$query = "
		MATCH (O:Organization {oid:'".$this->Organization_array['oid']."'})
		CREATE (O)<-[:Works_For]-(:Person {oid:'$id',".$queryObject->insert."})
		";
		$result = $this->client->run($query,$queryObject->fields);

	}
	public function insertWeb_Account($data=[]){			

		$queryObject=$this->buildQuery($data);		
		
		$id = $this->createID('Web_Account');
		$this->Web_Account_array['oid'] = $id;
		
		$query = "
		MATCH (W:Website {oid:'".$this->Website_array['oid']."'})
		CREATE (W)<-[:Account_Of]-(:Web_Account {oid:'$id',".$queryObject->insert."})
		//RETURN O
		";
		$result = $this->client->run($query,$queryObject->fields);
		
		/*echo '<pre>';
		var_dump($queryObject->fields);
		echo '</pre>';
		*/
		
		//Add organization
		//$query = 'CREATE (biz:Organization {name:{name},type:{type},is_client:{is_client}})';
		//$neo4j->run($query,["name"=>$name,"type"=>$type,"is_client"=>$is_client]);

	}	
	
	
	
	//UPDATES--------

	
	
	
	public function updateForms(){
		$this->Organization_array['oid'] = $_GET['orgid'];
		//add to form out array
		foreach($_POST AS $name => $value){
	
			if($value !='Update'){//explode count !== 2...
				list($type,$field) = explode('-',$name);
				$update_array[$type][$field] = $value;//($value == '' ? '0' : $value);
				if(strtolower($type)== strtolower($_GET['field'])){
					$property = $type.'_array';
					$this->$property[$field] = $value;
				}
			}
			
		}	
		
		foreach($update_array AS $label => $value){
			$this->update($label,$value);
		}		
	}
	
	public function buildUpdateQuery($data){
		
		$update_array=[];
		$fields=[];
		foreach($data AS $field => $value){
			$update_array[]='n.'.$field."={".$field."}";
			$fields[$field]=$value;
		}
		$update=implode(',',$update_array);
		return (object)["update"=>$update,"fields"=>$fields];
	}
	
	public function update($label,$data=[]){			
		
		$prop_name= $label.'_array';	
		
		$queryObject=$this->buildUpdateQuery($data);	
		$query = "
		MATCH (n:$label {oid:'".$this->$prop_name['oid']."'}) 
		SET $queryObject->update";
		
		$result = $this->client->run($query,$queryObject->fields);
	}

	
	/**** DELETE FUNCTIONS ****/
	public function deleteOrganization($id){
		//Only deletes accounts they aren't attached to another organization (or another orgs location)
		$query = "MATCH (org:Organization {oid:'$id'})
		OPTIONAL MATCH (org:Organization)<-[*..3]-(w:Website)-[:Account_Of]-(a:Web_Account)
		WITH org,a
		OPTIONAL MATCH (a)-[*..3]->(o2:Organization)
		WHERE NOT o2.oid = '$id'
		WITH org,CASE WHEN COUNT(o2) > 0 THEN NULL ELSE a END AS a

		OPTIONAL MATCH (w:Website)-[:Website_Of]->(org)
		WITH org,a,w

		OPTIONAL MATCH (l:Location)-[:Location_Of]->(org)
		WITH org,a,w,l

		OPTIONAL MATCH (lw:Website)-[:Website_Of]->(l)
		WITH org,a,w,l,lw

		OPTIONAL MATCH (org)<-[*..3]-(p:Person)
		WITH org,a,w,l,lw,p
        OPTIONAL MATCH (p)-[*..3]->(o2:Organization)
        WHERE NOT o2.oid = '$id'
        WITH org,a,w,l,lw,CASE WHEN COUNT(o2) > 0 THEN NULL ELSE p END AS p

		WITH org,a,w,l,lw,p
		DETACH DELETE org,a,w,l,lw,p
		";
		$result = $this->client->run($query);
	}	  
	
	public function deleteLocation($id){
		//Only deletes accounts if they aren't an account for an organization or another location 
		$query = "		
	MATCH (loc:Location {oid:'$id'})
		OPTIONAL MATCH (loc:Location)<-[:Website_Of]-(ws:Website)-[:Account_Of]-(a:Web_Account)
		
		WITH loc,a
		
		OPTIONAL MATCH (a)-[:Account_Of]->(:Website)-[:Website_Of]->(l2:Location)
		WHERE NOT l2.oid = '$id'
		WITH loc,a,l2 AS locs 		

		WITH loc,a,locs
		OPTIONAL MATCH (a)-[:Account_Of]-(:Website)-[:Website_Of]->(orgs2:Organization) 
				
		WITH loc,CASE WHEN (COUNT(locs)  + COUNT(orgs2)) > 0 THEN NULL ELSE a END AS a		
		
		OPTIONAL MATCH (w:Website)-[:Website_Of]->(loc)
		WITH loc,a,w

		OPTIONAL MATCH (loc)<-[:Works_For]-(p:Person)		
		WITH loc,a,w,p
		
		
		OPTIONAL MATCH (p)-[:Works_For]->(orgs:Organization)
		WITH loc,a,w,p,orgs

		OPTIONAL MATCH (p)-[:Works_For]->(locs:Location)
		WHERE NOT locs.oid = '$id'
		
		WITH loc,a,w,CASE WHEN (COUNT(locs)  + COUNT(orgs)) > 0 THEN NULL ELSE p END AS p		
		

		DETACH DELETE loc,a,w,p
		";
		$result = $this->client->run($query);
	}	  	
	
	public function deleteWebsite($id){
		//Only deletes account if it is not an account for another website
		$query = "MATCH (ws:Website {oid:'$id'})
		OPTIONAL MATCH (ws:Website)<-[:Account_Of]-(a:Web_Account)
		WITH ws,a
		OPTIONAL MATCH (a)-[:Account_Of]->(w2:Website)
		WHERE NOT w2.oid = '$id'
		WITH ws,CASE WHEN COUNT(w2) > 0 THEN NULL ELSE a END AS a
		DETACH DELETE ws,a";
		$result = $this->client->run($query);
	}	  		
	
	public function deleteWeb_Account($id){
		$query = "MATCH (wa:Web_Account {oid:'$id'})
		DETACH DELETE wa";
		$result = $this->client->run($query);
	}	  	
	
	public function deletePerson($id){
		$query = "MATCH (p:Person {oid:'$id'})
		DETACH DELETE p";
		$result = $this->client->run($query);
	}	 
}
/*
 Old
	public function deleteOrganization($id){
		//Only deletes accounts they aren't attached to another organization (or another orgs location)
		$query = "MATCH (org:Organization {oid:'$id'})
		OPTIONAL MATCH (org:Organization)<-[*..3]-(w:Website)-[:Account_Of]-(a:Web_Account)
		WITH org,a
		OPTIONAL MATCH (a)-[*..3]->(o2:Organization)
		WHERE NOT o2.oid = '$id'
		WITH org,CASE WHEN COUNT(o2) > 0 THEN NULL ELSE a END AS a

		OPTIONAL MATCH (w:Website)-[:Website_Of]->(org)
		WITH org,a,w

		OPTIONAL MATCH (l:Location)-[:Location_Of]->(org)
		WITH org,a,w,l

		OPTIONAL MATCH (lw:Website)-[:Website_Of]->(l)
		WITH org,a,w,l,lw

		OPTIONAL MATCH (p:Person)-[:Works_For]->(org)
		WITH org,a,w,l,lw,p
		OPTIONAL MATCH (pl:Person)-[:Works_For]->(l)
		WITH org,a,w,l,lw,p,pl
		DETACH DELETE org,a,w,l,lw,p,pl";
		$result = $this->client->run($query);
	}	  


	
	
	
	
	
	
	
MATCH (loc:Location {oid:'4'})
		OPTIONAL MATCH (loc:Location)<-[:Website_Of]-(ws:Website)-[:Account_Of]-(a:Web_Account)
		
		WITH loc,a
		
		OPTIONAL MATCH (a)-[:Account_Of]->(:Website)-[:Website_Of]->(l2:Location)
		WHERE NOT l2.oid = '4'
		WITH loc,a,l2 AS locs 		

		WITH loc,a,locs
		OPTIONAL MATCH (a)-[:Account_Of]-(:Website)-[:Website_Of]->(orgs2:Organization) 
				
		WITH loc,CASE WHEN (COUNT(locs)  + COUNT(orgs2)) > 0 THEN NULL ELSE a END AS a		
		
		OPTIONAL MATCH (w:Website)-[:Website_Of]->(loc)
		WITH loc,a,w

		OPTIONAL MATCH (loc)<-[:Works_For]-(p:Person)		
		WITH loc,a,w,p
		
		
		OPTIONAL MATCH (p)-[:Works_For]->(orgs:Organization)
		WITH loc,a,w,p,orgs

		OPTIONAL MATCH (p)-[:Works_For]->(locs:Location)
		WHERE NOT locs.oid = '4'
		
		WITH loc,a,w,CASE WHEN (COUNT(locs)  + COUNT(orgs)) > 0 THEN NULL ELSE p END AS p		
		

		RETURN loc,a,w,p
	
	

*/





?>