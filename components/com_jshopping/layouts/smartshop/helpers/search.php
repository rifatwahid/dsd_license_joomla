<?php

$searchText = $displayData['searchText'] ?: '';

?>

<div class="js-stools-container-bar smartShopMiniSearch">
    <div class="filter-search btn-group pull-left">
        <input type="text" id="search_text" class="smartShopMiniSearch__search" name="search_text" placeholder="<?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?>" value="<?php echo htmlspecialchars($searchText); ?>" />
        <input type="hidden" name="search_text_reset" class="smartShopMiniSearch__search-reset" value="0">
    </div>

    <div class="btn-group pull-left">
        <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?>">
            <i class="icon-search"></i>
        </button>

        <button class="btn hasTooltip" onclick="document.querySelector('.smartShopMiniSearch #search_text').value = ''; document.querySelector('.smartShopMiniSearch .smartShopMiniSearch__search-reset').value = '1'; this.form.submit();" type="button" title="<?php echo JText::_('COM_SMARTSHOP_CLEAR'); ?>">
            <i class="icon-remove"></i>
        </button>
    </div>
</div>