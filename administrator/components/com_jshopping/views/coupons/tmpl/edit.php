<?php 
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php
	$row=$this->coupon;
	$lists=$this->lists;
	$edit=$this->edit;
	if (method_exists('JHtmlBehavior', 'calendar')) {
		JHtmlBehavior::calendar();
	}       
?>
<fieldset class="adminform">
<form action="index.php?option=com_jshopping&controller=coupons" method="post"name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? '' ?>
<div class="jshops_edit coupons_edit">
	<div class="form-group row align-items-center">
		<label for="coupon_publish" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="checkbox" id="coupon_publish" class="form-check-input" name="coupon_publish" value="1" <?php if ($row->coupon_publish) echo 'checked="checked"'?> />
		</div>
	</div>     
   <div class="form-group row align-items-center">
		<label for="coupon_code" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_CODE'); ?>*
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="coupon_code" name="coupon_code" value="<?php echo $row->coupon_code;?>" />
		</div>
	</div>     
   <div class="form-group row align-items-center">
		<label for="coupon_type" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_TYPE_COUPON');?>*
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<?php echo $lists['coupon_type']; ?>
		</div>
	</div>     
   <div class="form-group row align-items-center">
		<label for="coupon_value" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_VALUE'); ?>*
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<div class="input-group">
				<input type="text" class="inputbox form-control" id="coupon_value" name="coupon_value" value="<?php echo $row->coupon_value;?>" />
		   		<span id="ctype_percent" <?php if ($row->coupon_type==1) {?>style="display:none"<?php }?>>%</span>
		   		<span id="ctype_value" <?php if ($row->coupon_type==0) {?>style="display:none"<?php }?>><?php print $this->currency_code?></span>
			</div>
		</div>
	</div>     
   <div class="form-group row align-items-center">
		<label for="coupon_start_date" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_START_DATE_COUPON');?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<div class="coupon-calendar"><?php echo JHTML::_('calendar', $row->coupon_start_date, 'coupon_start_date', 'coupon_start_date', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19')); ?></div>
		</div>
	</div>     
   <div class="form-group row align-items-center">
		<label for="coupon_expire_date" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_EXPIRE_DATE_COUPON');?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			   <div class="coupon-calendar"><?php echo JHTML::_('calendar', $row->coupon_expire_date, 'coupon_expire_date', 'coupon_expire_date', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19')); ?></div>
		</div>
	</div>  
   <div class="form-group row align-items-center forUserIdRow">
		<label for="for_user_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_FOR_USER_ID');?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="for_user_id" name="for_user_id" value="<?php echo $row->for_user_id;?>" /> 
		</div>
	</div>     
   <div class="form-group row align-items-center">
		<label for="finished_after_used" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
		   <?php echo JText::_('COM_SMARTSHOP_FINISHED_AFTER_USED');?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="checkbox" id="finished_after_used" class="form-check-input" name="finished_after_used" value="1" <?php if ($row->finished_after_used) echo 'checked="true"'?> />
		</div>
	</div>     
   <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
 </div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="edit" value="<?php echo $edit;?>" />
<input type="hidden" name="count_use" value="<?php echo $row->count_use;?>" />
<?php if ($edit) {?>
  <input type="hidden" name="coupon_id" value="<?php echo $row->coupon_id?>" />
<?php }?>
<?php print $this->tmp_html_end ?? ''?>
</form>