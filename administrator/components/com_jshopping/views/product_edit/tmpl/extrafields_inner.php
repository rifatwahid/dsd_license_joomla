<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$groupname = '';
?>

<div class="jshops_edit extrafields_inner_edit">
	<?php foreach($this->fields as $field) : ?>
		<?php if ($groupname != $field->groupname) : $groupname = $field->groupname;?>
			<div class="form-group row align-items-center">
				<div for="is_use_additional_characteristics" class="col-sm-3 col-md-2 col-xl-2 col-12">
					<b>
						<?php echo $groupname; ?>
					</b>
				</div>
			</div>
		<?php endif; ?>

		<div class="form-group row align-items-center">
			<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<div style="padding-left:10px;">
					<?php echo $field->name; ?>
				</div>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $field->values; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>