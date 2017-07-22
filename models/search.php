<?php 

class search_model extends requestHandler{
	public function countRecords($label){
		$query = "	
		MATCH (n:$label)
		WHERE (n.name =~ {search}) 
		RETURN count(DISTINCT n)";
		
		$result1 = $neo4j->run($query,["search"=>"(?i).*$search.*"]);
			
		foreach ($result1->getRecords() as $record1) {
			$count = $record1->value('count(DISTINCT n)');
		}
		return $count;
	}
	
	
	public function getRecords($label,$search,$skip,$results_per_page){	
		if($label == 'Location'){
			$insert ='OPTIONAL MATCH (n)-[:Location_Of]->(o)';
		}else if($label == 'Website'){
			$insert ='OPTIONAL MATCH (n)-[*..3]->(o:Organization)';
			//$insert2 ='OPTIONAL MATCH (n)-[:Location_Of]->(o)';
		}
	
		$query = "
		MATCH (n:$label)
		WHERE (n.name =~ {search}) 
			
		RETURN DISTINCT n.name, n.oid, o.oid 
		ORDER BY n.oid DESC
		SKIP {skip}
		LIMIT {rpp}";
			
		return $neo4j->run($query,["search"=>"(?i).*$search.*","skip"=>$skip,"rpp"=>$results_per_page]);//'(?i).*
		

}

?>