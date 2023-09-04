<?php 
/**
* @version      4.7.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die();
$rows = $this->rows;
?>
<form name="adminForm" method="post" action="index.php?option=com_jshopping&controller=orders">
<?php print $this->tmp_html_start ?? ''?>
<div class="table-responsive">
<table class="table table-striped" width="100%">
<thead>
   <tr>
     <th scope="col" width = "20">#</th>
     <th scope="col" width = "20">
       <?php echo JText::_('COM_SMARTSHOP_TRANSACTION')?>
     </th>
     <th scope="col" >
       <?php echo JText::_('COM_SMARTSHOP_DATE')?>
     </th>
     <th scope="col" >
       <?php echo JText::_('COM_SMARTSHOP_CODE')?>
     </th>
     <th scope="col" >
       <?php echo JText::_('COM_SMARTSHOP_STATUS')?>
     </th>
     <th scope="col" >
       <?php echo JText::_('COM_SMARTSHOP_DESCRIPTION')?>
     </th>
     <th scope="col" width="50"><?php print JText::_('COM_SMARTSHOP_ID')?></th>
   </tr>
</thead>
<?php 
$i = 0; 
foreach($rows as $row){ ?>
   <tr class="row<?php echo ($i  %2);?>" >
     <td>
        <?php echo ($i+1);?>
     </td>
     <td>
        <?php print $row->transaction?> 
     </td>
     <td>
        <?php echo $row->date;?>
     </td>
     <td>
        <?php echo $row->rescode;?>
     </td>
     <td>
        <?php if ($row->status_id) echo $this->list_order_status[$row->status_id];?>
     </td>     
     <td>
        <?php foreach($row->data as $trx_data){?>
         <div class="trx_data_row">
             <?php print $trx_data->key?>: <?php print $trx_data->value?>
         </div>
        <?php }?>
     </td>
     <td>
        <?php print $row->id?> 
     </td>
   </tr>
<?php
$i++;
}?>
</table>
</div>
<input type = "hidden" name = "task" value = "" />
<input type = "hidden" name = "boxchecked" value = "0" />
<?php print $this->tmp_html_end ?? ''?>
</form>