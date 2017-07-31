<?php 
class manage extends requestHandler{
	public function __construct() {		
		parent::client(30,'Password'); 
    }	
	
	public function index(){
		$data['title']="Home";
		require_once $this->doc_root().'classes/labels.php';

		//$this->loadModel('manage_model',$Labels);	
		$fields = $this->loadModel('link_model',$Labels);	

		$fields = $this->link_model->getLinks();
	//	echo '<pre>';
	//	var_dump($fields);
	//	echo '</pre>';
		
		
		$this->addView('list',$data);
		
	}
	public function add(){
		
		$data['title']="Home";
		require_once $this->doc_root().'classes/labels.php';

		$this->loadModel('manage_model',$Labels);	
			switch (strtolower($_GET['field'])) {
			case 'organization':
				if(!isset($_POST['submit']) && !isset($_GET['update'])){					
					$data['view_mode'] = 'Add';
					$this->manage_model->makeForms('Organization');	   
					$this->manage_model->makeForms('Website');	 
					$this->manage_model->makeForms('Person');					
				} 
				break;				
				
			case 'location':
				if(!isset($_POST['submit']) && !isset($_GET['update'])){					
					$data['view_mode'] = 'Add';
					$this->manage_model->makeForms('Location');	 					
				} 			   
				break;		
				
			case 'website':
				if(!isset($_POST['submit']) && !isset($_GET['update'])){					
					$data['view_mode'] = 'Add';
					$this->manage_model->makeForms('Website');	 					
				}			   
				break;
				
			case 'person':
				if(!isset($_POST['submit']) && !isset($_GET['update'])){					
					$data['view_mode'] = 'Add';
					$this->manage_model->makeForms('Person');	 					
				}			   
				break;				
				
			case 'web_account':	
				if(!isset($_POST['submit']) && !isset($_GET['update'])){					
					$data['view_mode'] = 'Add';
					$this->manage_model->makeForms('Web_Account');	 					
				}					
					   
				break;
				
			case 'login_details':
				echo "i equals 1";
				break;
		}
		
		
			
		$data['forms'] = $this->manage_model->getForms();

		$type = ucfirst($_GET['field']).'_array';
		$data['add_in']='';
		//Org ID
	if(isset($_GET['orgid'])){
	   $data['add_in'] = '&orgid='.$_GET['orgid'];
	}else if(isset($this->manage_model->Organization_array['oid']) && $this->manage_model->Organization_array['oid'] !== ''){
	   $data['add_in'] = '&orgid='.$this->manage_model->Organization_array['oid'];	
	}


	//Loc ID
	if(isset($_GET['locationid'])){
	   $data['add_in'].= '&locationid='.$_GET['locationid'];
	}else if(isset($this->manage_model->Location_array['oid']) && $this->manage_model->Location_array['oid'] !== ''){
	   $data['add_in'].= '&locationid='.$this->manage_model->Location_array['oid'];	
	}



	//Website ID
	if(isset($_GET['websiteid'])){
	   $data['add_in'].= '&websiteid='.$_GET['websiteid'];
	}else if(isset($this->manage_model->Website_array['oid']) && $this->manage_model->Website_array['oid'] !== ''){
	   $data['add_in'].= '&websiteid='.$this->manage_model->Website_array['oid'];	
	}



	//Edit ID
	if(isset($_GET['editid'])){
	   $data['add_in'].= '&editid='.$_GET['editid'];
	}else if(isset($this->manage_model->$type['oid']) && $this->manage_model->$type['oid'] !== ''){
	   $data['add_in'].= '&editid='.$this->manage_model->$type['oid'];
	}
		
		$this->addView('manage',$data);
	}
	
	
	public function update(){
		
		
		
		$data['title']="Update";
		require_once $this->doc_root().'classes/labels.php';

		$this->loadModel('manage_model',$Labels);	
		$this->loadModel('link_model',$Labels);	
	

		switch (strtolower($_GET['field'])) {
			case 'organization':
			//Un-Linking
				if($_POST['submit']=='Website-Remove-Link'){
					$this->manage_model->removeLink($_POST['unlink-website'],$_GET['editid'],'Website','Organization','Website_Of');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done


			//Linking
				if($_POST['submit']=='Website-Add-Link'){
					$this->manage_model->createLink($_POST['link-website'],$_GET['editid'],'Website','Organization','Website_Of');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done
			
			
			//Un-Linking
				if($_POST['submit']=='Person-Remove-Link'){
					$this->manage_model->removeLink($_POST['unlink-person'],$_GET['editid'],'Person','Organization','Works_For');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done


			//Linking
				if($_POST['submit']=='Person-Add-Link'){
					$this->manage_model->createLink($_POST['link-person'],$_GET['editid'],'Person','Organization','Works_For');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done		
			
			
			

			//Deletion
				if(isset($_POST['submit']) && $_POST['submit'] == 'Delete' && !isset($_GET['update'])){

					if(isset($_GET['orgid'])){
						$this->manage_model->Organization_array['oid']=$_GET['editid'];
					}
					$this->manage_model->deleteOrganization($_GET['editid']);	  
					//redirect back to /manage or add
					$data['view_mode'] = 'Update';//next mode

					
				}
			
				if(isset($_POST['submit']) && $_POST['submit'] == 'Add' && !isset($_GET['update'])){
					/*
					explode names and update all fields
					*/
					$data['view_mode'] = 'Update';//next mode
					$this->manage_model->insertForms();	   					
					
				} else if((isset($_POST['submit']) && $_POST['submit'] == 'Update') && !isset($_GET['update'])){
					/*
					explode names and update all fields
					*/
					$data['view_mode'] = 'Update';//next mode
					$this->manage_model->updateForms();	   
					
				}else{
					$data['view_mode'] = 'Update';//next mode
				//	if(isset($_GET['orgid'])){
				//		$this->manage_model->Organization_array['oid']=$_GET['orgid'];
				//	}else{
						$this->manage_model->Organization_array['oid']=$_GET['editid'];
				//	}
				}	
				$this->manage_model->filledForms('Organization');
				$this->link_model->OrganizationLinks($this->manage_model->Organization_array['oid']);
				$data['links'] = $this->link_model->links;
				$data['add_links'] = $this->link_model->add_links;
			 // $this->load('Organization');	  
			 
			 	//Get node linking data / links -- Gets websites
				$arrays = $this->link_model->getNameAndIdOrganization($this->manage_model->Organization_array['oid']);
				$data['link_to_array'] = $this->link_model->link_to_array;
				$data['removal_array'] = $this->link_model->removal_array;
				
			   
				break;
				
				
			case 'location':
				//Un-Linking - website
				if($_POST['submit']=='Website-Remove-Link'){
					$this->manage_model->removeLink($_POST['unlink-website'],$_GET['editid'],'Website','Location','Website_Of');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done


				//Linking
				if($_POST['submit']=='Website-Add-Link'){
					$this->manage_model->createLink($_POST['link-website'],$_GET['editid'],'Website','Location','Website_Of');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done
			
			
			//Un-Linking - person
				if($_POST['submit']=='Person-Remove-Link'){
					$this->manage_model->removeLink($_POST['unlink-person'],$_GET['editid'],'Person','Location','Works_For');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done


			//Linking
				if($_POST['submit']=='Person-Add-Link'){
					$this->manage_model->createLink($_POST['link-person'],$_GET['editid'],'Person','Location','Works_For');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done		
			
			
			
			
			
			//Deletion
				if(isset($_POST['submit']) && $_POST['submit'] == 'Delete' && !isset($_GET['update'])){

					if(isset($_GET['orgid'])){
						$this->manage_model->Location_array['oid']=$_GET['editid'];
					}
					$this->manage_model->deleteLocation($_GET['editid']);	  
					//redirect back to /manage or add
					$data['view_mode'] = 'Update';//next mode

					
				}
			//Adding
				if(isset($_POST['submit']) && $_POST['submit'] == 'Add' && !isset($_GET['update'])){
					/*
					explode names and insert all fields
					*/
					if(isset($_GET['orgid'])){
						$this->manage_model->Organization_array['oid']=$_GET['orgid'];
					}
					$data['view_mode'] = 'Update';//next mode
					$this->manage_model->insertForms();	   					
					
				} else if((isset($_POST['submit']) && $_POST['submit'] == 'Update') && !isset($_GET['update'])){
					/*
			Update -- explode names and insert all fields
					*/
				//	$this->manage_model->Organization_array['oid']=$_GET['orgid'];
					$this->manage_model->Location_array['oid']=$_GET['editid'];
					
					$data['view_mode'] = 'Update';//next mode
					$this->manage_model->updateForms();	   					
					
				}else{
			//show
					$this->manage_model->Organization_array['oid']=$_GET['orgid'];
					$this->manage_model->Location_array['oid']=$_GET['editid'];
					$data['view_mode'] = 'Update';//next mode		 
					
				}	
				$this->manage_model->filledForms('Location');
				$this->link_model->LocationLinks($this->manage_model->Location_array['oid']);
				$data['links'] = $this->link_model->links;
			  	$data['add_links'] = $this->link_model->add_links;
				
				//Get node linking data / links -- Gets websites
				$arrays = $this->link_model->getNameAndIdLocation($this->manage_model->Location_array['oid']);
				$data['link_to_array'] = $this->link_model->link_to_array;
				$data['removal_array'] = $this->link_model->removal_array;
			
				break;		

				
			case 'website':
				//Linking
				if($_POST['submit']=='Web_Account-Remove-Link'){
					$this->manage_model->removeLink($_POST['unlink-web_account'],$_GET['editid'],'Web_Account','Website','Account_Of');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done


				//Linking
				if($_POST['submit']=='Web_Account-Add-Link'){
					$this->manage_model->createLink($_POST['link-web_account'],$_GET['editid'],'Web_Account','Website','Account_Of');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done


				
				//Delete
				if(isset($_POST['submit']) && $_POST['submit'] == 'Delete' && !isset($_GET['update'])){

					if(isset($_GET['orgid'])){
						$this->manage_model->Website_array['oid']=$_GET['editid'];
					}
					$this->manage_model->deleteWebsite($_GET['editid']);	  
					//redirect back to /manage or add
					$data['view_mode'] = 'Update';//next mode

					
				}
				
				
				
				if(isset($_POST['submit']) && $_POST['submit'] == 'Add' && !isset($_GET['update'])){
					/*
					explode names and insert all fields
					*/
					if(isset($_GET['orgid'])){
						$this->manage_model->Organization_array['oid']=$_GET['orgid'];
					}
					if(isset($_GET['locationid'])){
						$this->manage_model->Location_array['oid']=$_GET['locationid'];
					}
					
					$data['view_mode'] = 'Update';//next mode
					$this->manage_model->insertForms();	   					
					
				} else if((isset($_POST['submit']) && $_POST['submit'] == 'Update') && !isset($_GET['update'])){
					/*
					explode names and insert all fields
					*/

					$this->manage_model->Website_array['oid']=$_GET['editid'];
					
					$data['view_mode'] = 'Update';//next mode
					$this->manage_model->updateForms();	  					
					
				}else{
					$this->manage_model->Organization_array['oid']=$_GET['orgid'];
					$this->manage_model->Website_array['oid']=$_GET['editid'];
					$data['view_mode'] = 'Update';//next mode
				  				
				}	
				$this->manage_model->filledForms('Website');  
			   	$this->link_model->WebsiteLinks($this->manage_model->Website_array['oid']);
				$data['links'] = $this->link_model->links;
				$data['add_links'] = $this->link_model->add_links;
				
				//Gets web_accounts
				$arrays = $this->link_model->getNameAndIdWebsite($this->manage_model->Website_array['oid']);
				$data['link_to_array'] = $this->link_model->link_to_array;
				$data['removal_array'] = $this->link_model->removal_array;
				break;
				
			case 'person':
				//Delete
				if(isset($_POST['submit']) && $_POST['submit'] == 'Delete' && !isset($_GET['update'])){

					if(isset($_GET['orgid'])){
						$this->manage_model->Person_array['oid']=$_GET['editid'];
					}
					$this->manage_model->deletePerson($_GET['editid']);	  
					//redirect back to /manage or add
					$data['view_mode'] = 'Update';//next mode				
				}
				
				if(isset($_POST['submit']) && $_POST['submit'] == 'Add' && !isset($_GET['update'])){
					/*
					explode names and insert all fields
					*/
					if(isset($_GET['orgid'])){
						$this->manage_model->Organization_array['oid']=$_GET['orgid'];
					}
					if(isset($_GET['locationid'])){
						$this->manage_model->Location_array['oid']=$_GET['orgid'];
					}
					$data['view_mode'] = 'Update';//next mode
					$this->manage_model->insertForms();	   
					
				} else if((isset($_POST['submit']) && $_POST['submit'] == 'Update') && !isset($_GET['update'])){
					/*
					explode names and insert all fields
					*/
					$this->manage_model->Person_array['oid']=$_GET['editid'];
					
					$data['view_mode'] = 'Update';//next mode
					$this->manage_model->updateForms();	   
					
				}else{
					
					$this->manage_model->Person_array['oid']=$_GET['editid'];
					$data['view_mode'] = 'Update';//next mode  

				}	
				$this->manage_model->filledForms('Person');	
			   	$this->link_model->PersonLinks($this->manage_model->Person_array['oid']);
				$data['links'] = $this->link_model->links;			   
				break;
				
				
			case 'web_account':
				
				if($_POST['submit']=='Website-Remove-Link'){
					$this->manage_model->removeLink($_GET['editid'],$_POST['unlink-website'],'Web_Account','Website','Account_Of');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done
				
				if($_POST['submit']=='Website-Add-Link'){
					$this->manage_model->createLink($_GET['editid'],$_POST['link-website'],'Web_Account','Website','Account_Of');//createLink($aid,$bid,$labela,$labelb,$link_label){	//a->b
				}//skips to the edit part :D when done
			
				//Delete
				if(isset($_POST['submit']) && $_POST['submit'] == 'Delete' && !isset($_GET['update'])){

					if(isset($_GET['orgid'])){
						$this->manage_model->Web_Account_array['oid']=$_GET['editid'];
					}
					$this->manage_model->deleteWeb_Account($_GET['editid']);	  
					//redirect back to /manage or add
					$data['view_mode'] = 'Update';//next mode				
				}
			
			
				if(isset($_POST['submit']) && $_POST['submit'] == 'Add' && !isset($_GET['update'])){
					/*
					explode names and insert all fields
					*/	
					
					if(isset($_GET['websiteid'])){
						$this->manage_model->Website_array['oid']=$_GET['websiteid'];
					}
					//Not currently being, save for future use though! 
				//	if(isset($_GET['orgid'])){
				//		$this->manage_model->Organization_array['oid']=$_GET['orgid'];
				//	}
										
					$data['view_mode'] = 'Update';//next mode
					$this->manage_model->insertForms();	   
					
				} else if((isset($_POST['submit']) && $_POST['submit'] == 'Update') && !isset($_GET['update'])){
					/*
					explode names and insert all fields
					*/
					$this->manage_model->Web_Account_array['oid']=$_GET['editid'];
					//$this->manage_model->Organization_array['oid']=$_GET['orgid'];
					//if(isset($_GET['websiteid'])){
					//	$this->manage_model->Website_array['oid']=$_GET['websiteid'];
					//}
					
					$data['view_mode'] = 'Update';//next mode
					$this->manage_model->updateForms();	   
					
				}else{
					$this->manage_model->Web_Account_array['oid']=$_GET['editid'];
				/*	$this->manage_model->Organization_array['oid']=$_GET['orgid'];
					if(isset($_GET['websiteid'])){
						$this->manage_model->Website_array['oid']=$_GET['websiteid'];
					}
				*/	
					$data['view_mode'] = 'Update';//next mode
				}	
				
				$this->manage_model->filledForms('Web_Account');
			 	$this->link_model->Web_AccountLinks($this->manage_model->Web_Account_array['oid']);
				$data['links'] = $this->link_model->links;
				
				//Gets websites
				$arrays = $this->link_model->getNameAndIdWeb_Account($this->manage_model->Web_Account_array['oid']);
				$data['link_to_array'] = $this->link_model->link_to_array;
				$data['removal_array'] = $this->link_model->removal_array;
				break;
			case 'login_details':
				echo "i equals 1";
				break;
		}
	
	
	$data['forms'] = $this->manage_model->getForms();

	$type = ucfirst($_GET['field']).'_array';

	$data['add_in'] ='';
	//Org ID
	if(isset($_GET['orgid'])){
	   $data['add_in'] = '&orgid='.$_GET['orgid'];
	}else if(isset($this->manage_model->Organization_array['oid']) && $this->manage_model->Organization_array['oid'] !== ''){
	   $data['add_in'] = '&orgid='.$this->manage_model->Organization_array['oid'];	
	}


	//Org ID
	if(isset($_GET['locationid'])){
	   $data['add_in'].= '&locationid='.$_GET['locationid'];
	}else if(isset($this->manage_model->Location_array['oid']) && $this->manage_model->Location_array['oid'] !== ''){
	   $data['add_in'].= '&locationid='.$this->manage_model->Location_array['oid'];	
	}



	//Website ID
	if(isset($_GET['websiteid'])){
	   $data['add_in'].= '&websiteid='.$_GET['websiteid'];
	}else if(isset($this->manage_model->Website_array['oid']) && $this->manage_model->Website_array['oid'] !== ''){
	   $data['add_in'].= '&websiteid='.$this->manage_model->Website_array['oid'];	
	}



	//Edit ID
	if(isset($_GET['editid'])){
	   $data['add_in'].= '&editid='.$_GET['editid'];
	}else if(isset($this->manage_model->$type['oid']) && $this->manage_model->$type['oid'] !== ''){
	   $data['add_in'].= '&editid='.$this->manage_model->$type['oid'];
	}


	
	
	
	
	
		$this->addView('header',$data);	
		$this->addView('manage',$data);
		$this->addView('list',$data);	
		$this->addView('footer',$data);
	}
}
