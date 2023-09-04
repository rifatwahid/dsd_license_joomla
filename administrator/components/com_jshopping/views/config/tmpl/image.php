<?php 
/**
* @version      4.9.0 05.11.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die();
$jshopConfig = JSFactory::getConfig();
displaySubmenuConfigs('image',$this->canDo);
?>
<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start ?? ''?>
<input type="hidden" name="task" value="">
<input type="hidden" name="tab" value="3">

    <legend><?php echo  JText::_('COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS') ?></legend>
	<div class="striped-block jshops_edit config_image_tmpl">
		<div class="form-group row align-items-center">
			<label for="video_allowed" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_VIDEO_PRODUCT_ALLOWED');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="video_allowed" id="video_allowed" value ="<?php echo $jshopConfig->video_allowed?>" />      
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="video_product_width" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_VIDEO_PRODUCT_WIDTH');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="video_product_width" id="video_product_width" value ="<?php echo $jshopConfig->video_product_width?>" />      
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="mainCurrency" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_VIDEO_PRODUCT_HEIGHT'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="video_product_height" value ="<?php echo $jshopConfig->video_product_height?>" />
			</div>
		</div>
  
		<div class="form-group row align-items-center">
			<label for="image_resize_type" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_IMAGE_RESIZE_TYPE'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php print $this->select_resize_type;?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="image_quality" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_OC_IMAGE_QUALITY')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="image_quality" id="image_quality" value ="<?php echo $jshopConfig->image_quality?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="image_fill_color" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_OC_IMAGE_FILL_COLOR')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="image_fill_color" id="image_fill_color" value ="<?php echo $jshopConfig->image_fill_color?>" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="product_file_upload_count" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php $k='product_file_upload_count';?>
				<?php echo  JText::_('COM_SMARTSHOP_OC_'.strtoupper($k)); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php if (in_array($k, $this->other_config_checkbox)){?>
					<input type="hidden" name="<?php print $k?>" value="0">
					<input type="checkbox" name="<?php print $k?>" value="1" <?php if ($jshopConfig->$k==1) print 'checked'?>>
				<?php }elseif (isset($this->other_config_select[$k])){?>
					<?php 
					$option = array();
					foreach($this->other_config_select[$k] as $k2=>$v2){
						$option_name = $v2;
						if (defined(JText::_('COM_SMARTSHOP_OC_'.$k.'_'.$v2))){
							$option_name = constant(JText::_('COM_SMARTSHOP_OC_'.$k."_".$v2));
						}
						$option[] = JHTML::_('select.option', $k2, $option_name, 'id', 'name');
					}
					print JHTML::_('select.genericlist', $option, $k, 'class = "inputbox form-select"', 'id', 'name', $jshopConfig->$k);
					?>
				<?php }else{?>
					<input type="text" class="form-control" name="<?php print $k?>" value="<?php echo $jshopConfig->$k?>">
				<?php }?>
				
				<?php if (defined(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO"))) echo JHTML::tooltip(constant(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO")));?>	
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="product_image_upload_count" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php $k='product_image_upload_count';?>
				<?php echo  JText::_('COM_SMARTSHOP_OC_'.strtoupper($k)); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php if (in_array($k, $this->other_config_checkbox)){?>
					<input type="hidden" name="<?php print $k?>" value="0">
					<input type="checkbox" name="<?php print $k?>" value="1" <?php if ($jshopConfig->$k==1) print 'checked'?>>
				<?php }elseif (isset($this->other_config_select[$k])){?>
					<?php 
					$option = array();
					foreach($this->other_config_select[$k] as $k2=>$v2){
						$option_name = $v2;
						if (defined(JText::_('COM_SMARTSHOP_OC_'.$k.'_'.$v2))){
							$option_name = constant(JText::_('COM_SMARTSHOP_OC_'.$k."_".$v2));
						}
						$option[] = JHTML::_('select.option', $k2, $option_name, 'id', 'name');
					}
					print JHTML::_('select.genericlist', $option, $k, 'class = "inputbox form-select"', 'id', 'name', $jshopConfig->$k);
					?>
				<?php }else{?>
					<input type="text"  class="form-control" name="<?php print $k?>" value="<?php echo $jshopConfig->$k?>">
				<?php }?>
				
				<?php if (defined(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO"))) echo JHTML::tooltip(constant(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO")));?>	
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="product_video_upload_count" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php $k='product_video_upload_count';?>
				<?php echo  JText::_('COM_SMARTSHOP_OC_'.strtoupper($k)); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php if (in_array($k, $this->other_config_checkbox)){?>
					<input type="hidden" name="<?php print $k?>" value="0">
					<input type="checkbox" name="<?php print $k?>" value="1" <?php if ($jshopConfig->$k==1) print 'checked'?>>
				<?php }elseif (isset($this->other_config_select[$k])){?>
					<?php 
					$option = array();
					foreach($this->other_config_select[$k] as $k2=>$v2){
						$option_name = $v2;
						if (defined(JText::_('COM_SMARTSHOP_OC_'.$k.'_'.$v2))){
							$option_name = constant(JText::_('COM_SMARTSHOP_OC_'.$k."_".$v2));
						}
						$option[] = JHTML::_('select.option', $k2, $option_name, 'id', 'name');
					}
					print JHTML::_('select.genericlist', $option, $k, 'class = "inputbox form-select"', 'id', 'name', $jshopConfig->$k);
					?>
				<?php }else{?>
					<input type="text" class="form-control" name="<?php print $k?>" value="<?php echo $jshopConfig->$k?>">
				<?php }?>
				
				<?php if (defined(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO"))) echo JHTML::tooltip(constant(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO")));?>	
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="max_number_download_sale_file" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php $k='max_number_download_sale_file';?>
				<?php echo  JText::_('COM_SMARTSHOP_OC_'.strtoupper($k)); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php if (in_array($k, $this->other_config_checkbox)){?>
				<input type="hidden" name="<?php print $k?>" value="0">
				<input type="checkbox" name="<?php print $k?>" value="1" <?php if ($jshopConfig->$k==1) print 'checked'?>>
				<?php }elseif (isset($this->other_config_select[$k])){?>
					<?php 
					$option = array();
					foreach($this->other_config_select[$k] as $k2=>$v2){
						$option_name = $v2;
						if (defined(JText::_('COM_SMARTSHOP_OC_'.$k.'_'.$v2))){
							$option_name = constant(JText::_('COM_SMARTSHOP_OC_'.$k."_".$v2));
						}
						$option[] = JHTML::_('select.option', $k2, $option_name, 'id', 'name');
					}
					print JHTML::_('select.genericlist', $option, $k, 'class = "inputbox form-select"', 'id', 'name', $jshopConfig->$k);
					?>
				<?php }else{?>
					<input type="text"  class="form-control" name="<?php print $k?>" value="<?php echo $jshopConfig->$k?>">
				<?php }?>
	
				<?php if (defined(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO"))) echo JHTML::tooltip(constant(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO")));?>	
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="max_day_download_sale_file" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php $k='max_day_download_sale_file';?>
				<?php echo  JText::_('COM_SMARTSHOP_OC_'.strtoupper($k)); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php if (in_array($k, $this->other_config_checkbox)){?>
					<input type="hidden" name="<?php print $k?>" value="0">
					<input type="checkbox" name="<?php print $k?>" value="1" <?php if ($jshopConfig->$k==1) print 'checked'?>>
				<?php }elseif (isset($this->other_config_select[$k])){?>
					<?php 
					$option = array();
					foreach($this->other_config_select[$k] as $k2=>$v2){
						$option_name = $v2;
						if (defined(JText::_('COM_SMARTSHOP_OC_'.$k.'_'.$v2))){
							$option_name = constant(JText::_('COM_SMARTSHOP_OC_'.$k."_".$v2));
						}
						$option[] = JHTML::_('select.option', $k2, $option_name, 'id', 'name');
					}
					print JHTML::_('select.genericlist', $option, $k, 'class = "inputbox form-select"', 'id', 'name', $jshopConfig->$k);
					?>
				<?php }else{?>
					<input type="text"  class="form-control" name="<?php print $k?>" value="<?php echo $jshopConfig->$k?>">
				<?php }?>	
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="review_max_uploads" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_REVIEWIMAGE_MAX_UPLOADS'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text"  class="form-control" name="review_max_uploads" id="review_max_uploads" value ="<?php echo $jshopConfig->review_max_uploads?>" />
			</div>
		</div>
		
	
		<div class="form-group row align-items-center">
			<label for="video_autoplay" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php $k='video_autoplay';?>
				<?php echo  JText::_('COM_SMARTSHOP_OC_'.strtoupper($k)); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php if (in_array($k, $this->other_config_checkbox)){?>
					<input type="hidden" name="<?php print $k?>" value="0">
					<input type="checkbox" name="<?php print $k?>" value="1" <?php if ($jshopConfig->$k==1) print 'checked'?>>
				<?php }elseif (isset($this->other_config_select[$k])){?>
					<?php 
					$option = array();
					foreach($this->other_config_select[$k] as $k2=>$v2){
						$option_name = $v2;
						if (defined(JText::_('COM_SMARTSHOP_OC_'.$k.'_'.$v2))){
							$option_name = constant(JText::_('COM_SMARTSHOP_OC_'.$k."_".$v2));
						}
						$option[] = JHTML::_('select.option', $k2, $option_name, 'id', 'name');
					}
					print JHTML::_('select.genericlist', $option, $k, 'class = "inputbox form-select"', 'id', 'name', $jshopConfig->$k);
					?>
				<?php }else{?>
					<input type="text"  class="form-control" name="<?php print $k?>" value="<?php echo $jshopConfig->$k?>">
				<?php }?>	
			</div>
		</div>
		<?php $pkey="etemplatevar";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
	</div>
<div class="clr"></div>
<?php print $this->tmp_html_end ?? ''?>
</form>