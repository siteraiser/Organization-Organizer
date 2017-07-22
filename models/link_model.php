<?php 
//CREATE INDEX ON :User(name)
//CREATE INDEX ON :User(name)

//use Neoxygen\NeoClient\ClientBuilder;





class link_model extends requestHandler{
	
	public $labels =""; 
	public $links =[];
	public $add_links =[];	
	public function __construct($Labels){	
		
		$this->labels=$Labels;
	}

	
	/// GET Links	
	public function getLinks(){
		
		$array = $this->labels;
		//add to form out array
		foreach($array AS $key => $value){			
			$this->fields[] = $key;			
			$fields[] = strtolower($key);
		}	
		return $fields;
	}	

	
	/// GET Links	
	public function OrganizationLinks($id){
		//Set the links for adding new entries
		$this->addOrganizationLinks($id);
		
		$query = "MATCH (O:Organization {oid:'".$id."'})<-[:Location_Of]-(n:Location)
		RETURN n.name,n.oid,n.type";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Location'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name'),"type"=>$record->value('n.type')];
		}		
		
		$query = "MATCH (O:Organization {oid:'".$id."'})<-[:Website_Of]-(n:Website)
		RETURN n.name,n.oid,n.type";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Website'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name'),"type"=>$record->value('n.type')];
		}			
		
		$query = "MATCH (O:Organization {oid:'".$id."'})<-[:Works_For]-(n:Person)
		RETURN n.first_name,n.oid";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Person'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.first_name')];
		}	
		
	}	
	
	
	
	/// GET Links	
	public function WebsiteLinks($id){
		//Set the links for adding new entries
		$this->addWebsiteLinks($id);
		
		$query = "MATCH (W:Website {oid:'".$id."'})-[:Website_Of]->(n:Organization)
		RETURN n.name,n.oid";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Organization'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name')];
		}	
		
		$query = "MATCH (W:Website {oid:'".$id."'})-[:Website_Of]->(n:Location)
		RETURN n.name,n.oid";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Location'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name'),"type"=>$record->value('n.type')];
		}	

		$query = "MATCH (W:Website {oid:'".$id."'})<-[:Account_Of]-(n:Web_Account)
		RETURN n.name,n.oid";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Web_Account'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name')];
		}				
	}	

		/// GET Links	
	public function LocationLinks($id){
		//Set the links for adding new entries
		$this->addLocationLinks($id);
		
		$query = "MATCH (L:Location {oid:'".$id."'})-[:Location_Of]->(n:Organization)
		RETURN n.name,n.oid,n.type";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Organization'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name'),"type"=>$record->value('n.type')];
		}		
		
		$query = "MATCH (L:Location {oid:'".$id."'})<-[:Website_Of]-(n:Website)
		RETURN n.name,n.oid,n.type";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Website'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name'),"type"=>$record->value('n.type')];
		}		
	}	
	
		/// GET Links
	public function PersonLinks($id){
		$query = "MATCH (P:Person {oid:'".$id."'})-[:Works_For]->(n:Organization)
		RETURN DISTINCT n.name,n.oid,n.type";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Organization'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name'),"type"=>$record->value('n.type')];
		}	
		
		$query = "MATCH (P:Person {oid:'".$id."'})-[:Works_For]->(n:Location)
		RETURN DISTINCT n.name,n.oid,n.type";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Location'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name'),"type"=>$record->value('n.type')];
		}				
	}	
	
			/// GET Links	
	public function Web_AccountLinks($id){
		$query = "MATCH (W:Web_Account {oid:'".$id."'})-[:Account_Of]->(n:Website)
		OPTIONAL MATCH (n)-[:Website_Of]->(l:Location)
		RETURN DISTINCT n.name,n.oid,n.type,l.name";
		
		//add to form out array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$this->links['Website'][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name'),"type"=>$record->value('n.type'),"location-name"=>$record->value('l.name')];
		}		
	}	
	
	
	/**** Links Array to Insert New Records ****/
	
	/// GET Links	
	public function addOrganizationLinks($id){
				
		//add to form out array		
		$this->add_links['Website'][]=["orgid"=>$id];
		$this->add_links['Location'][]=["orgid"=>$id];
		$this->add_links['Person'][]=["orgid"=>$id];
	}	
	
		/// GET Links	
	public function addLocationLinks($id){
		//add to form out array
		$this->add_links['Website'][]=["locationid"=>$id];
		$this->add_links['Person'][]=["locationid"=>$id];
	
	}	
			/// GET Links	
	public function addWebsiteLinks($id){
		//add to form out array
		$this->add_links['Web_Account'][]=["websiteid"=>$id];	
	}	
	
	
	/* Get arrays for linking nodes */
	public function getNameAndId($type="Website",$id){
		if($type == 'Website'){
			$query = "
			MATCH (n:$type)-[:Account_Of]-(n2:Web_Account)
			WHERE NOT n2.oid = '$id'
			RETURN DISTINCT n.name,n.oid";
			
			$removal_query = "
			MATCH (n:$type)<-[:Account_Of]-(n2:Web_Account{oid:'$id'})
			RETURN DISTINCT n.name,n.oid";
			
			
		}else if($type == 'Web_Account'){
			$query = "MATCH (n:$type)
			WHERE NOT (n)-[:Account_Of]->(:Website{oid:'$id'})
			RETURN DISTINCT n.name,n.oid";
			
			$removal_query = "MATCH (n:$type)-[:Account_Of]->(:Website{oid:'$id'})
			RETURN DISTINCT n.name,n.oid";
		}
		
		$link_to_array=[];
		$removal_array=[];
		//values for inputs array
		$result = $this->client->run($query);
		foreach ($result->getRecords() AS $record) {
			$link_to_array[$type][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name')];
		}		
		//values for rel removal
		$result = $this->client->run($removal_query);
		foreach ($result->getRecords() AS $record) {
			$removal_array[$type][]=["oid"=>$record->value('n.oid'),"name"=>$record->value('n.name')];
		}				
		return [$link_to_array,$removal_array];
	}	
	
	
	
}
?>