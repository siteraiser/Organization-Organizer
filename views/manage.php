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

<?php if( strtolower($_GET['field']) == 'web_account' && $view_mode == 'Update'){ ?>
Link a Website
<select name="link-website">
 <option value=""></option>
 <?php

 foreach($link_to_array['Website'] AS $value){
	echo '<option value="'.$value['oid'].'">'.$value['name'].'</option>';
 }

?>
 </select>
<button type="submit" name="submit" value="Add-Link">Add-Link</button>


<br>
Unlink a Website
<select name="unlink-website">
 <option value=""></option>
 <?php

 foreach($removal_array['Website'] AS $value){
	echo '<option value="'.$value['oid'].'">'.$value['name'].'</option>';
 }

?>
 </select>
<button type="submit" name="submit" value="Remove-Link">Remove-Link</button>



<?php } ?>

<?php if( strtolower($_GET['field']) == 'website' && $view_mode == 'Update'){ ?>

Link Web Account
 <select name="link-web_account">
 <option value=""></option>
 <?php

 foreach($link_to_array['Web_Account'] AS $value){
	echo '<option value="'.$value['oid'].'">'.$value['name'].'</option>';
 }
 
?>
</select> 
<button type="submit" name="submit" value="Add-Link">Add-Link</button>

<br>
Unlink Web Account
 <select name="unlink-web_account">
 <option value=""></option>
 <?php

 foreach($removal_array['Web_Account'] AS $value){
	echo '<option value="'.$value['oid'].'">'.$value['name'].'</option>';
 }
 
?>
</select> 
<button type="submit" name="submit" value="Remove-Link">Remove-Link</button>

<?php } ?>

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

