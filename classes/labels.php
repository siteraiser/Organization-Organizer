<?php

$states = array('NY','NJ','OH');

$Labels = 
(object)array(
  'Organization' => 
	(object)array("Properties"=>
		array(
		'name'=>array('type'=>'text'),
		'type'=>
			array('type'=>'select','list'=>
				array('Organization','Corporation')
			),
		'email'=>array('type'=>'text'),
		'is_client'=>array('type'=>'checkbox'),
		'mailing_address'=>array('type'=>'text'),
		'locality'=>array('type'=>'text'),
		'state_region'=>
			array('type'=>'select','list'=>$states),
		'zip'=>array('type'=>'text'),
		)
	),
	'Location' => 
	(object)array("Properties"=>
		array(
		'name'=>array('type'=>'text'),
		'phone'=>array('type'=>'text'),
		'street_address'=>array('type'=>'text'),
		'locality'=>array('type'=>'text'),
		'state_region'=>
			array('type'=>'select','list'=>$states),
		'zip'=>array('type'=>'text'),
		'type'=>
			array('type'=>'select','list'=>
				array('LocalBusiness','Place')
			),
		'point_of_sale'=>array('type'=>'checkbox')
		)
	),
	'Website' => 
	(object)array("Properties"=>
		array(
		'type'=>array('type'=>'text'),
		'name'=>array('type'=>'text'),
		'domain'=>array('type'=>'text'),
		'url'=>array('type'=>'text'),
		'preferred_url'=>array('type'=>'text'),
		'is_https'=>array('type'=>'checkbox')
		)
	),
	'Web_Account' => 
	(object)array("Properties"=>
		array(
		'type'=>array('type'=>'text'),
		'name'=>array('type'=>'text'),
		'login_page'=>array('type'=>'text'),
		'associated_email'=>array('type'=>'text'),
		'user'=>array('type'=>'text'),
		'password'=>array('type'=>'text')
		)
	),
	'Person' => 
	(object)array("Properties"=>
		array(
		'first_name'=>array('type'=>'text'),
		'last_name'=>array('type'=>'text'),
		'age'=>array('type'=>'text')
		)
	)
	
);