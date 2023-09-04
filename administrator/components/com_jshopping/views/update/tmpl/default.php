<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>
<fieldset class="uploadform">
<legend><?php echo JText::_('COM_INSTALLER_UPLOAD_PACKAGE_FILE'); ?></legend>
<form enctype="multipart/form-data" action="index.php?option=com_jshopping&controller=update&task=update" method="post" name="adminForm" id="adminForm">
<?php if (isset($this->tmp_html_start)) print $this->tmp_html_start?>
<table>
<tr>
	<td width="140">
	    <label for="install_package"><?php echo  JText::_('COM_SMARTSHOP_UPDATE_PACKAGE_FILE'); ?>:</label>
	</td>
	<td>
	    <input class="input_box" id="install_package" name="install_package" type="file" size="57" />
        <input type="hidden" name="installtype" value="package" />
	    <input class="button" type="submit" value="<?php echo  JText::_('COM_SMARTSHOP_UPDATE_PACKAGE_UPLOAD'); ?>" />
	</td>
</tr>
<?php $pkey="etemplatevar1";if ($this->$pkey){print $this->$pkey;}?>
</table>
</form>
</fieldset>   

<form enctype="multipart/form-data" action="index.php?option=com_jshopping&controller=update&task=update" method="post" name="adminForm" id="adminForm">
<fieldset class="uploadform">
<legend><?php echo  JText::_('COM_SMARTSHOP_UPDATE_UPLOAD_FROM_URL_PACKAGE_FILE'); ?></legend>
<table>
<tr>
    <td width="140">
        <label for="install_url"><?php echo  JText::_('COM_SMARTSHOP_UPDATE_UPLOAD_FROM_URL_PACKAGE_FILE'); ?>:</label>
    </td>
    <td>
        <input class="input_box" id="install_url" name="install_url" type="text" value="http://" size="57" />
        <input type="hidden" name="installtype" value="url" />
        <input class="button" type="submit" value="<?php echo  JText::_('COM_SMARTSHOP_UPDATE_PACKAGE_UPLOAD'); ?>" />
    </td>
</tr>
<?php $pkey="etemplatevar2";if ($this->$pkey){print $this->$pkey;}?>
</table>
</fieldset>
<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?>
</form>