<?php 
/**
* @version      4.9.0 22.10.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$order = $this->order;
$order_history = $this->order_history;
$order_item = $this->order_items;
$lists = $this->lists;
$print = $this->print;
?>
<div class="jshops_edit order_show_edit">
<form action="index.php?option=com_jshopping&controller=orders" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start ?? ''?>
<input type="hidden" name="order_id" value="<?php print $order->order_id?>">

<div class="form-group row align-items-top">
	<div class="col-sm-6 col-md-6 col-xl-6 col-12 col-form-label ">
        <div class="admintable striped-block jshops_edit mt-5">
			<div class="form-group row align-items-center border-bottom">
				<div  class="col-12 col-form-label  font-weight-bold fw-bold">
					<?php echo JText::_('COM_SMARTSHOP_ORDER_PURCHASE');?>
				</div>
			</div>
      
        <div class="form-group row align-items-center border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php echo JText::_('COM_SMARTSHOP_NUMBER');?>
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
          <?php echo $order->order_number;?>
        </div>
		</div>
        <div class="form-group row align-items-center border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php echo JText::_('COM_SMARTSHOP_DATE');?>
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
          <?php echo formatdate($order->order_date, 1);?>
			</div>
			</div>
        <div class="form-group row align-items-center border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php echo JText::_('COM_SMARTSHOP_STATUS');?>
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
          <?php echo $order->status_name;?>
        </div>
		</div>
        <div class="form-group row align-items-center border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php echo JText::_('COM_SMARTSHOP_IPADRESS');?>
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
          <?php echo $order->ip_address;?>
        </div>
		</div>
      <?php print $this->tmp_html_info ?? ''?>
  </div>
  </div>
  	<?php if (!$print){?>
		<div  class="col-sm-6 col-md-6 col-xl-6 col-12 col-form-label ">
        <div class="admintable  jshops_edit mt-3">
	
		<?php if (!isJoomla4()) : ?>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#first-page" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_STATUS_CHANGE');?></a></li>
				<li><a href="#second-page" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_ORDER_HISTORY');?></a></li>
				<?php print $this->_tmp_html_after_nav_tab_title ?? ''?>
			</ul>
		<?php endif; ?>

		<div id="editdata-document" class="tab-content">
			<?php if (isJoomla4()) : ?>
				<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'first-page', 'recall' => true, 'breakpoint' => 768]); ?>
				<!-- Description tab -->
				<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'first-page', Text::_('COM_SMARTSHOP_STATUS_CHANGE')); ?>
			<?php endif; ?>
				<div id="first-page" class="tab-pane active">
					<div class="form-group row align-items-center">
						<div  class="col-12 col-form-label  font-weight-bold fw-bold text-center">
							<?php echo JText::_('COM_SMARTSHOP_STATUS_CHANGE')?>:
						</div>
					</div>
					<div class="form-group row align-items-center">
						<label for="status" class="col-2 col-form-label ">
							<?php echo JText::_('COM_SMARTSHOP_ORDER_STATUS')?>
						</label>
						<div  class="col-6 col-form-label ">
							<?php echo $lists['status'];?>
						</div>
						<div  class="col-4 col-form-label ">
							<input type="button" class="button btn btn-primary" name="update_status" onclick="shopOrderAndOffer.verifyStatus(<?php echo $order->order_status?>, <?php echo $order->order_id?>, '<?php echo JText::_('COM_SMARTSHOP_CHANGE_ORDER_STATUS');?>', 1)" value="<?php echo JText::_('COM_SMARTSHOP_UPDATE_STATUS')?>" />
						</div>
					</div>
					<div class="form-group row align-items-center">
						<label for="status" class="col-2 col-form-label ">
						<?php echo JText::_('COM_SMARTSHOP_COMMENT');?>:
						</label>
						<div  class="col-6 col-form-label ">
						<textarea id="comments" class="form-control" name="comments"></textarea>
						</div>
						<div  class="col-4 col-form-label ">
						<input type="checkbox" class="inputbox form-check-input"  name="notify" id="notify" /><label for="notify">  <?php echo JText::_('COM_SMARTSHOP_NOTIFY_USER');?></label><br />
						<input type="checkbox" class="inputbox form-check-input"  name="include" id="include" /><label for="include">  <?php echo JText::_('COM_SMARTSHOP_INCLUDE_COMMENT');?></label>
						</div>
					</div>
				</div>
			<?php if (isJoomla4()) : ?> 
				<?php echo HTMLHelper::_('uitab.endTab'); ?>
				<!-- Details tab -->
				<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'second-page', Text::_('COM_SMARTSHOP_ORDER_HISTORY')); ?>
			<?php endif; ?>
				<div id="second-page" class="tab-pane jshops_edit ">
					<div class="form-group row align-items-center border-bottom">
						<div class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
							<?php echo JText::_('COM_SMARTSHOP_DATE_ADDED');?>
						</div>
						<div class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
							<?php echo JText::_('COM_SMARTSHOP_NOTIFY_CUSTOMER');?>
						</div>
						<div class="col-sm-2 col-md-2 col-xl-2 col-12 col-form-label  font-weight-bold fw-bold">
							<?php echo JText::_('COM_SMARTSHOP_STATUS');?>
						</div>
						<div class="col-sm-2 col-md-2 col-xl-2 col-12 col-form-label  font-weight-bold fw-bold">
							<?php echo JText::_('COM_SMARTSHOP_COMMENT');?>
						</div>
					</div>

					<?php foreach($order_history as $history) : ?>
						<div class="form-group row align-items-center border-bottom">
							<div class="col-sm-4 col-md-4 col-xl-4 col-12">
								<?php echo formatdate($history->status_date_added, 1)?>
							</div>
							<div class="col-sm-4 col-md-4 col-xl-4 col-12">
								<?php $notify_customer = ($history->customer_notify) ? ('<i class="fas fa-check"></i>'): ('<i class="fas fa-minus-circle"></i>');?>
								<?php echo $notify_customer;?>                
							</div>
							<div class="col-sm-2 col-md-2 col-xl-2 col-12">
								<?php echo $history->status_name?>
							</div>
							<div class="col-sm-2 col-md-2 col-xl-2 col-12">
								<?php echo $history->comments?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php if (isJoomla4()) : ?> 
				<?php 
					echo HTMLHelper::_('uitab.endTab'); 
					print $this->_tmp_html_after_nav_tab_body_j4 ?? '';
					echo HTMLHelper::_('uitab.endTabSet');
				?>
			<?php endif; ?>
			<?php print $this->_tmp_html_after_nav_tab_body ?? '';?>
		</div>
  	<?php }?>
</div>
</div>
</div>

<div class="form-group row align-items-top">
	<div class="col-sm-6 col-md-6 col-xl-6 col-12 col-form-label ">
        <div class="admintable striped-block jshops_edit mt-5 jshops_edit">
			<div class="form-group row align-items-center  border-bottom border-bottom">
				<div  class="col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_BILL_TO') ?>				
				</div>
			</div>
        <?php if ($this->config_fields['title']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_USER_TITLE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->title?>
				</div>
			</div>
        <?php } ?>
        <?php if ($this->config_fields['firma_name']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIRMA_NAME')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->firma_name?>
				</div>
			</div>
        <?php } ?>
        <?php if ($this->config_fields['f_name']['display']){?>
       <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FULL_NAME')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->f_name?> <?php print $this->order->l_name?> <?php print $this->order->m_name?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['client_type']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_CLIENT_TYPE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->client_type_name;?>
			</div>
		</div>
        <?php } ?>        
        <?php if ($this->config_fields['firma_code']['display'] && ($this->order->client_type==2 || !$this->config_fields['client_type']['display'])){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIRMA_CODE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->firma_code?>
			</div>
		</div>
        <?php } ?>        
        <?php if ($this->config_fields['tax_number']['display'] && ($this->order->client_type==2 || !$this->config_fields['client_type']['display'])){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_VAT_NUMBER')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->tax_number?>
			</div>
		</div>
        <?php } ?>
		<?php if ($this->config_fields['birthday']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_BIRTHDAY')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->birthday?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['home']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIELD_HOME')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->home?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['apartment']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIELD_APARTMENT')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->apartment?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['street']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_STREET_NR')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->street?> <?php if ($this->config_fields['street_nr']['display']){?><?php print $this->order->street_nr?><?php }?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['city']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_CITY')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->city?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['state']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_STATE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->state?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['zip']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_ZIP')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->zip?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['country']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_COUNTRY')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->country?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['phone']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_TELEFON')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->phone?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['mobil_phone']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_MOBIL_PHONE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->mobil_phone?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['fax']['display']){?>
		<div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FAX')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->fax?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['email']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EMAIL')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->email?>
			</div>
		</div>
        <?php } ?>
        
        <?php if ($this->config_fields['ext_field_1']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_1')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->ext_field_1?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['ext_field_2']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_2')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->ext_field_2?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['ext_field_3']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_3')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->ext_field_3?>
			</div>
		</div>
        <?php } ?>                        
        </div>
    </div>
    <div class="col-sm-6 col-md-6 col-xl-6 col-12 col-form-label ">	   
        <div class="admintable striped-block jshops_edit mt-5 jshops_edit">
			<div class="form-group row align-items-center  border-bottom border-bottom">
				<div  class="col-12 col-form-label  font-weight-bold fw-bold">
					<?php print JText::_('COM_SMARTSHOP_SHIP_TO') ?>
				</div>
			</div>
       
        <?php if ($this->config_fields['title']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_USER_TITLE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_title?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['firma_name']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIRMA_NAME')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_firma_name?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['f_name']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FULL_NAME')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_f_name?> <?php print $this->order->d_l_name?> <?php print $this->order->d_m_name?>
			</div>
		</div>
        <?php } ?>
		<?php if ($this->config_fields['birthday']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_BIRTHDAY')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_birthday?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['home']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIELD_HOME')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_home?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['apartment']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FIELD_APARTMENT')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_apartment?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['street']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_STREET_NR')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_street?> <?php if ($this->config_fields['street_nr']['display']){?><?php print $this->order->d_street_nr?><?php }?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['city']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_CITY')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_city?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['state']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_STATE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_state?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['zip']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_ZIP') ?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_zip ?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['country']['display']){?>
        <div class="form-group row align-items-center  border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_COUNTRY') ?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_country ?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['phone']['display']){?>
        <div class="form-group row align-items-center  border-bottom border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_TELEFON') ?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_phone ?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['mobil_phone']['display']){?>
        <div class="form-group row align-items-center  border-bottom border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_MOBIL_PHONE')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_mobil_phone?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['fax']['display']){?>
        <div class="form-group row align-items-center  border-bottom border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_FAX') ?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_fax ?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['email']['display']){?>
        <div class="form-group row align-items-center  border-bottom border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EMAIL') ?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_email ?>
			</div>
		</div>
        <?php } ?>                            
        <?php if ($this->config_fields['ext_field_1']['display']){?>
        <div class="form-group row align-items-center  border-bottom border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_1')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_ext_field_1?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['ext_field_2']['display']){?>
        <div class="form-group row align-items-center  border-bottom border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_2')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_ext_field_2?>
			</div>
		</div>
        <?php } ?>
        <?php if ($this->config_fields['ext_field_3']['display']){?>
        <div class="form-group row align-items-center  border-bottom border-bottom">
			<label for="title" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label  font-weight-bold fw-bold">
				<?php print JText::_('COM_SMARTSHOP_EXT_FIELD_3')?>:
			</label>
			<div class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php print $this->order->d_ext_field_3?>
			</div>
		</div>
        <?php } ?>
      </div>  
    </div>
<?php print $this->_tmp_html_after_customer_info ?? ''; ?>
</div>

<div class="table-responsive">
<table class="table table-striped" width="100%">
<thead>
<tr>
 <th>
   <?php echo JText::_('COM_SMARTSHOP_NAME_PRODUCT')?>
 </th>
 <?php if ($this->config->show_product_code_in_order){?>
 <th>
   <?php echo JText::_('COM_SMARTSHOP_EAN_PRODUCT')?>
 </th>
 <?php }?>
 <?php if ($this->config->admin_show_vendors){?>
 <th>
   <?php echo JText::_('COM_SMARTSHOP_VENDOR')?>
 </th>
 <?php }?>
 <th>
   <?php echo JText::_('COM_SMARTSHOP_PRICE')?>
 </th>
 <th>
   <?php echo JText::_('COM_SMARTSHOP_QUANTITY')?>
 </th> 
 <th>
   <?php echo JText::_('COM_SMARTSHOP_TOTAL')?>
 </th>
</tr>
</thead>
<?php $i = 0; foreach ($order_item as $item){
	$i++;
	$files = '';
    if($item->files && is_array($item->files)) { $files = $item->files; }elseif($item->files){ $files = unserialize($item->files); }
?>
<tr>
 <td>
   <a target="_blank" href="index.php?option=com_jshopping&controller=products&task=edit&product_id=<?php print $item->product_id?>">
    <?php echo $item->product_name?>
   </a><br />
   <?php print sprintAtributeInOrder($item->product_attributes).sprintFreeAtributeInOrder($item->product_freeattributes);?>
   <?php print $item->_ext_attribute_html ?? '';?>
	
	<br>
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
	<div>
		
  <?php 
  // TODO: Replace to correct upload code!!!
  if(isset($item->upload_file_product) && $item->upload_file_product){ ?>
		<a href="<?php print $this->config->live_path; ?>files/files_upload/<?php print $item->upload_file_product;?>"><?php print $item->upload_file_product;?></a>
	<?php } ?>
	<?php if(isset($item->upload_file_description) && $item->upload_file_description){ ?>
		<div class="upload_description"><?php print $item->upload_file_description;?></div>
	<?php } ?>
    <?php print $item->_ext_file_html ?? '';?>
 </td>
 <?php if ($this->config->show_product_code_in_order){?>
 <td>
   <?php echo $item->product_ean?>
 </td>
 <?php }?>
 <?php if ($this->config->admin_show_vendors){?>
 <td>
   <?php echo isset($this->order_vendors) ? $this->order_vendors[$item->vendor_id]->f_name." ".$this->order_vendors[$item->vendor_id]->l_name : ''; ?>
 </td>
 <?php }?>
 <td>
   <?php echo formatprice($item->product_item_price, $order->currency_code);?>
   <?php if (isset($item->_ext_price_html)) print $item->_ext_price_html?>
 </td>
 <td>
   <?php if (isset($item->product_quantity)) echo formatqty($item->product_quantity)?><?php if (isset($item->_qty_unit)) print $item->_qty_unit?>
 </td> 
 <td>
   <?php echo formatprice($item->product_quantity * $item->product_item_price, $order->currency_code);?>
   <?php if (isset($item->_ext_price_total_html)) print $item->_ext_price_total_html?>
 </td>
</tr>
<?php }?>
</table>
</div>
<?php if (!isset($this->display_info_only_product) || !$this->display_info_only_product){?>

<div class="admintable striped-block jshops_edit mt-3">
	<div class="form-group row align-items-center border-top">
		<label for="title" class="col-sm-12 col-md-12 col-xl-12 col-12 col-form-label  font-weight-bold fw-bold text-right text-end">
			<?php if ($this->config->show_weight_order){?>  
				<div style="text-align:right;">
					<i><?php print JText::_('COM_SMARTSHOP_WEIGHT_PRODUCTS')?>: <span><?php print formatweight($this->order->weight);?></span></i>
				</div><br/>
			<?php }?>
		</label>
	</div>
	<div class="form-group row align-items-center border-top">
		<label for="title" class="col-sm-10 col-md-10 col-xl-10 col-10 col-form-label  font-weight-bold fw-bold text-right text-end">
    <?php echo JText::_('COM_SMARTSHOP_SUBTOTAL')?>
		 </label>
		<div class="col-sm-2 col-md-2 col-xl-2 col-2 font-weight-bold fw-bold">
   <?php if (isset($order->order_subtotal) && isset($order->currency_code)) echo formatprice($order->order_subtotal, $order->currency_code);?><?php if (isset($this->_tmp_ext_subtotal)) print $this->_tmp_ext_subtotal?>
 </div>
</div>
<?php print $this->_tmp_html_after_subtotal ?? ''?>
<?php if ($order->order_discount > 0){?>
	<div class="form-group row align-items-center border-top">
		<label for="title" class="col-sm-10 col-md-10 col-xl-10 col-10 col-form-label  font-weight-bold fw-bold text-right text-end">
    <?php echo JText::_('COM_SMARTSHOP_COUPON_DISCOUNT')?>
    <?php if ($order->coupon_id){?>(<?php print $order->coupon_code?>)<?php }?>
		 </label>
		<div class="col-sm-2 col-md-2 col-xl-2 col-2 font-weight-bold fw-bold">
   <?php echo formatprice(-$order->order_discount, $order->currency_code);?><?php print $this->_tmp_ext_discount ?? ''?>
 </div>
</div>
<?php } ?>

<?php if (!$this->config->without_shipping || $order->order_shipping > 0){?>
	<div class="form-group row align-items-center">
		<label for="title" class="col-sm-10 col-md-10 col-xl-10 col-10 col-form-label  font-weight-bold fw-bold text-right text-end">
    <?php echo JText::_('COM_SMARTSHOP_SHIPPING_PRICE')?>
		 </label>
		<div class="col-sm-2 col-md-2 col-xl-2 col-2 font-weight-bold fw-bold">
   <?php if (isset($order->order_shipping) && isset($order->currency_code)) echo formatprice($order->order_shipping, $order->currency_code);?><?php if (isset($this->_tmp_ext_shipping)) print $this->_tmp_ext_shipping?>
 </div>
</div>
<?php } ?>
<?php if (!$this->config->without_shipping || $order->order_package > 0){?>
	<div class="form-group row align-items-center">
		<label for="title" class="col-sm-10 col-md-10 col-xl-10 col-10 col-form-label  font-weight-bold fw-bold text-right text-end">
    <?php echo JText::_('COM_SMARTSHOP_PACKAGE_PRICE')?>
		 </label>
		<div class="col-sm-2 col-md-2 col-xl-2 col-2 font-weight-bold fw-bold">
   <?php echo formatprice($order->order_package, $order->currency_code);?><?php print $this->_tmp_ext_shipping_package?>
 </div>
</div>
<?php } ?>

<?php if ($order->order_payment > 0){?>
	<div class="form-group row align-items-center">
		<label for="title" class="col-sm-10 col-md-10 col-xl-10 col-10 col-form-label  font-weight-bold fw-bold text-right text-end">
     <?php print $order->payment_name;?>
		 </label>
		<div class="col-sm-2 col-md-2 col-xl-2 col-2 font-weight-bold fw-bold">
   <?php if (isset($order->order_payment) && isset($order->currency_code)) echo formatprice($order->order_payment, $order->currency_code);?><?php if (isset($this->_tmp_ext_payment)) print $this->_tmp_ext_payment?>
 </div>
</div>
<?php } ?>

<?php if (!$this->config->hide_tax){?>
    <?php foreach($order->order_tax_list as $percent=>$value){
		if ($value>0) {?>
		<div class="form-group row align-items-center">
		<label for="title" class="col-sm-10 col-md-10 col-xl-10 col-10 col-form-label  font-weight-bold fw-bold text-right text-end">
          <?php if ((double)$percent==0) {
				$tmp=explode('_',substr($percent,15,strlen($percent)));
				echo JSFactory::getTable('taxextadditional', 'jshop')->getAllAdditionalTaxes((double)$tmp[0])[0]->name." ";
				print $tmp[1]."%";						
		  }else{
			  print displayTotalCartTaxName($order->display_price);
			  print $percent."%";
			  }?>
		 </label>
		<div class="col-sm-2 col-md-2 col-xl-2 col-2 font-weight-bold fw-bold">
          <?php if (isset($value) && isset($order->currency_code)) print formatprice($value, $order->currency_code);?><?php if (isset($this->_tmp_ext_tax[$percent])) print $this->_tmp_ext_tax[$percent]?>
         </div>
		</div>
    <?php }}?>
	<?php print $this->_tmp_html_after_tax ?? ''?>
<?php }?>
	<div class="form-group row align-items-center">
		<label for="title" class="col-sm-10 col-md-10 col-xl-10 col-10 col-form-label  font-weight-bold fw-bold text-right text-end">
			<?php echo JText::_('COM_SMARTSHOP_TOTAL')?>
		 </label>
		<div class="col-sm-2 col-md-2 col-xl-2 col-2 font-weight-bold fw-bold">
   <?php if (isset($order->order_total) && isset($order->currency_code)) echo formatprice($order->order_total, $order->currency_code);?><?php if (isset($this->_tmp_ext_total)) print $this->_tmp_ext_total?>
 </div>
</div>
<?php print $this->_tmp_html_after_total ?? ''?>
</div>
<?php }?>

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
    <th width="34%">
    <?php echo JText::_('COM_SMARTSHOP_CUSTOMER_COMMENT')?>
    </th>
</tr>
</thead>
<tr>
    <?php if (!$this->config->without_shipping){?>
    <td valign="top">
        <div style="padding-bottom:4px;"><?php echo $order->shipping_info?></div>
        <div><i><?php echo nl2br($order->shipping_params)?></i></div>
        <?php if ($order->delivery_time_name){?>
        <div><?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME').": ".$order->delivery_time_name?></div>
        <?php }?>
        <?php if ($order->delivery_date_f){?>
        <div><?php echo JText::_('COM_SMARTSHOP_DELIVERY_DATE').": ".$order->delivery_date_f?></div>
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

<?php if (count($this->stat_download)){?>
<br/>
<table class="adminlist order_stat_file_download">
<thead>
<tr>
    <th width="50%">
        <?php echo JText::_('COM_SMARTSHOP_FILE_SALE')?>
    </th>
    <th>
        <?php echo JText::_('COM_SMARTSHOP_COUNT_DOWNLOAD')?>
    </th>
    <th>
        <?php echo JText::_('COM_SMARTSHOP_DATE')?>
    </th>
</tr>
</thead>
<?php foreach($this->stat_download as $v){?>
<tr>
    <td><?php print $v->file_descr?></td>
    <td><?php print $v->count_download?></td>
    <td><?php if ($v->time) print formatdate($v->time, 1)?></td>
</tr>
<?php }?>
</table>
<div class="order_stat_file_download_clear">
    <a onclick="return confirm('<?php print JText::_('COM_SMARTSHOP_CLEAR')?>')" href="index.php?option=com_jshopping&controller=orders&task=stat_file_download_clear&order_id=<?php print $order->order_id?>"><?php print JText::_('COM_SMARTSHOP_CLEAR')?></a>
</div>
<?php }?>
<?php print $this->_ext_end_html ?? ''?>
<input type="hidden" name="task" value="" />
<input type="hidden" name="js_nolang" id='js_nolang' value="0" />
<?php print $this->tmp_html_end ?? ''?>
</form>
</div>
<script type = "text/javascript">
Joomla.submitbutton = function(task){
    if (task=='send'){
        document.getElementById('js_nolang').value='1';
    }
    Joomla.submitform(task, document.getElementById('adminForm'));
}
</script>
<style>
.product_line{
	border:1px solid grey;
	margin:5px;
	
}
.package{
	border:1px solid grey;
	margin:10px;
	
}
.package_products,	
.return_package_products{	
	padding:10px;	
}
.package_add_new{
	margin:5px;
	color:blue;
	cursor:pointer;
}
.package_product_line,
.return_package_product_line{
	background-color:#eee;
}
</style>

<div class="col-12 col-form-label  font-weight-bold">
	<?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_SHIPPING');?>
</div>
<div class="container-fluid package" id='shipping_packages'>
<input type='hidden' value="" id="shipping_packages_products" name="shipping_packages_products">
<?php $i=0;
foreach ($order_item as $key=>$product){
	$prdcts[$product->product_id]=$product;
	$i++;
	$product_quantity = $product->product_quantity;
	foreach ($this->order_packages as $key=>$pack){
		$pack_products=get_object_vars(json_decode($pack->products));		
		if ($pack_products[$product->product_id]){
			$product_quantity = $product_quantity - $pack_products[$product->product_id];
		}
	}
	if ($product_quantity>0){
	?>
	<div id="p<?php echo $product->product_id;?>" draggable="true" class="row package_products">
		<div class="col font-weight-bold fw-bold">
			<a class="btn btn-micro" style='display:none' href="#" ><i class="icon-delete"></i></a> <?php echo "# ".$product->product_name;?>
		</div>
		<div class="col">
			<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_QUANTITY').": ".(int)$product_quantity;?></span>
		</div>
		<div class="col">
			<input type='hidden' name="package_product_quantity[]" value='<?php echo $product_quantity;?>'>
			<input type='hidden' name="package_product_id[]" value='<?php echo $product->product_id;?>'>
		</div>

	</div>	
	<?php
	}
}
?>
</div>

<div id='packages'>	
	<?php $i=1;foreach ($this->order_packages as $key=>$pack){?>
	<div class="container-fluid package" id='pack<?php echo $i;?>'>
		<div class="row">
			<div class="col font-weight-bold fw-bold"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_PACKAGE');?> </div>
			<div class="col"><?php echo $i;?></div>
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_STATUS');?></div>	
			<div class="col"><input disabled type="text" name="package_status[]" class="middle mb-1 form-control" value="<?php echo $pack->package_status;?>"></div>
		</div>
		<div class="row">
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_PROVIDER');?>: </div>
			<div class="col"><input disabled  type="text" name="package_provider[]" class="middle form-control" value="<?php echo $pack->package_provider;?>"></div>
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_TRACKING_NUMBER');?>: </div>
			<div class="col"><input disabled  type="text" name="package_tracking[]" class="middle form-control" value="<?php echo $pack->package_tracking;?>"></div>
		</div>
		<div class="row package_products" id="target" id='package_id_<?php echo $i;?>'>			
			<?php 
			$pack_products=get_object_vars(json_decode($pack->products));//print_r($pack_products);			
			foreach ($pack_products as $key=>$val){
			?>			
			<div id="pack<?php echo $pack->package;?>_p<?php echo $prdcts[$key]->product_id;?>" draggable="true" class="row package_product_line product_line">
				<div class="col font-weight-bold fw-bold">
					 <?php echo "# ".$prdcts[$key]->product_name;?>
				</div>
				<div class="col">
				<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_QUANTITY').": ".(int)$val."</span>";?></span>
				</div>
				<div class="col">
					<input type='hidden' name="package_product_quantity[]" value='<?php echo $val;?>'>
					<input type='hidden' name="package_product_id[]" value='<?php echo $prdcts[$key]->product_id;?>'>
				</div>

			</div>
			<?php } ?>
			</span>
		</div>
	</div>	
	<?php $i++;}?>
</div>


<div class="col-12 col-form-label pt-4 font-weight-bold">
	<?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_RETURNS');?>
</div>
<div class="container-fluid package" id='returns_packages'>
<input type='hidden' value="" id="returns_packages_products" name="returns_packages_products">
<?php $i=0;
foreach ($order_item as $key=>$product){
	$prdcts[$product->order_item_id]=$product;
	$i++;
	$product_quantity=$product->product_quantity;
	foreach ($this->return_packages as $key=>$pack){
		$pack_products=$pack->products;	
		if ($pack_products[$product->order_item_id]){ 
			$product_quantity -= $pack_products[$product->order_item_id]->quantity;
		}
	}
	if ($product_quantity>0){
	?>
	<div id="p<?php echo $product->order_item_id;?>" draggable="true" class="row return_package_products">
		<div class="col font-weight-bold fw-bold">
			<a class="btn btn-micro" style='display:none' href="#" ><i class="icon-delete"></i></a> <?php echo "# ".$product->product_name;?>
		</div>
		<div class="col">
			<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_QUANTITY').": ".(int)$product_quantity;?></span>
		</div>
		<div class="col">
			<input type='hidden' name="return_package_product_quantity[]" value='<?php echo $product_quantity;?>'>
			<input type='hidden' name="return_package_product_id[]" value='<?php echo $product->order_item_id;?>'>
		</div>

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
			<div class="col font-weight-bold fw-bold"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_RETURN');?> </div>
			<div class="col"><?php echo $i;?></div>
			<div class="col"><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_STATUS');?></div>	
			<div class="col"><input disabled type="text" name="return_package_status[]" class="middle mb-1 form-control" value="<?php echo $pack->package_status;?>"></div>
		</div>
		<div class="row return_package_products" id="target" id='return_package_id_<?php echo $i;?>'>			
			<?php 
			$pack_products=$pack->products;		
			foreach ($pack_products as $key=>$val){
			?>			
			<div id="return_pack<?php echo $pack->package;?>_p<?php echo $prdcts[$key]->order_item_id;?>" draggable="true" class="row return_package_product_line product_line">
				<div class="col font-weight-bold fw-bold">
					 <?php echo "# ".$prdcts[$key]->product_name;?>
				</div>
				<div class="col">
				<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_QUANTITY').": ".(int)$val->quantity."</span>";?></span>
				</div>
				<div class="col">
				<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_RETURN_REASON').": "; ?><?php print $this->return_status_list[(int)$val->return_status_id]->name ?? JText::_('COM_SMARTSHOP_ORDEREDIT_NO_REASON'); ?></span></span>
				</div>
				
				<div class="row row_target">
					<div class="col">
						<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_CUSTOMER_COMMENT') ?>: </span>
						<span><?php echo $val->customer_comment ?></span>
					</div>
				</div>
				<div class="row row_target">
					<div class="col">
						<span><?php echo JText::_('COM_SMARTSHOP_ORDEREDIT_ADMIN_NOTICE') ?>: </span>
						<span><?php echo $val->admin_notice ?></span>
					</div>
				</div>

			</div>
			<?php } ?>
			</span>
		</div>
	</div>	
	<?php $i++;}?>
</div>