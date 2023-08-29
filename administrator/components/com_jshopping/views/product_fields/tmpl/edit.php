<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$row=$this->row;
?>

<form action="index.php?option=com_jshopping&controller=productfields" method="post"name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start ?? ''?>
	<div class="jshops_edit product_fields_edit">
		<?php 
		foreach($this->languages as $lang) :
		$name="name_".$lang->language;
		?>
			<div class="form-group row align-items-center">
				<label for="<?php print $name?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="text" class="inputbox form-control" id="<?php print $name?>" name="<?php print $name?>" value="<?php echo $row->$name;?>" />
				</div>
			</div>
		<?php endforeach; ?>

		<?php 
		foreach($this->languages as $lang) :
		$description="description_".$lang->language;
		?>
			<div class="form-group row align-items-center">
				<label for="<?php print $description?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php
						$editor = \JEditor::getInstance(\JFactory::getConfig()->get('editor'));
						print $editor->display($description,  $row->$description , '100%', '350', '75', '20' ) ;
					?>
				</div>				
			</div>
		<?php endforeach; ?>

		<div class="form-group row align-items-center">
			<label for="allcats" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_SHOW_FOR_CATEGORY');?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $this->lists['allcats'];?>
			</div>
		</div>
		<div  id="tr_categorys" class="form-group row align-items-center" <?php if ($row->allcats=="1") print "style='display:none;'";?>>
			<label for="category_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_CATEGORIES');?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $this->lists['categories'];?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="type0" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_TYPE');?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $this->lists['type'];?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="group" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_GROUP');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $this->lists['group'];?>
			</div>
		</div>
		<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>

	</div>
	<div class="clr"></div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo isset($row->id) ? $row->id : 0; ?>" />
<?php print $this->tmp_html_end ?? ''?>
</form>