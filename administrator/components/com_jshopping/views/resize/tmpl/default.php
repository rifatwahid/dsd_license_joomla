<?php
displaySubmenuOptions("",$this->canDo);
?>
<form action = "index.php?option=com_jshopping&controller=resize&task=resize" method = "post" name = "adminForm">        
     <input type = "hidden" name = "hidemainmenu" value = "0" />
     <input type = "hidden" name = "boxchecked" value = "0" />  
     
      <fieldset class="adminform" >
      <legend><?php echo JText::_('COM_SMARTSHOP_LABEL_TITTLE') ?></legend> 
     <label style="font-size:14px;"><?php echo  JText::_('COM_SMARTSHOP_LABEL_DESCRIPTION')?></label><br/><br/>
     <button type="submit" name="Start" title="Start" class = "button" style="width:auto;height:20px"><?php echo JText::_('COM_SMARTSHOP_RESIZE_BUTTON')?></button>
     </fieldset>
        
</form>