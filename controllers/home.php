<?php 
class home extends requestHandler{

	public function index(){
		$data['value']='<a href="'.$this->base_url.'manage">Manage</a><br><a href="'.$this->base_url.'search">Search</a>';
		
		$this->addView('header',$data);	
		$this->addView('home',$data);
		$this->addView('footer',$data);
	}
}