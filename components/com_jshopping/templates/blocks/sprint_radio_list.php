<?php 

$key = $this->key;
$val = $this->val;
$name = $this->name;

foreach($this->lst as $obj) :
	$id_text = $this->id; //. $obj->$key;
	$sel = ($obj->$key == $this->actived) ? ' checked="checked"' : '';
?>

	<span class="input_type_radio">
		<label>
			<input type="radio" name="<?php echo $name; ?>" id="<?php echo $id_text; ?>" value="<?php echo $obj->$key; ?>" <?php echo $sel . ' ' . $this->params; ?>> 
			<?php echo $obj->$val; ?>
		</label>
	</span>

	<?php echo $this->separator;
endforeach; 