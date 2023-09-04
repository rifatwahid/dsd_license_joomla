<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

displaySubmenuOptions("",$this->canDo);
$rows=$this->rows;
$i=0;
?>
<form action="index.php?option=com_jshopping&controller=units" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? '' ?>
<div class="table-responsive">
<table class="table table-striped">
<thead>
  <tr>
    <th scope="col" class="title" width ="10">
      #
    </th>
    <th scope="col" width="20">
	  <input type="checkbox" class="form-check-input" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th scope="col" align="left">
      <?php echo  JText::_('COM_SMARTSHOP_TITLE');?>
    </th>    
    <th scope="col" width="50" class="center">
    	<?php print  JText::_('COM_SMARTSHOP_EDIT')?>
    </th>
    <th scope="col" width="40" class="center">
        <?php echo  JText::_('COM_SMARTSHOP_ID');?>
    </th>
  </tr>
</thead>
<?php $count=count($rows); foreach($rows as $row){?>
  <tr class="row<?php echo $i % 2;?>">
   <td>
     <?php echo $i+1;?>
   </td>
   <td>     
     <?php echo JHtml::_('grid.id', $i, $row->id);?>
   </td>
   <td>
     <a href="index.php?option=com_jshopping&controller=units&task=edit&id=<?php echo $row->id;?>"><?php echo $row->name;?></a>
   </td>
	<td class="center">
		<a class="btn btn-micro" href='index.php?option=com_jshopping&controller=units&task=edit&id=<?php print $row->id;?>'>
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