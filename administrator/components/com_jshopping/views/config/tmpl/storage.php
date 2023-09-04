<?php 
/**
* @version      4.9.0 10.02.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$jshopConfig=JSFactory::getConfig();
$lists=$this->lists;
displaySubmenuConfigs('storage',$this->canDo);
?>
<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<input type="hidden" name="task" value="">
	<input type="hidden" name="tab" value="13">
                
	<div class="striped-block jshops_edit storage_tmpl ">
		<div class="form-group row align-items-center">
			<label for="storage_delete_uploads" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_DELETE_UPLOADS');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <?php echo $lists['storage_delete_uploads']; ?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="storage_delete_offers" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_DELETE_OFFERS');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <?php echo $lists['storage_delete_offers']; ?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="storage_delete_deliverynotes" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_DELETE_DELIVERYNOTES');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <?php echo $lists['storage_delete_deliverynotes']; ?>
			</div>
		</div>
		<?php if (!empty($lists['storage_delete_editor_temporary_folder'])) : ?>
			<div class="form-group row align-items-center">
				<label for="storage_delete_editor_temporary_folder" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_DELETE_EDITORTEMPORARYFOLDER');?>
				</label>
				<div class="col-sm-8 col-md-9 col-xl-9 col-12">
					<?php echo $lists['storage_delete_editor_temporary_folder']; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if (!empty($lists['storage_delete_editor_print_files'])) : ?>
			<div class="form-group row align-items-center">
				<label for="storage_delete_editor_print_files" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_DELETE_EDITORPRINTFILES');?>
				</label>
				<div class="col-sm-8 col-md-9 col-xl-9 col-12">
					<?php echo $lists['storage_delete_editor_print_files']; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>        
</form>