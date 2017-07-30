<form method="post" action="<?php echo $this->base_url; ?>manage/update?field=<?php echo @$_GET['field']. @$add_in ?>">
<?php
echo '<h1>'.$view_mode .': ' . $_GET['field'].'</h1>';




foreach($forms AS $type => $form){
	
?> <h2><?php echo $type; ?></h2>

	<?php foreach($form AS $input){ ?>
	<br>
		<?php echo $input; ?>
		
	<?php }	?>
<?php
}
?>
<br>
<button type="submit" name="submit" value="<?php echo $view_mode ?>"><?php echo $view_mode ?></button>

<br>


<?php 


function addLink($type,$link_to_array){
	
	$html = '<select name="link-'.strtolower($type).'">
	 <option value=""></option>';
	  foreach($link_to_array[$type] AS $value){
		$html.= '<option value="'.$value['oid'].'">'.$value['name'].'</option>';
	 }
	 
	 $html.= '</select>
	<button type="submit" name="submit" value="'.$type.'-Add-Link">Add-Link</button>';
	return $html;
}


function removeLink($type,$removal_array){

	$html = '<select name="unlink-'.strtolower($type).'">
	 <option value=""></option>';
	  foreach($removal_array[$type] AS $value){
		$html.= '<option value="'.$value['oid'].'">'.$value['name'].'</option>';
	 }
	 
	 $html.= '</select>
	<button type="submit" name="submit" value="'.$type.'-Remove-Link">Remove-Link</button>';
	return $html;
}


/*******
ORGANIZATION
********/


if( strtolower($_GET['field']) == 'organization' && $view_mode == 'Update'){ ?>
Link a Website
<?php
 echo addLink('Website',$link_to_array);
?>
<br>
Unlink a Website
<?php
 echo removeLink('Website',$removal_array);
?>

<br>

Link a Person
<?php
 echo addLink('Person',$link_to_array);
?>
<br>
Unlink a Person
<?php
 echo removeLink('Person',$removal_array);
   
 } ?>




<?php 
/*******
WEB ACCOUNT
********/

if( strtolower($_GET['field']) == 'web_account' && $view_mode == 'Update'){ ?>
Link a Website
<?php
 echo addLink('Website',$link_to_array);
?>

<br>

Unlink a Website
<?php
 echo removeLink('Website',$removal_array);

 } ?>




<?php 
/*******
WEBSITE
********/
 if( strtolower($_GET['field']) == 'website' && $view_mode == 'Update'){ ?>

Link Web Account
<?php
 echo addLink('Web_Account',$link_to_array);
?>

<br>

Unlink Web Account
 <?php
 echo removeLink('Web_Account',$removal_array);

 } ?>




<?php 
/*******
LOCATION
********/
 if( strtolower($_GET['field']) == 'location' && $view_mode == 'Update'){ ?>
Link a Website

<?php
 echo addLink('Website',$link_to_array);
?>
<br>
Unlink a Website
<?php
 echo removeLink('Website',$removal_array);
 
?>

<br>

Link a Person
<?php
 echo addLink('Person',$link_to_array);
?>
<br>
Unlink a Person
<?php
 echo removeLink('Person',$removal_array); 
 
 } ?>






<hr>
<?php if($view_mode != 'Add'){ ?>
	<button type="submit" name="submit" value="Delete">Delete <?php echo $_GET['field'];?></button>


</form>

<hr>
<h3>Update:</h3>
<?php
foreach($links as $label => $values){?>
	<div><?php echo $label;?>	
	<?php foreach($values as $key => $property) { ?>
		<div>
			<a href="<?php echo $this->base_url.'manage/update?field='. strtolower($label) . '&amp;editid='.$property['oid'].'&amp;update=1';?>">
			Update: <?php echo($property['name']).(isset($property['type'])? ' - '. $property['type']:'').(isset($property['location-name'])? ' - '. $property['location-name']:'');?>
			</a>
		</div>
	<?php
	}
	?>
	</div>
	<?php
}
/*
echo'<pre>';
var_dump($links);
echo'</pre>';
*/
?>

<h3>Add:</h3>

<?php
foreach($add_links as $label => $values){?>
	<div><?php echo $label;?>	
	<?php foreach($values as $key => $property) { ?>
		<div>
			<a href="<?php echo $this->base_url.'manage/add?field='. strtolower($label);
			foreach($property as $key2 => $val){ 
			 echo'&amp;'.$key2.'='. $val.'">'; 
			} ?>
		Add: <?php echo $label;?>
			</a>
		</div>
	<?php
	}
	?>
	</div>
	<?php
}
?>

<?php } ?>

