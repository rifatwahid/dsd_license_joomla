<?php
/**
* @version      4.9.0 13.08.2013
* @author
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;

$rows     = $this->rows;
$pageNav  = $this->pageNav;

?>

<form name="adminForm" id="adminForm" method="post" action="index.php?option=com_jshopping&controller=users">
	<?php echo $this->tmp_html_start ?? ''; ?>

	<div id="filter-bar" class="btn-toolbar mb-3">
		<?php echo $this->tmp_html_filter ?? ''; ?>

		<?php if (isJoomla4()) : ?> 
				<?php 
					echo LayoutHelper::render('smartshop.helpers.search_j4', [
						'searchText' => $text_search ?? ''
					], JPATH_ROOT . '/components/com_jshopping/layouts'); 
				?>
		<?php else : ?>
			<div class="filter-search btn-group pull-left">
				<input type="text" id="text_search" name="text_search" placeholder="<?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?>" value="<?php echo htmlspecialchars($this->text_search); ?>"  onkeypress="shopSearch.searchEnterKeyPress(event,this);"  />
			</div>

			<div class="btn-group pull-left hidden-phone">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?>">
					<i class="icon-search"></i>
				</button>

				<button class="btn hasTooltip" onclick="document.id('text_search').value='';this.form.submit();" type="button" title="<?php echo JText::_('COM_SMARTSHOP_CLEAR'); ?>">
					<i class="icon-remove"></i>
				</button>
			</div>
		<?php endif; ?>
	</div>



<div class="table-responsive">
	<table class="table table-striped" width="100%">
		<thead>
			<tr>
				<th scope="col" width="20">
				   #
				</th>

				<th scope="col" width="20">
					<input type="checkbox" class="form-check-input" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>

				<th scope="col" align="left">
					<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_NUMBER'), 'number', $this->filter_order_Dir, $this->filter_order); ?>
				</th>

				<th scope="col" align="left">
					<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_USERNAME'), 'u_name', $this->filter_order_Dir, $this->filter_order); ?>
				</th>

				<th scope="col" width="150" align="left">
					<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_USER_FIRSTNAME'), 'f_name', $this->filter_order_Dir, $this->filter_order); ?>
				</th>

				<th scope="col" width="150" align="left">
					<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_USER_LASTNAME'), 'l_name', $this->filter_order_Dir, $this->filter_order); ?>
				</th>

				<th scope="col" >
					<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_EMAIL'), 'U.email', $this->filter_order_Dir, $this->filter_order); ?>
				</th>

				<?php echo $this->tmp_html_col_after_email ?? ''?>

				<th scope="col" >
					<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_USERGROUP_NAME'), 'usergroup_name', $this->filter_order_Dir, $this->filter_order); ?>
				</th>

				<th scope="col" class="center">
					<?php echo  JText::_('COM_SMARTSHOP_ORDERS'); ?>
				</th>

				<th scope="col" class="center">
					<?php echo  JText::_('COM_SMARTSHOP_ENABLED'); ?>
				</th>

				<th scope="col" width="50" class="center">
					<?php echo  JText::_('COM_SMARTSHOP_EDIT'); ?>
				</th>

				<th scope="col" width="40" class="center">
					<?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_ID'), 'user_id', $this->filter_order_Dir, $this->filter_order); ?>
				</th>

				<th scope="col" class="center">
					<?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_CLIENT_ORDER'); ?>
				</th>
				<?php print $this->tmp_html_col_after_id ?? ''; ?>
			</tr>
		</thead>

		<?php $i = 0;
				foreach($rows as $row) :
					$href = JRoute::_(JURI::base() . 'index.php?option=com_jshopping&controller=offer_and_order&task=relogin_to_frontend&admin_user_id=' . intval($this->currentUser->id) . '&user_id=' . intval($row->user_id));
		?>
			<tr class="row<?php echo ($i % 2); ?>">
				<td>
					<?php echo $pageNav->getRowOffset($i); ?>
				</td>

				<td>
					<?php echo JHtml::_('grid.id', $i, $row->user_id); ?>
				</td>

				<td>
					<?php echo $row->number; ?>
				</td>

				<td>
					<?php if ($this->canDo->get('core.edit') AND $this->canDo->get('smartshop.users.edit')){?>
					<a href="index.php?option=com_jshopping&controller=users&task=edit&user_id=<?php echo $row->user_id; ?>">
						<?php echo $row->u_name; ?>
					</a>
					<?} else {echo $row->u_name;}?>
				</td>

				<td>
					<?php echo $row->f_name; ?>
				</td>

				<td>
					<?php echo $row->l_name; ?>
				</td>

				<td>
					<?php echo $row->email; ?>
				</td>

			 	<?php echo $row->tmp_html_col_after_email ?? ''; ?>

				<td>
			   		<?php echo $row->usergroup_name; ?>
			 	</td>

				<td class="center">
					<?php if ( $this->canDo->get('smartshop.orders')){?>
						<a class="btn btn-mini" href='index.php?option=com_jshopping&controller=orders&client_id=<?php echo $row->user_id; ?>' target='_blank'>
							<?php echo  JText::_('COM_SMARTSHOP_ORDERS'); ?>
						</a>
					<?php } ?>	
				</td>

				<td class="center">
					<?php if ($this->canDo->get('core.publish') AND $this->canDo->get('smartshop.users.publish')){?>
						<?php echo JHtml::_('jgrid.published', !$row->block, $i); ?>
					<?php } ?>
				</td>

				<td class="center">
					<?php if ($this->canDo->get('core.edit') AND $this->canDo->get('smartshop.users.edit')){?>
						<a class="btn btn-micro" href='index.php?option=com_jshopping&controller=users&task=edit&user_id=<?php echo $row->user_id; ?>'>
							<i class="icon-edit"></i>
						</a>
					<?php } ?>
				</td>

				<td class="center">
					<?php echo $row->user_id; ?>
				</td>

				<td class="center">
					<a href="<?php echo $href; ?>" target="_blank">
						<i class="far fa-clipboard"></i>
					</a>
				</td>
				<?php print $row->tmp_html_col_after_id ?? ''; ?>
			</tr>
		<?php $i++; endforeach; ?>

		<tfoot>
			<tr>
				<?php echo $this->tmp_html_col_before_td_foot ?? ''; ?>

				<td colspan="13">
					<div class = "jshop_list_footer">
						<?php echo $pageNav->getListFooter(); ?>
					</div>

					<div class = "jshop_limit_box">
						<?php echo $pageNav->getLimitBox(); ?>
					</div>
				</td>

				<?php echo $this->tmp_html_col_after_td_foot ?? ''; ?>
			</tr>
		</tfoot>
	</table>
	</div>

	<input type="hidden" name="filter_order" value="<?php echo $this->filter_order; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo $this->tmp_html_end ?? ''; ?>
</form>
