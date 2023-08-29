<?php
$order = $this->order;
$order_history = $this->order_history;
$order_item = $this->order_items;
$lists = $this->lists;
$print = $this->print;
?>
<div class="jshop_edit offer_and_order_show">
<form action = "index.php?option=com_jshopping&controller=orders" method = "post" name = "adminForm" id="adminForm">
<table class = "adminlist" width = "100%">
<tr>
  <td width = "50%" style = "vertical-align:top;padding-top:14px;">
    <table class = "adminlist table table-striped">
    <thead>    
      <tr>
        <th colspan = "2">
          <?php echo JText::_('COM_SMARTSHOP_ORDER_PURCHASE');?>
        </th>
      </tr>
     </thead> 
      <tr>
        <td width = "50%">
          <b><?php echo JText::_('COM_SMARTSHOP_NUMBER');?></b>
        </td>
        <td>
          <?php echo $order->order_number;?>
        </td>
      </tr>
      <tr>
        <td width = "50%">
          <b><?php echo JText::_('COM_SMARTSHOP_DATE');?></b> 
       </td>
        <td>
          <?php echo formatdate($order->order_date,1);?>
        </td>
      </tr>
      <tr>
        <td>
          <b><?php echo JText::_('COM_SMARTSHOP_STATUS');?></b> 
       </td>
        <td>
          <?php echo $order->status_name;?>
        </td>
      </tr>
      <tr>
        <td>
          <b><?php echo JText::_('COM_SMARTSHOP_IPADRESS');?></b>
       </td>
        <td>
          <?php echo $order->ip_address;?>
        </td>
      </tr>
    </table>
  </td>
  <td width = "50%" style = "vertical-align: top">
      <?php
       if (!$print){
        jimport('joomla.html.pane');   
        $pane = JPane::getInstance('Tabs');
        echo $pane->startPane('orderPane');
                
        if ($this->display_block_change_order_status){
        echo $pane->startPanel(JText::_('COM_SMARTSHOP_STATUS_CHANGE'), 'first-page');
      ?>
        <table width = "100%">
          <tr>
            <th colspan = "2" align = "center">
              <?php echo JText::_('COM_SMARTSHOP_STATUS_CHANGE'); ?>:
            </th>
          </tr>
          <tr>
            <td colspan = "2">
              <?php echo JText::_('COM_SMARTSHOP_ORDER_STATUS'); ?>
              <?php echo $lists['status'];?>
              <input type = "button" class = "button" name = "update_status" onclick = "shopOrderAndOffer.verifyStatus(<?php echo $order->order_status?>, <?php echo $order->order_id?>, '<?php echo JText::_('COM_SMARTSHOP_CHANGE_ORDER_STATUS');?>', 1)" value = "<?php echo JText::_('COM_SMARTSHOP_UPDATE_STATUS'); ?>" />
            </td>
          </tr>
          <tr>
            <td colspan = "2">
              <table>
                <tr>
                  <td>
                    <?php echo JText::_('COM_SMARTSHOP_COMMENT');?>:
                  </td>
                  <td>
                    <textarea id = "comments" name = "comments"></textarea>
                  </td>
                  <td>
                    <input type = "checkbox" class = "inputbox"  name = "notify" id = "notify" /><label for = "notify">  <?php echo JText::_('COM_SMARTSHOP_NOTIFY_USER');?></label><br />
                    <input type = "checkbox" class = "inputbox"  name = "include" id = "include" /><label for = "include">  <?php echo JText::_('COM_SMARTSHOP_INCLUDE_COMMENT');?></label>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      <?php
        echo $pane->endPanel();
        }
        echo $pane->startPanel(JText::_('COM_SMARTSHOP_ORDER_HISTORY'), 'second-page');
      ?>
        <table>
            <tr class = "bold">
              <td>
                <?php echo JText::_('COM_SMARTSHOP_DATE_ADDED');?>
              </td>
              <td>
                <?php echo JText::_('COM_SMARTSHOP_NOTIFY_CUSTOMER');?>
              </td>
              <td>
                <?php echo JText::_('COM_SMARTSHOP_STATUS');?>
              </td>
              <td>
                <?php echo JText::_('COM_SMARTSHOP_COMMENT');?>
              </td>
            </tr>
          <?php foreach ($order_history as $history) {?>
            <tr>
              <td>
                <?php echo $history->status_date_added?>
              </td>
              <td class = "center">
                <?php $notify_customer = ($history->customer_notify) ? ('<i class="fas fa-check"></i>'): ('<i class="fas fa-minus-circle"></i>');?>
                <?php echo $notify_customer;?>
              </td>
              <td>
                <?php echo $history->status_name?>
              </td>
              <td>
                <?php echo $history->comments?>
              </td>
            </tr>
          <?php }?>
        </table>
      <?php
       echo $pane->endPanel();
       echo $pane->endPane();
       }
      ?>
  </td>
</tr>
</table>
<br/>

<table width="100%">
<tr>
    <td width="50%" valign="top">
        <table width = "100%" class = "adminlist">
        <thead>
        <tr>
          <th colspan="2" align="center"><?php print JText::_('COM_SMARTSHOP_BILL_TO'); ?></th>
        </tr>
        </thead>
        <?php if ($this->config_fields['title']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_USER_TITLE'); ?>:</b></td>
          <td><?php print $this->order->title?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['firma_name']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_FIRMA_NAME'); ?>:</b></td>
          <td><?php print $this->order->firma_name?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['f_name']['display']){?>
        <tr>
          <td width = "40%"><b><?php print JText::_('COM_SMARTSHOP_FULL_NAME'); ?>:</b></td>
          <td width = "60%"><?php echo $this->order->f_name . ' ' . $this->order->m_name . ' ' . $this->order->l_name; ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['client_type']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_CLIENT_TYPE'); ?>:</b></td>
          <td><?php print $this->order->client_type_name;?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['firma_code']['display'] && $this->order->client_type==2){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_FIRMA_CODE'); ?>:</b></td>
          <td><?php print $this->order->firma_code?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['tax_number']['display'] && $this->order->client_type==2){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_VAT_NUMBER'); ?>:</b></td>
          <td><?php print $this->order->tax_number?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['home']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_FIELD_HOME'); ?>:</b></td>
          <td><?php print $this->order->home?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['apartment']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_FIELD_APARTMENT'); ?>:</b></td>
          <td><?php print $this->order->apartment?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['street']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_STREET_NR'); ?>:</b></td>
          <td><?php print $this->order->street?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['city']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_CITY'); ?>:</b></td>
          <td><?php print $this->order->city?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['state']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_STATE'); ?>:</b></td>
          <td><?php print $this->order->state?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['zip']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_ZIP'); ?>:</b></td>
          <td><?php print $this->order->zip?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['country']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_COUNTRY'); ?>:</b></td>
          <td><?php print $this->order->country?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['phone']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_TELEFON'); ?>:</b></td>
          <td><?php print $this->order->phone?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['mobil_phone']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_MOBIL_PHONE'); ?>:</b></td>
          <td><?php print $this->order->mobil_phone?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['fax']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_FAX'); ?>:</b></td>
          <td><?php print $this->order->fax?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['email']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_EMAIL'); ?>:</b></td>
          <td><?php print $this->order->email?></td>
        </tr>
        <?php } ?>
        
        <?php if ($this->config_fields['ext_field_1']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_EXT_FIELD_1'); ?>:</b></td>
          <td><?php print $this->order->ext_field_1?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['ext_field_2']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_EXT_FIELD_2'); ?>:</b></td>
          <td><?php print $this->order->ext_field_2?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['ext_field_3']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_EXT_FIELD_3'); ?>:</b></td>
          <td><?php print $this->order->ext_field_3?></td>
        </tr>
        <?php } ?>                        
        </table>
    </td>
    <td width="50%"  valign="top">
    <?php if ($this->count_filed_delivery >0) {?>
        <table width = "100%" class = "adminlist">
        <thead>
        <tr>
          <th colspan="2" align="center"><?php print JText::_('COM_SMARTSHOP_SHIP_TO'); ?></th>
        </tr>
        </thead>
        <?php if ($this->config_fields['d_title']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_USER_TITLE'); ?>:</b></td>
          <td><?php print $this->order->d_title?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_firma_name']['display']){?>
        <tr>
            <td><b><?php print JText::_('COM_SMARTSHOP_FIRMA_NAME'); ?>:</b></td>
            <td><?php print $this->order->d_firma_name?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_f_name']['display']){?>
        <tr>
            <td width = "40%"><b><?php print JText::_('COM_SMARTSHOP_FULL_NAME'); ?>:</b></td>
            <td width = "60%"><?php print $this->order->d_f_name?> <?php print $this->order->d_l_name?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_home']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_FIELD_HOME'); ?>:</b></td>
          <td><?php print $this->order->d_home?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_apartment']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_FIELD_APARTMENT'); ?>:</b></td>
          <td><?php print $this->order->d_apartment?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_street']['display']){?>
        <tr>
            <td><b><?php print JText::_('COM_SMARTSHOP_STREET_NR'); ?>:</b></td>
            <td><?php print $this->order->d_street?><br></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_city']['display']){?>
        <tr>
            <td><b><?php print JText::_('COM_SMARTSHOP_CITY'); ?>:</b></td>
            <td><?php print $this->order->d_city?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_state']['display']){?>
        <tr>
            <td><b><?php print JText::_('COM_SMARTSHOP_STATE'); ?>:</b></td>
            <td><?php print $this->order->d_state?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_zip']['display']){?>
        <tr>
            <td><b><?php print JText::_('COM_SMARTSHOP_ZIP'); ?>:</b></td>
            <td><?php print $this->order->d_zip ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_country']['display']){?>
        <tr>
            <td><b><?php print JText::_('COM_SMARTSHOP_COUNTRY'); ?>:</b></td>
            <td><?php print $this->order->d_country ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_phone']['display']){?>
        <tr>
            <td><b><?php print JText::_('COM_SMARTSHOP_TELEFON'); ?>:</b></td>
            <td><?php print $this->order->d_phone ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_mobil_phone']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_MOBIL_PHONE'); ?>:</b></td>
          <td><?php print $this->order->d_mobil_phone?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_fax']['display']){?>
        <tr>
        <td><b><?php print JText::_('COM_SMARTSHOP_FAX'); ?>:</b></td>
        <td><?php print $this->order->d_fax ?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_email']['display']){?>
        <tr>
        <td><b><?php print JText::_('COM_SMARTSHOP_EMAIL'); ?>:</b></td>
        <td><?php print $this->order->d_email ?></td>
        </tr>
        <?php } ?>                            
        <?php if ($this->config_fields['d_ext_field_1']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_EXT_FIELD_1'); ?>:</b></td>
          <td><?php print $this->order->d_ext_field_1?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_ext_field_2']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_EXT_FIELD_2'); ?>:</b></td>
          <td><?php print $this->order->d_ext_field_2?></td>
        </tr>
        <?php } ?>
        <?php if ($this->config_fields['d_ext_field_3']['display']){?>
        <tr>
          <td><b><?php print JText::_('COM_SMARTSHOP_EXT_FIELD_3'); ?>:</b></td>
          <td><?php print $this->order->d_ext_field_3?></td>
        </tr>
        <?php } ?>
      </table>
    <?php } ?>  
    </td>
</tr>
</table>

<br/>
<table class = "adminlist" width = "100%">
<thead>
<tr>
 <th>
   <?php echo JText::_('COM_SMARTSHOP_NAME_PRODUCT'); ?>
 </th>
 <?php if ($this->config->show_product_code_in_order){?>
 <th>
   <?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT'); ?>
 </th>
 <?php }?>
 <?php if ($this->config->admin_show_vendors){?>
 <th>
   <?php echo JText::_('COM_SMARTSHOP_VENDOR'); ?>
 </th>
 <?php }?>
 <th>
   <?php echo JText::_('COM_SMARTSHOP_PRICE'); ?>
 </th>
 <th>
   <?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?>
 </th> 
 <th>
   <?php echo JText::_('COM_SMARTSHOP_TOTAL'); ?>
 </th>
</tr>
</thead>
<?php foreach ($order_item as $item){ ?>
<tr>
 <td>
   <a target="_blank" href="index.php?option=com_jshopping&controller=products&task=edit&product_id=<?php print $item->product_id?>">
    <?php echo $item->product_name?>
   </a><br />
   <?php print sprintAtributeInOrder($item->product_attributes).sprintFreeAtributeInOrder($item->product_freeattributes);?>
 </td>
 <?php if ($this->config->show_product_code_in_order){?>
 <td>
   <?php echo $item->product_ean?>
 </td>
 <?php }?>
 <?php if ($this->config->admin_show_vendors){?>
 <td>
   <?php echo $this->order_vendors[$item->vendor_id]->f_name." ".$this->order_vendors[$item->vendor_id]->l_name; ?>
 </td>
 <?php }?>
 <td>
   <?php echo formatprice($item->product_item_price, $order->currency_code);?>
   <?php print $item->_ext_price_html?>
 </td>
 <td>
   <?php echo formatqty($item->product_quantity)?><?php print $item->_qty_unit?>
 </td> 
 <td>
   <?php echo formatprice($item->product_quantity * $item->product_item_price, $order->currency_code);?>
   <?php print $item->_ext_price_total_html?>
 </td>
</tr>
<?php }?>
</table>

<?php if (!$this->display_info_only_product){?>
<table class = "adminlist" width = "100%">
<tr>
 <td colspan = "5" style = "height: 20px">
    <?php if ($this->config->show_weight_order){?>  
    <div style="text-align:right;">
        <i><?php print JText::_('COM_SMARTSHOP_WEIGHT_PRODUCTS'); ?>: <span><?php print formatweight($this->order->weight);?></span></i>
    </div><br/>
  <?php }?>
 </td>
</tr>
<tr class = "bold">
 <td colspan = "4" class = "right">
    <?php echo JText::_('COM_SMARTSHOP_SUBTOTAL'); ?>
 </td>
 <td class = "left" width="18%">
   <?php echo formatprice($order->order_subtotal, $order->currency_code);?><?php print $this->_tmp_ext_subtotal?>
 </td>
</tr>
<?php if ($order->order_discount > 0){?>
<tr class = "bold">
 <td colspan = "4" class = "right">
    <?php echo JText::_('COM_SMARTSHOP_COUPON_DISCOUNT'); ?>
 </td>
 <td class = "left">
   <?php echo formatprice(-$order->order_discount, $order->currency_code);?><?php print $this->_tmp_ext_discount?>
 </td>
</tr>
<?php } ?>

<?php if (!$this->config->without_shipping || $order->order_shipping > 0){?>
<tr class = "bold">
 <td colspan = "4" class = "right">
    <?php echo JText::_('COM_SMARTSHOP_SHIPPING_PRICE'); ?>
 </td>
 <td class = "left">
   <?php echo formatprice($order->order_shipping, $order->currency_code);?><?php print $this->_tmp_ext_shipping?>
 </td>
</tr>
<?php } ?>

<?php if ($order->order_payment > 0){?>
<tr class = "bold">
 <td colspan = "4" class = "right">
     <?php print $order->payment_name;?>
 </td>
 <td class = "left">
   <?php echo formatprice($order->order_payment, $order->currency_code);?><?php print $this->_tmp_ext_payment?>
 </td>
</tr>
<?php } ?>

<?php if (!$this->config->hide_tax){?>
    <?php foreach($order->order_tax_list as $percent=>$value){?>
      <tr class="bold">
        <td  colspan = "4" class = "right">
          <?php print displayTotalCartTaxName($order->display_price);?>
          <?php print $percent."%"?>
        </td>
        <td  class = "left">
          <?php print formatprice($value, $order->currency_code);?><?php print $this->_tmp_ext_tax[$percent]?>
        </td>
      </tr>
    <?php }?>
<?php }?>
<tr class = "bold">
 <td colspan = "4" class = "right">
    <?php echo JText::_('COM_SMARTSHOP_TOTAL'); ?>
 </td>
 <td class = "left">
   <?php echo formatprice($order->order_total, $order->currency_code);?><?php print $this->_tmp_ext_total?>
 </td>
</tr>
</table>
<?php }?>
<br/>

<table class="adminlist">
<thead>
<tr>
    <?php if (!$this->config->without_shipping){?>
    <th width = "33%">
    <?php echo JText::_('COM_SMARTSHOP_SHIPPING_INFORMATION'); ?>
    </th>
    <?php }?>
    <?php if (!$this->config->without_payment){?>
    <th width = "33%">
    <?php echo JText::_('COM_SMARTSHOP_PAYMENT_INFORMATION'); ?>
    </th>
    <?php } ?>
    <th width = "34%">
    <?php echo JText::_('COM_SMARTSHOP_CUSTOMER_COMMENT'); ?>
    </th>
</tr>
</thead>
<tr>
    <?php if (!$this->config->without_shipping){?>
    <td valign="top">
        <div style="padding-bottom:4px;"><?php echo $order->shipping_info?></div>
        <?php if ($this->config->delivery_times_on_product_page){?>
        <div><?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME') . ': ' . $order->delivery_time_name?></div>
        <?php }?>
    </td>
    <?php } ?>
    <?php if (!$this->config->without_payment){?>
    <td valign="top">
        <div style="padding-bottom:4px;"><?php print $order->payment_name; ?></div>
        <div><i><?php echo nl2br($order->payment_params)?></i></div>
    </td>
    <?php } ?>
    <td valign="top"><?php echo $order->order_add_info?></td>    
</tr>
</table>

</form>
</div>