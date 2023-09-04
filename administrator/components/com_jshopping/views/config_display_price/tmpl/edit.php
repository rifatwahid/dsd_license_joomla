<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
$row=$this->row;
?>
<div class="jshop_edit config_display_price_edit">
<form action="index.php?option=com_jshopping&controller=configdisplayprice" method="post"name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
<div class="col100">
<fieldset class="adminform">
<table width="100%" class="admintable">
   <tr>
    <td class="key" style="width:250px;">
        <?php echo JText::_('COM_SMARTSHOP_COUNTRY') . "<br/><br/><span style='font-weight:normal'>".JText::_('COM_SMARTSHOP_MULTISELECT_INFO')."</span>"; ?>
    </td>
    <td>
        <?php echo $this->lists['countries'];?>
    </td>
   </tr>
   <tr>
     <td  class="key">
       <?php echo JText::_('COM_SMARTSHOP_DISPLAY_PRICE'); ?>
     </td>
     <td>
       <?php echo $this->lists['display_price'];?>
     </td>
   </tr>
   <tr>
     <td class="key">
       <?php echo JText::_('COM_SMARTSHOP_DISPLAY_PRICE_FOR_FIRMA'); ?>
     </td>
     <td>
       <?php echo $this->lists['display_price_firma'];?>
     </td>
   </tr>
   <?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
 </table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo isset($row->id) ? $row->id : 0; ?>" />
<?php print $this->tmp_html_end ?? ''?>
</form>
</div>