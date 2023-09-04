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
$rows=$this->rows;
$i=0;
?>
<form action="index.php?option=com_jshopping&controller=languages" method="post" name="adminForm" id="adminForm">
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
      <?php echo JText::_('COM_SMARTSHOP_LANGUAGE_NAME');?>
    </th>
    <th width="160" class="center">
      <?php echo JText::_('COM_SMARTSHOP_DEFAULT_FRONT_LANG');?>
    </th>
    <th width="190" class="center">
      <?php echo JText::_('COM_SMARTSHOP_DEFAULT_LANG_FOR_COPY');?>
    </th>
    <th width="50" class="center">
      <?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
    </th>
    <th width="40" class="center">
        <?php echo JText::_('COM_SMARTSHOP_ID');?>
    </th>
  </tr>
</thead>  
<?php
$count=count($rows);
foreach($rows as $row){
?>
  <tr class="row<?php echo $i % 2;?>">
   <td align="center">
     <?php echo $i+1;?>
   </td>
   <td align="center">
     <?php echo JHtml::_('grid.id', $i, $row->id);?>
   </td>
   <td>
     <?php echo $row->name; ?>
   </td>
   <td class="center">
     <?php if ($this->default_front == $row->language) {?>
        <a class="btn btn-micro">
            <i class="icon-default"></i>
        </a>
     <?php }?>
   </td>
   <td class="center">
     <?php if ($this->defaultLanguage == $row->language) {?>
        <a class="btn btn-micro">
            <i class="icon-default"></i>
        </a>
     <?php }?>
   </td>
   <td class="center">     
     <?php echo JHtml::_('jgrid.published', $row->publish, $i);?>
   </td>
   <td class="center">
    <?php print $row->id;?>
   </td>
  </tr>
<?php
$i++;
}
?>
</table>
<br/>
<br/>
<div class="helpbox">
    <div class="head"><i class="fas fa-info-circle"></i> <?php echo JText::_('COM_SMARTSHOP_DEFAULT_FRONT_LANG');?></div>
    <div class="text"><?php print JText::_('COM_SMARTSHOP_DEFAULT_FRONT_LANG_INFO');?></div>
    <br/>
    <div class="head"><i class="fas fa-info-circle"></i> <?php echo JText::_('COM_SMARTSHOP_DEFAULT_LANG_FOR_COPY');?></div>
    <div class="text"><?php print JText::_('COM_SMARTSHOP_DEFAULT_LANG_FOR_COPY_INFO');?></div>
</div>


<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?>
</form>