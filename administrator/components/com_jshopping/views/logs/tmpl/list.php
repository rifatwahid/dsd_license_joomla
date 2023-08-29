<?php
/**
* @version      4.7.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/ 
defined('_JEXEC') or die();
displaySubmenuOptions("",$this->canDo);
$rows = $this->rows;
?>
<form action = "index.php?option=com_jshopping&controller=logs" method = "post" name = "adminForm">
<?php print $this->tmp_html_start ?? '' ?>
<div class="table-responsive">
<table class="table table-striped">
<thead>
<tr>
    <th scope="col" class="title" width="10"> # </th>    
    <th scope="col" align = "left"><?php echo JText::_('COM_SMARTSHOP_TITLE');?></th>
    <th scope="col" align = "left"><?php echo JText::_('COM_SMARTSHOP_DATE');?></th>
    <th scope="col" align = "left"><?php echo JText::_('COM_SMARTSHOP_SIZE');?></th>
</tr>
</thead>
<?php $i = 0; foreach($rows as $file){?>
  <tr class = "row<?php echo $i % 2;?>">
   <td>
     <?php echo $i + 1;?>
   </td>
   <td>    
    <a href = "index.php?option=com_jshopping&controller=logs&task=edit&id=<?php echo $file[0];?>"><?php echo $file[0];?></a>
   </td>
   <td><?php print date('Y-m-d H:i:s', $file[1])?></td>
   <td><?php print $file[2]?></td>
  </tr>
<?php
$i++;
}
?>
</table>
</div>
<input type = "hidden" name = "task" value = "" />
<input type = "hidden" name = "hidemainmenu" value = "0" />
<input type = "hidden" name = "boxchecked" value = "0" />
<?php print $this->tmp_html_end ?? '' ?>
</form>