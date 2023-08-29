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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$row=$this->sh_method_price;
$lists=$this->lists;
$jshopConfig=JSFactory::getConfig();
?>
<form action="index.php?option=com_jshopping&controller=shippingsprices&shipping_id_back=<?php echo $this->shipping_id_back;?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
	<?php echo $this->tmp_html_start ?? ''; ?>

	<?php if (!isJoomla4()) : ?>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#first-tab" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_PAYMENT_GENERAL');?></a></li>   
			<li><a href="#image" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_IMAGE');?></a></li>
		</ul>
	<?php endif; ?>

	<div id="editdata-document" class="tab-content">

		<?php if (isJoomla4()) {
			echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'first-tab', 'recall' => true, 'breakpoint' => 768]); 
			echo HTMLHelper::_('uitab.addTab', 'myTab', 'first-tab', Text::_('COM_SMARTSHOP_PAYMENT_GENERAL'));
		} ?>
			<div id="first-tab" class="tab-pane active">
				<div class="jshops_edit shippingsprices_edit">
					<div class="form-group row align-items-center">
						<label for="published" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
							<?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="checkbox" id="published" class="form-check-input" name="published" value="1" <?php if ($row->published) echo 'checked="checked"'?> />
						</div>
					</div>  
					<?php 
					foreach($this->languages as $lang){
					$field="name_".$lang->language;
					?>
					<div class="form-group row align-items-center">
						<label for="<?php print $field?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
							<?php echo JText::_('COM_SMARTSHOP_TITLE');?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="text" class="inputbox form-control" id="<?php print $field?>" name="<?php print $field?>" value="<?php echo $row->$field;?>" />
						</div>
					</div>  
					<?php }?>
					<div class="form-group row align-items-center">
						<label for="alias" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
					<?php echo JText::_('COM_SMARTSHOP_ALIAS');?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="text" class="inputbox form-control" id="alias" name="alias" value="<?php echo $row->alias?>" <?php if ($this->config->shop_mode==0 && $row->shipping_id){?>readonly <?php }?> />
						</div>
					</div>  
					<div class="form-group row align-items-center">
						<label for="listpayments" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
							<?php echo JText::_('COM_SMARTSHOP_PAYMENTS');?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php print $this->lists['payments']?>
						</div>
					</div>  
					<div class="form-group row align-items-center">
						<label for="usergroup_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
						<?php echo JText::_('COM_SMARTSHOP_USERGROUPS');?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php         
						print JHTML::_('select.genericlist', $this->usergroups_list, 'usergroup_id[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'usergroup_id', 'usergroup_name',explode(',',$row->usergroup_id));
						?>
						</div>
					</div>  
					<?php print $this->tmp_html_after_image ?? ''?>
					<?php 
					foreach($this->languages as $lang){
					$field="description_".$lang->language;
					?>
					<div class="form-group row align-items-center">
						<label for="description<?php print $lang->id ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
							<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<?php
								$editor = \JEditor::getInstance(\JFactory::getConfig()->get('editor'));
								print $editor->display('description'.$lang->id,  $row->$field , '100%', '350', '75', '20' ) ;
							?>
						</div>
					</div>  
					<?php }?>
					<?php $pkey="etemplatevar";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
				</div>
				<div class="jshops_edit shippingsprices_countries">
					<div class="form-group row align-items-center">
						<label for="shipping_countries_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
							<?php echo  JText::_('COM_SMARTSHOP_COUNTRY')."*"."<br/><br/><span style='font-weight:normal'>". JText::_('COM_SMARTSHOP_MULTISELECT_INFO')."</span>"; ?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<?php echo $lists['countries'];?>
						</div>
					</div>

					<?php if (!empty($this->states)) { ?>
						<div class="form-group row align-items-center states_list">
							<label for="delivery_times_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
								<?php echo  JText::_('COM_SMARTSHOP_STATE');?>
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo $lists['states'];?>
							</div>
						</div>  
					<?php }?>

					<?php if ($jshopConfig->admin_show_delivery_time) { ?>
						<div class="form-group row align-items-center">
							<label for="delivery_times_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
								<?php echo  JText::_('COM_SMARTSHOP_DELIVERY_TIME');?>
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo $lists['deliverytimes'];?>
							</div>
						</div>
					<?php }?>

					<div class="form-group row align-items-center">
						<label for="shipping_stand_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
							<?php echo  JText::_('COM_SMARTSHOP_PRICE')?>*
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<div class="input-group">
								<input type = "text" class = "inputbox form-control" id = "shipping_stand_price" name = "shipping_stand_price" value = "<?php echo $row->shipping_stand_price?>" />
								<?php echo $this->currency->currency_code; ?>
							</div>
						</div>
					</div>  
					<?php if ($this->config->tax){?>
						<div class="form-group row align-items-center">
							<label for="shipping_tax_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
								<?php echo  JText::_('COM_SMARTSHOP_TAX')?>*
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo $lists['taxes']?>
							</div>
						</div>  
					<?php }?>
				
					<div class="form-group row align-items-center">
						<label for="package_stand_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
							<?php echo  JText::_('COM_SMARTSHOP_PACKAGE_PRICE')?>*
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<div class="input-group">
								<input type = "text" class = "inputbox form-control" name = "package_stand_price" id = "package_stand_price" value = "<?php echo $row->package_stand_price?>" />
								<?php echo $this->currency->currency_code; ?>
							</div>
						</div>
					</div>  
					<?php if ($this->config->tax){?>
						<div class="form-group row align-items-center">
							<label for="package_tax_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
								<?php echo  JText::_('COM_SMARTSHOP_PACKAGE_TAX')?>*
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo $lists['package_taxes']?>
							</div>
						</div>  
					<?php }?>

					<?php foreach($this->extensions as $extension){
						$extension->exec->showShippingPriceForm($row->getParams(), $extension, $this);
					}
					?>

					<?php $pkey="etemplatevar";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
				</div>
			</div>
		<?php if (isJoomla4()) {
			echo HTMLHelper::_('uitab.endTab');
			echo HTMLHelper::_('uitab.addTab', 'myTab', 'image', Text::_('COM_SMARTSHOP_IMAGE'));
		} ?> 
			<div id="image" class="tab-pane">
				<div class="jshops_edit shippingsprices_image">
					<div class="form-group row align-items-center">
						<label for="btn_old_image" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
							<?php echo JText::_('COM_SMARTSHOP_IMAGE_SELECT');?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<?php echo LayoutHelper::render('fields.media', [
								'name' => 'image',
								'id' => 'img',
								'folder' => 'img_shippings',
								'type' => 'smartshopimgs',
								'preview' => 'tooltip',
								'value' => $row->image
							]); ?>                                                   
						</div>
					</div>  
					<?php $pkey = 'plugin_template_img_'.$lang->language; if (isset($this->$pkey) && $this->$pkey){ print $this->$pkey;}?>
				</div>
			</div>
		<?php if (isJoomla4()) {
			echo HTMLHelper::_('uitab.endTab');
			echo HTMLHelper::_('uitab.endTabSet');
		} ?>
	</div>
	<input type="hidden" id="shippings_width_image" name="shippings_width_image" value="<?php echo $jshopConfig->image_shippings_width?>" disabled="disabled" />
	<input type="hidden" id="shippings_height_image" name="shippings_height_image" value="<?php echo $jshopConfig->image_shippings_height?>" disabled="disabled" />           
	<input type="hidden" name="sh_pr_method_id" value="<?php echo $row->sh_pr_method_id?>" />
	<input type="hidden" name="task" value="" />
	<?php print $this->tmp_html_end ?? ''?>
</form>
<div class="hidden">
<?php print $lists['conditions']; ?>
</div>