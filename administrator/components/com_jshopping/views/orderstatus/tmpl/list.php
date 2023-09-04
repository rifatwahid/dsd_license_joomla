<?php 
/**
* @version      4.7.1 22.10.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php

displaySubmenuOptions("",$this->canDo);
$rows=$this->rows;
$i=0;
?>
<form action="index.php?option=com_jshopping&controller=orderstatus" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
<div class="table-responsive">
<table class="table table-striped">
<thead>
  <tr>
    <th scope="col" class="title" width ="10">
      #
    </th>
    <th scope="col" width="20">
	  <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th scope="col" width="200" align="left">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_TITLE'), 'name', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width="200">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_CODE'), 'status_code', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" >
        <?php echo JText::_('COM_SMARTSHOP_COLOR'); ?>
    </th>
    <th scope="col" width="50" class="center">
        <?php echo JText::_('COM_SMARTSHOP_EDIT');?>
    </th>
    <th scope="col" width="40" class="center">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ID'), 'status_id', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
  </tr>
</thead>  
<?php foreach($rows as $row){ ?>
  <tr class="row<?php echo $i % 2;?>">
   <td>
     <?php echo $i+1;?>
   </td>
   <td>     
     <?php echo JHtml::_('grid.id', $i, $row->status_id);?>
   </td>
   <td>
     <a title="<?php echo JText::_('COM_SMARTSHOP_EDIT_ORDER_STATUS');?>" href="index.php?option=com_jshopping&controller=orderstatus&task=edit&status_id=<?php echo $row->status_id; ?>"><?php echo $row->name;?></a>
   </td>
   <td>
     <?php echo $row->status_code;?>
   </td>
   <td>
    <div style="width: 20px; height: 20px; background: <?php echo $row->color;?>;"></div>
   </td>
	<td class="center">
   	    <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=orderstatus&task=edit&status_id=<?php echo $row->status_id; ?>'>
            <i class="icon-edit"></i>
        </a>
   	</td>
    <td class="center">
        <?php print $row->status_id;;?>
    </td>
  </tr>
<?php
$i++;
}
?>
</table>
</div>

<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />
<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task', 0)?>" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end ?? ''?>
</form>