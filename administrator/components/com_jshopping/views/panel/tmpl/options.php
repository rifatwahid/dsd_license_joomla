<?php 
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;

?>
<form name="adminForm" id="adminForm" method="post" action="">
	<?php print $this->tmp_html_start ?? ''?>
	<div id="filter-bar" class="btn-toolbar">
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
	
	<div class="striped-block">
		<?php
		if (!empty($this->rows))
			foreach($this->rows as $row){?>
				  <div class="row<?php echo $i % 2;?>">
					 <a href="<?php echo $row['links'];?>"><?php echo $row['title'];?></a>
				  </div>
				<?php
				$i++;
			}
		?>
	</div>

	<div class="flex-container" id="cpanel">
	
		<?php displayOptionPanelIco($this->canDo); ?>
		
	</div>

	<input type="hidden" name="task" value="search" />
	<?php echo $this->tmp_html_end ?? ''; ?>
</form>