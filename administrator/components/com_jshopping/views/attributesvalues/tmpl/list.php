<?php 
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

displaySubmenuOptions("attributes",$this->canDo);
$rows=$this->rows;
$attr_id=$this->attr_id;
$count=count ($rows);
$i=0;
$saveOrder = $this->filter_order_Dir=="asc" && $this->filter_order=="value_ordering";
?>
<form action="index.php?option=com_jshopping&controller=attributesvalues&attr_id=<?php echo $attr_id?>" method="post" name="adminForm" id="adminForm">
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
      <?php echo JText::_('COM_SMARTSHOP_NAME_ATTRIBUT_VALUE'); ?>
    </th>
    <th scope="col" align="left">
      <?php echo JText::_('COM_SMARTSHOP_IMAGE_ATTRIBUT_VALUE');?>
    </th>
    <th scope="col" colspan="3" width="40">
    	<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ORDERING'), 'value_ordering', $this->filter_order_Dir, $this->filter_order); ?>
        <?php if ($saveOrder){?>
			<button onClick="shopHelper.saveorder(<?php echo ($count-1);?>, 'saveorder', event)" title="Save Order" class="saveorder btn btn-sm btn-secondary float-end float-right"><span class="icon-menu-2"></span></button>
        <?php }?>
    </th>
	<th scope="col" width="50" class="center">
        <?php echo JText::_('COM_SMARTSHOP_EDIT');?>
    </th>
    <th scope="col" width="40" class="center">
        <?php echo JText::_('COM_SMARTSHOP_ID'); ?>
    </th>
  </tr>
</thead>
<?php foreach ($rows as $row){ ?>
  <tr class="row<?php echo $i % 2;?>">
   <td>
     <?php echo $i + 1;?>
   </td>
   <td>     
     <?php echo JHtml::_('grid.id', $i, $row->value_id);?>
   </td>
   <td>
     <a href="index.php?option=com_jshopping&controller=attributesvalues&task=edit&value_id=<?php echo $row->value_id; ?>&attr_id=<?php echo $attr_id?>"><?php echo $row->name;?></a>
   </td>
   <td>
     <?php if ($row->image) {?>
        <img src="<?php echo getPatchProductImage($row->image, '', 1); ?>"  alt="" width="20" height="20" />
     <?php }?>
   </td>
   <td align="right" width="20">
    <?php
      if ($i != 0 && $saveOrder) echo '<a class="btn btn-micro" href="index.php?option=com_jshopping&controller=attributesvalues&task=order&id=' . $row->value_id . '&order=up&number=' . $row->value_ordering . '&attr_id=' . $attr_id . '"><i class="icon-uparrow"></i></a>';
    ?>
   </td>
   <td align="left" width="20">
      <?php
        if ($i != $count - 1 && $saveOrder) echo '<a class="btn btn-micro" href="index.php?option=com_jshopping&controller=attributesvalues&task=order&id=' . $row->value_id . '&order=down&number=' . $row->value_ordering . '&attr_id=' . $attr_id . '"><i class="icon-downarrow"></i></a>';
      ?>
   </td>
   <td align="center" width="10">
    <input type="text" name="order[]" id="ord<?php echo $row->value_id;?>" size="5" value="<?php echo $row->value_ordering?>" <?php if (!$saveOrder) echo 'disabled'?> class="inputordering" style="text-align: center" />
   </td>
   <td class="center">
        <a class="btn btn-micro" href="index.php?option=com_jshopping&controller=attributesvalues&task=edit&value_id=<?php echo $row->value_id; ?>&attr_id=<?php echo $attr_id?>">
            <i class="icon-edit"></i>
        </a>
   </td>
   <td class="center">
    <?php print $row->value_id;?>
   </td>
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
<?php print $this->tmp_html_end ?? '' ?>
</form>