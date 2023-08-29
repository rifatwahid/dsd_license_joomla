<?php 
/**
* @version      4.7.1 13.08.2013
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
	$pageNav=$this->pageNav;
	$i=0;
?>
<form action="index.php?option=com_jshopping&controller=coupons" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
<?php
if (isset($this->ext_coupon_html_befor_list)){
    print $this->ext_coupon_html_befor_list;
}
?>

<div class="table-responsive">
<table class="table table-striped">
<thead>
  <tr>
    <th scope="col" class="title" width ="10">
      #
    </th>
    <th scope="col" width="20">
      <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th scope="col" align = "left">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_CODE'), 'C.coupon_code', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width = "200" align = "left">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_VALUE'), 'C.coupon_value', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width = "80">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_START_DATE_COUPON'), 'C.coupon_start_date', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width = "80">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_EXPIRE_DATE_COUPON'), 'C.coupon_expire_date', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width = "80" class="center">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_FINISHED_AFTER_USED'), 'C.finished_after_used', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width = "80" class="center">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_FOR_USER'), 'C.for_user_id', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width = "80" class="center">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_COUPON_USED'), 'C.used', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
	<?php echo $this->tmp_extra_column_headers?>
    <th scope="col" width = "50" class="center">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_USAGE'), 'C.count_use', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width = "50" class="center">
        <?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
    </th>
    <th scope="col" width = "50" class="center">
        <?php echo JText::_('COM_SMARTSHOP_EDIT');?>
    </th>
    <th scope="col" width = "40" class="center">
        <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ID'), 'C.coupon_id', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
  </tr>
</thead>  
<?php
foreach($rows as $row){
    $finished=0; $date=date('Y-m-d');
    if ($row->used) $finished=1;
    if ($row->coupon_expire_date < $date && $row->coupon_expire_date!='0000-00-00' ) $finished=1;
?>
  <tr class="row<?php echo $i % 2;?>" <?php if ($finished) print "style='font-style:italic; color: #999;'"?>>
   <td>
     <?php echo $pageNav->getRowOffset($i);?>
   </td>
   <td>
    <?php echo JHtml::_('grid.id', $i, $row->coupon_id);?>
   </td>
   <td>
     <a href="index.php?option=com_jshopping&controller=coupons&task=edit&coupon_id=<?php echo $row->coupon_id; ?>"><?php echo $row->coupon_code;?></a>
   </td>
   <td>
     <?php echo $row->coupon_value; ?>
     <?php if ($row->coupon_type==0) print "%"; else print $this->currency;?>
   </td>
   <td>
    <?php if ($row->coupon_start_date!='0000-00-00') print formatdate($row->coupon_start_date);?>
   </td>
   <td>
    <?php if ($row->coupon_expire_date!='0000-00-00')  print formatdate($row->coupon_expire_date);?>
   </td>
   <td class="center">
    <?php if ($row->finished_after_used) print JText::_('COM_SMARTSHOP_YES'); else print JText::_('COM_SMARTSHOP_NO')?>
   </td>
   <td class="center">
    <?php if ($row->for_user_id) print $row->f_name." ".$row->l_name; else print JText::_('COM_SMARTSHOP_ALL');?>
   </td>
   <td class="center">
    <?php if ($row->used) print JText::_('COM_SMARTSHOP_YES'); else print JText::_('COM_SMARTSHOP_NO')?>
   </td>
   <?php echo $row->tmp_extra_column_cells; ?>
   <td class="center">     
		<a href='index.php?option=com_jshopping&controller=orders&coupon_id=<?php print $row->coupon_id?>'>
            <?php echo $row->count_use;?>
		</a>
   </td>
   <td class="center">     
     <?php echo JHtml::_('jgrid.published', $row->coupon_publish, $i);?>
   </td>
   <td class="center">
        <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=coupons&task=edit&coupon_id=<?php print $row->coupon_id?>'>
            <i class="icon-edit"></i>
        </a>
   </td>
   <td class="center">
     <?php echo $row->coupon_id ?>
   </td>
  </tr>
<?php
$i++;
}
?>
<tfoot>
<tr>	
    <td colspan="<?php echo 12+(int)$this->deltaColspan?>">
		<div class = "jshop_list_footer"><?php echo $pageNav->getListFooter(); ?></div>
        <div class = "jshop_limit_box"><?php echo $pageNav->getLimitBox(); ?></div>
	</td>
</tr>
</tfoot>
</table>
</div>

<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end ?? ''?>
</form>