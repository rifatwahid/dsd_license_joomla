<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;
$row=$this->row;
?>

<form action = "index.php?option=com_jshopping&controller=productfieldvalues&field_id=<?php echo $this->field_id; ?>" method="post" id="adminForm" name="adminForm" enctype="multipart/form-data">
<?php echo $this->tmp_html_start ?? '' ?>
<div class="jshops_edit product_field_values_edit">
   <?php 
    foreach($this->languages as $lang){
    $field="name_".$lang->language;
    ?>
		<div class="form-group row align-items-center">
			<label for="<?php echo $field?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
               <?php echo JText::_('COM_SMARTSHOP_TITLE'); ?> <?php if ($this->multilang) echo "(".$lang->lang.")";?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
               <input type="text" class="inputbox form-control" id="<?php echo $field?>" name="<?php echo $field?>" value="<?php echo $row->$field;?>" />
			</div>
		</div>
    <?php }?>
    <?php $pkey="etemplatevar";if (isset($this->$pkey) && $this->$pkey){echo $this->$pkey;}?> 
					
	<div class="form-group row align-items-center">
		<label for="" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
			<?php echo JText::_('COM_SMARTSHOP_IMAGE_PRODUCTFIELD_VALUE')?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<?php echo LayoutHelper::render('fields.media', [
				'name' => 'image',
				'id' => 'image',
				'folder' => 'img_characteristics',
				'type' => 'smartshopimgs',
				'preview' => 'tooltip',
				'value' => $this->row->image
			]); ?>                       
		</div>
	</div>   
</div>
<div class="clr"></div>

<input type="hidden" name="old_image" value="<?php echo $this->row->image; ?>" />
<input type="hidden" name="field_id" value="<?php echo $this->field_id?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo isset($row->id) ? $row->id : 0; ?>" />
<?php echo $this->tmp_html_end ?? '' ?>
</form>