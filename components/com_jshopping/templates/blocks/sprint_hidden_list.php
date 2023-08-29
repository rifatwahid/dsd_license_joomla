<?php 

$key = $this->key;
$val = $this->val;
$name = $this->name;

foreach($this->lst as $obj) {
	$sel = '';
	$id_text = $this->id . $obj->$key;

	//if ($obj->$key == $this->actived) 
	{
		$sel = ' checked="checked"';
	}?>

	<span class="input_type_radio">
		<input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $id_text; ?>" value="<?php echo $obj->$key; ?>" <?php echo $sel . ' ' . $this->params; ?>> 

		<label for="<?php echo $id_text; ?>">
			<?php echo $obj->$val; ?>
		</label>
	</span>

	<?php echo $this->separator;
}
