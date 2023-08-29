<?php

use Joomla\CMS\Language\Text;

$jshopConfig = $additionalData['jshopConfig'];
?>

<?php if (!empty($orderDescription)) : ?>
    <br>

    <div class="orderDescription">
        <?php echo $orderDescription; ?>
    </div>
<?php endif; ?>

<!-- Info about bank -->
<?php if ($isShowBankInfo && $isBankSectionNotEmpty && $isIntermSectionNotEmpty) : ?>
    <br>

    <table class="bankInfoWrapper">
        <tr>
            <td width="40%"></td>
            <td width="60%">
                <table class="bankInfo" cellpadding="3" border="1" nobr="true">

                    <!-- Bank section -->
                    <?php if ($isBankSectionNotEmpty) : ?>
                        <!-- Title -->
                        <tr class="bankInfo__title bankInfo__row" bgcolor="#c8c8c8">
                            <td colspan="2" class="bankInfo__name">
                                <?php echo JText::_('COM_SMARTSHOP_BANK'); ?>
                            </td>
                        </tr>

                        <!-- Name -->
                        <?php if (!empty($vendorInfo->benef_bank_info)) : ?>
                            <tr class="bankInfo__row bankInfoBenef">
                                <td class="bankInfo__name bankInfoBenef__name">
                                    <?php echo JText::_('COM_SMARTSHOP_BENEF_BANK_NAME'); ?>
                                </td>

                                <td class="bankInfo__value bankInfoBenef__value">
                                    <?php echo $vendorInfo->benef_bank_info; ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <!-- BIC -->
                        <?php if (!empty($vendorInfo->benef_bic)) : ?>
                            <tr class="bankInfo__row bankInfoBic">
                                <td class="bankInfo__name bankInfoBic__name">
                                    <?php echo JText::_('COM_SMARTSHOP_BENEF_BIC'); ?>
                                </td>

                                <td class="bankInfo__value bankInfoBic__value">
                                    <?php echo $vendorInfo->benef_bic; ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <!-- Conto -->
                        <?php if (!empty($vendorInfo->benef_conto)) : ?>
                            <tr class="bankInfo__row bankInfoConto">
                                <td class="bankInfo__name bankInfoConto__name">
                                    <?php echo JText::_('COM_SMARTSHOP_BENEF_CONTO'); ?>
                                </td>

                                <td class="bankInfo__value bankInfoConto__value">
                                    <?php echo $vendorInfo->benef_conto; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    
                        <!-- Payee -->
                        <?php if (!empty($vendorInfo->benef_payee)) : ?>
                            <tr class="bankInfo__row bankInfoPayee">
                                <td class="bankInfo__name bankInfoPayee__name">
                                    <?php echo JText::_('COM_SMARTSHOP_BENEF_PAYEE'); ?>
                                </td>

                                <td class="bankInfo__value bankInfoPayee__value">
                                    <?php echo $vendorInfo->benef_payee; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        <!-- Iban -->
                        <?php if (!empty($vendorInfo->benef_iban)) : ?>
                            <tr class="bankInfo__row bankInfoIban">
                                <td class="bankInfo__name bankInfoIban__name">
                                    <?php echo JText::_('COM_SMARTSHOP_BENEF_IBAN'); ?>
                                </td>

                                <td class="bankInfo__value bankInfoIban__value">
                                    <?php echo $vendorInfo->benef_iban; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        <!-- Bic bic -->
                        <?php if (!empty($vendorInfo->benef_bic_bic)) : ?>
                            <tr class="bankInfo__row bankInfoBicBic">
                                <td class="bankInfo__name bankInfoBicBic__name">
                                    <?php echo JText::_('COM_SMARTSHOP_BIC_BIC'); ?>
                                </td>

                                <td class="bankInfo__value bankInfoBicBic__value">
                                    <?php echo $vendorInfo->benef_bic_bic; ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <!-- Swift -->
                        <?php if (!empty($vendorInfo->benef_swift)) : ?>
                            <tr class="bankInfo__row bankInfoBicBic">
                                <td class="bankInfo__name bankInfoBicBic__name">
                                    <?php echo JText::_('COM_SMARTSHOP_BENEF_SWIFT'); ?>
                                </td>

                                <td class="bankInfo__value bankInfoBicBic__value">
                                    <?php echo $vendorInfo->benef_swift; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>

                    
                    <!-- Interm -->
                    <?php if ($isIntermSectionNotEmpty) : ?>
                        <!-- Title -->
                        <tr class="bankInfo__title bankInfo__row" bgcolor="#c8c8c8">
                            <td colspan="2" class="bankInfo__name">
                                <?php echo JText::_('COM_SMARTSHOP_INTERM_BANK'); ?>
                            </td>
                        </tr>

                        <!-- Name -->
                        <?php if (!empty($vendorInfo->interm_name)) : ?>
                            <tr class="bankInfo__row bankInfoInterm">
                                <td class="bankInfo__name bankInfoInterm__name">
                                    <?php echo JText::_('COM_SMARTSHOP_INTERM_NAME'); ?>
                                </td>

                                <td class="bankInfo__value bankInfoInterm__value">
                                    <?php echo $vendorInfo->interm_name; ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <!-- Swift -->
                        <?php if (!empty($vendorInfo->interm_swift)) : ?>
                            <tr class="bankInfo__row bankInfoIntermSwift">
                                <td class="bankInfo__name bankInfoIntermSwift__name">
                                    <?php echo JText::_('COM_SMARTSHOP_INTERM_SWIFT'); ?>
                                </td>

                                <td class="bankInfo__value bankInfoIntermSwift__value">
                                    <?php echo $vendorInfo->interm_swift; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
    </table>
<?php endif; ?>

<?php if (!empty(trim($vendorInfo->additional_information))) : ?>
    <br>

    <div class="orderAdditionalInform">
        <?php echo $vendorInfo->additional_information; ?>
    </div>
<?php endif; ?>

<?php if ($jshopConfig->show_return_policy_text_in_pdf && !empty($returnPolicyText)) : ?>
    <br>

    <div class="orderReturnPolicy">
        <?php echo $returnPolicyText; ?>
    </div>
<?php endif; ?>

<br>

<?php 
if ($isShowEuB2BTagMsg) {
    echo Text::_('COM_SMARTSHOP_INVOICE_EU_LAW_TAX_FREE');
}
?>

<br>

<?php 
    echo Text::_('COM_SMARTSHOP_INVOICE_NOTICE_SERVICE_DATE');
?>