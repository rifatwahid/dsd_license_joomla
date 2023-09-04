<?php
displaySubmenuOptions("",$this->canDo);
$langs = $this->langs;
$translate = $this->translate;
$constants = $this->constants;
$list_files = $this->list_files;
$i=0;
?>
<form action = "index.php?option=com_jshopping&controller=langpackedit&task=save"method="post" name="adminForm" id="adminForm">        
     
      <fieldset class="adminform" >
          <table class="admintable">
              <tr>
                <td class="key">
                    <?php echo JText::_('COM_SMARTSHOP_LANG_PACK_SELECT'); ?>
                </td>
                <td>
                    <?php echo $list_files; ?>
                </td>                 
              </tr>   
          </table>         
    <?php 
		$max_input_vars = ini_get('max_input_vars');
		if(count($constants) && $max_input_vars > $this->count_vars + 50){?>   
     <table>
         <thead>
         <th><?php echo  JText::_('COM_SMARTSHOP_LABEL_CONSTANT')?></th>
            <?php foreach ($langs as $_lang) {?>
         <th><?php echo  $_lang?></th>
         <?php }?>
         </thead>
         <?php foreach ($constants as $k=>$constant) {?>
            <tr class="row<?php echo $i % 2;?>">
                <td>
                    <?php echo  '<b>'.$constant.'</b>';?>
                </td>
            <?php foreach ($langs as $_lang) {?>
            <td>
                <?php /*<textarea name="lang[<?php echo $_lang?>][]"><?php echo htmlspecialchars(stripslashes($translate[$_lang][$constant]))?></textarea>*/?>
				<textarea name="lang[<?php echo $_lang?>][]"><?php echo htmlspecialchars(str_replace(array("\\\\","\'"), array("\\","'"), $translate[$_lang][$constant]))?></textarea>
				
             </td> 
            <?php }?>                
         </tr>
         <?php 
         $i++;
            }?>
         </table>
      <?php }else{
			$recomend = (ceil(($this->count_vars + 50)/100)*100);	  
            \Joomla\CMS\Factory::getApplication()->enqueueMessage('No edit this file! Parameter <b>max_input_vars</b> small (now '.$max_input_vars.')! Zoom this parameter in <b>php.ini</b> or <b>.htaccess</b> file! Recomended size <b>'.$recomend.'</b>', 'notice');
	  }?>   
     </fieldset>
     <input type = "hidden" name = "hidemainmenu" value = "0" />
     <input type="hidden" name="task" value="" />
     <input type = "hidden" name = "boxchecked" value = "0" /> 
     <input type = "hidden" name = "fileheader" value = "<?php echo $this->header;?>" />        
</form>