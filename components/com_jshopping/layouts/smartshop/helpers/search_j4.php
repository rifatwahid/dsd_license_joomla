<?php
$searchText = (isset($displayData['searchText']) && $displayData['searchText']) ? $displayData['searchText'] : '';
$descrText = (isset($displayData['descriptionText']) && $displayData['descriptionText']) ? $displayData['descriptionText'] : '';
?>

<div class="smartShopMiniSearchJ4">
    <div class="input-group">
        <input type="text" id="text_search" aria-describedby="smartShopMiniSearchJ4-desc" class="smartShopMiniSearchJ4__search form-control" name="text_search" placeholder="<?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?>" value="<?php echo htmlspecialchars($searchText); ?>" onkeypress="shopSearch.searchEnterKeyPress(event,this);"/>
        <input type="hidden" name="search_text_reset" class="smartShopMiniSearch__search-reset" value="0">

        <?php if (!empty($descrText )) : ?>
            <div role="tooltip" id="smartShopMiniSearchJ4-desc">
                <?php echo $descrText; ?>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary" aria-label="<?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?>" title="<?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?>">
            <span class="icon-search" aria-hidden="true"></span>
        </button>

        <button class="btn btn-primary js-stools-btn-clear" onclick="document.querySelector('.smartShopMiniSearchJ4 #text_search').value = ''; document.querySelector('.smartShopMiniSearchJ4 .smartShopMiniSearch__search-reset').value = '1'; this.form.submit();" type="button" title="<?php echo JText::_('COM_SMARTSHOP_CLEAR'); ?>">
            <i class="icon-remove"></i>
        </button>
    </div>
</div>