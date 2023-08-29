<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

displaySubmenuOptions("conditions",$this->canDo);
$shipping_prices=$this->rows;
$i=0;
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_jshopping&controller=conditions" method="post">
<?php print $this->tmp_html_start ?? ''?>
<div class="table-responsive">
<table class="table table-striped">
<thead>
	<tr class="row<?php echo $i % 2;?>">
    	<th class="title" width ="10">
      		#
    	</th>
		<th width="20">
		  <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
		</th>
    	<th align="left">
      		<?php echo  JText::_('COM_SMARTSHOP_TITLE'); ?>
    	</th>
    	<th width="70" class="center">
	        <?php echo  JText::_('COM_SMARTSHOP_EDIT'); ?>
	    </th>
        <th width="40" class="center">
            <?php echo  JText::_('COM_SMARTSHOP_ID'); ?>
        </th>
  	</tr>
</thead>
<?php foreach($shipping_prices as $row){?>
<tr>
	<td>
		<?php echo $i + 1;?>
	</td>
   <td>
    <?php echo JHtml::_('grid.id', $i, $row->condition_id);?>
   </td>
	<td>
		<a href="index.php?option=com_jshopping&controller=conditions&task=edit&condition_id=<?php echo $row->condition_id?>&condition_id_back=<?php print $this->condition_id_back?>"><?php echo $row->name;?></a>
	</td>
	<td class="center">
        <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=conditions&task=edit&condition_id=<?php echo $row->condition_id?>&shipping_id_back=<?php print $this->condition_id_back?>'>
            <i class="icon-edit"></i>
        </a>
   </td>
   <td class="center">
    <?php print  $row->condition_id;?>
   </td>  
</tr>
<?php $i++;} ?>
</table>
</div>

<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="condition_id_back" value="<?php echo $this->condition_id_back;?>" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end ?? ''?>
</form>