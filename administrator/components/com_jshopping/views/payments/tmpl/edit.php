<?php
/**
* @version      4.7.1 22.10.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$row=$this->payment;
$params=$this->params;
$lists=$this->lists;
$usergroups_list=$this->usergroups_list;
$jshopConfig=JSFactory::getConfig();
?>
<form action="index.php?option=com_jshopping&controller=payments" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
	<?php echo $this->tmp_html_start ?? ''; ?>

	<?php if (!isJoomla4()) {?>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#first-tab" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_PAYMENT_GENERAL');?></a></li>
			<?php if (!empty($lists['html'])) : ?>
				<li><a href="#second-tab" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_PAYMENT_CONFIG');?></a></li>
			<?php endif ?>
			<li><a href="#image" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_IMAGE');?></a></li>
		</ul>
	<?php } else { ?>
		<ul class="nav nav-tabs" id="myTab" role="tablist">
		  <li class="nav-item" role="presentation">
			<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#first-tab" type="button" role="tab" aria-controls="home" aria-selected="true"><?php echo JText::_('COM_SMARTSHOP_PAYMENT_GENERAL');?></button>
		  </li>
		  <?php if (!empty($lists['html'])) : ?>
		  <li class="nav-item" role="presentation">
			<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#second-tab" type="button" role="tab" aria-controls="profile" aria-selected="false"><?php echo JText::_('COM_SMARTSHOP_PAYMENT_CONFIG');?></button>
		  </li>
		  <?php endif ?>
		  <li class="nav-item" role="presentation">
			<button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#image" type="button" role="tab" aria-controls="contact" aria-selected="false"><?php echo JText::_('COM_SMARTSHOP_IMAGE');?></button>
		  </li>
		</ul>
	<?php } ?>
	

<div id="editdata-document" class="tab-content">
		<!-- First tab -->
		<div id="first-tab" class="tab-pane active">
			<div class="jshops_edit payments_edit">
				<div class="form-group row align-items-center">
					<label for="payment_publish" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_PUBLISH')?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="checkbox" id="payment_publish" class="form-check-input" name="payment_publish" value="1" <?php if ($row->payment_publish) echo 'checked="checked"'?> />
					</div>
				</div> 
				<div class="form-group row align-items-center">
					<label for="payment_code" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_CODE')?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="text" class="inputbox form-control" id="payment_code" name="payment_code" value="<?php echo $row->payment_code;?>" />
					</div>
				</div> 
			<?php
			foreach($this->languages as $lang){
			$field="name_".$lang->language;
			?>
					<div class="form-group row align-items-center">
						<label for="<?php print $field?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
							<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="text" class="inputbox form-control" id="<?php print $field?>" name="<?php print $field?>" value="<?php echo $row->$field;?>" />
						</div>
					</div> 
				<?php }?>
				<div class="form-group row align-items-center">
					<label for="payment_class" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_ALIAS');?>*
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="text" class="inputbox form-control" id="payment_class" name="payment_class" value="<?php echo $row->payment_class;?>" />
					</div>
				</div> 
				<div class="form-group row align-items-center hidden">
					<label for="scriptname" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_SCRIPT_NAME')?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="text" class="inputbox form-control" id="scriptname" name="scriptname" value="<?php echo $row->scriptname;?>" <?php if ($this->config->shop_mode==0 && $row->payment_id){?>readonly <?php }?> />
					</div>
				</div> 
			<?php if ($this->config->tax){?>
				<div class="form-group row align-items-center">
					<label for="tax" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_SELECT_TAX');?>*
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php echo $lists['tax'];?>
					</div>
				</div> 
			<?php }?>
				<div class="form-group row align-items-center">
					<label for="price_type" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_PRICE');?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<div class="input-group">
							<input type="text" class="inputbox form-control" name="price" value="<?php echo $row->price;?>" />
						<?php echo $lists['price_type'];?>
						</div>
					</div>
				</div> 
				<div class="form-group row align-items-center hidden">
					<label for="type_payment" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_TYPE_PAYMENT');?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php echo $lists['type_payment'];?>
					</div>
				</div> 
			<?php if (empty($params)) : ?>
				<div class="form-group row align-items-center">
					<label for="tax_name" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_DEFAULT_ORDER_STATUS');?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php print JHTML::_('select.genericlist', $this->order_status, 'payment_status', 'class = "inputbox form-select form-control" size = "1"', 'status_id', 'name', $this->payment_status ); ?>
					</div>
				</div> 
			<?php endif ?>
				<div class="form-group row align-items-center">
					<label for="usergroup_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_USERGROUPS');?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php print JHTML::_('select.genericlist', $this->usergroups_list, 'usergroup_id[]', 'class = "inputbox form-select form-control" size = "10" multiple = "multiple"', 'usergroup_id', 'usergroup_name',explode(',',$row->usergroup_id));	?>
					</div>
				</div> 
			<?php  foreach($this->languages as $lang){
					$field="description_".$lang->language;  ?>
					<div class="form-group row align-items-center">
						<label for="description<?php print $lang->id ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
							<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php
							$editor = \JEditor::getInstance(\JFactory::getConfig()->get('editor'));
							print $editor->display("description".$lang->id,  $row->$field , '100%', '350', '75', '20' ) ;
						?>
						</div>
					</div> 
			<?php }?>
				<div class="form-group row align-items-center">
					<label for="show_descr_in_email" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_SHOW_DESCR_IN_EMAIL');?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="checkbox" id="show_descr_in_email" class="form-check-input" name="show_descr_in_email" value="1" <?php if ($row->show_descr_in_email) echo 'checked="checked"'?> />
					</div>
				</div> 
				<div class="form-group row align-items-center">
					<label for="show_bank_in_order" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_SHOW_DEFAULT_BANK_IN_BILL');?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="hidden" name="show_bank_in_order" value="0">
						<input type="checkbox" id="show_bank_in_order" class="form-check-input" name="show_bank_in_order" value="1" <?php if ($row->show_bank_in_order) echo 'checked="checked"'?> />
					</div>
				</div> 
				<div class="form-group row align-items-center">
					<label for="order_description" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION_IN_BILL');?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<textarea id="order_description" class="form-control" name="order_description" rows="6" cols="30"><?php print $row->order_description?></textarea>
					</div>
				</div> 
			<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>		
			</div>
		</div>
	
		<!-- Second tab -->
		<?php if (!empty($lists['html'])) : ?>
				<div id="second-tab" class="tab-pane">
					<?php
						if ($lists['html']!=""){
							echo $lists['html'];
						}
					?>
				</div>
		<?php endif; ?>

		<!-- Image tab -->
		<div id="image" class="tab-pane">
			<div class="jshops_edit payments_settings_edit">
				<div class="form-group row align-items-center">
					<label for="image_btn" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
						<?php echo JText::_('COM_SMARTSHOP_IMAGE_SELECT');?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php echo LayoutHelper::render('fields.media', [
							'name' => 'image',
							'id' => 'img',
							'folder' => 'img_payments',
							'type' => 'smartshopimgs',
							'preview' => 'tooltip',
							'value' => $row->image
						]); ?>
					</div>
				</div>  
			
				<?php $pkey = 'plugin_template_img_'.reset($this->languages)->language; if (isset($this->$pkey) && $this->$pkey){ print $this->$pkey;}?>
			</div>
		</div>

</div>
<input type="hidden" name="payment_ordering" value="<?php echo $row->payment_ordering;?>" />
<input type="hidden" id="payments_width_image" name="payments_width_image" value="<?php echo $jshopConfig->image_payments_width?>" disabled="disabled" />
<input type="hidden" id="payments_height_image" name="payments_height_image" value="<?php echo $jshopConfig->image_payments_height?>" disabled="disabled" /> 
<input type="hidden" name="task" value="" />
<input type="hidden" name="payment_id" value="<?php echo $row->payment_id?>" />
<?php print $this->tmp_html_end ?? ''?>
</form>
