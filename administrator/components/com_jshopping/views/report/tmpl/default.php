<form action = "index.php?option=com_jshopping&controller=report" method = "post">
<table width = "50%">
	<tr>
		<td colspan = "2">
			<?php echo JText::_('COM_SMARTSHOP_REPORT_TEXT')?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('COM_SMARTSHOP_REPORT_NAME')?>
		</td>
		<td>
			<input type = "text" class = "inputbox" name = "name" width = "100px" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('COM_SMARTSHOP_REPORT_PHONE')?>
		</td>
		<td>
			<input type = "text" class = "inputbox" name = "phone" width = "100px" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('COM_SMARTSHOP_REPORT_TEXT_INFO')?>
		</td>
		<td>
			<textarea name = "text" id = "text" cols = "40" rows = "10"></textarea>
		</td>
	</tr>
	<tt>
		<td>
			 
		</td>
		<td>
			<input type = "submit" class = "button" name = "send" value = "<?php echo JText::_('COM_SMARTSHOP_REPORT_SEND');?>" />
		</td>
	</tt>
</table>
<input type = "hidden" name = "task" value = "send" />
</form>