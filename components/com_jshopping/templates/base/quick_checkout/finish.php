<?php 
/**
* @version      4.8.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

if (!empty($this->text)) {
    echo $this->text;
} else { ?> 
    <p><?php echo JText::_('COM_SMARTSHOP_THANK_YOU_ORDER') ?></p>
<?php } 

echo $this->dynamicFinishText;
?>