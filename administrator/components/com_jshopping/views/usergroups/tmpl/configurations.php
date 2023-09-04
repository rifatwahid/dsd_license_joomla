<?php 
defined('_JEXEC') or die('Restricted access');
$jshopConfig=$this->config;
?>
<form action="index.php?option=com_jshopping&controller=usergroups" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
     <div class="jshops_edit striped-block usergroups_edit">
		<div class="form-group row align-items-center">
			<?php $k='display_user_groups_info';?>
			<label for="<?php print $k?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php print JText::_('COM_SMARTSHOP_OC_DISPLAY_USER_GROUPS_INFO');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php if (in_array($k, $this->other_config_checkbox)){?>
					<input type="hidden" name="<?php print $k?>" value="0">
					<input type="checkbox" class="form-check-input" id="<?php print $k?>" name="<?php print $k?>" value="1" <?php if ($jshopConfig->$k==1) print 'checked'?>>
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
					<input type="text" id="<?php print $k?>" name="<?php print $k?>" value="<?php echo $jshopConfig->$k?>">
				<?php }?>
				
				<?php if (defined(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO"))) echo JHTML::tooltip(constant(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO")));?>	
			</div>
		</div>
		<div class="form-group row align-items-center">
			<?php $k='display_user_group';?>
			<label for="<?php print $k?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php print JText::_('COM_SMARTSHOP_OC_DISPLAY_USER_GROUP');?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php if (in_array($k, $this->other_config_checkbox)){?>
					<input type="hidden" name="<?php print $k?>" value="0">
					<input type="checkbox" class="form-check-input" id="<?php print $k?>" name="<?php print $k?>" value="1" <?php if ($jshopConfig->$k==1) print 'checked'?>>
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
					<input type="text" id="<?php print $k?>" name="<?php print $k?>" value="<?php echo $jshopConfig->$k?>">
				<?php }?>
				
				<?php if (defined(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO"))) echo JHTML::tooltip(constant(JText::_('COM_SMARTSHOP_OC_'.$k."_INFO")));?>	
			</div>
		</div>
     </div>
     <input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task', 0)?>" />
</form>