<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

displaySubmenuOptions("productfields",$this->canDo);
$rows=$this->rows; 
$count=count($rows);
$i=0; 
?>
<form action="index.php?option=com_jshopping&controller=productfieldgroups" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? '' ?>
<div class="table-responsive">
<table class="table table-striped">
<thead>
  <tr>
    <th scope="col" class="title" width="10">
      #
    </th>
    <th scope="col" width="20">
	  <input type="checkbox" class="form-check-input" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th scope="col" align="left">
      <?php echo JText::_('COM_SMARTSHOP_TITLE');?>
    </th>
    <th scope="col" colspan="3" width="40">
      <?php echo JText::_('COM_SMARTSHOP_ORDERING');?>      
      <button onClick="shopHelper.saveorder(<?php echo count($rows); ?>, 'saveorder', event)" title="Save Order" class="saveorder btn btn-sm btn-secondary float-end"><span class="icon-sort"></span></button>
    </th>
    <th scope="col" width="50" class="center">
        <?php echo JText::_('COM_SMARTSHOP_EDIT');?>
    </th>
    <th scope="col" width="40" class="center">
        <?php echo JText::_('COM_SMARTSHOP_ID');?>
    </th>
  </tr>
</thead>
<?php foreach($rows as $row){?>
  <tr class="row<?php echo $i % 2;?>">
   <td>
     <?php echo $i + 1;?>
   </td>
   <td>
     <?php echo JHtml::_('grid.id', $i, $row->id);?>
   </td>
   <td>
     <a href="index.php?option=com_jshopping&controller=productfieldgroups&task=edit&id=<?php echo $row->id; ?>"><?php echo $row->name;?></a>
   </td>
   <td align="right" width="20">
    <?php
        if ($i != 0) echo '<a class="btn btn-micro" href="index.php?option=com_jshopping&controller=productfieldgroups&task=order&id='.$row->id.'&move=-1"><i class="icon-uparrow"></i></a>';
    ?>
   </td>
   <td align="left" width="20">
    <?php
        if ($i != $count - 1) echo '<a class="btn btn-micro" href="index.php?option=com_jshopping&controller=productfieldgroups&task=order&id='.$row->id.'&move=1"><i class="icon-downarrow"></i></a>';
    ?>
   </td>
   <td align="center" width="10">
    <input type="text" name="order[]" id="ord<?php echo $row->id;?>"  size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?? '' ?> class="inputordering" style="text-align: center" />    
   </td>
   <td class="center">
        <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=productfieldgroups&task=edit&id=<?php print $row->id;?>'>
            <i class="icon-edit"></i>
        </a>
   </td>
   <td class="center">
    <?php print $row->id;?>
   </td>
  </tr>
<?php
$i++;
}
?>
</table>
</div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end ?? ''?>
</form>