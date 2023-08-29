<?php 
/**
* @version      4.10.3 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php
$row=$this->row;
?>
<form action="index.php?option=com_jshopping&controller=licensekeyaddon" method="post" id="adminForm">
<?php if (isset($this->tmp_html_start)) print $this->tmp_html_start?>
<div class="jshops_edit addonkey">
	<div class="form-group row align-items-center">
		<label for="key" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
			<?php echo JText::_('COM_SMARTSHOP_KEY'); ?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox" id="key" name="key" value="<?php echo $row->key;?>" size="100" />
		</div>
	</div>
<div class="clr"></div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php print $row->id?>" />
<input type="hidden" name="alias" value="<?php print $row->alias?>" />
<input type="hidden" name="back" value="<?php print $this->back?>" />
<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?>
</form>