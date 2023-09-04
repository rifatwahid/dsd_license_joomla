<?php 

$key = $this->key;
$val = $this->val;
$name = $this->name;

$jsConfig = JSFactory::getConfig();
$urlToAttrs = $jsConfig->image_attributes_live_path . '/';
$pathToAttrs = $jsConfig->image_attributes_path . '/';

foreach($this->lst as $i=>$obj) {
	$sel = '';
	$id_text = $this->id.'_'. $obj->$key;
	$class_text = $this->id.'_'. $obj->$key;
	if (is_array($this->actived) && in_array($obj->$key, $this->actived)) {
		$sel = ' checked="checked"';
	}?>

	<span class="input_type_checkbox">
		<label>
			<input type="checkbox" name="<?php echo $name; ?>[<?php echo $obj->$key; ?>]" id="<?php echo $id_text; ?>" class="<?php echo $class_text; ?>" value="<?php echo $obj->$key; ?>" <?php echo $sel . ' ' . $this->params; ?>> 

			<?php if (!empty($obj->image) && file_exists($pathToAttrs . $obj->image)): ?>
				<img src="<?php echo $urlToAttrs . $obj->image; ?>">
			<?php endif; ?>

			<?php echo $obj->$val; ?>
		</label>
	</span>

	<?php echo $this->separator;
}