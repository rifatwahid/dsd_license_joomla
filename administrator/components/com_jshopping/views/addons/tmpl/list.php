<?php 
/**
* @version      4.6.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php
displaySubmenuOptions("",$this->canDo);
$rows=$this->rows;
$count=count($rows);
$i=0;
?>
<form action="index.php?option=com_jshopping&controller=addons" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
<div class="table-responsive">
<table class="table table-striped">
<thead>
  <tr>
    <th scope="col" class="title" width="10">#</th>
    <th scope="col" align="left">
      <?php echo JText::_('COM_SMARTSHOP_TITLE')?>
    </th>
    <th scope="col" width="120">
        <?php echo JText::_('COM_SMARTSHOP_VERSION')?>
    </th>
    <th scope="col" width="60" class="center">
        <?php echo JText::_('COM_SMARTSHOP_DESCRIPTION')?>
    </th>
    <th scope="col" width="60" class="center">
        <?php echo JText::_('COM_SMARTSHOP_KEY')?>
    </th>
    <th scope="col" width="60" class="center">
        <?php echo JText::_('COM_SMARTSHOP_CONFIG')?>
    </th>
    <th scope="col" width="50" class="center">
        <?php echo JText::_('COM_SMARTSHOP_DELETE')?>
    </th>
    <th scope="col" width="40" class="center">
        <?php echo JText::_('COM_SMARTSHOP_ID')?>
    </th>
  </tr>
</thead>  
<?php foreach($rows as $row){?>
  <tr class="row<?php echo $i % 2;?>">
   <td>
     <?php echo $i+1;?>
   </td>
   <td>
     <?php echo $row->name;?>
   </td>
   <td>
    <?php echo $row->version;?>
    <?php if ($row->version_file_exist){?>
    <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=addons&task=version&id=<?php print $row->id?>'><i class="fas fa-file-invoice"></i></a>
    <?php }?>
   </td>
   <td class="center">
   <?php if ($row->info_file_exist){?>
    <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=addons&task=info&id=<?php print $row->id?>'><i class="fas fa-info-circle"></i></a>
   <?php }?>
   </td>
   <td class="center">
   <?php if ($row->usekey){?>
    <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=licensekeyaddon&alias=<?php print $row->alias?>&back=<?php print $this->back64?>'><i class="fas fa-key"></i></a>
   <?php }?>
   </td>
   <td class="center">
   <?php if ($row->config_file_exist){?>
        <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=addons&task=edit&id=<?php print $row->id?>'>
            <i class="icon-edit"></i>
        </a>
    <?php }?>
   </td>
   <td class="center">
    <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=addons&task=remove&id=<?php print $row->id?>' onclick="return confirm('<?php print JText::_('COM_SMARTSHOP_DELETE_ALL_DATA')?>')">
        <i class="icon-delete"></i>
    </a>
   </td>
   <td class="center">
    <?php print $row->id;?>
   </td>
  </tr>
<?php $i++;}?>
</table>
</div>
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end ?? ''?>
</form>