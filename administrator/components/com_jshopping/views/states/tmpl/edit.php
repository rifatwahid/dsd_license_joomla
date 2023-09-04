<?php
$row = $this->state;
$lists = $this->lists;
$edit = $this->edit;
$countries_list = $this->countries_list; 

?>
<div class="jshop_edit states_edit">
    <form action = "index.php?option=com_jshopping&controller=states" method = "post" name = "adminForm" id="adminForm">

    <div class="col100">
        <fieldset class="adminform">
            <table class="admintable" width = "100%" >
                <tr>
                    <td class="key" width = "30%">
                        <?php echo JText::_('COM_SMARTSHOP_PUBLISH'); ?>
                    </td>
                    <td>
                        <input type = "checkbox" class="form-check-input" name = "state_publish" value = "1" <?php if ($row->state_publish) echo 'checked = "checked"'?> />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <?php echo JText::_('COM_SMARTSHOP_LIST_COUNTRY');  ?>
                    </td>
                    <td id = "countries_list">
                        <?php echo $countries_list?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <?php echo JText::_('COM_SMARTSHOP_ORDERING'); ?>
                    </td>
                    <td id = "ordering">
                        <?php echo $lists['order_states']?>
                    </td>
                </tr>
                <?php 
                    foreach($this->languages as $lang) :
                        $field = "name_".$lang->language;
                ?>
                <tr>
                    <td class="key">
                        <?php echo JText::_('COM_SMARTSHOP_TITLE'); ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
                    </td>
                    <td>
                        <input type = "text" class = "inputbox form-control" id = "name_<?php print $lang->language;?>" name = "name_<?php print $lang->language;?>" value = "<?php echo $row->$field;?>" />
                    </td>
                </tr>
                <?php endforeach ?>
                <?php $pkey = "etemplatevar";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
            </table>
        </fieldset>
    </div>

    <div class="clr"></div>

    <input type = "hidden" name = "task" value = "<?php echo JFactory::getApplication()->input->getVar('task')?>" />
    <input type = "hidden" name = "edit" value = "<?php echo $edit;?>" />
    <?php if ($edit)  : ?>
        <input type = "hidden" name = "state_id" value = "<?php echo $row->state_id?>" />
    <?php endif ?>

    </form>
</div>