<?php 
defined('_JEXEC') or die('Restricted access');
$jshopConfig = JSFactory::getConfig();
$lists = $this->lists;
displaySubmenuConfigs('email_hub',$this->canDo);
displaySubSubmenuConfigs('template_creator');
?>
<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm">

<table class="table table-striped">
<thead>
  <tr>
    <th class="title" width ="10">
      #
    </th>
    <th width="20">
	  <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th align="left">
      <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_TITLE'), 'name', $this->filter_order_Dir, $this->filter_order); ?>
    </th>    
    <th width="50" class="center">
    	<?php print JText::_('COM_SMARTSHOP_EDIT')?>
    </th>
    <th width="40" class="center">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ID'), 'id', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
  </tr>
</thead>
</table>
<input type="hidden" name="task" value="" />
</form>