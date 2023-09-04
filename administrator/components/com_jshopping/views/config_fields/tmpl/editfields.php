<?php 
/**
* @version      4.9.0 13.08.2013
* @author
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$jshopConfig = JSFactory::getConfig();

$current_fields = $this->current_fields ?? '';

displaySubmenuConfigs('fieldregister', $this->canDo);
?>
<div class="jshop_edit config_fields_editfields">
<form action="index.php?option=com_jshopping&controller=configfields&task=save" method="post"name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
<input type="hidden" name="task" value="">
<input type="hidden" name="id" value="<?php print $this->row->id?>">
<input type="hidden" name="sorting" value="<?php print $this->row->sorting?>">

<div class="col100 main-page">
    <div class="admintable jshops_edit">

        <div class="form-group row align-items-center">
            <label for="name" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
                <?php echo JText::_('COM_SMARTSHOP_NAME'); ?>
            </label>
            <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                <input type="text" readonly name="name" id="name" value="<?php echo $this->row->name; ?>"  />
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label for="name" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
                <?php echo JText::_('COM_SMARTSHOP_DISPLAY'); ?>
            </label>
            <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                <div class="btn-group" role="display" >
                    <input type="radio" class="btn-check d-none" name="display" id="publish0" autocomplete="off" value="0" checked>
                    <label class="btn btn-outline-primary" for="publish0"><?php echo JText::_('COM_SMARTSHOP_NONE'); ?></label>

                    <input type="radio" class="btn-check d-none" name="display" id="publish1" autocomplete="off" value="1" <?php if($this->row->display == 1) echo 'checked'; ?>>
                    <label class="btn btn-outline-primary" for="publish1"><?php echo JText::_('COM_SMARTSHOP_REGISTER'); ?></label>

                    <input type="radio" class="btn-check d-none" name="display" id="publish2" autocomplete="off" value="2" <?php if($this->row->display == 2) echo 'checked'; ?>>
                    <label class="btn btn-outline-primary" for="publish2"><?php echo JText::_('COM_SMARTSHOP_CHECKOUT_ADDRESS'); ?></label>

                    <input type="radio" class="btn-check d-none" name="display" id="publish3" autocomplete="off" value="3" <?php if($this->row->display == 3) echo 'checked'; ?>>
                    <label class="btn btn-outline-primary" for="publish3"><?php echo JText::_('COM_SMARTSHOP_LABEL_BOTH'); ?></label>
                </div>
            </div>
        </div>
        <div class="form-group row align-items-center">
            <label for="name" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
                <?php echo JText::_('COM_SMARTSHOP_REQUIRE'); ?>
            </label>
            <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                <div class="btn-group" role="require" >
                    <input type="radio" class="btn-check d-none" name="require" id="require0" autocomplete="off" value="0" checked>
                    <label class="btn btn-outline-primary" for="require0"><?php echo JText::_('COM_SMARTSHOP_NONE'); ?></label>

                    <input type="radio" class="btn-check d-none" name="require" id="require1" autocomplete="off" value="1" <?php if($this->row->require == 1) echo 'checked'; ?>>
                    <label class="btn btn-outline-primary" for="require1"><?php echo JText::_('COM_SMARTSHOP_REGISTER'); ?></label>

                    <input type="radio" class="btn-check d-none" name="require" id="require2" autocomplete="off" value="2" <?php if($this->row->require == 2) echo 'checked'; ?>>
                    <label class="btn btn-outline-primary" for="require2"><?php echo JText::_('COM_SMARTSHOP_CHECKOUT_ADDRESS'); ?></label>

                    <input type="radio" class="btn-check d-none" name="require" id="require3" autocomplete="off" value="3" <?php if($this->row->require == 3) echo 'checked'; ?>>
                    <label class="btn btn-outline-primary" for="require3"><?php echo JText::_('COM_SMARTSHOP_LABEL_BOTH'); ?></label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clr"></div>
<?php print $this->tmp_html_end ?? ''?>
</form>
</div>