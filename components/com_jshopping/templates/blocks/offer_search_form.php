<form name="adminForm" id="adminForm" method="post" action="<?php echo $sefLinkSearchOffers;?>">
	<div class="js-stools-container-bar text_right">
		<div class="filter-search btn-group pull-left">
			<input type="text" id="text_search" name="text_search" placeholder="<?php print JText::_('COM_SMARTSHOP_SEARCH')?>" value="<?php echo htmlspecialchars(JFactory::getApplication()->input->getVar('text_search'));?>"  onkeypress="shopSearch.searchEnterKeyPress(event,this);"  />
		</div>

		<div class="btn-group pull-left hidden-phone">
			<button class="btn hasTooltip" type="submit" title="<?php print JText::_('COM_SMARTSHOP_SEARCH')?>">
				<i class="fas fa-search"></i>
			</button>
			<button class="btn hasTooltip" onclick="document.id('text_search').value='';this.form.submit();" type="button" title="<?php print JText::_('COM_SMARTSHOP_CLEAR_FILTERS')?>">
				<i class="fas fa-window-close"></i>
			</button>
		</div>
		
	</div>
</form>