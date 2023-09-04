<?php 
    defined('_JEXEC') or die('Restricted access');
	Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT_SITE . '/helpers/html/');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
	JHtmlBootstrap::modal('a.modal');
    displaySubmenuOptions("", $this->access);
?>
	<?php echo HTMLHelper::_('smartshopmodal.renderButton', 'hidden btn', 'bcalendar-modal', '', JText::_('COM_SMARTSHOP_LOAD')); ?>
	<?php echo HTMLHelper::_('smartshopmodal.renderWindow', 'bcalendar-modal', '', '<iframe src="index.php?option=com_jshopping&controller=production_calendar&task=modal&tmpl=modal" id="bcalendar-modal" frameborder="0" width="700" height="240"></iframe>'); ?>
 
<form action="index.php?option=com_jshopping&controller=production_calendar" method="post"name="adminForm" id="adminForm" >
    
	<div id="calendar"></div>
    
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="working_days" value="" />
    <input type="hidden" name="extra_weekend_days" value="" />
    <input type="hidden" name="extra_working_days" value="" />
</form>