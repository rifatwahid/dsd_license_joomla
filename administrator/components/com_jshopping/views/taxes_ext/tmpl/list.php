<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

displaySubmenuOptions("taxes",$this->canDo);
$rows=$this->rows;
$additional_taxes=$this->additional_taxes;
$i=0;
?>
<form action="index.php?option=com_jshopping&controller=exttaxes&back_tax_id=<?php print $this->back_tax_id;?>" method="post" name="adminForm" id="adminForm">
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
    <th scope="col" >
        <?php echo  JText::_('COM_SMARTSHOP_COUNTRY'); ?>
    </th>
    <th scope="col" width="60">
        <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_TAX'), 'ET.tax', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width="100">
        <?php 
        if ($this->config->ext_tax_rule_for==1) 
            echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_USER_WITH_TAX_ID_TAX'), 'ET.firma_tax', $this->filter_order_Dir, $this->filter_order);
        else
            echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_FIRMA_TAX'), 'ET.firma_tax', $this->filter_order_Dir, $this->filter_order);
        ?>
    </th>
	<?php foreach ($additional_taxes as $key=>$value){?>
		<th scope="col" >
			<?php echo  $value->name; ?>
		</th>
	<?php } ?>
    <th scope="col" width="50" class="center">
        <?php echo  JText::_('COM_SMARTSHOP_EDIT'); ?>
    </th>
    <th scope="col" width="40" class="center">
        <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_ID'), 'ET.id', $this->filter_order_Dir, $this->filter_order); ?>
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
     <?php echo $row->tax_name;?>
   </td>
   <td>
    <?php echo $row->countries;?>
   </td>
   <td>
    <?php echo $row->tax;?> %
   </td>
   <td>
    <?php echo $row->firma_tax;?> %
   </td>
   <?php foreach ($additional_taxes as $key=>$value){?>
		<td>
		<?php $addtaxname="additional_tax_".$value->id;if ($row->$addtaxname!="") { echo $row->$addtaxname;?> % <?php } ?>
		</td>
   <?php } ?>
   <td class="center">
        <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=exttaxes&task=edit&id=<?php print $row->id?>&back_tax_id=<?php print $this->back_tax_id?>'>
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
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?>
</form>