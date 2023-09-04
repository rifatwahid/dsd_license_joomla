<?php

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;

$rows = $this->rows;
$lists = $this->lists;
$pageNav = $this->pagination;
$text_search = $this->text_search;
$category_id = $this->category_id;
$manufacturer_id = $this->manufacturer_id;
$count = count($rows);
$i = 0;
$saveOrder = ($this->filter_order_Dir == 'asc' && $this->filter_order == 'ordering');

if (!isJoomla4()) {
	JHtml::_('formbehavior.chosen', 'select');
}

JFactory::getLanguage()->load('com_jshopping');
?>

<form action="index.php?option=com_jshopping&controller=products" method="post" name="adminForm" id="adminForm">
	<?php echo $this->tmp_html_start ?? ''; ?>

	<div class="js-stools clearfix jshop_block_filter">
		<div class="js-stools-container-bar float-left float-start">
			<?php if (isJoomla4()) : ?> 
				<div id="filter-bar" class="btn-toolbar mb-3">
					<?php 
						echo LayoutHelper::render('smartshop.helpers.search_j4', [
							'searchText' => $text_search
						], JPATH_ROOT . '/components/com_jshopping/layouts'); 
					?>
				</div>
			<?php else : ?>
				<div class="filter-search btn-group pull-left">
					<input type="text" id="text_search" name="text_search" placeholder="<?php print JText::_('COM_SMARTSHOP_SEARCH')?>" value="<?php echo htmlspecialchars($text_search);?>"  onkeypress="shopSearch.searchEnterKeyPress(event,this);" />
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

		<div class="js-stools-container-bar float-right float-end">
			<?php echo $this->tmp_html_filter ?? ''; ?>
			<div class="js-stools-field-filter">
				<?php echo $lists['treecategories'];?>
			</div>
			<div class="js-stools-field-filter">
				<?php echo $lists['manufacturers'];?>
			</div>
			<?php if ($this->config->admin_show_product_labels) : ?>
				<div class="js-stools-field-filter">
					<?php echo $lists['labels']?>
				</div>
			<?php endif; ?>
			<div class="js-stools-field-filter">
				<?php echo $lists['publish']; ?>
			</div>
		</div>
	</div>


	<table class="table table-striped" >
		<thead> 
			<tr>
				<th class="title" width ="10">
					#
				</th>
				<th width="20">
					<input type="checkbox" name="checkall-toggle" class="form-check-input" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="93">
					<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_IMAGE'), 'product_name_image', $this->filter_order_Dir, $this->filter_order)?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_TITLE'), 'name', $this->filter_order_Dir, $this->filter_order)?>
				</th>
				<?php echo $this->tmp_html_col_after_title ?? ''; ?>
				<?php if (!$category_id) : ?>
					<th width="80">
						<?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_CATEGORY'), 'category', $this->filter_order_Dir, $this->filter_order)?>
					</th>
				<?php endif; ?>
				<?php if (!$manufacturer_id) : ?>
					<th width="80">
						<?php echo JHTML::_( 'grid.sort', JText::_('COM_SMARTSHOP_MANUFACTURER'), 'manufacturer', $this->filter_order_Dir, $this->filter_order)?>
					</th>
				<?php endif; ?>
				<th width="80">
					<?php echo JHTML::_( 'grid.sort', JText::_('COM_SMARTSHOP_EAN_PRODUCT'), 'ean', $this->filter_order_Dir, $this->filter_order);?>
				</th>
				<?php if ($this->config->stock) : ?>
					<th width="60">
						<?php echo JHTML::_( 'grid.sort', JText::_('COM_SMARTSHOP_QUANTITY'), 'qty', $this->filter_order_Dir, $this->filter_order);?>
					</th>
				<?php endif; ?>
				<th width="80">
					<?php echo JHTML::_( 'grid.sort', JText::_('COM_SMARTSHOP_PRICE'), 'price', $this->filter_order_Dir, $this->filter_order);?>
				</th>
				<th width="40">
					<?php echo JHTML::_( 'grid.sort', JText::_('COM_SMARTSHOP_HITS'), 'hits', $this->filter_order_Dir, $this->filter_order);?>
				</th>
				<th width="60">
					<?php echo JHTML::_( 'grid.sort', JText::_('COM_SMARTSHOP_DATE'), 'date', $this->filter_order_Dir, $this->filter_order);?>
				</th>
				<?php if ($category_id) : ?>
					<th colspan="3" width="40">
						<?php 
						echo JHTML::_( 'grid.sort', JText::_('COM_SMARTSHOP_ORDERING'), 'ordering', $this->filter_order_Dir, $this->filter_order);
						
						if ($saveOrder) { ?>
                            <button onClick="shopHelper.saveorder(<?php echo ($count-1);?>, 'saveorder', event)" title="Save Order" class="saveorder btn btn-sm btn-secondary float-end float-right"><span class="icon-menu-2"></span></button>
                       <?php }
						?>      
					</th>
				<?php endif; ?>
				<th width="40" class="center">
					<?php echo JText::_('COM_SMARTSHOP_PUBLISH'); ?>
				</th>
				<th width="40" class="center">
					<?php echo JText::_('COM_SMARTSHOP_EDIT'); ?>
				</th>
				<th width="40" class="center">
					<?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>
				</th>
				<th width="30" class="center">
					<?php echo JHTML::_( 'grid.sort', JText::_('COM_SMARTSHOP_ID'), 'product_id', $this->filter_order_Dir, $this->filter_order); ?>
				</th>
			</tr>
		</thead> 

		<?php foreach($rows as $row) : ?>
			<tr class="row<?php echo $i % 2;?>">
				<td>
					<?php echo $pageNav->getRowOffset($i); ?>
				</td>
				<td>
					<?php echo JHtml::_('grid.id', $i, $row->product_id); ?>
				</td>
				<td>
					<?php if ($row->label_id) : ?>
						<div class="product_label">
							<?php if (isset($row->_label_image) && $row->_label_image) : ?>
								<img src="<?php echo $row->_label_image; ?>" width="25" alt="" />
							<?php else : ?>
								<span class="label_name">
								<?php echo $row->_label_name ?? ''; ?>
							</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if (!empty($row->image)) : ?>
						<?php if ($this->canDo->get('core.edit') AND $this->canDo->get('smartshop.products.copy')) : ?>
							<a href="index.php?option=com_jshopping&controller=products&task=edit&product_id=<?php echo $row->product_id; ?>">
								<img src="<?php echo getPatchProductImage($row->image, '', 1); ?>" width="90" border="0" />
							</a>
						<?php else : ?>
							<img src="<?php echo $row->image; ?>" width="90" border="0" />
						<?php endif; ?>
					<?php endif; ?>
				</td>
				<td>
					<b>
					<?php if ($this->canDo->get('core.edit') AND $this->canDo->get('smartshop.products.copy')) { ?>
						<a href="index.php?option=com_jshopping&controller=products&task=edit&product_id=<?php echo $row->product_id; ?>"><?php echo $row->name; ?></a>
					<?php } else { 
						echo $row->name; 
					} ?>	
					</b>
					<div>
						<?php echo $row->short_description; ?>
					</div>
				</td>
				<?php echo $row->tmp_html_col_after_title ?? ''; ?>

				<?php if (!$category_id) : ?>
					<td>
						<?php echo $row->namescats; ?>
					</td>
				<?php endif; ?>

				<?php if (!$manufacturer_id) : ?>
					<td>
						<?php echo $row->man_name; ?>
					</td>
				<?php endif; ?>

				<td>
					<?php echo $row->ean; ?>
				</td>

				<?php if ($this->config->stock) : ?>
					<td>
						<?php echo ($row->unlimited) ? JText::_('COM_SMARTSHOP_UNLIMITED') : $row->qty; ?>
					</td>
				<?php endif; ?>

				<td>
					<?php echo formatprice($row->product_price, sprintCurrency($row->currency_id)); ?>
				</td>
				<td>
					<?php echo $row->hits; ?>
				</td>
				<td>
					<?php echo formatdate($row->product_date_added, 1); ?>
				</td>

				<?php if ($category_id) : ?>
					<td align="right" width="20">
						<?php echo ($i != 0 && $saveOrder) ? "<a class=\"btn btn-micro\" href=\"index.php?option=com_jshopping&controller=products&task=order&product_id={$row->product_id}&category_id={$category_id}&order=up&number={$row->product_ordering}\"><i class=\"icon-uparrow\"></i></a>" : ''; ?>
					</td>
					<td align="left" width="20">
						<?php echo ($i != ($count-1) && $saveOrder) ? "<a class=\"btn btn-micro\" href=\"index.php?option=com_jshopping&controller=products&task=order&product_id={$row->product_id}&category_id={$category_id}&order=down&number={$row->product_ordering}\"><i class=\"icon-downarrow\"></i></a>" : ''; ?>
					</td>
					<td align="center" width="10">
						<input type="text" name="order[]" id="ord<?php echo $row->product_id; ?>" value="<?php echo $row->product_ordering; ?>" <?php echo (!$saveOrder) ? 'disabled' : ''; ?> class="inputordering" />
					</td>
				<?php endif; ?>

				<td class="center">     
					<?php if ($this->canDo->get('core.publish') AND $this->canDo->get('smartshop.products.publish')) {	
						echo JHtml::_('jgrid.published', $row->product_publish, $i);
					}?>
				</td>

				<td class="center">
					<?php if ($this->canDo->get('core.edit') AND $this->canDo->get('smartshop.products.edit')) : ?>
						<a class="btn btn-micro" href='index.php?option=com_jshopping&controller=products&task=edit&product_id=<?php echo $row->product_id; ?>'>
							<i class="icon-edit"></i>
						</a>
					<?php endif; ?>
				</td>

				<td class="center">
					<?php if ($this->canDo->get('core.delete') AND $this->canDo->get('smartshop.products.delete')) : ?>
						<a class="btn btn-micro" href='index.php?option=com_jshopping&controller=products&task=remove&cid[]=<?php echo $row->product_id; ?>' onclick="return confirm('<?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>')">
							<i class="icon-delete"></i>
						</a>
					<?php endif; ?>
				</td>

				<td class="center">
					<?php echo $row->product_id; ?>
				</td>
			</tr>
		<?php $i++; endforeach; ?>

		<tfoot>
			<tr>
				<?php echo $this->tmp_html_col_before_td_foot ?? ''; ?>
				<td colspan="18">
					<div class = "jshop_list_footer"><?php echo $pageNav->getListFooter(); ?></div>
					<div class = "jshop_limit_box"><?php echo $pageNav->getLimitBox(); ?></div>
				</td>
				<?php echo $this->tmp_html_col_after_td_foot ?? ''; ?>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="filter_order" value="<?php echo $this->filter_order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo $this->tmp_html_end ?? ''; ?>
</form>