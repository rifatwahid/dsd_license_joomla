<?php 
defined('_JEXEC') or die('Restricted access');
$jshopConfig = JSFactory::getConfig();
$lists = $this->lists;
displaySubmenuConfigs('email_hub',$this->canDo);
displaySubSubmenuConfigs('email_hub');
?>
<form action="index.php?option=com_jshopping&controller=deliverytimes" method="post" name="adminForm" id="adminForm">
<?php if (isset($this->tmp_html_start)) print $this->tmp_html_start?>
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
<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?>
</form>