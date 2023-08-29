<?php
	$freeAttributes = $this->freeAttributes;
	$data_options = $this->data_options;
?>
<form action="index.php?option=com_jshopping&controller=conditions&task=saveConditionsOptions" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
<fieldset>
    <div class="padding--5px">
		<div class = "facp_free_attr_def width--280px" id="facp_free_attr_column_1">
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH'); ?> <span class = "var_descr">($width)</span></div>
                <div class = "facp_row_input"><?php echo JHTML::_('select.genericlist', $freeAttributes, 'width_id', 'class = "facp_input form-select" size = "1"', 'id', 'name', $data_options->width_id); ?></div>
            </div>
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT'); ?> <span class = "var_descr">($height)</span></div>
                <div class = "facp_row_input"><?php echo JHTML::_('select.genericlist', $freeAttributes, 'height_id', 'class = "facp_input form-select" size = "1"', 'id', 'name', $data_options->height_id); ?></div>
            </div>
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH'); ?> <span class = "var_descr">($depth)</span></div>
                <div class = "facp_row_input"><?php echo JHTML::_('select.genericlist', $freeAttributes, 'depth_id', 'class = "facp_input form-select" size = "1"', 'id', 'name', $data_options->depth_id); ?></div>
            </div>
		</div>
	</div>
	
	<input type="hidden" name="task" value="" />
	<div class = "clr margin--top-10px"></div>
</form>