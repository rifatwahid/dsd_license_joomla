<?php
/**
* @version      4.9.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$row=$this->shipping; 
$edit=$this->edit; 
$usergroups_list=$this->usergroups_list;
$jshopConfig=JSFactory::getConfig();
?>
<form action="index.php?option=com_jshopping&controller=shippings" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
<?php if (isset($this->tmp_html_start)) print $this->tmp_html_start?>
<ul class="nav nav-tabs">
    <li class="active"><a href="#first-tab" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_PAYMENT_GENERAL');?></a></li>    
	<li><a href="#image" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_IMAGE');?></a></li>
</ul>
<div id="editdata-document" class="tab-content">
	<div id="first-tab" class="tab-pane active">
		<div class="jshops_edit shippins_edit_data">
			<div class="form-group row align-items-center">
				<label for="published" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
					<?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="checkbox" id="published" name="published" value="1" <?php if ($row->published) echo 'checked="checked"'?> />
				</div>
			</div>  
			<?php 
			foreach($this->languages as $lang){
			$field="name_".$lang->language;
			?>
				<div class="form-group row align-items-center">
					<label for="<?php print $field?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
						<?php echo JText::_('COM_SMARTSHOP_TITLE');?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="text" class="inputbox" id="<?php print $field?>" name="<?php print $field?>" value="<?php echo $row->$field;?>" />
					</div>
				</div>  
			<?php }?>
			<div class="form-group row align-items-center">
				<label for="alias" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
					<?php echo JText::_('COM_SMARTSHOP_ALIAS');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="text" class="inputbox" name="alias" id="alias" value="<?php echo $row->alias?>" <?php if ($this->config->shop_mode==0 && $row->shipping_id){?>readonly <?php }?> />
				</div>
			</div>  
			<div class="form-group row align-items-center">
				<label for="listpayments" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
					<?php echo JText::_('COM_SMARTSHOP_PAYMENTS');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				   <?php print $this->lists['payments']?>
				</div>
			</div>  
			<div class="form-group row align-items-center">
				<label for="usergroup_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
				  <?php echo JText::_('COM_SMARTSHOP_USERGROUPS');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php         
					print JHTML::_('select.genericlist', $this->usergroups_list, 'usergroup_id[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'usergroup_id', 'usergroup_name',explode(',',$row->usergroup_id));
					?>
				</div>
			</div>  
			<?php print $this->tmp_html_after_image?>
			<?php 
			foreach($this->languages as $lang){
			$field="description_".$lang->language;
			?>
				<div class="form-group row align-items-center">
					<label for="description<?php print $lang->id ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
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
			<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
		</div>
	</div>

	<div id="image" class="tab-pane">
		 <?php if ($row->image){ ?>
		 <div class="jshop_quote" id="foto_shippings">
			<div>
				<div><img src="<?php echo $jshopConfig->image_shippings_live_path . '/' . $row->image?>" /></div>
				<div class="link_delete_foto">
					<a class="btn btn-primary btn-micro" href="#" onclick="if (confirm('<?php print JText::_('COM_SMARTSHOP_DELETE_IMAGE');?>')) shopImage.delete('<?php echo $row->shipping_id?>', 'shipping');return false;">
						<i class="fas fa-trash-alt"></i> <?php print JText::_('COM_SMARTSHOP_DELETE_IMAGE');?>
					</a>
				</div>
			</div>
		 </div>
		 <?php } ?>
		 <div class="jshops_edit shippings_image_select">
			<div class="form-group row align-items-center">
				<label for="btn_image" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">					  
					<?php echo JText::_('COM_SMARTSHOP_IMAGE_SELECT');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="old_image" value="<?php echo $row->image;?>" />		
					<input class='btn' id='btn_image' type="button" onClick="document.querySelector('#img').click();"  value="<?php print JText::_('COM_SMARTSHOP_SELECT_FILE')?>">			
					<label id="img_label" ><?php print JText::_('COM_SMARTSHOP_NONE_SELECTED')?></label>
					<input size="55" type="file" name="image" id="img" value="" class="product_image"   hidden onchange="document.querySelector('#img_label').innerHTML=this.files[0].name;"/>                               
				</div>
			</div>  
			<div class="form-group row align-items-center">
				<label for="size_1" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
					<?php echo JText::_('COM_SMARTSHOP_IMAGE_THUMB_SIZE');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				   <div>
					   <input type="radio" name="size_im_shippings" id="size_1" checked="checked" onclick="shopImage.setDefaultSize(<?php echo $jshopConfig->image_shippings_width; ?>,<?php echo $jshopConfig->image_shippings_height; ?>, 'shippings')" value="1" />
					   <label for="size_1"><?php echo JText::_('COM_SMARTSHOP_IMAGE_SIZE_1');?></label>
					   <div class="clear"></div>
				   </div>
				   <div>
					   <input type="radio" name="size_im_shippings" value="3" id="size_3" onclick="shopImage.setOriginalSize('shippings')" value="3"/>
					   <label for="size_3"><?php echo JText::_('COM_SMARTSHOP_IMAGE_SIZE_3');?></label>
					   <div class="clear"></div>
				   </div>
				   <div>
					   <input type="radio" name="size_im_shippings" id="size_2" onclick="shopImage.setManualSize('shippings')" value="2" />
					   <label for="size_2"><?php echo JText::_('COM_SMARTSHOP_IMAGE_SIZE_2');?></label> <?php echo JHTML::_('tooltip',  JText::_('COM_SMARTSHOP_IMAGE_SIZE_INFO') );?>
					   <div class="clear"></div>
				   </div>
				</div>
			</div>  
			<div class="form-group row align-items-center">
				<label for="shippings_width_image" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">					  
					<?php echo  JText::_('COM_SMARTSHOP_IMAGE_WIDTH');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="text" id="shippings_width_image" name="shippings_width_image" value="<?php echo $jshopConfig->image_shippings_width?>" disabled="disabled" />
				</div>
			</div>  
			<div class="form-group row align-items-center">
				<label for="shippings_height_image" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">					  
					<?php echo  JText::_('COM_SMARTSHOP_IMAGE_HEIGHT');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="text" id="shippings_height_image" name="shippings_height_image" value="<?php echo $jshopConfig->image_shippings_height?>" disabled="disabled" />           
				</div>
			</div>  
			<?php $pkey = 'plugin_template_img_'.$lang->language; if ($this->$pkey){ print $this->$pkey;}?>
		 </div>
		 <br/><br/>
		 <div class="helpbox">
			<div class="head"><i class="fas fa-info-circle"></i> <?php echo  JText::_('COM_SMARTSHOP_ABOUT_UPLOAD_FILES');?></div>
			<div class="text">
				<?php print  JText::_('COM_SMARTSHOP_IMAGE_UPLOAD_EXT_INFO')?><br/>
				<?php print JText::sprintf( 'COM_SMARTSHOP_SIZE_FILES_INFO', ini_get("upload_max_filesize"), ini_get("post_max_size"));?>
			</div>
		</div>
    </div>

</div>

<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task')?>" />
<input type="hidden" name="edit" value="<?php echo $edit;?>" />
<?php if ($edit) {?>
  <input type="hidden" name="shipping_id" value="<?php echo $row->shipping_id?>" />
<?php }?>
<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?>
</form>