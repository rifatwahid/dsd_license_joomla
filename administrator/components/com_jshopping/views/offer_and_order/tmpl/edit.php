<?php 
defined('_JEXEC') or die('Restricted access');
global $num;

Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT_SITE . '/helpers/html/');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
$order = $this->order;
$order_history = $this->order_history ?? [];
$order_item = $this->order_items;
$lists = $this->lists ?? [];
$config_fields = $this->config_fields;
JHtmlBootstrap::modal('a.modal');
 $num = count($order_item);
 $row[$num] = $num++;
?>
<script type="text/javascript">

var admin_show_attributes=<?php print $this->config->admin_show_attributes?>;
var admin_show_freeattributes=<?php print $this->config->admin_show_freeattributes?>;
var admin_order_edit_more = <?php print $this->config->admin_order_edit_more?>;
var hide_tax = <?php print intval($this->config->hide_tax)?>;
var lang_load='<?php print JText::_('COM_SMARTSHOP_LOAD')?>';
var lang_price='<?php print JText::_('COM_SMARTSHOP_PRICE')?>';
var lang_tax='<?php print JText::_('COM_SMARTSHOP_TAX')?>';
var lang_weight='<?php print JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT')?>';
var lang_vendor='<?php print JText::_('COM_SMARTSHOP_VENDOR')?>';
function selectProductBehaviour(pid, eName){
  let currencyIdEl = document.querySelector('#currency_id');

  if (currencyIdEl) {
    let currency_id = currencyIdEl.value;
    shopOrderAndOffer.loadProductInfo(pid, eName, currency_id);
    setTimeout(shopOrderAndOffer.calculateTax, 900);
  }

  let closeElems = document.querySelectorAll('.modal-dialog .close');
  if (closeElems) {
    closeElems.forEach(function (item) {
      item.click();
    });
  }
}

var userinfo_fields = {};
<?php foreach ($config_fields as $k=>$v){
    if ($v['display']) echo "userinfo_fields['".$k."']='';";
}?>
var userinfo_ajax = null;
var userinfo_link = "<?php print "index.php?option=com_jshopping&controller=users&task=get_userinfo&ajax=1"?>";


if ( typeof order_tax_calculate != 'function' ) {
  function order_tax_calculate(){
    var user_id = document.querySelector('#user_id');
    var product = getListOrderItems();
    var data_order = getOrderData();
    data_order['product'] = product;
    
    var url = 'index.php?option=com_jshopping&controller=offer_and_order&task=loadtaxorder';
    if (user_id && user_id>0){
      url+='&admin_load_user_id='+user_id;
    }

    fetch(url, {
      method: 'POST',
      body: JSON.stringify({'data_order': data_order})
    })
    .then(response => response.json())
    .then(json => {
      let taxPercentEl = document.querySelector('input[name="tax_percent[]"]');
      if (taxPercentEl) {
        taxPercentEl.parentNode.parentNode.remove();
      }

      for (var i=0; i<json.length; i++){
        var html="<tr class='bold'>";
        html+='<td class="right"><input type="text" class="small3" name="tax_percent[]" value="'+json[i]['tax']+'"/> %</td>';
        html+='<td class="left"><input type="text" class="small3" name="tax_value[]" onkeyup="shopOrderAndOffer.updateOrderTotal();" value="'+json[i]['value']+'"/></td>';
        html+='</tr>';

        let taxRowEl = document.querySelector('#row_button_add_tax');
        if (taxRowEl) {
          taxRowEl.insertAdjacentHTML('beforeBegin', html);
        }
      }

      updateOrderTotalValue();
    });
  }
}

if (typeof getListOrderItems != 'function') {
  function getListOrderItems(){
      var max_count = end_number_order_item + 1;
      var product = {};
      for(var a=1; a<=max_count; a++){
          detal_product = {};
          let productIdEl = document.querySelector('input[name="product_id['+ a +']"]');
          if (!productIdEl || !productIdEl.value) continue;

          detal_product['product_id'] = product_id;
          detal_product['product_tax'] = document.querySelector('input[name="product_tax['+a+']"]').value;
          detal_product['product_name'] = document.querySelector('input[name="product_name['+a+']"]').value;
          detal_product['product_ean'] = document.querySelector('input[name="product_ean['+a+']"]').value;
          detal_product['product_attributes'] = document.querySelector('input[name="product_attributes['+a+']"]').value;
          detal_product['product_freeattributes'] = document.querySelector('input[name="product_freeattributes['+a+']"]').value;
          detal_product['thumb_image'] = document.querySelector('input[name="thumb_image['+a+']"]').value;
          detal_product['weight'] = document.querySelector('input[name="weight['+a+']"]').value;
          detal_product['delivery_times_id'] = document.querySelector('input[name="delivery_times_id['+a+']"]').value;
          detal_product['vendor_id'] = document.querySelector('input[name="vendor_id['+a+']"]').value;
          detal_product['product_quantity'] = document.querySelector('input[name="product_quantity['+a+']"]').value;
          detal_product['product_item_price'] = document.querySelector('input[name="product_item_price['+a+']"]').value;
          detal_product['order_item_id'] = document.querySelector('input[name="order_item_id['+a+']"]').value;
          product[a] = detal_product;
      }
      return product;
  }
}

if (typeof getOrderData != 'function') {
  function getOrderData(){
      var data_order = {};
      let elems = document.querySelectorAll('.jshop_address input, .jshop_address select');
      if (elems) {
        elems.forEach(function (item) {
          let name = item.getAttribute('name');
          data_order[name] = item.value;
        });
      }
      
      data_order['user_id'] = document.querySelector('#user_id').value;
      data_order['currency_id'] = document.querySelector('select[name="currency_id"]').value;
      data_order['display_price'] = document.querySelector('select[name="display_price"]').value;
      data_order['lang'] = document.querySelector('select[name="lang"]').value;
      data_order['shipping_method_id'] = document.querySelector('select[name="shipping_method_id"]').value;
      data_order['payment_method_id'] = document.querySelector('select[name="payment_method_id"]').value;
      data_order['order_delivery_times_id'] = document.querySelector('select[name="order_delivery_times_id"]').value;
      data_order['order_payment'] = document.querySelector('input[name="order_payment"]').value;
      data_order['order_shipping'] = document.querySelector('input[name="order_shipping"]').value;
      data_order['order_package'] = document.querySelector('input[name="order_package"]').value;
      data_order['order_discount'] = document.querySelector('input[name="order_discount"]').value;
      data_order['coupon_code'] = document.querySelector('input[name="coupon_code"]').value;
      return data_order;
  }
}

document.addEventListener('DOMContentLoaded', function () {
  let productTaxEl = document.querySelector('input[name*=product_tax]');

  if (productTaxEl) {
    productTaxEl.addEventListener('focusout', function () {
      shopOrderAndOffer.updateOrderSubtotal();
      shopOrderAndOffer.calculateTax();  
    });
  }
});
</script>
<div class="jshop_edit form-horizontal offer_and_order_edit">
<form action="index.php?option=com_jshopping" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
<?php if (!isset($this->display_info_only_product) || !$this->display_info_only_product){?>

<div class="row">
  <?php if (!empty($this->users_list_select)) : ?>
    <div class="col-6">
      <div class="row-fluid">
        <div class="span2"><?php print JText::_('COM_SMARTSHOP_USER')?>:</div>
        <div class="span10"><?php echo $this->users_list_select;?></div>
      </div>
    </div>
  <?php endif; ?>
  <div class="col-6">
    <?php if ($this->config->date_invoice_in_invoice){?>
      <div class="row-fluid">
          <div class="span2"><?php print JText::_('COM_SMARTSHOP_INVOICE_DATE')?>:</div>
          <div class="span10"><?php echo JHTML::_('calendar', getDisplayDate($order->invoice_date ?? 0, $this->config->store_date_format), 'invoice_date', 'invoice_date', $this->config->store_date_format , array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19'));?></div>
      </div>
    <?php }?>
  </div>
</div>

<div class="form-group row align-items-top ">
	<div  class="col-sm-6 col-md-6 col-xl-6 col-12 col-form-label ">
        <div class="admintable striped-block jshops_edit mt-3">
			<div class="form-group row align-items-center">
				<div  class="col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_BILL_TO') ?>
				</div>
			</div>
        
    <?php if ($config_fields['title']['display']){?>
        <div class="form-group row align-items-center">
			<label for="title" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_USER_TITLE')?>:</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<?php print $this->select_titles?></div>
		</div>
        <?php } ?>
        <?php if ($config_fields['firma_name']['display']){?>
        <div class="form-group row align-items-center">
			<label for="firma_name" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIRMA_NAME')?>:</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="firma_name" id="firma_name" value="<?php print $order->firma_name?>" />
			</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['f_name']['display']){?>
        <div class="form-group row align-items-center">
			<label for="f_name" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FULL_NAME')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
          <div class="input-group mb-2">
            <input type="text" class="form-control" id="f_name" name="f_name" value="<?php print $order->f_name ?? ''?>" /> 
            <input type="text" class="form-control" name="m_name" value="<?php print $order->m_name ?? ''?>" />
          </div>

          <input type="text" class="form-control" name="l_name" value="<?php print $order->l_name ?? ''?>" />	
			</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['client_type']['display']){?>
        <div class="form-group row align-items-center">
			<label for="client_type" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_CLIENT_TYPE')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<?php print $this->select_client_types;?>
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['firma_code']['display']){?>
        <div class="form-group row align-items-center" id="tr_field_firma_code" <?php if ($config_fields['client_type']['display'] && $order->client_type!="2"){?>style="display:none;"<?php } ?>>
			<label for="firma_code" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIRMA_CODE')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="firma_code" id="firma_code" value="<?php print $order->firma_code ?? ''?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['tax_number']['display']){?>		
		<div id="tr_field_tax_number" class="form-group row align-items-center" <?php if ($config_fields['client_type']['display'] && $order->client_type!="2"){?>style="display:none;"<?php } ?>>
			<label for="tax_number" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_VAT_NUMBER')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="tax_number" id="tax_number" value="<?php print $order->tax_number ?? ''?>" />
        	</div>
		</div>
        <?php } ?>
    <?php if ($config_fields['birthday']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="birthday" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_BIRTHDAY')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<?php echo JHTML::_('calendar', $order->birthday, 'birthday', 'birthday', $this->config->field_birthday_format, array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19'));?>
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['home']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="home" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIELD_HOME')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="home" id="home" value="<?php print $order->home ?? ''?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['apartment']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="apartment" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIELD_APARTMENT')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" id="apartment" name="apartment" value="<?php print $order->apartment ?? ''?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['street']['display']){?>        
		<div class="form-group row align-items-center">
			<label for="street" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_STREET_NR')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
          <div class="input-group">
            <input type="text" class="form-control" name="street" id="street" value="<?php print $order->street ?? ''?>" />
            <?php if ($config_fields['street_nr']['display']){?>
            <input type="text" class="form-control" name="street_nr" value="<?php print $order->street_nr ?? ''?>" />
            <?php }?>    
          </div>     
      </div>
		</div>
        <?php } ?>
        <?php if ($config_fields['city']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="city" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_CITY')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="city" id="city" value="<?php print $order->city?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['state']['display']){?>        
		<div class="form-group row align-items-center">
			<label for="state" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_STATE')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="state" id="state" value="<?php print $order->state?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['zip']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="zip" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_ZIP')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="zip" id="zip" value="<?php print $order->zip?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['country']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="country" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_COUNTRY')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<?php print $this->select_countries;?>
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['phone']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="phone" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_TELEFON')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" id="phone" name="phone" value="<?php print $order->phone?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['mobil_phone']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="mobil_phone" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_MOBIL_PHONE')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" id="mobil_phone" name="mobil_phone" value="<?php print $order->mobil_phone?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['fax']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="fax" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
			<?php print JText::_('COM_SMARTSHOP_FAX')?>:</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="fax" id="fax" value="<?php print $order->fax?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['email']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="email" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EMAIL')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="email" id="email" value="<?php print $order->email?>" />
        	</div>
		</div>
        <?php } ?>
        
        <?php if ($config_fields['ext_field_1']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="ext_field_1" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_1')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" id="ext_field_1"  name="ext_field_1" value="<?php print $order->ext_field_1?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['ext_field_2']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="ext_field_2" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
			<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_2')?>:</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
			<input type="text" class="form-control" name="ext_field_2" id="ext_field_2" value="<?php print $order->ext_field_2?>" />
        	</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['ext_field_3']['display']){?>
        
		<div class="form-group row align-items-center">
			<label for="ext_field_3" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
			<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_3')?>:</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" name="ext_field_3" id="ext_field_3" value="<?php print $order->ext_field_3?>" />
        	</div>
		</div>
        <?php } ?>
        </div>
    </div>
	<div  class="col-sm-6 col-md-6 col-xl-6 col-12 col-form-label  font-weight-bold fw-bold">
    <?php if ($this->count_filed_delivery >0) {?>
		<div class="admintable striped-block jshops_edit mt-3">
		<div class="form-group row align-items-center">
			<div  class="col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_SHIP_TO') ?>
			</div>
		</div>	   
		<?php if ($config_fields['d_title']['display']){?>        
        <div class="form-group row align-items-center">
			<label for="d_title" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_USER_TITLE')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<?php print $this->select_d_titles?>
			</div>
		</div>
        <?php } ?>
        <?php if ($config_fields['d_firma_name']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_firma_name" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIRMA_NAME')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="d_firma_name" id="d_firma_name" value="<?php print $order->d_firma_name?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_f_name']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_f_name" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
			<?php print JText::_('COM_SMARTSHOP_FULL_NAME')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
        <input type="text" class="form-control" id="d_f_name" name="d_f_name" value="<?php print $order->d_f_name?>" /> 
        <input type="text" class="form-control" name="d_m_name" value="<?php print $order->d_m_name?>" />
			  <input type="text" class="form-control" name="d_l_name" value="<?php print $order->d_l_name?>" />
			</div>
        </div>
        <?php } ?>
    <?php if ($config_fields['d_birthday']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_birthday" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
			<?php print JText::_('COM_SMARTSHOP_BIRTHDAY')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><?php echo JHTML::_('calendar', $order->d_birthday, 'd_birthday', 'd_birthday', $this->config->field_birthday_format, array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19'));?>
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_home']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_home" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
			<?php print JText::_('COM_SMARTSHOP_FIELD_HOME')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" id="d_home" name="d_home" value="<?php print $order->d_home?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_apartment']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_apartment" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIELD_APARTMENT')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" id="d_apartment" name="d_apartment" value="<?php print $order->d_apartment?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_street']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_street" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
			<?php print JText::_('COM_SMARTSHOP_STREET_NR')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12">
          <input type="text" class="form-control" name="d_street" id="d_street" value="<?php print $order->d_street?>" />
          <?php if ($config_fields['d_street_nr']['display']){?>
          <input type="text" class="form-control" name="d_street_nr" id="d_street_nr" value="<?php print $order->d_street_nr?>" />
          <?php }?>
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_city']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_city" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_CITY')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" id="d_city" name="d_city" value="<?php print $order->d_city?>" /></div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_state']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_state" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_STATE')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" id="d_state" name="d_state" value="<?php print $order->d_state?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_zip']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_zip" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_ZIP')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" id="d_zip" name="d_zip" value="<?php print $order->d_zip?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_country']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_countries" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_COUNTRY')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><?php print $this->select_d_countries?>
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_phone']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_phone" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_TELEFON')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" id="d_phone" name="d_phone" value="<?php print $order->d_phone?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_mobil_phone']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_mobil_phone" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_MOBIL_PHONE')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" id="d_mobil_phone" name="d_mobil_phone" value="<?php print $order->d_mobil_phone?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_fax']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_fax" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FAX')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" name="d_fax" id="d_fax" value="<?php print $order->d_fax?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_email']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_email" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EMAIL')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" id="d_email" name="d_email" value="<?php print $order->d_email?>" />
			</div>
        </div>
        <?php } ?>
        
        <?php if ($config_fields['d_ext_field_1']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_ext_field_1" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_1')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" id="d_ext_field_1" name="d_ext_field_1" value="<?php print $order->d_ext_field_1?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_ext_field_2']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_ext_field_2" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_2')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" id="d_ext_field_2" name="d_ext_field_2" value="<?php print $order->d_ext_field_2?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['d_ext_field_3']['display']){?>
        <div class="form-group row align-items-center">
			<label for="d_ext_field_3" class="col-sm-3 col-md-3 col-xl-3 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_3')?>:
			</label>
			<div class="col-sm-9 col-md-9 col-xl-9 col-12"><input type="text" class="form-control" name="d_ext_field_3" id="d_ext_field_3" value="<?php print $order->d_ext_field_3?>" />
			</div>
        </div>
        <?php } ?>
        </div>
    <?php } ?>
</div>
</div>
<?php } ?>
<br/>
<div class="row">
  <div class="col-4">
    <?php echo JText::_('COM_SMARTSHOP_CURRENCIES')?>: <?php echo $this->select_currency?>
  </div>
  <div class="col-4">
    <?php echo JText::_('COM_SMARTSHOP_DISPLAY_PRICE')?>: <?php echo $this->display_price_select?>
  </div>
  <div class="col-4">
    <?php echo JText::_('COM_SMARTSHOP_LANGUAGE_NAME')?>: <?php echo $this->select_language?>
  </div>
</div>
<br/>

<div class="table-responsive">
<table class="admintable table table-striped" width="100%" id='list_order_items'>
<thead>
<tr>
 <th scope="col" >
   <?php echo JText::_('COM_SMARTSHOP_NAME_PRODUCT')?>
 </th>
 <th scope="col" >
   <?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT')?>
 </th>
 <th scope="col" >
   <?php echo JText::_('COM_SMARTSHOP_QUANTITY')?>
 </th> 
 <th scope="col" width="16%">
   <?php echo JText::_('COM_SMARTSHOP_PRICE')?>
 </th>
 <th scope="col" width="4%">
   <?php echo JText::_('COM_SMARTSHOP_DELETE')?>
 </th>
</tr>
</thead>
<?php $i=0;?>
<?php foreach ($order_item as $item){ $i++; ?>
<tr valign="top" id="order_item_row_<?php echo $i?>">
 <td>
   <input type="text" name="product_name[<?php echo $i?>]" class="form-control mb-2" value="<?php echo $item->product_name?>" size="44" title="<?php print JText::_('COM_SMARTSHOP_TITLE')?>" />
	<?php echo HTMLHelper::_('smartshopmodal.renderButton', '', 'product_list_selectable', '', JText::_('COM_SMARTSHOP_LOAD')); ?>
	<?php echo HTMLHelper::_('smartshopmodal.renderWindow', 'product_list_selectable', '', '<iframe src="index.php?option=com_jshopping&controller=product_list_selectable&tmpl=component&e_name='.$i.'" id="product_list_selectable" frameborder="0" width="758" height="540"></iframe>'); ?>
  <br />
   <?php if ($this->config->admin_show_attributes){?>
   <textarea rows="2" cols="24" class="form-control" name="product_attributes[<?php echo $i?>]" title="<?php print JText::_('COM_SMARTSHOP_ATTRIBUTES')?>"><?php print $item->product_attributes?></textarea><br />
   <?php }?>
   <?php if ($this->config->admin_show_freeattributes){?>
   <textarea rows="2" cols="24" class="form-control" name="product_freeattributes[<?php echo $i?>]" title="<?php print JText::_('COM_SMARTSHOP_FREE_ATTRIBUTES')?>"><?php print $item->product_freeattributes?></textarea>
   <?php }?>   
   <input type="hidden" name="product_id[<?php echo $i?>]" value="<?php echo $item->product_id?>" />
   <input type="hidden" name="delivery_times_id[<?php echo $i?>]" value="<?php echo $item->delivery_times_id?>" />
   <input type="hidden" name="thumb_image[<?php echo $i?>]" value="<?php echo $item->thumb_image?>" />
   <?php if ($this->config->admin_order_edit_more){?>
   <div>
   <?php echo JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT')?> <input type="text" class="form-control" name="weight[<?php echo $i?>]" value="<?php echo $item->weight?>" />
   </div>
   <div>   
   <?php echo JText::_('COM_SMARTSHOP_VENDOR')?> ID <input type="text" class="form-control" name="vendor_id[<?php echo $i?>]" value="<?php echo $item->vendor_id?>" />
   </div>
   <?php }else{?>
   <input type="hidden" name="weight[<?php echo $i?>]" value="<?php echo $item->weight?>" />
   <input type="hidden" name="vendor_id[<?php echo $i?>]" value="<?php echo $item->vendor_id?>" />
   <?php }?>
 </td>
 <td>
   <input type="text" class="form-control" name="product_ean[<?php echo $i?>]" class="middle" value="<?php echo $item->product_ean?>" />
 </td>
 <td>
   <input type="text" class="form-control" name="product_quantity[<?php echo $i?>]" class="small3" value="<?php echo $item->product_quantity?>" onkeyup="shopOrderAndOffer.updateOrderSubtotal();shopOrderAndOffer.calculateTax();"/>   
 </td>
 <td>
   <div class="price"><?php print JText::_('COM_SMARTSHOP_PRICE')?>: <input class="small3 form-control" type="text" name="product_item_price[<?php echo $i?>]" value="<?php echo $item->product_item_price;?>" onkeyup="shopOrderAndOffer.updateOrderSubtotal();shopOrderAndOffer.calculateTax();"/><?php echo ' '.$order->currency_code;?></div>
   <?php if (!$this->config->hide_tax){?>
   <div class="tax"><?php print JText::_('COM_SMARTSHOP_TAX')?>: <input class="small3 form-control" type="text" name="product_tax[<?php echo $i?>]" value="<?php echo $item->product_tax?>" /> %</div>
   <?php }?>
   <input type="hidden" name="order_item_id[<?php echo $i?>]" value="<?php echo $item->order_item_id?>" />
   <input type="hidden" name="product_one_time_price[<?php echo $i?>]" value="<?php  if($item->product_item_one_time_cost > 0){echo $item->product_item_one_time_cost - ($item->product_item_price * $item->product_quantity);}else{ echo 0; } ?>" />
 </td>
 <td>
    <a class="btn btn-micro" href='#' onclick="document.querySelector('#order_item_row_<?php echo $i?>').remove();shopOrderAndOffer.updateOrderSubtotal();shopOrderAndOffer.calculateTax();return false;">
        <i class="icon-delete"></i>
    </a>
 </td>
</tr>
<?php }?>
</table>
<div style="text-align:right;padding-top:3px;">
    <input type="button" class="btn btn-primary" value="<?php print JText::_('COM_SMARTSHOP_ADD')." ".JText::_('COM_SMARTSHOP_PRODUCT')?>" onclick="shopOrderAndOffer.addItemRow();">
</div>
<script>var end_number_order_item=<?php echo $i?>;</script>

<br/>
<table class="table table-striped" width="100%">
<tr class="bold">
 <td class="right">
    <?php echo JText::_('COM_SMARTSHOP_SUBTOTAL')?>
 </td>
 <td class="left">
   <input type="text" class="small3 form-control" name="order_subtotal" value="<?php echo $order->order_subtotal;?>" onkeyup="shopOrderAndOffer.calculateTax();"/> <?php echo $order->currency_code;?>
 </td>
</tr>
<?php print $this->_tmp_html_after_subtotal ?? ''?>
<tr class="bold">
 <td class="right">
   <?php echo JText::_('COM_SMARTSHOP_COUPON_DISCOUNT')?>
 </td>
 <td class="left">
   <input type="text" class="small3 form-control" name="order_discount" value="<?php echo $order->order_discount;?>" onkeyup="shopOrderAndOffer.calculateTax();"/> <?php echo $order->currency_code;?>
 </td>
</tr>

<?php if (!$this->config->without_shipping){?>
<tr class="bold">
 <td class="right">
    <?php echo JText::_('COM_SMARTSHOP_SHIPPING_PRICE')?>
 </td>
 <td class="left">
    <input type="text" class="small3 form-control" name="order_shipping" value="<?php echo $order->order_shipping;?>" onkeyup="shopOrderAndOffer.calculateTax();"/> <?php echo $order->currency_code;?> 
 </td>
</tr>
<tr class="bold">
 <td class = "right">
    <?php echo JText::_('COM_SMARTSHOP_PACKAGE_PRICE')?>
 </td>
 <td class = "left">
    <input type="text" class="small3 form-control" name="order_package" value="<?php echo $order->order_package ?? '';?>" onkeyup="shopOrderAndOffer.calculateTax();"/> <?php echo $order->currency_code;?> 
 </td>
</tr>
<?php }?>
<?php if (!$this->config->without_payment){?>
<tr class="bold">
 <td class="right">
     <?php print ($order->payment_name) ? $order->payment_name : JText::_('COM_SMARTSHOP_PAYMENT');?>
 </td>
 <td class="left">
   <input type="text" class="small3 form-control" name="order_payment" value="<?php echo $order->order_payment ?? ''?>" onkeyup="shopOrderAndOffer.calculateTax();"/> <?php echo $order->currency_code;?>
 </td>
</tr>
<?php }?>

<?php $i=0; if (!$this->config->hide_tax){?>
<?php foreach($order->order_tax_list as $percent=>$value){ $i++;?>
  <tr class="bold">
    <td class="right">
      <?php print displayTotalCartTaxName($order->display_price);?>
      <input type="text" class="small3 form-control" name="tax_percent[]" value="<?php print $percent?>" /> %
    </td>
    <td class="left">
      <input type="text" class="small3 form-control" name="tax_value[]" value="<?php print $value; ?>" /> <?php print $order->currency_code?>
    </td>
  </tr>
<?php }?>
  <tr class="bold" id='row_button_add_tax'>
    <td></td>
    <td class="left">
      <input type="button" class="btn btn-primary" value="<?php print JText::_('COM_SMARTSHOP_TAX_CALCULATE'); ?>" onclick="shopOrderAndOffer.calculateTax();">
      <input type="button" class="btn btn-primary" value="<?php print JText::_('COM_SMARTSHOP_ADD')." ".JText::_('COM_SMARTSHOP_TAX')?>" onclick="shopOrderAndOffer.addTaxRow();">
    </td>
  </tr>
<?php }?>

<tr class="bold">
 <td class="right">
    <?php echo JText::_('COM_SMARTSHOP_TOTAL')?>
 </td>
 <td class="left" width="20%">
   <input type="text" class="small3 form-control" name="order_total" value="<?php echo $order->order_total;?>" /> <?php echo $order->currency_code;?>
 </td>
</tr>
<?php print $this->_tmp_html_after_total ?? ''?>
<?php $pkey="etemplatevar";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
</table>

<table class="table table-striped">
<thead>
<tr>
    <?php if (!$this->config->without_shipping){?>
    <th width="33%">
    <?php echo JText::_('COM_SMARTSHOP_SHIPPING_INFORMATION')?>
    </th>
    <?php }?>
    <?php if (!$this->config->without_payment){?>
    <th width="33%">
    <?php echo JText::_('COM_SMARTSHOP_PAYMENT_INFORMATION')?>
    </th>
    <?php } ?>
    <?php if ($this->config->delivery_times_on_product_page){?>
    <th width="33%">
    <?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME')?>
    </th>
    <?php } ?>
</tr>
</thead>
<tr>
    <?php if (!$this->config->without_shipping){?>
    <td valign="top"><?php echo $this->shippings_select?></td>
    <?php } ?>
    <?php if (!$this->config->without_payment){?>
    <td valign="top">
        <div style="padding-bottom:4px;"><?php print $this->payments_select?></div>
        <div><textarea name="payment_params"  class="form-control"><?php echo $order->payment_params?></textarea></div>
    </td>
    <?php } ?>
    <?php if ($this->config->delivery_times_on_product_page){?>
    <td valign="top"><?php echo $this->delivery_time_select?></td>
    <?php } ?>
</tr>
</table>
</div>

<input type="hidden" name="js_nolang" value="1" />
<input type="hidden" name="order_id" value="<?php echo $this->order_id;?>" />
<input type="hidden" name="controller" value="offer_and_order" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="client_id" value="<?php echo $this->client_id?>" />
<?php print $this->tmp_html_end ?? ''?>
</form>
</div>

  
