<?php 
/**
* @version      4.9.0 22.10.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;

$rows = $this->rows;
$lists = $this->lists;
$pageNav = $this->pageNav;
$jshopConfig = JSFactory::getConfig();
$limitstart = JFactory::getApplication()->input->getVar('limitstart', '');
$limit = JFactory::getApplication()->input->getVar('limit', 10);
$status_id = JFactory::getApplication()->input->getVar('status_id', '');
$adv_string = '&limitstart=' . $limitstart . '&limit=' . $limit . '&status_id=' . $status_id . '&client_id=' . $this->client_id;

if (!isJoomla4()) {
	JHtml::_('formbehavior.chosen', 'select');
}
?>

<form name="adminForm" id="adminForm" method="post" action="index.php?option=com_jshopping&controller=orders">
	<?php echo $this->tmp_html_start ?? ''; ?>

	<div class="js-stools clearfix jshop_block_filter">
		
		<div class="js-stools-container-bar">
			<?php if (isJoomla4()) : ?> 
				<div id="filter-bar" class="btn-toolbar mb-3">
					<?php 
						echo LayoutHelper::render('smartshop.helpers.search_j4', [
							'searchText' => $text_search ?? ''
						], JPATH_ROOT . '/components/com_jshopping/layouts'); 
					?>
				</div>
			<?php else : ?>
				<div class="filter-search btn-group pull-left">
					<input type="text" id="text_search" name="text_search" placeholder="<?php print JText::_('COM_SMARTSHOP_SEARCH')?>" value="<?php echo htmlspecialchars($this->text_search);?>"  onkeypress="shopSearch.searchEnterKeyPress(event,this);"  />
				</div>

				<div class="btn-group pull-left hidden-phone">
					<button class="btn hasTooltip" type="submit" title="<?php print JText::_('COM_SMARTSHOP_SEARCH')?>">
						<i class="icon-search"></i>
					</button>
					<button class="btn hasTooltip" onclick="document.id('text_search').value='';this.form.submit();" type="button" title="<?php print JText::_('COM_SMARTSHOP_CLEAR')?>">
						<i class="icon-remove"></i>
					</button>
				</div>
			<?php endif; ?>
		</div>
		
		<div class="clearfix"></div>
		
		<div class="js-stools-container-filters">

			<?php print $this->tmp_html_filter ?? ''?>
			
			<div class="js-stools-field-filter">
				<label><?php print JText::_('COM_SMARTSHOP_ORDER_STATUS')?>:</label>
				<div class="control"><?php print $lists['changestatus'];?></div>
			</div>
			<div class="js-stools-field-filter">
				<label><?php print JText::_('COM_SMARTSHOP_NOT_FINISHED')?>:</label>
				<div class="control"><?php print $lists['notfinished'];?></div>
			</div>
			<div class="js-stools-field-filter">
				<label><?php print JText::_('COM_SMARTSHOP_DATE')?>:</label>
				<div class="control inlineBlockSelectChildren"><?php print $lists['year']." : ".$lists['month']." : ".$lists['day'];?></div>
			</div>
			<div class="js-stools-field-filter">
				<div class="control">
					<button class="btn btn-primary hasTooltip" type="submit" title="<?php print JText::_('COM_SMARTSHOP_SEARCH')?>">
						<i class="icon-search"></i>
					</button>
				</div>
			</div>
			
		</div>
		
	</div>
	
<div class="table-responsive">
	<table class="table table-striped" width="100%">
	<thead>
	<tr>
		<th scope="col" width="20">
		#
		</th>
		<th scope="col" width="20">
		<input type="checkbox" name="checkall-toggle" value="" class="form-check-input" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
		</th>
		<th scope="col" width="20">
		<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_NUMBER'), 'order_number', $this->filter_order_Dir, $this->filter_order)?>
		</th>
		<?php print $this->_tmp_cols_1 ?? ''?>
		<th scope="col" >
		<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_USER'), 'name', $this->filter_order_Dir, $this->filter_order)?>
		</th>
		<?php print $this->_tmp_cols_after_user ?? '' ?>
		<th scope="col" >
		<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_EMAIL'), 'email', $this->filter_order_Dir, $this->filter_order)?>
		</th>
		<?php print $this->_tmp_cols_3 ?? ''?>
		<?php if (isset($this->show_vendor) && $this->show_vendor){?>
		<th scope="col" >
		<?php echo JText::_('COM_SMARTSHOP_VENDOR')?>
		</th>
		<?php }?>
		<th scope="col" class="center">
		<?php echo JText::_('COM_SMARTSHOP_ORDER_PRINT_VIEW')?>
		</th>
		<?php print $this->_tmp_cols_4 ?? ''?>
		<th scope="col" >
		<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_DATE'), 'order_date', $this->filter_order_Dir, $this->filter_order)?>
		</th>
		<th scope="col" >
		<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ORDER_MODIFY_DATE'), 'order_m_date', $this->filter_order_Dir, $this->filter_order)?>
		</th>
		<?php print $this->_tmp_cols_5 ?? ''?>
		<?php if (!$jshopConfig->without_payment){?>
		<th scope="col" >
		<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_PAYMENT'), 'payment_name', $this->filter_order_Dir, $this->filter_order); ?>
		</th>
		<?php }?>
		<?php if (!$jshopConfig->without_shipping){?>
		<th scope="col" >
		<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_SHIPPINGS'), 'shipping_name', $this->filter_order_Dir, $this->filter_order); ?>
		</th>
		<?php }?>
		<th scope="col" >
		<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_STATUS'), 'order_status', $this->filter_order_Dir, $this->filter_order)?>
		</th>
		<?php print $this->_tmp_cols_6 ?? ''?>
		<th scope="col" >
		<?php echo JText::_('COM_SMARTSHOP_ORDER_UPDATE')?>
		</th>
		<?php print $this->_tmp_cols_7 ?? ''?>
		<th scope="col" >
		<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ORDER_TOTAL'), 'order_total', $this->filter_order_Dir, $this->filter_order)?>
		</th>
		<?php print $this->_tmp_cols_8 ?? ''?>
		<?php if ($jshopConfig->shop_mode==1){?>
		<th scope="col" class="center">
		<?php echo JText::_('COM_SMARTSHOP_TRANSACTIONS')?>
		</th>
		<?php }?>
		<th scope="col" class="center">
		<?php echo JText::_('COM_SMARTSHOP_EDIT')?>
		</th>  
	</tr>
	</thead>
	<?php 
	$i=0; 
	foreach($rows as $row){ //print_r($row);die;
		$display_info_order=$row->display_info_order;
		$orderStatusId = ($row->order_status == 0) ? 1 : $row->order_status; 
		$orderColor = $this->list_status_order[$orderStatusId]->color;

		if (!empty($orderColor)) {
			echo '<style>';
			echo '.row' . $i . ' > * {background:' . $orderColor . ' !important;}';
			echo '</style>';
		}
	?>
	<tr class="row<?php echo $i;?>" <?php if (!$row->order_created) print "style='font-style:italic; color: #b00;'"?>>
		<td>
		<?php echo $pageNav->getRowOffset($i);?>
		</td>
		<td>
			<?php if ($row->blocked){?>
				<i class="fas fa-check"></i>
			<?php }else{?>
				<?php echo JHtml::_('grid.id', $i, $row->order_id);?>
			<?php }?>
		</td>
		<td>
		<a class="order_detail" href = "index.php?option=com_jshopping&controller=orders&task=show&order_id=<?php echo $row->order_id?>"><?php echo $row->order_number;?></a>
		<?php if (!$row->order_created) print "(".JText::_('COM_SMARTSHOP_NOT_FINISHED').")";?>
		<?php print $row->_tmp_ext_info_order_number ?? ''?>
		</td>
		<?php print $row->_tmp_cols_1 ?? '' ?>
		<td>        
			<?php echo $row->name?>
		</td>
		<?php print $row->_tmp_cols_after_user ?? ''?>
		<td><?php echo $row->email?></td>
		<?php print $row->_tmp_cols_3 ?? ''?>
		<?php if (isset($this->show_vendor) && $this->show_vendor){?>
		<td>
			<?php print $row->vendor_name;?>
		</td>
		<?php }?>
		<td class="center">
			<?php if (file_exists($jshopConfig->pdf_orders_path."/".$row->pdf_file)AND($row->pdf_file!="")){?>
				<a href = "javascript:void window.open('<?php echo $jshopConfig->pdf_orders_live_path."/".$row->pdf_file?>', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no');">
					<i class="fas fa-print"></i>
				</a>
			<?php }?>
			
			<?php if (isset($row->_ext_order_info)) echo $row->_ext_order_info;?>
			<?php 
			if (!empty($row->uploads_files['urls'])) {
				foreach ($row->uploads_files['urls'] as $key => $uploadedFileUrl) {
				echo '<br><a href="' . $uploadedFileUrl . '" target="_blank">' . $row->uploads_files['filesNames'][$key] . '</a>';
				}
			}
			?>  

			<?php if(!empty($row->refund_pdfs)): ?>
				<?php foreach($row->refund_pdfs as $d=>$file):  ?>
					<?php if ($file!="" && file_exists($jshopConfig->pdf_orders_path."/refunds/".$row->order_id.'/'.$file)){?>
						<a href = "javascript:void window.open('<?php echo $jshopConfig->pdf_orders_live_path."/refunds/".$row->order_id.'/'.$file?>', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no');">
							<i class="fa fa-file-pdf"></i>
						</a>
					<?php }?>
				<?php endforeach; ?>
			<?php endif; ?>			
		</td>
		<?php print $row->_tmp_cols_4 ?? ''?>
		<td>
		<?php echo formatdate($row->order_date, 1);?>
		</td>
		<td>
		<?php echo formatdate($row->order_m_date, 1);?>
		</td>
		<?php print $row->_tmp_cols_5 ?? ''?>
		<?php if (!$jshopConfig->without_payment){?>
		<td>
		<?php echo $row->payment_name?>
		</td>
		<?php }?>
		<?php if (!$jshopConfig->without_shipping){?>
		<td>
		<?php echo $row->shipping_name?>
		</td>
		<?php }?>
		<td>
			<?php if ($display_info_order && $row->order_created){
				echo JHTML::_('select.genericlist', $lists['status_orders'], 'select_status_id['.$row->order_id.']', 'class="inputbox form-select" style = "width: 100px" id="status_id_'.$row->order_id.'"', 'status_id', 'name', $row->order_status );
			}else{
				print $this->list_order_status[$row->order_status] ?? '';
			}
			?>
			<?php print $row->_tmp_ext_info_status ?? ''?>
		</td>
		<?php print $row->_tmp_cols_6 ?? ''?>
		<td>
		<?php if ($row->order_created && $display_info_order){?>
			<input class="inputbox form-check-input" type="checkbox" name="order_check_id[<?php echo $row->order_id?>]" id="order_check_id_<?php echo $row->order_id?>" />
			<label for="order_id_<?php echo $row->order_id?>"><?php echo JText::_('COM_SMARTSHOP_NOTIFY_CUSTOMER')?></label>
			<input class="button btn btn-small btn-primary" type="button" name="" value="<?php echo JText::_('COM_SMARTSHOP_UPDATE_STATUS')?>" onclick="shopOrderAndOffer.verifyStatus(<?php echo $row->order_status; ?>, <?php echo $row->order_id; ?>, '<?php echo JText::_('COM_SMARTSHOP_CHANGE_ORDER_STATUS');?>', 0, '<?php echo $adv_string?>');" />
		<?php }?>
		<?php if ($display_info_order && !$row->order_created && !$row->blocked){?>
			<a href="index.php?option=com_jshopping&controller=orders&task=finish&order_id=<?php print $row->order_id?>&js_nolang=1"><?php print JText::_('COM_SMARTSHOP_FINISH_ORDER')?></a>
		<?php }?>
		<?php print $row->_tmp_ext_info_update ?? ''?>
		</td>
		<?php print $row->_tmp_cols_7 ?? ''?>
		<td>
		<?php if ($display_info_order) echo formatprice( $row->order_total,$row->currency_code)?>
		<?php print $row->_tmp_ext_info_order_total ?? ''?>
		</td>
		<?php print $row->_tmp_cols_8 ?? ''?>
		<?php if ($jshopConfig->shop_mode==1){?>
		<td class="center">
		<a class="btn btn-micro" href='index.php?option=com_jshopping&controller=orders&task=transactions&order_id=<?php print $row->order_id;?>'>
			<i class="fas fa-tools"></i>
		</a>
		</td>
		<?php }?>
		<td class="center">
		<?php if ($display_info_order){?>
			<a class="btn btn-micro" href='index.php?option=com_jshopping&controller=orders&task=edit&order_id=<?php print $row->order_id;?>&client_id=<?php print $this->client_id;?>'>
				<i class="icon-edit"></i>
			</a>
		<?php }?>
	</td>
	</tr>
	<?php
	$i++;
	}
	?>
	<tr>
		<?php
		$cols = 10;
		if (!$jshopConfig->without_payment) $cols++;
		if (!$jshopConfig->without_shipping) $cols++;
		if (isset($this->show_vendor) && $this->show_vendor) $cols++;
		?>
		<?php print $this->_tmp_cols_foot_total ?? ''?>
		<td colspan="<?php print (isset($this->deltaColspan0)) ? $cols+(int)$this->deltaColspan0 : $cols; ?>" class="right"><b><?php print JText::_('COM_SMARTSHOP_TOTAL')?></b></td>
		<td><b><?php print formatprice($this->total, getMainCurrencyCode())?></b></td>
		<?php if ($jshopConfig->shop_mode==1){?>
		<td></td>
		<?php }?>
		<td></td>
	</tr>
	<tfoot>
	<tr>
		<?php 
		$cols = 20;
		if (!$jshopConfig->without_payment) $cols++;
		if (!$jshopConfig->without_shipping) $cols++;
		if (isset($this->show_vendor) && $this->show_vendor) $cols++;
		?>
	<?php print $this->tmp_html_col_before_td_foot ?? ''?>
	<td colspan="<?php print isset($this->deltaColspan) ? $cols+(int)$this->deltaColspan : $cols ?>">
		<div class="jshop_list_footer"><?php echo $pageNav->getListFooter(); ?></div>
		<div class="jshop_limit_box"><?php echo $pageNav->getLimitBox(); ?></div>
	</td>
	<?php print $this->tmp_html_col_after_td_foot ?? ''?>
	</tr>
	</tfoot>  
	</table>
</div>
	<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />
	<input type="hidden" name="task" value="" />
	<input type = "hidden" name = "boxchecked" value = "0" />
	<input type = "hidden" name = "client_id" value ="<?php echo $this->client_id?>" />
	<?php print $this->tmp_html_end ?? ''?>
</form>
<?php print $this->_tmp_order_list_html_end ?? '';?>

<?php include_once('modal.php') ?>