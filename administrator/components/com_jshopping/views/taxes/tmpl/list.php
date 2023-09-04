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
$rows = $this->rows;
$i = 0;
?>
<form action="index.php?option=com_jshopping&controller=taxes" method="post" name="adminForm" id="adminForm">

<?php print $this->tmp_html_start ?? ''?>

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
      <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_TITLE'), 'tax_name', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width="150">
        <?php echo  JText::_('COM_SMARTSHOP_EXTENDED_RULE_TAX'); ?>
    </th>
    <th scope="col" width="50" class="center">
        <?php echo  JText::_('COM_SMARTSHOP_EDIT'); ?>
    </th>
    <th scope="col" width="40" class="center">
        <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_ID'), 'tax_id', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
  </tr>
</thead>  
<?php foreach($rows as $row){ ?>
  <tr class="row<?php echo $i % 2;?>">
   <td>
     <?php echo $i+1;?>
   </td>
   <td>
     <?php echo JHtml::_('grid.id', $i, $row->tax_id);?>
   </td>
   <td>
     <a href="index.php?option=com_jshopping&controller=taxes&task=edit&tax_id=<?php echo $row->tax_id; ?>"><?php echo $row->tax_name;?></a> (<?php echo $row->tax_value;?> %)
   </td>
   <td>
    <a class="btn btn-mini" href="index.php?option=com_jshopping&controller=exttaxes&back_tax_id=<?php echo $row->tax_id; ?>">
        <?php echo  JText::_('COM_SMARTSHOP_EXTENDED_RULE_TAX'); ?>
    </a>
   </td>
   <td class="center">
        <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=taxes&task=edit&tax_id=<?php echo $row->tax_id;?>'>
            <i class="icon-edit"></i>
        </a>
   </td>
   <td class="center">
        <?php print $row->tax_id;?>
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
<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task')?>" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end ?? ''?>
</form>