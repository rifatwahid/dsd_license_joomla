<?php 
/**
* @version      4.9.0 10.02.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT_SITE . '/helpers/html/');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
$order = $this->order;
$order_history = $this->order_history ?? '';
$order_item = $this->order_items;
$copy_order_item = $this->order_items;
$lists = $this->lists ?? [];
$config_fields = $this->config_fields;
JHtmlBootstrap::modal('a.modal');
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
var lang_one_time_cost='<?php print JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE_ADD')?>';
function selectProductBehaviour(pid, eName){
	let currencyIdEl = document.querySelector('#currency_id');
	if (currencyIdEl) {
		shopOrderAndOffer.loadProductInfo(pid, eName, currencyIdEl.value);
		setTimeout(shopOrderAndOffer.calculateTax, 900);
	}

	let closeElms = document.querySelectorAll('.modal-dialog .close');
	if (closeElms) {
		closeElms.forEach(function (item) {
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
</script>
<div class="jshop_edit form-horizontal orders_edit">
<form action="index.php?option=com_jshopping" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<?php print $this->tmp_html_start ?? ''?>
<?php if (!isset($this->display_info_only_product) || !$this->display_info_only_product){?>

	<div class="row">
		<div class="col-2">
			<?php echo JText::_('COM_SMARTSHOP_FINISHED')?>: <br>
			<input type="checkbox" class="form-check-input" name="order_created" value="1" <?php if ($order->order_created){ echo "checked";}?>>
		</div>
		<div class="col-4">
			<?php echo JText::_('COM_SMARTSHOP_USER')?>: <br>
			<?php echo $this->users_list_select; ?>
		</div>

		<?php if ($this->config->date_invoice_in_invoice) : ?>
			<div class="col-4">
				<?php echo JText::_('COM_SMARTSHOP_INVOICE_DATE')?>: <br>
				<?php echo JHTML::_('calendar', getDisplayDate($order->invoice_date, $this->config->store_date_format), 'invoice_date', 'invoice_date', $this->config->store_date_format , array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19'));?>
			</div>
		<?php endif; ?>
	</div>
<?php print $this->tmp_html_after_top ?? '';?>
<div class="form-group row align-items-top jshop_address">
	<div class="col-sm-6 col-md-6 col-xl-6 col-12 col-form-label ">
    <div class="admintable striped-block jshops_edit mt-5 jshops_edit">
        <div class="form-group row align-items-center  border-bottom">
			<div class="col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_BILL_TO') ?></th>
			</div>
		</div>
    <?php if ($config_fields['title']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_USER_TITLE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->select_titles?>
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['firma_name']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="firma_name" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIRMA_NAME')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="firma_name" id="firma_name" class="form-control" value="<?php print $order->firma_name?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['f_name']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="f_name" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FULL_NAME')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<div class="input-group mb-2">
					<input type="text" name="f_name" class="form-control" id="f_name" value="<?php print $order->f_name?>" /> 
					<input type="text" name="m_name" class="form-control" value="<?php print $order->m_name?>" />
				</div>

				<input type="text" name="l_name" class="form-control" value="<?php print $order->l_name?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['client_type']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="client_type" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_CLIENT_TYPE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->select_client_types;?>
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['firma_code']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="firma_code" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIRMA_CODE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" id="firma_code" class="form-control" name="firma_code" value="<?php print $order->firma_code?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['tax_number']['display']){?>
        <div class="form-group row align-items-center  border-bottom" id="tr_field_tax_number" <?php if ($config_fields['client_type']['display'] && $order->client_type!="2"){?>style="display:none;"<?php } ?>>
			<label for="tax_number" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_VAT_NUMBER')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="tax_number" class="form-control" id="tax_number" value="<?php print $order->tax_number?>" />
			</div>
        </div>
        <?php } ?>
    <?php if ($config_fields['birthday']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="birthday" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_BIRTHDAY')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php echo JHTML::_('calendar', $order->birthday, 'birthday', 'birthday', $this->config->field_birthday_format, array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19'));?>
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['home']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="home" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIELD_HOME')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="home" class="form-control" id="home" value="<?php print $order->home?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['apartment']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="apartment" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIELD_APARTMENT')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="apartment" class="form-control" id="apartment" value="<?php print $order->apartment?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['street']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_STREET_NR')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">		
				<div class="input-group">
					<input type="text" name="street" class="form-control" value="<?php print $order->street?>" />
					<?php if ($config_fields['street_nr']['display']){?>
						<input type="text" name="street_nr" class="form-control" id="street_nr" value="<?php print $order->street_nr?>" />
					<?php }?>
				</div>
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['city']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="city" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_CITY')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="city" class="form-control" id="city" value="<?php print $order->city?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['state']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="state" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_STATE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="state" class="form-control" id="state" value="<?php print $order->state?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['zip']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="zip" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_ZIP')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="zip" class="form-control" id="zip" value="<?php print $order->zip?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['country']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="country" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_COUNTRY')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->select_countries;?>
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['phone']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="phone" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_TELEFON')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="phone" class="form-control" id="phone" value="<?php print $order->phone?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['mobil_phone']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="mobil_phone" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_MOBIL_PHONE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="mobil_phone" class="form-control" id="mobil_phone" value="<?php print $order->mobil_phone?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['fax']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="fax" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FAX')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="fax" class="form-control" id="fax" value="<?php print $order->fax?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['email']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="email" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EMAIL')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="email" class="form-control" id="email" value="<?php print $order->email?>" />
			</div>
        </div>
        <?php } ?>
        
        <?php if ($config_fields['ext_field_1']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="ext_field_1" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_1')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="ext_field_1" class="form-control" id="ext_field_1" value="<?php print $order->ext_field_1?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['ext_field_2']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="ext_field_2" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_2')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="ext_field_2" class="form-control" id="ext_field_2" value="<?php print $order->ext_field_2?>" />
			</div>
        </div>
        <?php } ?>
        <?php if ($config_fields['ext_field_3']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="ext_field_3" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_3')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<input type="text" name="ext_field_3" class="form-control" id="ext_field_3" value="<?php print $order->ext_field_3?>" />
			</div>
        </div>
        <?php } ?>
    </div>
    </div>
	<div class="col-sm-6 col-md-6 col-xl-6 col-12 col-form-label ">

        <div class="admintable striped-block jshops_edit mt-5 jshops_edit">
			<div class="form-group row align-items-center  border-bottom">
				<div class="col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_SHIP_TO') ?>
				</div>
			</div>
					
		<?php if ($config_fields['title']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_USER_TITLE')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<?php print $this->select_d_titles?>				
					</div>
				</div>
			<?php } ?>
			<?php if ($config_fields['firma_name']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_firma_name" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_FIRMA_NAME')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_firma_name" class="form-control" id="d_firma_name" value="<?php print $order->d_firma_name?>" />				
					</div>
				</div>
			<?php } ?>
			<?php if ($config_fields['f_name']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_f_name" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_FULL_NAME')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<div class="input-group mb-2">
						<input type="text" name="d_f_name" class="form-control" id="d_f_name" value="<?php print $order->d_f_name?>" />
						<input type="text" name="d_m_name" class="form-control" value="<?php print $order->d_m_name?>" />
					</div>

					<input type="text" name="d_l_name" class="form-control" value="<?php print $order->d_l_name?>" />
				</div>
			</div>
			<?php } ?>
		<?php if ($config_fields['birthday']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_birthday" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_BIRTHDAY')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<?php echo JHTML::_('calendar', $order->d_birthday, 'd_birthday', 'd_birthday', $this->config->field_birthday_format, array('class'=>'inputbox', 'size'=>'25', 'maxlength'=>'19'));?>			
					</div>
			</div>

			<?php } ?>
			<?php if ($config_fields['home']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_home" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_FIELD_HOME')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_home" class="form-control" id="d_home" value="<?php print $order->d_home?>" />			
				</div>
			</div>

			<?php } ?>
			<?php if ($config_fields['apartment']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_apartment" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_FIELD_APARTMENT')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_apartment" class="form-control" id="d_apartment" value="<?php print $order->d_apartment?>" />			
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['street']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_street" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_STREET_NR')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">		
					<div class="input-group">
						<input type="text" name="d_street" class="form-control" id="d_street" value="<?php print $order->d_street?>" />
						<?php if ($config_fields['street_nr']['display']){?>
							<input type="text" name="d_street_nr" class="form-control" value="<?php print $order->d_street_nr?>" />
						<?php }?>	
					</div>
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['city']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_city" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_CITY')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_city" class="form-control" id="d_city" value="<?php print $order->d_city?>" />			
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['state']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_state" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_STATE')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_state" class="form-control" id="d_state" value="<?php print $order->d_state?>" />			
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['zip']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_zip" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_ZIP')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_zip" class="form-control" id="d_zip" value="<?php print $order->d_zip?>" />			
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['country']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_country" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_COUNTRY')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<?php print $this->select_d_countries?>			
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['phone']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_phone" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_TELEFON')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_phone" class="form-control" id="d_phone" value="<?php print $order->d_phone?>" />			
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['mobil_phone']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_mobil_phone" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_MOBIL_PHONE')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_mobil_phone" class="form-control" id="d_mobil_phone" value="<?php print $order->d_mobil_phone?>" />			
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['fax']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_fax" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_FAX')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_fax" class="form-control" id="d_fax" value="<?php print $order->d_fax?>" />			
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['email']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_email" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_EMAIL')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_email" class="form-control" id="d_email" value="<?php print $order->d_email?>" />			
				</div>
			</div>
			<?php } ?>
			
			<?php if ($config_fields['ext_field_1']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_ext_field_1" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_1')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_ext_field_1" class="form-control" id="d_ext_field_1" value="<?php print $order->d_ext_field_1?>" />			
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['ext_field_2']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_ext_field_2" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_2')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_ext_field_2" class="form-control" id="d_ext_field_2" value="<?php print $order->d_ext_field_2?>" />			
				</div>
			</div>
			<?php } ?>
			<?php if ($config_fields['ext_field_3']['display']){?>
			<div class="form-group row align-items-center  border-bottom">
				<label for="d_ext_field_3" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_3')?>:
				</label>
				<div class="col-sm-8 col-md-8 col-xl-8 col-12">
					<input type="text" name="d_ext_field_3" class="form-control" id="d_ext_field_3" value="<?php print $order->d_ext_field_3?>" />			
				</div>
			</div>
			<?php } ?>
        </div>
    
    </div>
</div>
<?php } ?>
<br/>


<div class="row">
	<div class="col-4">
		<?php echo JText::_('COM_SMARTSHOP_CURRENCIES'); ?>: <br> <?php echo $this->select_currency; ?>
	</div>
	<div class="col-4">
		<?php echo JText::_('COM_SMARTSHOP_DISPLAY_PRICE'); ?>: <br> <?php echo $this->display_price_select; ?>
	</div>
	<div class="col-4">
		<?php echo JText::_('COM_SMARTSHOP_LANGUAGE_NAME'); ?>: <br> <?php echo $this->select_language; ?>
	</div>
</div>

<br/>

<table class="admintable table table-striped" width="100%" id='list_order_items'>
	<thead>
		<tr>
			<th>
				<?php echo JText::_('COM_SMARTSHOP_NAME_PRODUCT'); ?>
			</th>
			<th>
				<?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT'); ?>
			</th>
			<th>
				<?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?>
			</th> 
			<th width="16%">
				<?php echo JText::_('COM_SMARTSHOP_PRICE'); ?>
			</th>
			<th width="4%">
				<?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>
			</th>
		</tr>
	</thead>
	
	<tbody>
	<?php $i=0; foreach($order_item as $item): $i++; ?>
		<tr valign="top" id="order_item_row_<?php echo $i; ?>">
			<td class="order_item_row_first">
				<input type="text" name="product_name[<?php echo $i; ?>]" value="<?php echo $item->product_name; ?>" class="form-control" title="<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?>" />
				<?php echo HTMLHelper::_('smartshopmodal.renderButton', 'btn', 'product_list_selectable_'.$i, '', JText::_('COM_SMARTSHOP_LOAD')); ?>
				<?php echo HTMLHelper::_('smartshopmodal.renderWindow', 'product_list_selectable_'.$i, '', '<iframe src="index.php?option=com_jshopping&controller=product_list_selectable&tmpl=component&e_name='.$i.'" id="product_list_selectable" frameborder="0" width="758" height="540"></iframe>'); ?>
  
				<br />

				<?php if ($this->config->admin_show_attributes) : ?>
					<textarea rows="2" cols="24" name="product_attributes[<?php echo $i; ?>]" class="form-control" title="<?php echo JText::_('COM_SMARTSHOP_ATTRIBUTES'); ?>">
						<?php echo $item->product_attributes; ?>
					</textarea><br />
				<?php endif; ?>

				<?php if ($this->config->admin_show_freeattributes) : ?>
					<textarea rows="2" cols="24" name="product_freeattributes[<?php echo $i; ?>]" class="form-control" title="<?php echo JText::_('COM_SMARTSHOP_FREE_ATTRIBUTES'); ?>">
						<?php echo $item->product_freeattributes; ?>
					</textarea>
				<?php endif; ?>  
				
				<div class="orderItemUploads-<?php echo $i; ?> orderItemUploads">
					<p class="orderItemUploads__title">
						<?php echo JText::_('COM_SMARTSHOP_UPLOADS'); ?> :
					</p>

					<div class="orderItemUploads__uploads">
						<?php if (!empty($item->uploadData) && !empty($item->uploadData['files'])) : 
							$iteration = 0;
							foreach ($item->uploadData['files'] as $uploadKey => $uploadedFileName) : ?>
								<div class="orderItemUploadsItem orderItemUploadsItem-<?php echo $iteration; ?>" data-iteration="<?php echo $iteration; ?>">
									<div class="orderItemUploadsItem__data">
										<input type="text" style="width: 300px;" class="orderItemUploadsItem__files" disabled value="<?php echo $item->uploadData['files'][$uploadKey]; ?>">
									</div>
								</div>
							<?php ++$iteration; endforeach;  
						endif; ?>
					</div>

					<!-- <a class="orderItemUploads__addNewUploadFile btn" onclick="shopOrder.addNewUploadRow('.orderItemUploads-<?php echo $i; ?> .orderItemUploads__uploads', <?php echo $item->order_item_id; ?>); return false;"><?php echo JText::_('COM_SMARTSHOP_UPLOAD_A_NEW_FILE'); ?></a> -->
				<!-- <div> -->

				<?php if ($this->config->admin_order_edit_more) : ?>
					<div>
						<?php echo JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT'); ?> <input type="text" name="weight[<?php echo $i; ?>]" value="<?php echo $item->weight; ?>" />
					</div>
					<div>   
						<?php echo JText::_('COM_SMARTSHOP_VENDOR'); ?> ID <input type="text" name="vendor_id[<?php echo $i; ?>]" value="<?php echo $item->vendor_id; ?>" />
					</div>
				<?php else : ?>
					<input type="hidden" name="weight[<?php echo $i; ?>]" value="<?php echo $item->weight; ?>" />
					<input type="hidden" name="vendor_id[<?php echo $i; ?>]" value="<?php echo $item->vendor_id; ?>" />
				<?php endif; ?>

				<input type="hidden" name="product_id[<?php echo $i; ?>]" value="<?php echo $item->product_id; ?>" />
				<input type="hidden" name="delivery_times_id[<?php echo $i; ?>]" value="<?php echo $item->delivery_times_id; ?>" />
				<input type="hidden" name="thumb_image[<?php echo $i; ?>]" value="<?php echo $item->thumb_image; ?>" />
			</td>

			<td>
				<input type="text" name="product_ean[<?php echo $i; ?>]" class="middle form-control" value="<?php echo $item->product_ean; ?>" />
			</td>

			<td>
				<input type="text" name="product_quantity[<?php echo $i; ?>]" class="small3 form-control" value="<?php echo $item->product_quantity; ?>" onkeyup="shopOrderAndOffer.updateOrderSubtotal();"/>   
			</td>

			<td>
				<div class="price">
					<?php echo JText::_('COM_SMARTSHOP_PRICE')?>: <input class="small3 form-control" type="text" name="product_item_price[<?php echo $i; ?>]" value="<?php echo $item->product_item_price; ?>" onkeyup="shopOrderAndOffer.updateOrderSubtotal();"/><?php echo ' ' . $order->currency_code; ?>
				</div>
				<?php if (isset($item->one_time_cost)AND($item->one_time_cost!=0)) : ?>
					<div class="tax">
						<?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE_ADD')?>: <input class="small3" type="text" name="product_one_time_price[<?php echo $i; ?>]" onkeyup="shopOrderAndOffer.updateOrderSubtotal();" value="<?php echo $item->one_time_cost; ?>" /><?php echo ' ' . $order->currency_code; ?>
					</div>
				<?php endif; ?>

				<?php if (!$this->config->hide_tax) : ?>
					<div class="tax">
						<?php echo JText::_('COM_SMARTSHOP_TAX')?>: <input class="small3 form-control" type="text" name="product_tax[<?php echo $i; ?>]" value="<?php echo $item->product_tax; ?>" /> %
					</div>
				<?php endif; ?>
				<input type="hidden" name="order_item_id[<?php echo $i; ?>]" value="<?php echo $item->order_item_id; ?>" />
				<?php print $this->_tmp_html_after_product_tax[$item->order_item_id] ?? '' ?>
			</td>

			<td>
				<a class="btn btn-micro" href='#' onclick="document.querySelector('#order_item_row_<?php echo $i?>').remove();shopOrderAndOffer.updateOrderSubtotal();return false;">
					<i class="icon-delete"></i>
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
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
    <input type="text" class="small3 form-control" name="order_package" value="<?php echo $order->order_package;?>" onkeyup="shopOrderAndOffer.calculateTax();"/> <?php echo $order->currency_code;?> 
 </td>
</tr>
<?php }?>
<?php if (!$this->config->without_payment){?>
<tr class="bold">
 <td class="right">
     <?php print ($order->payment_name) ? $order->payment_name : JText::_('COM_SMARTSHOP_PAYMENT');?>
 </td>
 <td class="left">
   <input type="text" class="small3 form-control" name="order_payment" value="<?php echo $order->order_payment?>" onkeyup="shopOrderAndOffer.calculateTax();"/> <?php echo $order->currency_code;?>
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
      <!--<input type="button" class="btn" value="<?php print JText::_('COM_SMARTSHOP_TAX_CALCULATE'); ?>" onclick="shopOrderAndOffer.calculateTax();">-->
      <input type="button" class="btn btn-primary" value="<?php print JText::_('COM_SMARTSHOP_ADD')." ".JText::_('COM_SMARTSHOP_TAX')?>" onclick="shopOrderAndOffer.addTaxRow();">
    </td>
  </tr>
  <?php print $this->_tmp_html_after_tax ?? ''?>
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
<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
</table>

<table class="table table-striped">
<thead>
<tr>
    <?php if (!$this->config->without_shipping){?>
    <th width="25%">
    <?php echo JText::_('COM_SMARTSHOP_SHIPPING_INFORMATION')?>
    </th>
    <?php }?>
    <?php if (!$this->config->without_payment){?>
    <th width="25%">
    <?php echo JText::_('COM_SMARTSHOP_PAYMENT_INFORMATION')?>
    </th>
    <?php } ?>
    <?php if ($this->config->delivery_times_on_product_page){?>
    <th width="25%">
    <?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME')?>
    </th>
    <?php } ?>
    <th width="25%">
    <?php echo JText::_('COM_SMARTSHOP_CUSTOMER_COMMENT')?>
    </th>
</tr>
</thead>
<tr>
    <?php if (!$this->config->without_shipping){?>
    <td valign="top"><?php echo $this->shippings_select?></td>
    <?php } ?>
    <?php if (!$this->config->without_payment){?>
    <td valign="top">
        <div style="padding-bottom:4px;"><?php print $this->payments_select?></div>
        <div><textarea name="payment_params" class="form-control"><?php echo $order->payment_params?></textarea></div>
    </td>
    <?php } ?>
    <?php if ($this->config->delivery_times_on_product_page){?>
    <td valign="top"><?php echo $this->delivery_time_select?></td>
    <?php } ?>
    <td valign="top"><div><textarea name="order_add_info" class="form-control"><?php echo $order->order_add_info?></textarea></div></td>
</tr>
</table>
<style>
.product_line{
	border:1px solid grey;
	margin:5px;
	padding:5px;
}
.package{
	border:1px solid grey;
	margin:10px;
	
}
.package_products,
.return_package_products{
	border:1px solid grey;
	margin:10px;
	padding:10px;
	min-height:100px;
}
.package_add_new{
	margin:5px;
	color:blue;
	cursor:pointer;
}
.package_product_line, .return_product_line,
.return_package_product_line{
	background-color:#eee;
}
#returns_packages .row_target{
	display: none;
}
</style>
<script>
var package_index=<?php if (count($this->order_packages)>1) { echo count($this->order_packages);}else {echo 1;};?>;
var return_package_index=<?php if (count($this->return_packages)>1) { echo count($this->return_packages);}else {echo 1;};?>;
var refund_index=<?php if (count($this->refunds)>0){ echo count($this->refunds);}else {echo 0;} ?>
</script>


<div class="col-12 col-form-label  font-weight-bold">
	<?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_SHIPPING');?>
</div>
<div class="container-fluid package" id='shipping_packages'>
<input type='hidden' value="" id="shipping_packages_products" name="shipping_packages_products">
<?php $i=0;
foreach ($order_item as $key=>$product){
	$prdcts[$product->product_id]=$product;
	$i++;
	$product_quantity=$product->product_quantity;
	foreach ($this->order_packages as $key=>$pack){
		$pack_products=get_object_vars(json_decode($pack->products));		
		if ($pack_products[$product->product_id]){
			$product_quantity=$product_quantity-$pack_products[$product->product_id];
		}
	}
	if ($product_quantity>0){
	?>
	<div id="p<?php echo $product->product_id;?>" draggable="true" class="row package_product_line product_line">
		<div class="col font-weight-bold fw-bold">
			<a class="btn btn-micro" style='display:none' href="#" onclick="shopOrder.delete_from_package(this);return false;"><i class="icon-delete"></i></a> <?php echo "# ".$product->product_name;?>
		</div>
		<div class="col">
			<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_QUANTITY').": </span>
			<span><input  type='text' class='form-control' name='pq_".(int)$product->product_id."' value='".(int)$product_quantity."' disabled  onChange='shopOrder.productPackage_change_quantity(this)' onFocus='shopOrder.productPackage_start_change_quantity(this)' >";?></span>
		</div>
		<div class="col">
			<input type='hidden' name="package_product_quantity[]" value='<?php echo $product_quantity;?>'>
			<input type='hidden' name="package_product_id[]" value='<?php echo $product->product_id;?>'>
		</div>
		<script>	
		window.addEventListener('DOMContentLoaded', () => {	
			const element = document.getElementById("p<?php echo $product->product_id;?>");
			element.addEventListener("dragstart", shopOrder.productPackage_dragstart_handler);	
		});
		</script>
	</div>	
	<?php
	}
} 
?>
</div>

<div id='packages'>	
	<?php $i=1;foreach ($this->order_packages as $key=>$pack){ ?>
	<div class="container-fluid package" id='pack<?php echo $i;?>'>		
		<div class="row">
			<div class="col remove_package"><a class="btn btn-micro" href="#" onClick="shopOrder.delete_package(this, event, '<?php print JText::_('COM_SMARTSHOP_ORDEREDIT_REMOVE_PACK') ?>');return false;"><i class="icon-delete"></i></a></div>
			<div class="col font-weight-bold fw-bold"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_PACKAGE');?> </div>
			<div class="col"><?php echo $i;?></div>
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_STATUS');?></div>	
			<div class="col"><input type="text" name="package_status[]" class="middle mb-1 form-control" value="<?php echo $pack->package_status;?>"></div>
		</div>
		<div class="row">
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_PROVIDER');?>: </div>
			<div class="col"><input type="text" name="package_provider[]" class="middle form-control" value="<?php echo $pack->package_provider;?>"></div>
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_TRACKING_NUMBER');?>: </div>
			<div class="col"><input type="text" name="package_tracking[]" class="middle form-control" value="<?php echo $pack->package_tracking;?>"></div>
		</div>
		<div class="row package_products" id="target" ondrop="shopOrder.productPackage_drop_handler(event)" ondragover="shopOrder.productPackage_dragover_handler(event)" id='package_id_<?php echo $i;?>'>
			<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_DRAG_AND_DROP_PRODUCTS_HERE');?>
			<?php 
			$pack_products=get_object_vars(json_decode($pack->products));//print_r($pack_products);			
			foreach ($pack_products as $key=>$val){
			?>			
			<div id="pack<?php echo $pack->package;?>_p<?php echo $prdcts[$key]->product_id;?>" draggable="true" class="row package_product_line product_line">
				<div class="col font-weight-bold fw-bold">
					<a class="btn btn-micro delete_package" style='display:' href="#" onclick="shopOrder.delete_from_package(this);return false;"><i class="icon-delete"></i></a> <?php echo "# ".$prdcts[$key]->product_name;?>
				</div>
				<div class="col">
				<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_QUANTITY').": </span>
				<span><input type='text' name='pq_".(int)$prdcts[$key]->product_id."' class='form-control' value='".(int)$val."' onChange='shopOrder.productPackage_change_quantity(this)' onFocus='shopOrder.productPackage_start_change_quantity(this)' >";?></span>
				</div>
				<div class="col">
					<input type='hidden' name="package_product_quantity[]" value='<?php echo $val;?>'>
					<input type='hidden' name="package_product_id[]" value='<?php echo $prdcts[$key]->product_id;?>'>
				</div>
				<script>	
				window.addEventListener('DOMContentLoaded', () => {	
					const element = document.getElementById("pack<?php echo $pack->package;?>_p<?php echo $prdcts[$key]->product_id;?>");
					element.addEventListener("dragstart", shopOrder.productPackage_dragstart_handler);						
				});
				</script>
			</div>
			<?php } ?>
			</span>
		</div>
	</div>	
	<?php $i++;} if (count($this->order_packages)==0) {?>
	<div class="container-fluid package" id='pack1'>
		<div class="row">
			<div class="col remove_package"><a class="btn btn-micro" href="#" onClick="shopOrder.delete_package(this, event, '<?php print JText::_('COM_SMARTSHOP_ORDEREDIT_REMOVE_PACK') ?>');return false;"><i class="icon-delete"></i></a></div>
			<div class="col font-weight-bold fw-bold"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_PACKAGE');?> </div>
			<div class="col">1</div>
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_STATUS');?></div>	
			<div class="col"><input type="text" name="package_status[]" class="middle  mb-1 form-control" value=""></div>
		</div>
		<div class="row">
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_PROVIDER');?>: </div>
			<div class="col"><input type="text" name="package_provider[]" class="middle form-control" value=""></div>
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_TRACKING_NUMBER');?>: </div>
			<div class="col"><input type="text" name="package_tracking[]" class="middle form-control" value=""></div>
		</div>
		<div class="row package_products" id="target" ondrop="shopOrder.productPackage_drop_handler(event)" ondragover="shopOrder.productPackage_dragover_handler(event)" id='package_id_1'>
			<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_DRAG_AND_DROP_PRODUCTS_HERE');?>						
			</span>
		</div>
	</div>	
	<?php } ?>
</div>

<div class="package_add_new" onclick="shopOrder.orderedit_package_add('<?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_DRAG_AND_DROP_PRODUCTS_HERE');?>')">
	<?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_ADD_ANOTHER_PACKAGE');?>
</div>
<script>window.addEventListener('DOMContentLoaded', () => {shopOrder.setSavedPackages("shipping_packages_products")});</script>




<div class="col-12 col-form-label pt-4 font-weight-bold">
	<?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_RETURNS');?>
</div>
<div class="container-fluid package" id='returns_packages'>
<!--<input type='hidden' value="" id="returns_packages_products" name="returns_packages_products"> -->
<?php $i=0; 
foreach ($order_item as $k=>$product){
	$prdcts[$product->order_item_id]=$product;
	$i++;
	$product_quantity=$product->product_quantity;
	foreach ($this->return_packages as $key=>$pack){
		$pack_products=$pack->products;			
		if ($pack_products[$product->order_item_id]){
			$product_quantity=$product_quantity-$pack_products[$product->order_item_id]->quantity;
		}
	}
	if ($product_quantity>0){
	?>
	<div id="return_p<?php echo $product->order_item_id;?>" draggable="true" class="row return_package_product_line return_product_line product_line">
			<div class="col font-weight-bold fw-bold">
				<a class="btn btn-micro delete_package" style='display:none' href="#" onclick="shopOrder.delete_from_return_package(this);return false;"><i class="icon-delete"></i></a> <?php echo "# ".$product->product_name;?>
			</div>
			<div class="col">
				<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_QUANTITY').": </span>
				<span><input  type='text' class='form-control product_quantity return_product_quantity' id='pq_".(int)$product->order_item_id."' name='' value='".(int)$product_quantity."' disabled  onChange='shopOrder.productReturnPackage_change_quantity(this)' onFocus='shopOrder.productReturnPackage_start_change_quantity(this)' >";?></span>
			</div>
			<div class="col d-none row_target">
						<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_RETURN_REASON') ?>: </span>
						<span><?php print JHTML::_('select.genericlist', $this->return_status_list,'','class = "inputbox form-select return_reason" size = "1" ','status_id','name', 0);?></span>
	 
	 
				<input type='hidden' class="return_package_product_id" name="" value='<?php echo $product->order_item_id;?>'>
			</div>
		<div class="row d-none row_target">
			<div class="col">
				<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_CUSTOMER_COMMENT') ?>: </span>
				<span><input type="text" class="form-control customer_comment" name=""></span>
			</div>		
		</div>		
		<div class="row d-none row_target">
			<div class="col">
				<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_ADMIN_NOTICE') ?>: </span>
				<span><input type='text' name="" class='form-control admin_notice' ></span>
			</div>
		</div>
		<script>	
		window.addEventListener('DOMContentLoaded', () => {	
			const element = document.getElementById("return_p<?php echo $product->order_item_id;?>");
			element.addEventListener("dragstart", shopOrder.productReturnPackage_dragstart_handler);	
		});
		</script>
	</div>	
	<?php
	}
}
?>
</div>

<div id='return_packages'>	
	<?php $i=1;foreach ($this->return_packages as $key=>$pack){?>
	<div class="container-fluid package" id='return_pack<?php echo $i;?>'>
		<div class="row">
			<div class="col remove_package"><a class="btn btn-micro " href="#" onClick="shopOrder.delete_return_package(this, event, '<?php print JText::_('COM_SMARTSHOP_ORDEREDIT_REMOVE_PACK') ?>');return false;"><i class="icon-delete"></i></a></div>
			<div class="col font-weight-bold fw-bold"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_RETURN');?> </div>
			<div class="col"><?php echo $i;?></div>
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_STATUS');?></div>	
			<div class="col"><input type="text" name="return_package_status[<?php print $i ?>]" class="middle mb-1 form-control return_package_status" value="<?php echo $pack->package_status;?>"></div>
			<input type='hidden' class="return_package_id" name="return_package_id[]" value='<?php echo  $i; ?>'>
		</div>
		<div class="row return_package_products" id="target" ondrop="shopOrder.productReturnPackage_drop_handler(event)" ondragover="shopOrder.productReturnPackage_dragover_handler(event)" id='return_package_id_<?php echo $i;?>'>
			<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_DRAG_AND_DROP_PRODUCTS_HERE');?>
			<?php 
			$pack_products=$pack->products;//get_object_vars(json_decode($pack->products));
			foreach ($pack_products as $key=>$val){
			?>			
			<div id="return_pack<?php echo $pack->package;?>_p<?php echo $prdcts[$key]->order_item_id;?>" draggable="true" class="row return_product_line product_line">

					<div class="col font-weight-bold fw-bold">
						<a class="btn btn-micro delete_package" style='display:' href="#" onclick="shopOrder.delete_from_return_package(this);return false;"><i class="icon-delete"></i></a> <?php echo "# ".$prdcts[$key]->product_name;?>
					</div>
					<div class="col">
					<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_QUANTITY') ?>: </span>
					<span><input type='text' id='pq_<?php print (int)$prdcts[$key]->order_item_id ?>' name="return_product_quantity[<?php print $i ?>][<?php print $prdcts[$key]->order_item_id ?>]" class='form-control product_quantity return_product_quantity' value='<?php print (int)$val->quantity ?>' onChange='shopOrder.productReturnPackage_change_quantity(this)' onFocus='shopOrder.productReturnPackage_start_change_quantity(this)' ></span>
					</div>
					<div class="col row_target">
					<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_RETURN_REASON') ?>: </span>
					<span><?php print JHTML::_('select.genericlist', $this->return_status_list,'return_reason['.$i.']['.$prdcts[$key]->order_item_id.']','class = "inputbox form-select return_reason" size = "1" id = "return_package_status"','status_id','name', (int)$val->return_status_id); ?>
					<input type='hidden' class="return_package_product_id" name="return_package_product_id[<?php print $i ?>][<?php print $prdcts[$key]->order_item_id ?>]" value='<?php echo $prdcts[$key]->order_item_id;?>'>
					</div>
					
				
				<div class="row row_target">
					<div class="col">
						<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_CUSTOMER_COMMENT') ?>: </span>
						<span><input type='text' name="customer_comment[<?php print $i ?>][<?php print $prdcts[$key]->order_item_id ?>]" class='form-control customer_comment' value="<?php echo $val->customer_comment ?>"></span>
					</div>
				</div>
				<div class="row row_target">
					<div class="col">
						<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_ADMIN_NOTICE') ?>: </span>
						<span><input type='text' name="admin_notice[<?php echo $i ?>][<?php echo $prdcts[$key]->order_item_id ?>]" class='form-control admin_notice' value="<?php echo $val->admin_notice ?>"></span>
					</div>
				</div>
				<script>	
				window.addEventListener('DOMContentLoaded', () => {	
					const element = document.getElementById("return_pack<?php echo $pack->package;?>_p<?php echo $prdcts[$key]->order_item_id;?>");
					element.addEventListener("dragstart", shopOrder.productReturnPackage_dragstart_handler);						
				});
				</script>
				
			</div>
			<?php } ?>
			</span>
		</div>
	</div>	
	<?php $i++;} if (count($this->return_packages)==0) {?>
	<div class="container-fluid package" id='return_pack1'>
		<div class="row">
			<div class="col remove_package"><a class="btn btn-micro " href="#" onClick="shopOrder.delete_return_package(this, event, '<?php print JText::_('COM_SMARTSHOP_ORDEREDIT_REMOVE_PACK') ?>');return false;"><i class="icon-delete"></i></a></div>
			<div class="col font-weight-bold fw-bold"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_RETURN');?> </div>
			<div class="col">1</div>
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_STATUS');?></div>	
			<div class="col"><input type="text" name="return_package_status[1]" class="middle  mb-1 form-control return_package_status" value=""></div>
			
				<input type='hidden' class="return_package_id" name="return_package_id[]" value='1'>
		</div>
		<div class="row return_package_products" id="target" ondrop="shopOrder.productReturnPackage_drop_handler(event)" ondragover="shopOrder.productReturnPackage_dragover_handler(event)" id='package_id_1'>
			<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_DRAG_AND_DROP_PRODUCTS_HERE');?>						
			</span>
		</div>
	</div>	
	<?php } ?>
</div>

<div class="package_add_new" onclick="shopOrder.orderedit_return_package_add('<?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_DRAG_AND_DROP_PRODUCTS_HERE');?>')">
	<?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_ADD_ANOTHER_RETURN');?>
</div>


<div class="col-12 col-form-label pt-4 font-weight-bold">
	<?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_REFUNDS');?>
</div>

<div id="refunds_block">
<?php foreach($this->refunds as $key=>$refund){ ?>
<h5><?php echo JText::_('COM_SMARTSHOP_REFUND').' '.$key+1;?></h5>
<div id="refund_block" class="pt-4">
	<table class="admintable table table-striped" width="100%" id='list_order_items'>
		<thead>
			<tr>
				<th>
					<?php echo JText::_('COM_SMARTSHOP_NAME_PRODUCT'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?>
				</th> 
				<th width="16%">
					<?php echo JText::_('COM_SMARTSHOP_PRICE'); ?>
				</th>
				<th width="4%">
					<?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php $i=0; foreach ($refund->products as $k=>$item) : $i++; ?>
			<tr valign="top" id="refund_item_row_<?php echo $i; ?>">
				<td class="refund_item_row_first">
					<?php echo $item->product_name; ?>
					<?php //echo HTMLHelper::_('smartshopmodal.renderButton', 'btn', 'product_list_selectable_'.$i, '', JText::_('COM_SMARTSHOP_LOAD')); ?>
					<?php //echo HTMLHelper::_('smartshopmodal.renderWindow', 'product_list_selectable_'.$i, '', '<iframe src="index.php?option=com_jshopping&controller=product_list_selectable&tmpl=component&e_name='.$i.'" id="product_list_selectable" frameborder="0" width="758" height="540"></iframe>'); ?>
	  
					<br />

					<?php if ($this->config->admin_show_attributes) : ?>
						<?php echo $item->product_attributes; ?>
						<br />
					<?php endif; ?>

					<?php if ($this->config->admin_show_freeattributes) : ?>
						<?php echo $item->product_freeattributes; ?>					
					<?php endif; ?>  
					
					<div class="orderItemUploads-<?php echo $i; ?> orderItemUploads">
						<p class="orderItemUploads__title">
							<?php echo JText::_('COM_SMARTSHOP_UPLOADS'); ?> :
						</p>

						<div class="orderItemUploads__uploads">
							<?php if (!empty($item->uploadData) && !empty($item->uploadData['files'])) : 
								$iteration = 0;
								foreach ($item->uploadData['files'] as $uploadKey => $uploadedFileName) : ?>
									<div class="orderItemUploadsItem orderItemUploadsItem-<?php echo $iteration; ?>" data-iteration="<?php echo $iteration; ?>">
										<div class="orderItemUploadsItem__data">
											<input type="text" style="width: 300px;" class="orderItemUploadsItem__files" disabled value="<?php echo $item->uploadData['files'][$uploadKey]; ?>">
										</div>
									</div>
								<?php ++$iteration; endforeach;  
							endif; ?>
						</div>

						<!-- <a class="orderItemUploads__addNewUploadFile btn" onclick="shopOrder.addNewUploadRow('.orderItemUploads-<?php echo $i; ?> .orderItemUploads__uploads', <?php echo $item->order_item_id; ?>); return false;"><?php echo JText::_('COM_SMARTSHOP_UPLOAD_A_NEW_FILE'); ?></a> -->
					</div>

					<?php if ($this->config->admin_order_edit_more) : ?>
						<div>
							<?php echo JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT'); ?> <?php echo $item->weight; ?>
						</div>
						<div>   
							<?php echo JText::_('COM_SMARTSHOP_VENDOR'); ?> ID <?php echo $item->vendor_id; ?>
						</div>
					<?php else : ?>
						<input type="hidden" name="refund[<?php print $key; ?>][weight][<?php echo $i; ?>]" value="<?php echo $item->weight; ?>" />
						<input type="hidden" name="refund[<?php print $key; ?>][vendor_id][<?php echo $i; ?>]" value="<?php echo $item->vendor_id; ?>" />
					<?php endif; ?>

					<input type="hidden" name="refund[<?php print $key; ?>][product_id][<?php echo $i; ?>]" value="<?php echo $item->product_id; ?>" />
				</td>

				<td>
					<?php echo $item->product_ean; ?>
				</td>

				<td>
					<input type="text" name="refund[<?php print $key; ?>][product_quantity][<?php echo $i; ?>]" class="small3 form-control" value="<?php echo $item->product_quantity; ?>" onkeyup="shopRefund.updateRefundSubtotal(<?php print $key; ?>);"/>   
				</td>

				<td>
					<div class="price">
						<?php echo JText::_('COM_SMARTSHOP_PRICE')?>: - <input class="small3 form-control" type="text" id="refund_index_product_item_price" name="refund[<?php print $key; ?>][product_item_price][<?php echo $i; ?>]" value="<?php echo $item->product_item_price; ?>" onkeyup="shopRefund.updateRefundSubtotal(<?php print $key; ?>);"/><?php echo ' ' . $order->currency_code; ?>
					</div>
					<?php if (isset($item->one_time_cost)AND($item->one_time_cost!=0)) : ?>
						<div class="tax">
							<?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE_ADD')?>: - <input class="small3" disabled type="text" name="refund[<?php print $key; ?>][product_one_time_price][<?php echo $i; ?>]" value="<?php echo $item->one_time_cost; ?>" /><?php echo ' ' . $order->currency_code; ?>
						</div>
					<?php endif; ?>

					<?php if (!$this->config->hide_tax) : ?>
						<div class="tax">
							<?php echo JText::_('COM_SMARTSHOP_TAX')?>: <input class="small3 form-control" type="text" name="refund[<?php print $key; ?>][product_tax][<?php echo $i; ?>]" value="<?php echo $item->product_tax; ?>" /> %
						</div>
					<?php endif; ?>
					<input type="hidden" name="refund[<?php print $key; ?>][item_id][<?php echo $i; ?>]" value="<?php echo $item->order_item_id; ?>" />
				</td>
				<td>
					<a class="btn btn-micro" href='#' onclick="document.querySelector('#refund_item_row_<?php echo $i?>').remove();shopRefund.updateRefundSubtotal(<?php print $key; ?>);return false;">
						<i class="icon-delete"></i>
					</a>
					<input type="hidden" name="refund[<?php print $key; ?>][product_name][<?php echo $i; ?>]" value="<?php echo $item->product_name; ?>"	/>
					<input type="hidden" name="refund[<?php print $key; ?>][product_attributes][<?php echo $i; ?>]" value="<?php echo $item->product_attributes; ?>" />
					<input type="hidden" name="refund[<?php print $key; ?>][product_freeattributes][<?php echo $i; ?>]" value="<?php echo $item->product_attributes; ?>" />
				
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<br/>
	<table class="table table-striped" width="100%">
	<tr class="bold">
	 <td class="right">
		<?php echo JText::_('COM_SMARTSHOP_SUBTOTAL')?>
	 </td>
	 <td class="left">
	   - <input type="text" class="small3 form-control" name="refund[<?php print $key; ?>][subtotal]" value="<?php echo $refund->refund_subtotal;?>" onkeyup="shopRefund.calculateTax(<?php print $key; ?>);"/> <?php echo $order->currency_code;?>
	 </td>
	</tr>
	<?php print $this->_tmp_html_after_subtotal ?? ''?>
	<tr class="bold">
	 <td class="right">
	   <?php echo JText::_('COM_SMARTSHOP_COUPON_DISCOUNT')?>
	 </td>
	 <td class="left">
	   - <input type="text" class="small3 form-control" name="refund[<?php print $key; ?>][discount]" value="<?php echo $refund->refund_discount;?>" onkeyup="shopRefund.calculateTax(<?php print $key; ?>);"/> <?php echo $order->currency_code;?>
	 </td>
	</tr>

	<?php if (!$this->config->without_shipping){?>
	<tr class="bold">
	 <td class="right">
		<?php echo JText::_('COM_SMARTSHOP_SHIPPING_PRICE')?>
	 </td>
	 <td class="left">
		- <input type="text" class="small3 form-control" name="refund[<?php print $key; ?>][shipping]" value="<?php echo $refund->refund_shipping;?>" onkeyup="shopRefund.calculateTax(<?php print $key; ?>);"/> <?php echo $order->currency_code;?> 
	 </td>
	</tr>
	<tr class="bold">
	 <td class = "right">
		<?php echo JText::_('COM_SMARTSHOP_PACKAGE_PRICE')?>
	 </td>
	 <td class = "left">
		- <input type="text" class="small3 form-control" name="refund[<?php print $key; ?>][package]" value="<?php echo $refund->refund_package;?>" onkeyup="shopRefund.calculateTax(<?php print $key; ?>);"/> <?php echo $order->currency_code;?> 
	 </td>
	</tr>
	<?php }?>
	<?php if (!$this->config->without_payment){?>
	<tr class="bold">
	 <td class="right">
		 <?php print ($order->payment_name) ? $order->payment_name : JText::_('COM_SMARTSHOP_PAYMENT');?>
	 </td>
	 <td class="left">
	   - <input type="text" class="small3 form-control" name="refund[<?php print $key; ?>][payment]" value="<?php echo $refund->refund_payment?>" onkeyup="shopRefund.calculateTax(<?php print $key; ?>);"/> <?php echo $order->currency_code;?>
	 </td>
	</tr>
	<?php }?>

	<?php $i=0; if (!$this->config->hide_tax){?>
	<?php  foreach($refund->refund_tax_list as $percent=>$value){ $i++;?>
	  <tr class="bold">
		<td class="right">
		  <?php print displayTotalCartTaxName($order->display_price);?>
		  <input type="text" class="small3 form-control" name="refund[<?php print $key; ?>][tax_percent][]" value="<?php print $percent?>" /> %
		</td>
		<td class="left">
		  - <input type="text" class="small3 form-control" name="refund[<?php print $key; ?>][tax_value][]" value="<?php print $value; ?>" /> <?php print $order->currency_code?>
		</td>
	  </tr>
	<?php } ?>
	  <tr class="bold" id='refund_row_button_add_tax_<?php print $key; ?>'>
		<td></td>
		<td class="left">
		  <!--<input type="button" class="btn" value="<?php print JText::_('COM_SMARTSHOP_TAX_CALCULATE'); ?>" onclick="shopOrderAndOffer.calculateTax();">-->
		  <input type="button" class="btn btn-primary" value="<?php print JText::_('COM_SMARTSHOP_ADD')." ".JText::_('COM_SMARTSHOP_TAX')?>" onclick="shopRefund.addTaxRow(<?php print $key; ?>);">
		</td>
	  </tr>
	<?php }?>

	<tr class="bold">
	 <td class="right">
		<?php echo JText::_('COM_SMARTSHOP_TOTAL')?>
	 </td>
	 <td class="left" width="20%">
	   - <input type="text" class="small3 form-control" name="refund[<?php print $key; ?>][total]" value="<?php echo $refund->refund_total;?>" /> <?php echo $order->currency_code;?>
	 </td>
	</tr>
	<?php print $this->_tmp_html_after_total ?? ''; ?>
	<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
		
	</table>
	<input type="hidden" name="refund[<?php print $key; ?>][refund_date]" value="<?php echo $refund->refund_date ?? date('Y-m-d'); ?>" />
	<input type="hidden" name="refund[<?php print $key; ?>][pdf_date]" value="<?php echo $refund->pdf_date ?? date('Y-m-d'); ?>" />
	<input type="hidden" name="refund[<?php print $key; ?>][pdf_file]" value="<?php echo $refund->pdf_file; ?>" />
	<input type="hidden" name="refund[<?php print $key; ?>][refund_number]" value="<?php echo $refund->refund_number; ?>" />
				
	</div>
<?php } ?>
</div>
<div class="col-12 col-form-label pt-4 font-weight-bold">
	<input type="button" class="refund_butt btn btn-primary" value="<?php echo JText::_('COM_SMARTSHOP_START_REFUND').' '.count($this->refunds)+1;?>" onClick="shopRefund.start_refund();" />
</div>

<input type="hidden" name="js_nolang" value="1" />
<input type="hidden" name="order_id" value="<?php echo $this->order_id;?>" />
<input type="hidden" name="order_number" value="<?php echo $this->order->order_number;?>" />
<input type="hidden" name="controller" value="orders" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="client_id" value="<?php echo $this->client_id?>" />
<?php print $this->tmp_html_end ?? ''?>
</form>

</div>

	<template id="templateOfOrderPack" >
		<div class="container-fluid package" id='pack1'>
			<div class="row">
				<div class="col remove_package"><a class="btn btn-micro" href="#" onClick="shopOrder.delete_package(this, event, '<?php print JText::_('COM_SMARTSHOP_ORDEREDIT_REMOVE_PACK') ?>');return false;"><i class="icon-delete"></i></a></div>
				<div class="col font-weight-bold fw-bold"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_PACKAGE');?> </div>
				<div class="col"><?php echo $i;?></div>
				<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_STATUS');?></div>	
				<div class="col"><input type="text" name="package_status[]" class="middle mb-1 form-control" value=""></div>
			</div>
			<div class="row">
				<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_PROVIDER');?>: </div>
				<div class="col"><input type="text" name="package_provider[]" class="middle form-control" value=""></div>
				<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_TRACKING_NUMBER');?>: </div>
				<div class="col"><input type="text" name="package_tracking[]" class="middle form-control" value=""></div>
			</div>
			<div class="row package_products" id="target" ondrop="shopOrder.productPackage_drop_handler(event)" ondragover="shopOrder.productPackage_dragover_handler(event)" id='package_id_1'>
				<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_DRAG_AND_DROP_PRODUCTS_HERE');?>						
				</span>
			</div>
		</div>	
	</template>

	<template id="templateOfOrderReturnPack">
		<div class="container-fluid package" id='return_pack1'>
			<div class="row">
				<div class="col remove_package"><a class="btn btn-micro " href="#" onClick="shopOrder.delete_return_package(this, event, '<?php print JText::_('COM_SMARTSHOP_ORDEREDIT_REMOVE_PACK') ?>');return false;"><i class="icon-delete"></i></a></div>
				<div class="col font-weight-bold fw-bold"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_RETURN');?> </div>
				<div class="col"><?php echo $i;?></div>
				<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_STATUS');?></div>	
				<div class="col"><input type="text" name="return_package_status[<?php print $i; ?>]" class="middle mb-1 form-control return_package_status" value=""></div>
				<input type='hidden' class="return_package_id" name="return_package_id[]" value='<?php echo  $i; ?>'>
			</div>
			<div class="row return_package_products" id="target" ondrop="shopOrder.productReturnPackage_drop_handler(event)" ondragover="shopOrder.productReturnPackage_dragover_handler(event)" id='package_id_1'>
				<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_DRAG_AND_DROP_PRODUCTS_HERE');?>						
				</span>
			</div>
		</div>
	</template>
	<template id="templateOfOrderUploadANewFile">
		<div class="orderItemUploadsItem" data-iteration="">
			<div class="orderItemUploadsItem__data">
				<input name="newUploads[qty][]" type="number" min="0" value="0" class="orderItemUploadsItem__qty"/>
				<input name="newUploads[file][]" value="null" type="file" class="orderItemUploadsItem__files">
				<textarea name="newUploads[description][]" class="orderItemUploadsItem__description"></textarea>
			</div>

			<a href="#" class="orderItemUploadsItem__delete fas fa-times">
			</a>
		</div>
	</template>	
<template id="template_refund_block">
	<div id="refund_block" class="pt-2">
	
	<h5><?php echo JText::_('COM_SMARTSHOP_REFUND').' index'; ?></h5>
	<table class="admintable table table-striped" width="100%" id='list_order_items'>
		<thead>
			<tr>
				<th>
					<?php echo JText::_('COM_SMARTSHOP_NAME_PRODUCT'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?>
				</th> 
				<th width="16%">
					<?php echo JText::_('COM_SMARTSHOP_PRICE'); ?>
				</th>
				<th width="4%">
					<?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
		<?php $i=0; foreach ($order_item as $item) : $i++; ?>
			<tr valign="top" id="refund_item_row_<?php echo $i; ?>">
				<td class="refund_item_row_first">
					<?php echo $item->product_name; ?>
					<?php //echo HTMLHelper::_('smartshopmodal.renderButton', 'btn', 'product_list_selectable_'.$i, '', JText::_('COM_SMARTSHOP_LOAD')); ?>
					<?php //echo HTMLHelper::_('smartshopmodal.renderWindow', 'product_list_selectable_'.$i, '', '<iframe src="index.php?option=com_jshopping&controller=product_list_selectable&tmpl=component&e_name='.$i.'" id="product_list_selectable" frameborder="0" width="758" height="540"></iframe>'); ?>
	  
					<br />

					<?php if ($this->config->admin_show_attributes) : ?>
						<?php echo $item->product_attributes; ?>
						<br />
					<?php endif; ?>

					<?php if ($this->config->admin_show_freeattributes) : ?>
						<?php echo $item->product_freeattributes; ?>					
					<?php endif; ?>  
					
					<div class="orderItemUploads-<?php echo $i; ?> orderItemUploads">
						<p class="orderItemUploads__title">
							<?php echo JText::_('COM_SMARTSHOP_UPLOADS'); ?> :
						</p>

						<div class="orderItemUploads__uploads">
							<?php if (!empty($item->uploadData) && !empty($item->uploadData['files'])) : 
								$iteration = 0;
								foreach ($item->uploadData['files'] as $uploadKey => $uploadedFileName) : ?>
									<div class="orderItemUploadsItem orderItemUploadsItem-<?php echo $iteration; ?>" data-iteration="<?php echo $iteration; ?>">
										<div class="orderItemUploadsItem__data">
											<input type="text" style="width: 300px;" class="orderItemUploadsItem__files" disabled value="<?php echo $item->uploadData['files'][$uploadKey]; ?>">
										</div>
									</div>
								<?php ++$iteration; endforeach;  
							endif; ?>
						</div>

						<!-- <a class="orderItemUploads__addNewUploadFile btn" onclick="shopOrder.addNewUploadRow('.orderItemUploads-<?php echo $i; ?> .orderItemUploads__uploads', <?php echo $item->order_item_id; ?>); return false;"><?php echo JText::_('COM_SMARTSHOP_UPLOAD_A_NEW_FILE'); ?></a> -->
					</div>

					<?php if ($this->config->admin_order_edit_more) : ?>
						<div>
							<?php echo JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT'); ?> <?php echo $item->weight; ?>
						</div>
						<div>   
							<?php echo JText::_('COM_SMARTSHOP_VENDOR'); ?> ID <?php echo $item->vendor_id; ?>
						</div>
					<?php else : ?>
						<input type="hidden" name="refund[0][weight][<?php echo $i; ?>]" value="<?php echo $item->weight; ?>" />
						<input type="hidden" name="refund[0][vendor_id][<?php echo $i; ?>]" value="<?php echo $item->vendor_id; ?>" />
					<?php endif; ?>

					<input type="hidden" name="refund[0][product_id][<?php echo $i; ?>]" value="<?php echo $item->product_id; ?>" />
				</td>

				<td>
					<?php echo $item->product_ean; ?>
				</td>

				<td>
					<input type="text" name="refund[0][product_quantity][<?php echo $i; ?>]" class="small3 form-control" value="<?php echo $item->product_quantity; ?>" onkeyup="shopRefund.updateRefundSubtotal(index);"/>   
				</td>

				<td>
					<div class="price">
						<?php echo JText::_('COM_SMARTSHOP_PRICE')?>: - <input class="small3 form-control" type="text" id="refund_index_product_item_price" name="refund[0][product_item_price][<?php echo $i; ?>]" value="<?php echo $item->product_item_price; ?>" onkeyup="shopRefund.updateRefundSubtotal(index);"/><?php echo ' ' . $order->currency_code; ?>
					</div>
					<?php if (isset($item->one_time_cost)AND($item->one_time_cost!=0)) : ?>
						<div class="tax">
							<?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE_ADD')?>: - <input class="small3" disabled type="text" name="refund[0][product_one_time_price][<?php echo $i; ?>]" value="<?php echo $item->one_time_cost; ?>" /><?php echo ' ' . $order->currency_code; ?>
						</div>
					<?php endif; ?>

					<?php if (!$this->config->hide_tax) : ?>
						<div class="tax">
							<?php echo JText::_('COM_SMARTSHOP_TAX')?>: <input class="small3 form-control" type="text" name="refund[0][product_tax][<?php echo $i; ?>]" value="<?php echo $item->product_tax; ?>" /> %
						</div>
					<?php endif; ?>
					<input type="hidden" name="refund[0][item_id][<?php echo $i; ?>]" value="<?php echo $item->order_item_id; ?>" />
				</td>
				<td>
					<a class="btn btn-micro" href='#' onclick="document.querySelector('#refund_item_row_<?php echo $i?>').remove();shopRefund.updateRefundSubtotal(index);return false;">
						<i class="icon-delete"></i>
					</a>
					<input type="hidden" name="refund[0][product_name][<?php echo $i; ?>]" value="<?php echo $item->product_name; ?>"	/>
					<input type="hidden" name="refund[0][product_attributes][<?php echo $i; ?>]" value="<?php echo $item->product_attributes; ?>" />
					<input type="hidden" name="refund[0][product_freeattributes][<?php echo $i; ?>]" value="<?php echo $item->product_attributes; ?>" />
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<br/>
	<table class="table table-striped" width="100%">
	<tr class="bold">
	 <td class="right">
		<?php echo JText::_('COM_SMARTSHOP_SUBTOTAL')?>
	 </td>
	 <td class="left">
	   - <input type="text" class="small3 form-control" name="refund[0][subtotal]" value="<?php echo $order->order_subtotal;?>" onkeyup="shopRefund.calculateTax(index);"/> <?php echo $order->currency_code;?>
	 </td>
	</tr>
	<?php print $this->_tmp_html_after_subtotal ?? ''?>
	<tr class="bold">
	 <td class="right">
	   <?php echo JText::_('COM_SMARTSHOP_COUPON_DISCOUNT')?>
	 </td>
	 <td class="left">
	   - <input type="text" class="small3 form-control" name="refund[0][discount]" value="<?php echo $order->order_discount;?>" onkeyup="shopRefund.calculateTax(index);"/> <?php echo $order->currency_code;?>
	 </td>
	</tr>

	<?php if (!$this->config->without_shipping){?>
	<tr class="bold">
	 <td class="right">
		<?php echo JText::_('COM_SMARTSHOP_SHIPPING_PRICE')?>
	 </td>
	 <td class="left">
		- <input type="text" class="small3 form-control" name="refund[0][shipping]" value="<?php echo $order->order_shipping;?>" onkeyup="shopRefund.calculateTax(index);"/> <?php echo $order->currency_code;?> 
	 </td>
	</tr>
	<tr class="bold">
	 <td class = "right">
		<?php echo JText::_('COM_SMARTSHOP_PACKAGE_PRICE')?>
	 </td>
	 <td class = "left">
		- <input type="text" class="small3 form-control" name="refund[0][package]" value="<?php echo $order->order_package;?>" onkeyup="shopRefund.calculateTax(index);"/> <?php echo $order->currency_code;?> 
	 </td>
	</tr>
	<?php }?>
	<?php if (!$this->config->without_payment){?>
	<tr class="bold">
	 <td class="right">
		 <?php print ($order->payment_name) ? $order->payment_name : JText::_('COM_SMARTSHOP_PAYMENT');?>
	 </td>
	 <td class="left">
	   - <input type="text" class="small3 form-control" name="refund[0][payment]" value="<?php echo $order->order_payment?>" onkeyup="shopRefund.calculateTax(index);"/> <?php echo $order->currency_code;?>
	 </td>
	</tr>
	<?php }?>

	<?php $i=0; if (!$this->config->hide_tax){?>
	<?php foreach($order->order_tax_list as $percent=>$value){ $i++;?>
	  <tr class="bold">
		<td class="right">
		  <?php print displayTotalCartTaxName($order->display_price);?>
		  <input type="text" class="small3 form-control" name="refund[0][tax_percent][]" value="<?php print $percent?>" /> %
		</td>
		<td class="left">
		  - <input type="text" class="small3 form-control" name="refund[0][tax_value][]" value="<?php print $value; ?>" /> <?php print $order->currency_code?>
		</td>
	  </tr>
	<?php }?>
	  <tr class="bold" id='refund_row_button_add_tax_index'>
		<td></td>
		<td class="left">
		  <!--<input type="button" class="btn" value="<?php print JText::_('COM_SMARTSHOP_TAX_CALCULATE'); ?>" onclick="shopOrderAndOffer.calculateTax();">-->
		  <input type="button" class="btn btn-primary" value="<?php print JText::_('COM_SMARTSHOP_ADD')." ".JText::_('COM_SMARTSHOP_TAX')?>" onclick="shopRefund.addTaxRow(index);">
		</td>
	  </tr>
	<?php }?>

	<tr class="bold">
	 <td class="right">
		<?php echo JText::_('COM_SMARTSHOP_TOTAL')?>
	 </td>
	 <td class="left" width="20%">
	   - <input type="text" class="small3 form-control" name="refund[0][total]" value="<?php echo $order->order_total;?>" /> <?php echo $order->currency_code;?>
	 </td>
	</tr>
	<?php print $this->_tmp_html_after_total ?? ''; ?>
	<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
		
	</table>
	<input type="hidden" name="refund[<?php print $key; ?>][refund_date]" value="" />
	<input type="hidden" name="refund[<?php print $key; ?>][pdf_date]" value="" />
	<input type="hidden" name="refund[<?php print $key; ?>][pdf_file]" value="" />
	<input type="hidden" name="refund[<?php print $key; ?>][refund_number]" value="" />
		
	</div>
	</div>
</template> 
