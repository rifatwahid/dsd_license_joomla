<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

displaySubmenuOptions("",$this->canDo);
$rows=$this->rows;
$count=count($rows);
$i=0;
$saveOrder = $this->filter_order_Dir=="asc" && $this->filter_order=="payment_ordering";
?>
<form action="index.php?option=com_jshopping&controller=payments" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
<div class="table-responsive">
<table class="table table-striped" width="70%">
<thead>
  <tr>
    <th scope="col" class="title" width ="10">
      #
    </th>
    <th scope="col" width="20">
	  <input type="checkbox" name="checkall-toggle" class="form-check-input" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th scope="col" align="left">
      <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_TITLE'), 'name', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width="12%" align="left">
      <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_CODE'), 'payment_code', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width="15%" align="left">
      <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ALIAS'), 'payment_class', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <?php echo $this->tmp_extra_column_headers ?? ''?>
    <th scope="col" width = "15%" align = "left">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_SCRIPT_NAME'), 'scriptname', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width="40" colspan="3">
      <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ORDERING'), 'payment_ordering', $this->filter_order_Dir, $this->filter_order); ?>
      <?php if ($saveOrder){?>
      <button onClick="shopHelper.saveorder(<?php echo $count; ?>, 'saveorder', event)" title="Save Order" class="saveorder btn btn-sm btn-secondary float-end float-right"><span class="icon-menu-2"></span></button>
      <?php }?>
    </th>
    <th scope="col" width="50" class="center">
      <?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
    </th>
    <th scope="col" width="50" class="center">
    	<?php print JText::_('COM_SMARTSHOP_EDIT');?>
    </th>
    <th scope="col" width="40" class="center">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ID'), 'payment_id', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
  </tr>
</thead>
<?php foreach($rows as $row){?>
  <tr class="row<?php echo $i % 2;?>">
   <td>
     <?php echo $i+1;?>
   </td>
   <td>     
     <?php echo JHtml::_('grid.id', $i, $row->payment_id);?>
   </td>
   <td>
     <a title="<?php echo JText::_('COM_SMARTSHOP_EDIT_PAYMENT');?>" href="index.php?option=com_jshopping&controller=payments&task=edit&payment_id=<?php echo $row->payment_id; ?>"><?php echo $row->name;?></a>
   </td>
   <td>
     <?php echo $row->payment_code;?>
   </td>
   <td>
     <?php echo $row->payment_class;?>
   </td>
   <?php echo $row->tmp_extra_column_cells ?? ''?>
   <td>
     <?php echo $row->scriptname;?>
   </td>
   <td align="right" width="20">
    <?php
      if ($i!=0 && $saveOrder) echo '<a class="btn btn-micro" href="index.php?option=com_jshopping&controller=payments&task=order&id=' . $row->payment_id . '&order=up&number=' . $row->payment_ordering . '"><i class="icon-uparrow"></i></a>';
    ?>
   </td>
   <td align="left" width="20">
      <?php
        if ($i!=$count-1 && $saveOrder) echo '<a class="btn btn-micro" href="index.php?option=com_jshopping&controller=payments&task=order&id=' . $row->payment_id . '&order=down&number=' . $row->payment_ordering . '"><i class="icon-downarrow"></i></a>';
      ?>
   </td>
   <td align="center" width="10">
    <input type="text" name="order[]" id="ord<?php echo $row->payment_id;?>" value="<?php echo $row->payment_ordering?>" <?php if (!$saveOrder) echo 'disabled'?> class="inputordering" style="text-align: center" />
   </td>
   <td class="center">
     <?php echo JHtml::_('jgrid.published', $row->payment_publish, $i);?>
   </td>
   <td class="center">
        <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=payments&task=edit&payment_id=<?php print $row->payment_id?>'>
            <i class="icon-edit"></i>
        </a>
   </td>
   <td class="center">
        <?php print $row->payment_id;?>
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
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end ?? ''?>
</form>