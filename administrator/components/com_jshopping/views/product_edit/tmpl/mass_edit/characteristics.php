<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');
?>

<div class="jshops_edit mass_edit_characteristics">
	<div class="form-group row align-items-center">
		<label class="col-sm-3 col-md-2 col-xl-2 col-12 font-weight-bold fw-bold text-uppercase col-form-label">
			<div>
				<?php echo Text::_('COM_SMARTSHOP_BATH_PRODUCT_EDIT_ACTION'); ?>
			</div>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<?php echo $this->characteristics_action; ?>
		</div>
	</div>

	<?php foreach($this->fields as $field) : ?>
		<div class="form-group row align-items-center">
			<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<div>
					<?php echo $field->name; ?>
				</div>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $field->values; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>