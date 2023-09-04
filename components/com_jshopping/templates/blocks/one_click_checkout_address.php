<?php
$addresses = $this->addresses;
$pagination = $this->pagination;
$limitStart = $pagination->limitstart ?: 0;
?>

<div class="overlay" id="checkout_address_step" <?php if($this->display){ print "style='display: block;'";} ?>>

    <div class="_back mb-3 pt-3 pb-3 ps-1 btn btn-link ps-0" onClick="shopOneClickCheckout.closeNav('checkout_address_step');">< <?php echo JText::_('COM_SMARTSHOP_SELECT_ADDRESS'); ?></div>
<div class="container-popup addressPopup">
<!--   <form action="/index.php?option=com_jshopping&controller=one_click_checkout&task=addressData&limitstart=--><?php //echo $limitStart; ?><!--" method="post" id="adminForm" name="adminForm" class="addressModal">-->
        <div class="addressPopup__search clearfix">
            <?php
            echo JLayouthelper::render('smartshop.helpers.search', [
                'searchText' => $this->searchText
            ]);
            ?>
        </div>

        <button id="addressPopupAddNewAddress1" onClick="open('<?php echo SEFLink("index.php?option=com_jshopping&controller=user&task=addNewAddress&isCloseTabAfterSave=1", 1); ?>', '_blank', '');" class="user-addresses__new mb-3 pt-3 pb-3 ps-5 btn btn-link ps-0">
            + <?php echo JText::_('COM_SMARTSHOP_ADD_NEW_ADDRESS'); ?>
        </button>

        <?php if (empty($addresses)) : ?>
            <div class="alert alert-no-items addressPopup__no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php else : ?>

            <!-- List of addresses -->
            <div class="addressPopup__tbody">
                <?php foreach ($addresses as $i => $userAddress) : ?>
                    <div class="user-address border border-secondary mb-3 pt-2 pb-2 ps-5 pe-3 row<?php echo $i % 2; ?> addressPopup__address" data-address-id="<?php echo $userAddress->address_id; ?>" onclick="shopUserAddressesPopup.runAddressHandler(this);">						
						<?php if ($userAddress->firma_name!=""){?>
							<p class="user-address__firma">
								<?php echo "$userAddress->firma_name"; ?>
							</p>
						<?php } ?>					
                        <p class="user-address__name">
                            <?php echo "$userAddress->l_name $userAddress->f_name"; ?>
                        </p>
                        <p class="user-address__address mb-1">
                            <?php
                            $address = [
                                trim($userAddress->street . ' ' . $userAddress->street_nr),
                                trim($userAddress->zip . ' ' . $userAddress->city),
                                trim($userAddress->country)
                            ];

                            $i = 0;
                            foreach ($address as $item) {
                                $i++;

                                if (!empty($item)) {
                                    echo $item;

                                    if (!empty($address[$i])) {
                                        echo ', ';
                                    }
                                }
                            }
                            ?>
                        </p>

                        <button class="user-address__edit btn btn-link ps-0" onclick="open('<?php echo SEFLink("index.php?option=com_jshopping&controller=user&task=editAddress&editId={$userAddress->address_id}&isCloseTabAfterSave=1", 1); ?>', '_blank', '');event.stopPropagation();" target="_blank"><?php echo JText::_('COM_SMARTSHOP_EDIT'); ?></button>

                        <input type="hidden" class="addressPopup__address-id" value="<?php echo $userAddress->address_id; ?>">
                    </div>
                <?php endforeach;?>
            </div>

            <!-- Pagination -->
            <div class="addressPopup__pagination">
                <div class="addressPopup__pagination-links"><?php echo $pagination->getPagesLinks(); ?></div>
                <div class="addressPopup__pagination-limit"><?php echo $pagination->getLimitBox(); ?></div>
            </div>
        <?php endif; ?>
<!--    </form>-->
</div>
</div>