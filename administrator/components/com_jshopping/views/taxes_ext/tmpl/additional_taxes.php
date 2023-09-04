<?php
/**
* @version      5.9.0 22.02.2023
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

if (isset($this->tax)) $row=$this->tax; else $row="";
$rows=$this->lists;
$columns=$this->columns;
$i=0;
?>
<form action="index.php?option=com_jshopping&controller=exttaxes&back_tax_id=<?php print $this->back_tax_id;?>" method="post"name="adminForm" id="adminForm">
	<?php if (isset($this->tmp_html_start)) print $this->tmp_html_start?>
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
		<th scope="col" align="left">
			<?php echo  JText::_('COM_SMARTSHOP_TITLE'); ?>
		</th>
		<th scope="col" width="50" class="center">
			<?php echo  JText::_('COM_SMARTSHOP_EDIT'); ?>
		</th>
		<th scope="col" width="40" class="center">
			<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_ID'), 'ET.id'); ?>
		</th>
		</tr>
	</thead>  
	<?php foreach($rows as $row){?>
	  <tr class="row<?php echo $i % 2;?>">
	   <td>
		 <?php echo $i+1;?>
	   </td>
	   <td>     
		 <?php echo JHtml::_('grid.id', $i, $row->id);?>
	   </td>
	   <td>
		 <?php echo $row->name;?>
	   </td>
	   <td class="center">
			<a class="btn btn-micro" href='index.php?option=com_jshopping&controller=exttaxes&task=add_additional_taxes&id=<?php print $row->id?>&back_tax_id=<?php print $this->back_tax_id?>'>
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
	<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="" />
	<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?>
</form>