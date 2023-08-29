<?php 
defined('_JEXEC') or die('Restricted access');
$jshopConfig=$this->config;
?>
<form action="index.php?option=com_jshopping&controller=reviews" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
     <div class="jshops_edit striped-block comments_config">
		<div class="form-group row align-items-center">
			<label for="allow_reviews_prod" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo  JText::_('COM_SMARTSHOP_ALLOW_REVIEW_PRODUCT');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" id="allow_reviews_prod" name="allow_reviews_prod" value="1" <?php if ($jshopConfig->allow_reviews_prod) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="allow_reviews_uploads" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo  JText::_('COM_SMARTSHOP_ALLOW_REVIEW_UPLOADS');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" id="allow_reviews_uploads" name="allow_reviews_uploads" value="1" <?php if ($jshopConfig->allow_reviews_uploads) echo 'checked="checked"';?> />
			</div>
		</div>		
		<div class="form-group row align-items-center">
			<label for="allow_reviews_only_registered" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo  JText::_('COM_SMARTSHOP_ALLOW_REVIEW_ONLY_REGISTERED');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" id="allow_reviews_only_registered" name="allow_reviews_only_registered" value="1" <?php if ($jshopConfig->allow_reviews_only_registered) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="allow_reviews_only_buyers" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_ALLOW_REVIEW_ONLY_BUYERS');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" id="allow_reviews_only_buyers" name="allow_reviews_only_buyers" value="1" <?php if ($jshopConfig->allow_reviews_only_buyers) echo 'checked="checked"';?> />
			</div>
		</div>		
		<div class="form-group row align-items-center">
			<label for="display_reviews_without_confirm" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_DISPLAY_REVIEW_WITHOUT_CONFIRM');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="hidden" name="s_reviews_without_confirm" value="0">
				<input type="checkbox" class="form-check-input" id="display_reviews_without_confirm" name="display_reviews_without_confirm" value="1" <?php if ($jshopConfig->display_reviews_without_confirm) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="max_mark" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_REVIEW_MAX_MARK');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="form-control" name="max_mark" id="max_mark" value="<?php echo $jshopConfig->max_mark?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="rating_starparts" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_JSHOPPING_CONFIGURATION_RATING_STARPARTS'); ?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php print $this->select ?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="sendmail_reviews_admin_email" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<b><?php echo  JText::_('COM_SMARTSHOP_DISPLAY_REVIEWS_EMAIL_TO_ADMIN');?></b>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" id="sendmail_reviews_admin_email" name="sendmail_reviews_admin_email" value="1" <?php if ($jshopConfig->sendmail_reviews_admin_email) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="sendmail_reviews_admin_email_all_reviews" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_DISPLAY_REVIEWS_EMAIL_TO_ADMIN_ALL_REVIEWS');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" id="sendmail_reviews_admin_email_all_reviews" name="sendmail_reviews_admin_email_all_reviews" value="1" <?php if ($jshopConfig->sendmail_reviews_admin_email_all_reviews) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="sendmail_reviews_admin_email_require_confirmation" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_DISPLAY_REVIEWS_EMAIL_TO_ADMIN_REVIEWS_THAT_REQUIRE_CONFIRMATION');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" id="sendmail_reviews_admin_email_require_confirmation" name="sendmail_reviews_admin_email_require_confirmation" value="1" <?php if ($jshopConfig->sendmail_reviews_admin_email_require_confirmation) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="sendmail_reviews_admin_email_from_guests1" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_DISPLAY_REVIEWS_EMAIL_TO_ADMIN_REVIEWS_FROM_GUESTS');?>
			</label>
			<div id="sendmail_reviews_admin_email_from_guests" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" id="sendmail_reviews_admin_email_from_guests1" name="sendmail_reviews_admin_email_from_guests" value="1" <?php if ($jshopConfig->sendmail_reviews_admin_email_from_guests) echo 'checked="checked"';?> />
			</div>
		</div>
     </div>
     <input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task', 0)?>" />
</form>