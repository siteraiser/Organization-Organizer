<!doctype html>
<html>
<head>
<style>
h4#stats{color:grey;}
.item {border: solid 1px grey;padding:1px 3px}
.page{border: 2px solid black;margin:5px;padding:5px;}


table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
</head>

<body>

<form method="get" action="<?php echo parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); ?>">
<?php echo (isset($hidden_inputs)?$hidden_inputs:'');?>


<h2>Fields</h2>
 <select name="field">
 <option value=""></option>
 <?php
 echo $this->selectedOption($fields, @$_GET['field']);
?>
</select> 

<h2>Properties</h2>
 <select name="search_property">
 <option value=""></option>
 <?php
 echo $this->selectedOption($properties, @$_GET['search_property']);
?>
</select> 

<br>
Contains:<input type="text" name="search" value="<?php echo(isset($_GET['search'])?$_GET['search']:'')?>"/>
<div class="center-align">
  <button type="submit">Search
  </button>
  </div>
</form>

<br>
<?php	
$total_page_count = ceil($count / $results_per_page);
$i = 0;
while(++$i <= $total_page_count){	
$style='black';
if(($page + 1) == $i){
	$style='green';
}
?>
<a style="border: 2px solid <?php echo $style; ?>; font-size:18px;margin: 3px;padding: 2px;" href="?page=<?php echo $i . $addon; ?>"><?php echo $i; ?></a>
<?php	
}
echo $count. ' Results';
?>


<hr>
<?php echo $out;?>
<hr>


<?php	
$i = 0;
while(++$i <= $total_page_count){	
$style='black';
if(($page + 1) == $i){
	$style='green';
}
?>
<a style="border: 2px solid <?php echo $style; ?>; font-size:18px;margin: 3px;padding: 2px;" href="?page=<?php echo $i . $addon; ?>"><?php echo $i; ?></a>
<?php	
}
?>

</body>
</html>