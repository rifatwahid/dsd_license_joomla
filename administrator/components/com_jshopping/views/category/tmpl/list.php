<?php 

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;

$categories = $this->categories; 
$i = 0;
$text_search = $this->text_search;
$count = count($categories); 
$pageNav = $this->pagination;
$saveOrder = ($this->filter_order_Dir == 'asc' && $this->filter_order == 'ordering');
?>

<form action="index.php?option=com_jshopping&controller=categories" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
	<?php echo $this->tmp_html_start ?? ''; ?>

	<!-- Filter -->
	<div id="filter-bar" class="btn-toolbar mb-3">
		<?php echo $this->tmp_html_filter ?? ''; ?> 

		<?php if (isJoomla4()) : ?> 
			<?php 
				echo LayoutHelper::render('smartshop.helpers.search_j4', [
					'searchText' => $text_search
				], JPATH_ROOT . '/components/com_jshopping/layouts'); 
			?>
		<?php else : ?>
			<div class="filter-search btn-group pull-left">
				<input type="text" id="text_search" name="text_search" placeholder="<?php echo JText::_('COM_SMARTSHOP_SEARCH')?>" value="<?php echo htmlspecialchars($text_search);?>" onkeypress="shopSearch.searchEnterKeyPress(event,this);" />
			</div>

			<div class="btn-group pull-left hidden-phone">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('COM_SMARTSHOP_SEARCH')?>">
					<i class="icon-search"></i>
				</button>
				<button class="btn hasTooltip" onclick="document.id('text_search').value='';this.form.submit();" type="button" title="<?php echo JText::_('COM_SMARTSHOP_CLEAR')?>">
					<i class="icon-remove"></i>
				</button>
			</div>
		<?php endif; ?>
	</div>

	<!-- Categories list -->
	<div class="table-responsive">
		<table class="table table-striped form-inline">
			<!-- Head -->
			<thead>
				<tr>
					<th scope="col" class="title" width ="10">
						#
					</th>
					<th scope="col" width="20">
						<input type="checkbox" name="checkall-toggle" class="form-check-input" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th scope="col" width="200" align="left">
						<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_TITLE'), 'name', $this->filter_order_Dir, $this->filter_order); ?>
					</th>

					<?php echo $this->tmp_html_col_after_title ?? ''; ?>

					<th scope="col" align="left">
						<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_DESCRIPTION'), 'description', $this->filter_order_Dir, $this->filter_order); ?>
					</th>
					<th scope="col" width="80" align="left">
						<?php echo  JText::_('COM_SMARTSHOP_CATEGORY_PRODUCTS');?>
					</th>    
					<th scope="col" colspan="3" width="40">
						<?php 
						echo JHTML::_( 'grid.sort',  JText::_('COM_SMARTSHOP_ORDERING'), 'ordering', $this->filter_order_Dir, $this->filter_order);

						if ($saveOrder) { ?>
							<button onClick="shopHelper.saveorder(<?php echo ($count-1);?>, 'saveorder', event)" title="Save Order" class="saveorder btn btn-sm btn-secondary float-end float-right"><span class="icon-menu-2"></span></button>
						<?php }
						?>
					</th>
					<th scope="col" width="50" class="center">
						<?php echo  JText::_('COM_SMARTSHOP_PUBLISH');?>
					</th>
					<th scope="col" width="50" class="center">
						<?php echo  JText::_('COM_SMARTSHOP_EDIT');?>
					</th>
					<th scope="col" width="50" class="center">
						<?php echo  JText::_('COM_SMARTSHOP_DELETE');?>
					</th>
					<th scope="col" width="50" class="center">
						<?php echo JHTML::_( 'grid.sort',  JText::_('COM_SMARTSHOP_ID'), 'id', $this->filter_order_Dir, $this->filter_order); ?>
					</th>
				</tr>
			</thead>  
			
			<!-- Body -->
			<?php foreach($categories as $category) { ?>
				<tr class="row<?php echo $i % 2;?>">
					<td>
						<?php echo $pageNav->getRowOffset($i);?>
					</td>
					<td>     
						<?php if ($category->category_id!=1) echo JHtml::_('grid.id', $i, $category->category_id);?>
					</td>
					<td>
						<?php if ($this->canDo->get('core.edit') AND $this->canDo->get('smartshop.categories.copy')){?>
						<?php echo $category->space; ?><a href = "index.php?option=com_jshopping&controller=categories&task=edit&category_id=<?php echo $category->category_id; ?>"><?php echo $category->name;?></a>
						<?php } else {echo $category->name;}?>
					</td>

					<?php echo $category->tmp_html_col_after_title ?? ''; ?>

					<td>
						<?php echo $category->short_description ?? ''; ?>
					</td>
					<td align="center">
						<?php if (isset($this->countproducts[$category->category_id])){?>
						<?php if ($this->canDo->get('smartshop.products')){?>
						<a href="index.php?option=com_jshopping&controller=products&category_id=<?php echo $category->category_id?>">
						(<?php echo intval($this->countproducts[$category->category_id]);?>) <i class="fas fa-long-arrow-alt-right"></i>
						</a>
						<?php } else {echo intval($this->countproducts[$category->category_id]);}?>
						<?php }else{?>
						(0)
						<?php }?>
					</td>
					<td align = "right" width = "20">
						<?php if ($this->canDo->get('core.ordering') AND $this->canDo->get('smartshop.categories.ordering')){?>
						<?php if ($saveOrder && $category->isPrev) echo '<a class="btn btn-micro" href = "index.php?option=com_jshopping&controller=categories&task=order&id='.$category->category_id.'&move=-1"><i class="icon-uparrow"></i></a>'; ?>
						<?php } ?>
					</td>
					<td align = "left" width = "20"> 
						<?php if ($this->canDo->get('core.ordering') AND $this->canDo->get('smartshop.categories.ordering')){?>
						<?php if ($saveOrder && $category->isNext) echo '<a class="btn btn-micro" href = "index.php?option=com_jshopping&controller=categories&task=order&id='.$category->category_id.'&move=1"><i class="icon-downarrow"></i></a>'; ?>
						<?php } ?>
					</td>
					<td align="center" width="10">
						<?php if ($category->category_id!=1){ ?><input type="text" name="order[]" id="ord<?php echo $category->category_id;?>" value="<?php echo $category->ordering;?>"  <?php if (!$saveOrder) echo 'disabled'?> class="inputordering" /><?php } ?>
					</td>
					<td class="center">     
						<?php if ($this->canDo->get('core.publish') AND $this->canDo->get('smartshop.categories.publish')) echo JHtml::_('jgrid.published', $category->category_publish, $i);?>
					</td>
					<td class="center">
						<?php if ($this->canDo->get('core.edit') AND $this->canDo->get('smartshop.categories.copy')){?>
						<a class="btn btn-micro" href='index.php?option=com_jshopping&controller=categories&task=edit&category_id=<?php print $category->category_id?>'>
						<i class="icon-edit"></i>
						</a>
						<?php } ?>
					</td>
					<td class="center">
						<?php if ($this->canDo->get('core.delete') AND $this->canDo->get('smartshop.categories.delete') AND $category->category_id!=1){?>
						<a class="btn btn-micro" href='index.php?option=com_jshopping&controller=categories&task=remove&cid[]=<?php print $category->category_id?>' onclick="return confirm('<?php print  JText::_('COM_SMARTSHOP_DELETE')?>');">
						<i class="icon-delete"></i>
						</a>
						<?php } ?>
					</td>
					<td class="center">
						<?php echo $category->category_id; ?>
					</td>
				</tr>
			<?php $i++; } ?>
			
			<!-- Footer -->
			<tfoot>
				<tr>
					<?php echo $this->tmp_html_col_before_td_foot ?? ''; ?>
					<td colspan = "12">
						<div class = "jshop_list_footer"><?php echo $pageNav->getListFooter(); ?></div>
						<div class = "jshop_limit_box"><?php echo $pageNav->getLimitBox(); ?></div>
					</td>
					<?php echo $this->tmp_html_col_after_td_foot ?? ''; ?>
				</tr>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="filter_order" value="<?php echo $this->filter_order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="hidemainmenu" value="0" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo $this->tmp_html_end ?? ''; ?>
</form>