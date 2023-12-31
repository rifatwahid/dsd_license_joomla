<?php
/**
* @version      4.7.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

displaySubmenuOptions("",$this->canDo);
$shippings=$this->rows;
$i=0;
$saveOrder = $this->filter_order_Dir=="asc" && $this->filter_order=="ordering";
?>
<form action="index.php?option=com_jshopping&controller=shippings" method="post" name="adminForm" id="adminForm">
<?php if (isset($this->tmp_html_start)) print $this->tmp_html_start?>
<div class="table-responsive">
<table class="table table-striped">
<thead>
  <tr>
    <th scope="col" class="title" width ="10">#</th>
    <th width="20">
      <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th scope="col" align="left">
      <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_TITLE'), 'name', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <?php echo $this->tmp_extra_column_headers?>
    <th scope="col" width="200">
        <?php echo JText::_('COM_SMARTSHOP_SHIPPING_PRICES'); ?>    
    </th>
    <th scope="col" width="100">
      <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ORDERING'), 'ordering', $this->filter_order_Dir, $this->filter_order); ?>
      <?php if ($saveOrder){?>
      <button onClick="shopHelper.saveorder(<?php echo count($shippings); ?>, 'saveorder', event)" title="Save Order" class="saveorder btn btn-sm btn-secondary float-end float-right"><span class="icon-menu-2"></span></button>
      <?php }?>
    </th>
    <th scope="col" width="30" class="center">
      <?php echo JText::_('COM_SMARTSHOP_PUBLISH'); ?>
    </th>
    <th scope="col" width="50" class="center">
        <?php echo JText::_('COM_SMARTSHOP_EDIT'); ?>
    </th>
    <th scope="col" width="40" class="center">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ID'), 'shipping_id', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
  </tr>
</thead>  
<?php
$count=count($shippings);
foreach($shippings as $i=>$shipping){
?>
<tr class="row<?php echo $i % 2;?>">
   <td>
     <?php echo $i+1;?>
   </td>
   <td>
    <?php echo JHtml::_('grid.id', $i, $shipping->shipping_id);?>
   </td>
   <td>
     <a href="index.php?option=com_jshopping&controller=shippings&task=edit&shipping_id=<?php echo $shipping->shipping_id; ?>">
         <?php if ($shipping->count_shipping_price==0){?>
            <i class="fas fa-minus-circle"></i>&nbsp;
        <?php }?>
        <?php echo $shipping->name;?>             
     </a>
   </td>
   <?php echo $shipping->tmp_extra_column_cells?>
   <td>
    <a href="index.php?option=com_jshopping&controller=shippingsprices&shipping_id_back=<?php print $shipping->shipping_id;?>"><?php echo JText::_('COM_SMARTSHOP_SHIPPING_PRICES')." (".$shipping->count_shipping_price.")"?> <i class="fas fa-long-arrow-alt-right"></i></a>
   </td>
   <td class="order">
    <span><?php if ($i != 0 && $saveOrder) echo JHtml::_('jgrid.orderUp', $i, "orderup");?></span>
    <span><?php if ($i != $count-1 && $saveOrder) echo JHtml::_('jgrid.orderDown', $i, "orderdown");?></span>
    <input type="text" name="order[]" size="5" value="<?php echo $shipping->ordering;?>" <?php if (!$saveOrder) echo 'disabled'?> class="inputordering" />
   </td>
   <td class="center">
    <?php echo JHtml::_('jgrid.published', $shipping->published, $i);?>
   </td>
	<td class="center">
        <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=shippings&task=edit&shipping_id=<?php echo $shipping->shipping_id; ?>'>
            <i class="icon-edit"></i>
        </a>
   </td>
   <td class="center">
    <?php print $shipping->shipping_id;?>
   </td>
  </tr>
<?php $i++;} ?>
</table>
</div>

<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?>
</form>