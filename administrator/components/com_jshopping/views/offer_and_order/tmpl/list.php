<?php

use Joomla\CMS\Layout\LayoutHelper;

$rows = $this->rows;
$lists = $this->lists;
$pageNav = $this->pageNav;
$jshopConfig = JSFactory::getConfig();
$limitstart = JFactory::getApplication()->input->getVar('limitstart', '');
$limit = JFactory::getApplication()->input->getVar('limit', 10);
$status_id = JFactory::getApplication()->input->getVar('status_id', '');
$adv_string = '&limitstart=' . $limitstart . '&limit=' . $limit . '&status_id=' . $status_id;

$isSupportSendOfferAndOrderToUser = true;
$isSupportSendOfferAndOrderToAdmin = true;

displaySubmenuOptions("",$this->canDo);
?>
<form name="adminForm" method="post" action="index.php?option=com_jshopping&controller=offer_and_order" id="adminForm">
    <table width="100%" style="padding-bottom:5px;">
        <tr>
            <td align="right">   
                <div class="row">
                    <div class="col-6">
                        <?php if (isJoomla4()) : ?> 
                            <div id="filter-bar" class="btn-toolbar mb-3">
                                <?php 
                                    echo LayoutHelper::render('smartshop.helpers.search_j4', [
                                        'searchText' => $text_search ?? ''
                                    ], JPATH_ROOT . '/components/com_jshopping/layouts'); 
                                ?>
                            </div>
                        <?php else : ?>
                            <input type = "text" name = "text_search" value = "<?php echo htmlspecialchars($this->text_search); ?>"  onkeypress="shopSearch.searchEnterKeyPress(event,this);" />&nbsp;&nbsp;&nbsp;
                            <input type = "submit" class = "button" value = "<?php echo JText::_('COM_SMARTSHOP_SEARCH'); ?>" />
                        <?php endif; ?>
                    </div>
                    <div class="col-6">
                        <div class="inlineBlockSelectChildren">
                            <?php echo JText::_('COM_SMARTSHOP_DATE') . ": " . $lists['year'] . " : " . $lists['month'] . " : " . $lists['day']; ?>&nbsp;&nbsp;&nbsp;
                            <?php echo JText::_('COM_SMARTSHOP_USER') . "  " . $lists['user']; ?>&nbsp;&nbsp;&nbsp;
                        </div> 
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <table class = "adminlist table table-striped" width = "100%">
        <thead>
            <tr>
                <th scope="col" width = "20">
                    #
                </th>
                <th scope="col" width = "20">
                    <input type="checkbox" class="form-check-input" onclick="Joomla.checkAll(this)" value="" name="toggle" />
                </th>
                <th scope="col" width = "20">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_NUMBER'), 'order_number', $this->filter_order_Dir, $this->filter_order) ?>
                </th>
                <th scope="col" >
                    <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_PROJECTNAME'), 'projectname', $this->filter_order_Dir, $this->filter_order) ?>
                </th>
                <th scope="col" >
                    <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_USER'), 'name', $this->filter_order_Dir, $this->filter_order) ?>
                </th>
                <th scope="col" >
                    <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_EMAIL'), 'email', $this->filter_order_Dir, $this->filter_order) ?>
                </th>				
                <th scope="col" >
                    <?php echo JText::_('COM_SMARTSHOP_STATUS'); ?>
                </th>
				<?php echo $this->tmp_extra_column_headers ?? ''?>
                <?php if (isset($this->show_vendor) && $this->show_vendor) { ?>
                    <th scope="col" >
                        <?php echo JText::_('COM_SMARTSHOP_VENDOR') ?>
                    </th>
                <?php } ?>
                <?php if ($isSupportSendOfferAndOrderToUser || $isSupportSendOfferAndOrderToAdmin) { ?>
                    <th scope="col" class = "center">
                        <?php echo JText::_('COM_SMARTSHOP_ORDER_PRINT_VIEW'); ?>
                    </th>
                <?php } ?>
                <th scope="col" >
                    <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_DATE'), 'order_date', $this->filter_order_Dir, $this->filter_order) ?>
                </th>     
                <th scope="col" >
                    <?php echo JHTML::_('grid.sort', JText::_('COM_SMARTSHOP_ORDER_TOTAL'), 'order_total', $this->filter_order_Dir, $this->filter_order) ?>
                </th>
                <th scope="col" >
                    <?php echo JText::_('COM_SMARTSHOP_EDIT') ?>
                </th>  
                <th scope="col" class = "center">
                    <?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_SEND_OFFER'); ?>
                </th>  
                <th scope="col" class = "center">
                    <?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_TRIGGER_ORDER'); ?>
                </th>  
            </tr>
        </thead>
        <?php
        $i = 0;
        foreach ($rows as $row) {?>
            <tr class = "row<?php echo ($i % 2); ?>" >
                <td>
                    <?php echo $pageNav->getRowOffset($i); ?>
                </td>
                <td>
                    <?php if (isset($row->blocked) && $row->blocked) { ?>
                        <i class="fas fa-check"></i>
                    <?php } else { ?>
                        <input type = "checkbox" class="form-check-input" id = "cb<?php echo $i ?>" name = "cid[]" value = "<?php echo $row->order_id ?>" />
                    <?php } ?>
                </td>
                <td>
                    <?php echo $row->order_number; ?>       
                </td>
                <td>
                    <?php echo $row->projectname; ?>
                </td>
                <td>        
                    <?php echo $row->f_name ?? '' . ' ' . $row->m_name ?? '' . ' ' . $row->l_name ?? ''; ?>
                </td>
                <td><?php echo $row->email ?></td>				
                <td>
                    <?php
                    if (empty($row->status_email)) {
                        echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_NO_SEND_EMAIL');
                    } else {
                        echo sprintf(JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_SEND_EMAIL'), intval($row->status_email));
                    }
                    ?>
                </td>
				<?php echo $row->tmp_extra_column_cells ?? ''; ?>
                <?php if (isset($this->show_vendor) && $this->show_vendor) { ?>
                    <td>
                        <?php print $row->vendor_name; ?>
                    </td>
                <?php } ?>
                <?php if ($isSupportSendOfferAndOrderToUser || $isSupportSendOfferAndOrderToAdmin) { ?>
                    <td class = "center">
						<a href = "javascript:void window.open('<?php echo $jshopConfig->pdf_orders_live_path . "/" . $row->pdf_file ?>', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no');">
							<i class="fas fa-print"></i>
						</a>
                        <?php echo $row->_ext_order_info ?? ''; ?>
                    </td>
                <?php } ?>
                <td>
                    <?php echo $row->order_m_date; ?>
                </td>     
                <td>
                    <?php  echo formatprice($row->order_total, $row->currency_code) ?>
                </td>  
                <td align="center">
					<a href='index.php?option=com_jshopping&controller=offer_and_order&task=edit&order_id=<?php print $row->order_id; ?>'><i class="fas fa-edit"></i></a>
                </td>
                <td class="center">
                    <?php
                    $href_send_offer_email = JURI::base() . 'index.php?option=com_jshopping&controller=offer_and_order&task=send_offer_email&user_id=' . intval($row->user_id) . '&order_id=' . intval($row->order_id);
                    ?>
                    <a href="<?php echo $href_send_offer_email; ?>" class="sendOfferEmail" onclick="this.style.pointerEvents = 'none'"><i class="fas fa-paper-plane"></i>
                </td>  
                <td class="center">
                    <?php
                    $href_send_offer_email = JURI::base() . 'index.php?option=com_jshopping&controller=offer_and_order&task=trigger_offer_to_order&user_id=' . intval($row->user_id) . '&order_id=' . intval($row->order_id);
                    ?>
                    <a href="<?php echo $href_send_offer_email; ?>" class="triggerOfferToOrder" onclick="this.style.pointerEvents = 'none'"><i class="fas fa-paper-plane"></i></a>
                </td>
            </tr>
            <?php
            $i++;
        }
        ?>
        <tfoot>
            <tr>
                <?php
                $colspan = 12;
                if (isset($this->show_vendor) && $this->show_vendor)
                    $colspan++;
                if ($isSupportSendOfferAndOrderToUser || $isSupportSendOfferAndOrderToAdmin)
                    $colspan++;
                ?>
                <td colspan="<?php echo $colspan; ?>">
                    <div class = "jshop_list_footer"><?php echo $pageNav->getListFooter(); ?></div>
                    <div class = "jshop_limit_box"><?php echo $pageNav->getLimitBox(); ?></div>
                </td>
            </tr>
        </tfoot>  
    </table>

    <input type="hidden" name="filter_order" value="<?php echo $this->filter_order ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir ?>" />
    <input type = "hidden" name = "task" value = "" />
    <input type = "hidden" name = "boxchecked" value = "1" />
</form>