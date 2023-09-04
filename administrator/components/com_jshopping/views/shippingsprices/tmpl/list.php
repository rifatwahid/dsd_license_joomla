<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

displaySubmenuOptions("shippingsprices",$this->canDo);
$shipping_prices=$this->rows;
$rows=$this->rows;
$count=count($rows);
$i=0;
$saveOrder = $this->filter_order_Dir=="asc" && $this->filter_order=="ordering";
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_jshopping&controller=shippingsprices" method="post">
<?php print $this->tmp_html_start ?? '' ?>
<div class="table-responsive">
<table class="table table-striped">
<thead>
	<tr class="row<?php echo $i % 2;?>">
    	<th scope="col" class="title" width ="10">
      		#
    	</th>
    	<th scope="col" width="20">
	  		<input type="checkbox" class="form-check-input" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    	</th>
    	<th scope="col" align="left">
      		<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_TITLE'), 'name', $this->filter_order_Dir, $this->filter_order); ?>
    	</th>
        <th scope="col" >
            <?php echo  JText::_('COM_SMARTSHOP_COUNTRIES'); ?>
        </th>
        <th scope="col" width="100">
            <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_PRICE'), 'shipping_price.shipping_stand_price', $this->filter_order_Dir, $this->filter_order); ?>
        </th>
		<th scope="col" width="40" colspan="3">
		  <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ORDERING'), 'ordering', $this->filter_order_Dir, $this->filter_order); ?>
		  <?php if ($saveOrder){?>
		  <button onClick="shopHelper.saveorder(<?php echo $count; ?>, 'saveorder', event)" title="Save Order" class="saveorder btn btn-sm btn-secondary float-end float-right"><span class="icon-menu-2"></span></button>
		  <?php }?>
		</th>
		<th scope="col" width="30" class="center">
			<?php echo JText::_('COM_SMARTSHOP_PUBLISH'); ?>
		</th>
    	<th scope="col" width="70" class="center">
	        <?php echo  JText::_('COM_SMARTSHOP_EDIT'); ?>
	    </th>
        <th scope="col" width="40" class="center">
            <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_ID'), 'shipping_price.sh_pr_method_id', $this->filter_order_Dir, $this->filter_order); ?>
        </th>
  	</tr>
</thead>
<?php foreach($shipping_prices as $row){?>
<tr class="row<?php echo $i % 2;?>">
	<td>
		<?php echo $i + 1;?>
	</td>
	<td>		
        <?php echo JHtml::_('grid.id', $i, $row->sh_pr_method_id);?>
	</td>
	<td>
		<a href="index.php?option=com_jshopping&controller=shippingsprices&task=edit&sh_pr_method_id=<?php echo $row->sh_pr_method_id?>&shipping_id_back=<?php print $this->shipping_id_back?>"><?php echo $row->name;?></a>
	</td>
    <td>
        <?php print $row->countries; ?>
        <?php if(!empty($row->states)){
            print '('.$row->states.')';
         } ?>
    </td>
    <td>
        <?php print formatprice($row->shipping_stand_price);?>
    </td>
	<td align="right" width="20">
		<?php
		  if ($i!=0 && $saveOrder) echo '<a class="btn btn-micro" href="index.php?option=com_jshopping&controller=shippingsprices&task=order&id=' . $row->sh_pr_method_id . '&order=up&number=' . $row->ordering . '"><i class="icon-uparrow"></i></a>';
		?>
   </td>
   <td align="left" width="20">
      <?php $count = $count ?? 0;
        if ($i!=$count-1 && $saveOrder) echo '<a class="btn btn-micro" href="index.php?option=com_jshopping&controller=shippingsprices&task=order&id=' . $row->sh_pr_method_id . '&order=down&number=' . $row->ordering . '"><i class="icon-downarrow"></i></a>';
      ?>
   </td>
   <td align="center" width="10">
		<input type="text" name="order[]" id="ord<?php echo $row->sh_pr_method_id;?>" value="<?php echo $row->ordering?>" <?php if (!$saveOrder) echo 'disabled'?> class="inputordering" style="text-align: center" />
   </td>
   <td class="center">
		<?php echo JHtml::_('jgrid.published', $row->published, $i);?>
	</td>
	<td class="center">
        <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=shippingsprices&task=edit&sh_pr_method_id=<?php echo $row->sh_pr_method_id?>&shipping_id_back=<?php print $this->shipping_id_back?>'>
            <i class="icon-edit"></i>
        </a>
   </td>
   <td class="center">
    <?php print  $row->sh_pr_method_id;?>
   </td>  
</tr>
<?php $i++;} ?>
</table>
</div>

<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="shipping_id_back" value="<?php echo $this->shipping_id_back;?>" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end ?? ''?>
</form>