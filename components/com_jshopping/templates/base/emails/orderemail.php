
	<?php echo $this->_tmp_ext_html_ordermail_start; ?>
	<table width="794px" align="center" border="0" cellspacing="0" cellpadding="0" style="line-height:100%;">
		<tr valign="top">
			<td colspan="2"><?php echo $this->info_shop; ?></td>
		</tr>

		<?php if ($this->client) : ?>
			<tr>
				<td colspan="2" style="padding-bottom:10px;"><?php echo $this->order_email_descr; ?></td>
			</tr>
		<?php endif; ?>

		<tr class="bg_gray">
			<td colspan="2"><h3><?php echo JText::_('COM_SMARTSHOP_EMAIL_PURCHASE_ORDER'); ?></h3></td>
		</tr>

		<tr>
			<td style="height:10px;font-size:1px;">&nbsp;</td>
		</tr>

		<tr>
			<td width="50%"><?php echo JText::_('COM_SMARTSHOP_ORDER_NUMBER'); ?>:</td>
			<td width="50%"><?php echo $this->order->order_number; ?></td>
		</tr>

		<tr>
			<td><?php echo JText::_('COM_SMARTSHOP_ORDER_DATE'); ?>:</td>
			<td><?php echo $this->order->order_date; ?></td>
		</tr>

		<tr>
			<td><?php echo JText::_('COM_SMARTSHOP_ORDER_STATUS'); ?>:</td>
			<td><?php echo $this->order->status; ?></td>
		</tr>

		<?php if ($this->show_customer_info){?>
			<tr>
				<td style="height:10px;font-size:1px;">&nbsp;</td>
			</tr>

			<tr class="bg_gray">
				<td colspan="2" width = "50%"><h3><?php echo JText::_('COM_SMARTSHOP_CUSTOMER_INFORMATION'); ?></h3></td>
			</tr>

			<tr>
				<td style="vertical-align:top;padding-top:10px;" width="50%">
					<table cellspacing="0" cellpadding="0" style="line-height:100%;">
						<tr>
							<td colspan="2">
								<b><?php echo JText::_('COM_SMARTSHOP_EMAIL_BILL_TO'); ?></b>
							</td>
						</tr>

						<?php if ($this->config_fields['firma_name']['display']) : ?>
							<tr>
								<td width="100"><?php echo JText::_('COM_SMARTSHOP_FIRMA_NAME'); ?>:</td>
								<td><?php echo $this->order->firma_name; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['title']['display']) : ?>
							<tr>
								<td width="100"><?php echo JText::_('COM_SMARTSHOP_REG_TITLE'); ?>:</td>
								<td><?php echo $this->order->title; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['f_name']['display'] || $this->config_fields['m_name']['display'] || $this->config_fields['l_name']['display']) : ?>
							<tr>
								<td width="100"><?php echo JText::_('COM_SMARTSHOP_FULL_NAME') ?>:</td>
								<td><?php
									echo ($this->config_fields['f_name']['display']) ? $this->order->f_name . ' ': '';
									echo ($this->config_fields['m_name']['display']) ? $this->order->m_name . ' ': '';
									echo ($this->config_fields['l_name']['display']) ? $this->order->l_name: '';
								?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['birthday']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_BIRTHDAY'); ?>:</td>
								<td><?php echo $this->order->birthday; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['firma_code']['display'] && ($this->order->client_type==2 || !$this->config_fields['client_type']['display'])) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_FIRMA_CODE'); ?>:</td>
								<td><?php echo $this->order->firma_code; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['tax_number']['display'] && ($this->order->client_type==2 || !$this->config_fields['client_type']['display'])) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_VAT_NUMBER'); ?>:</td>
								<td><?php echo $this->order->tax_number; ?></td>
							</tr>
						<?php endif; ?>
						
						<?php if ($this->config_fields['home']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_HOME'); ?>:</td>
								<td><?php echo $this->order->home; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['apartment']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_APARTMENT'); ?>:</td>
								<td><?php echo $this->order->apartment; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['street']['display'] || $this->config_fields['street_nr']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_STREET'); ?>:</td>
								<td><?php
									echo ($this->config_fields['street']['display']) ? $this->order->street . ' ': ''; 
									echo ($this->config_fields['street_nr']['display']) ? $this->order->street_nr: '';
								?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['zip']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_ZIP'); ?>:</td>
								<td><?php echo $this->order->zip; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['state']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_STATE'); ?>:</td>
								<td><?php echo $this->order->state; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['city']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_CITY'); ?>:</td>
								<td><?php echo $this->order->city; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['country']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_COUNTRY'); ?>:</td>
								<td><?php echo $this->order->country; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['phone']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_TELEFON'); ?>:</td>
								<td><?php echo $this->order->phone; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['mobil_phone']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_MOBIL_PHONE'); ?>:</td>
								<td><?php echo $this->order->mobil_phone; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['fax']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_FAX'); ?>:</td>
								<td><?php echo $this->order->fax; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['email']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_EMAIL'); ?>:</td>
								<td><?php echo $this->order->email; ?></td>
							</tr>
						<?php endif; ?>
						
						<?php if ($this->config_fields['ext_field_1']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_EXT_FIELD_1'); ?>:</td>
								<td><?php echo $this->order->ext_field_1; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['ext_field_2']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_EXT_FIELD_2'); ?>:</td>
								<td><?php echo $this->order->ext_field_2; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->config_fields['ext_field_3']['display']) : ?>
							<tr>
								<td><?php echo JText::_('COM_SMARTSHOP_EXT_FIELD_3'); ?>:</td>
								<td><?php echo $this->order->ext_field_3; ?></td>
							</tr>
						<?php endif; ?>
						
					</table>
				</td>

				<td style="vertical-align:top;padding-top:10px;" width="50%">
					
						<table cellspacing="0" cellpadding="0" style="line-height:100%;">
							<tr>
								<td colspan=2>
									<b><?php echo JText::_('COM_SMARTSHOP_EMAIL_SHIP_TO'); ?></b> 
								</td>
							</tr>

							<?php if ($this->config_fields['firma_name']['display']) : ?>
								<tr>
									<td width="100"><?php echo JText::_('COM_SMARTSHOP_FIRMA_NAME'); ?>:</td>
									<td ><?php echo $this->order->d_firma_name; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['title']['display']) : ?>
								<tr>
									<td width="100"><?php echo JText::_('COM_SMARTSHOP_REG_TITLE'); ?>:</td>
									<td><?php echo $this->order->d_title; ?></td>
								</tr>
							<?php endif; ?> 

							<?php if ($this->config_fields['f_name']['display'] || $this->config_fields['m_name']['display'] || $this->config_fields['l_name']['display']) : ?>
								<tr>
									<td width="100"><?php echo JText::_('COM_SMARTSHOP_FULL_NAME'); ?></td>
									<td><?php 
										echo ($this->config_fields['f_name']['display']) ? $this->order->d_f_name . ' ': '';
										echo ($this->config_fields['m_name']['display']) ? $this->order->d_m_name . ' ': '';
										echo ($this->config_fields['l_name']['display']) ? $this->order->d_l_name: '';
									?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['birthday']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_BIRTHDAY'); ?>:</td>
									<td><?php echo $this->order->d_birthday; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['home']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_HOME'); ?>:</td>
									<td><?php echo $this->order->d_home; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['apartment']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_APARTMENT'); ?>:</td>
									<td><?php echo $this->order->d_apartment; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['street']['display'] || $this->config_fields['street_nr']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_STREET') ?>:</td>
									<td><?php 
										echo ($this->config_fields['street']['display']) ? $this->order->d_street . ' ': ''; 
										echo ($this->config_fields['street_nr']['display']) ? $this->order->d_street_nr: '';
									?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['zip']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_ZIP'); ?>:</td>
									<td><?php echo $this->order->d_zip; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['state']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_STATE'); ?>:</td>
									<td><?php echo $this->order->d_state; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['city']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_CITY'); ?>:</td>
									<td><?php echo $this->order->d_city; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['country']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_COUNTRY'); ?>:</td>
									<td><?php echo $this->order->d_country; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['phone']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_TELEFON'); ?>:</td>
									<td><?php echo $this->order->d_phone; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['mobil_phone']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_MOBIL_PHONE'); ?>:</td>
									<td><?php echo $this->order->d_mobil_phone; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['fax']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_FAX'); ?>:</td>
									<td><?php echo $this->order->d_fax; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['email']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_EMAIL'); ?>:</td>
									<td><?php echo $this->order->d_email; ?></td>
								</tr>
							<?php endif; ?>    

							<?php if ($this->config_fields['ext_field_1']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_EXT_FIELD_1'); ?>:</td>
									<td><?php echo $this->order->d_ext_field_1; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['ext_field_2']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_EXT_FIELD_2'); ?>:</td>
									<td><?php echo $this->order->d_ext_field_2; ?></td>
								</tr>
							<?php endif; ?>

							<?php if ($this->config_fields['ext_field_3']['display']) : ?>
								<tr>
									<td><?php echo JText::_('COM_SMARTSHOP_EXT_FIELD_3'); ?>:</td>
									<td><?php echo $this->order->d_ext_field_3; ?></td>
								</tr>
							<?php endif; ?>            
						</table>
					
				</td>
			</tr>
		<?php } ?>

		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>

		<tr>
			<td colspan="2" class="bg_gray">
				<h3><?php echo JText::_('COM_SMARTSHOP_ORDER_ITEMS'); ?></h3>
			</td>
		</tr>

		<tr>
			<td colspan="2" style="padding:0px;padding-top:10px;">
				<table width="100%" cellspacing="0" cellpadding="0" class="table_items2">
					<tr>
						<td colspan="5" style="vertical-align:top;padding-bottom:5px;font-size:1px;"><div style="height:1px;border-top:1px solid #999;"></div></td>
					</tr>

					<tr class = "bold">            
						<td width="45%" style="padding-left:10px;padding-bottom:5px;"><?php echo JText::_('COM_SMARTSHOP_NAME_PRODUCT') ?></td>            
						<td width="15%" style="padding-bottom:5px;"><?php if ($this->config->show_product_code_in_order) { echo JText::_('COM_SMARTSHOP_EAN_PRODUCT'); } ?></td>
						<td width="10%" style="padding-bottom:5px;"><?php echo JText::_('COM_SMARTSHOP_QUANTITY'); ?></td>
						<?php if($this->config->single_item_price): ?>	
							<td width="15%" style="padding-bottom:5px;"><?php echo JText::_('COM_SMARTSHOP_SINGLEPRICE'); ?></td>
						<?php endif; ?>
						<td width="15%" style="padding-bottom:5px;"><?php echo JText::_('COM_SMARTSHOP_PRICE_TOTAL'); ?></td>
					</tr>

					<tr>
						<td colspan="5" style="vertical-align:top;padding-bottom:10px;font-size:1px;"><div style="height:1px;border-top:1px solid #999;"></div></td>
					</tr>
					<!-- Product loop -->
					<?php $jsUri = JSFactory::getJSUri(); foreach($this->products as $key_id=>$prod) :
						if(!is_array($prod->files)){
							$files = unserialize($prod->files);				
						}else{
							$files = $prod->files;		
						}
						
						$urlToProductImg = !empty($prod->thumb_image) ? getPatchProductImage($prod->thumb_image, '', 3): ($this->config->image_product_live_path . '/' . $this->noimage);
					?>
						<tr class="vertical">
							<td>
								<img src="<?php echo $urlToProductImg; ?>" align="left" style="margin-right:5px; max-width:200px" width="200">
								<?php echo $prod->product_name; ?>
								<?php if (($prod->publish_editor_pdf==1) && ($this->order->products_pdf[$prod->product_id]!="") && $this->isOrderHasBeenPaid) {?>
									<?php echo "<br>".$this->order->products_pdf;?>
								<?php } ?>
								<?php if ($prod->manufacturer!='') : ?>
									<div class="manufacturer"><?php echo JText::_('COM_SMARTSHOP_MANUFACTURER') ?>: <span><?php echo $prod->manufacturer?></span></div>
								<?php endif; ?>

								<div class="jshop_cart_attribute">
									<?php 
										echo sprintAtributeInOrder($prod->product_attributes);
										echo sprintFreeAtributeInOrder($prod->product_freeattributes);
										if(!empty($prod->extra_fields)){
                                            echo separateMailExtraFieldsWithUseCharactParams(json_decode($prod->extra_fields));
                                        }

									?>
								</div>
								<?php echo $prod->_ext_attribute_html; ?>

								<?php if ($this->config->display_delivery_time_for_product_in_order_mail && $prod->delivery_time) : ?>
									<div class="deliverytime"><?php echo JText::_('COM_SMARTSHOP_DELIVERY_TIME') ?>: <?php echo $prod->delivery_time; ?></div>
								<?php endif; ?>
									
								<?php if (!empty($prod->uploadData['files'])) : 
									$countOfUploadedFiles = count($prod->uploadData['files']);

									for($i = 0; $i < $countOfUploadedFiles; $i++) :
										$uploadedFileName = $prod->uploadData['files'][$i] ?: '';
								?>
									<div class="uploadBlock__title"><?php echo JText::_('COM_SMARTSHOP_UPLOADS'); ?></div>
									<div><a href="<?php echo JUri::root() . '/components/com_jshopping/files/files_upload/' . $uploadedFileName; ?>" target="_blank"><?php echo $uploadedFileName; ?></a> - <?php echo $prod->uploadData['qty'][$i];?></div>
									<div><?php echo $prod->uploadData['descriptions'][$i] ?: ''; ?></div>
								<?php endfor; endif; ?>
							</td>   

							<td>
								<?php if ($this->config->show_product_code_in_order) { 
									echo $prod->product_ean; 
								} ?>
							</td>

							<td>
								<?php echo formatqty($prod->product_quantity) . ' ' . $prod->_qty_unit; ?>
							</td>

							<?php if($this->config->single_item_price): ?>	
								<td>
									<?php 
										echo precisionformatprice($prod->product_item_price, $this->order->currency_code);
										echo $prod->_ext_price_html;
									?>

									<?php if ($this->config->show_tax_product_in_cart && $prod->product_tax > 0) : ?>
										<div class="taxinfo"><?php echo productTaxInfo($prod->product_tax, $this->order->display_price); ?></div>
										<?php echo $this->html->_tmp_html_after_product_tax_singl_item[$key_id]; ?>
									<?php endif; ?>

									<?php if ($this->config->cart_basic_price_show && $prod->basicprice > 0) : ?>
										<div class="basic_price"><?php echo JText::_('COM_SMARTSHOP_BASIC_PRICE'); ?>: <span><?php echo sprintBasicPrice($prod); ?></span></div>
									<?php endif; ?>
								</td>
							<?php endif; ?>
							<td>
								<?php 
									echo formatprice($prod->total_price, $this->order->currency_code);
									echo $prod->_ext_price_total_html; 
								?>

								<?php if ($this->config->show_tax_product_in_cart && $prod->product_tax > 0) : ?>
									<div class="taxinfo"><?php echo productTaxInfo($prod->product_tax, $this->order->display_price); ?></div>
									<?php echo $this->html->_tmp_html_after_product_tax[$key_id]; ?>
								<?php endif; ?>
							</td>
						</tr>
						
						<!-- Render product files -->
						<?php if (count($files)) : ?>
							<tr>
								<td colspan="5">
									<?php foreach($files as $file) : ?>
										<?php if($file->file){ ?>
											<div><?php echo $file->file_descr; ?> <a href="<?php print JURI::root(); ?>index.php?option=com_jshopping&controller=product&task=getfile&oid=<?php echo $this->order->order_id; ?>&id=<?php echo $file->id; ?>&hash=<?php echo $this->order->file_hash; ?>"><?php echo JText::_('COM_SMARTSHOP_DOWNLOAD'); ?></a></div>
										<?php } ?>    
									<?php endforeach; ?>    
								</td>
							</tr>
						<?php endif; ?>
						<!-- Render product files END -->

						<tr>
							<td colspan="5" style="vertical-align:top;padding-bottom:10px;font-size:1px;"><div style="height:1px;border-top:1px solid #999;"></div></td>
						</tr>
					<?php endforeach; ?>
					<!-- Product loop END -->

					<?php if ($this->show_weight_order && $this->config->show_weight_order) : ?>
						<tr>
							<td colspan="5" style="text-align:right;font-size:11px;">            
								<?php echo JText::_('COM_SMARTSHOP_WEIGHT_PRODUCTS'); ?>: <span><?php echo formatweight($this->order->weight); ?></span>
							</td>
						</tr>   
					<?php endif; ?>

					<?php if ($this->show_total_info) : ?>
						<tr>
							<td colspan="5">&nbsp;</td>
						</tr>

						<?php if (!$this->hide_subtotal){?>
							<tr>
								<td colspan="4" align="right" style="padding-right:15px;"><?php echo JText::_('COM_SMARTSHOP_SUBTOTAL'); ?>:</td>
								<td class="price"><?php echo formatprice($this->order->order_subtotal, $this->order->currency_code); ?><?php echo $this->_tmp_ext_subtotal; ?></td>
							</tr>
						<?php } ?>

						<?php echo $this->_tmp_html_after_subtotal; ?>

						<?php if ($this->order->order_discount > 0) : ?>
							<tr>
								<td colspan="4" align="right" style="padding-right:15px;"><?php echo JText::_('COM_SMARTSHOP_RABATT_VALUE'); ?>: </td>
								<td class="price">-<?php echo formatprice($this->order->order_discount, $this->order->currency_code); ?><?php echo $this->_tmp_ext_discount; ?></td>
							</tr>
						<?php endif; ?>

						<?php if (!$this->config->without_shipping) : ?>
							<tr>
								<td colspan="4" align="right" style="padding-right:15px;"><?php echo JText::_('COM_SMARTSHOP_SHIPPING_PRICE'); ?>:</td>
								<td class="price"><?php echo formatprice($this->order->order_shipping, $this->order->currency_code); ?><?php echo $this->_tmp_ext_shipping; ?></td>
							</tr>
						<?php endif; ?>

						<?php if (!$this->config->without_shipping && ($this->order->order_package>0 || $this->config->display_null_package_price)) : ?>
							<tr>
								<td colspan="4" align="right" style="padding-right:15px;"><?php echo JText::_('COM_SMARTSHOP_PACKAGE_PRICE'); ?>:</td>
								<td class="price"><?php echo formatprice($this->order->order_package, $this->order->currency_code); echo $this->_tmp_ext_shipping_package; ?></td>
							</tr>
						<?php endif; ?>

						<?php if ($this->order->order_payment != 0) : ?>
							<tr>
								<td colspan="4" align="right" style="padding-right:15px;"><?php echo $this->order->payment_name; ?>:</td>
								<td class="price"><?php echo formatprice($this->order->order_payment, $this->order->currency_code); echo $this->_tmp_ext_payment; ?></td>
							</tr>
						<?php endif; ?>

						<?php if (!$this->config->hide_tax) : ?>                           
							<?php foreach($this->order->order_tax_list as $percent => $value) 
							{ 
								if ($value>0) { ?>
								<tr>
									<td colspan="4" align="right" style="padding-right:15px;">
									<?php if ((double)$percent==0) {
										$tmp=explode('_',substr($percent,15,strlen($percent)));
										echo displayTotalCartTax().JSFactory::getTable('taxextadditional', 'jshop')->getAllAdditionalTaxes((double)$tmp[0])[0]->name." ";
										$percent=$tmp[1];
									}else { ?>
									<?php echo displayTotalCartTaxName($this->order->display_price);
									} 
									if ($this->show_percent_tax) echo ' ' . formattax($percent) . '%'; ?>:</td>
									<td class="price"><?php echo formatprice($value, $this->order->currency_code); echo $this->_tmp_ext_tax[$percent]; ?></td>
								</tr>
								<?php }}; ?>
								<?php echo $this->html->_tmp_html_after_tax; ?>
						<?php endif; ?>

						<tr>
							<td colspan="4" align="right" style="padding-right:15px;"><b><?php echo $this->text_total ?>:</b></td>
							<td class="price"><b><?php echo formatprice($this->order->order_total, $this->order->currency_code); echo $this->_tmp_ext_total; ?></b></td>
						</tr>

						<?php echo $this->_tmp_html_after_total; ?>
						<tr>
							<td colspan="5">&nbsp;</td>
						</tr>

						<?php if (!$this->client) : ?>
							<tr>
								<td colspan="5" class="bg_gray"><?php echo JText::_('COM_SMARTSHOP_CUSTOMER_NOTE'); ?></td>
							</tr>

							<tr>
								<td colspan="5" style="padding-top:10px;"><?php echo $this->order->order_add_info; ?></td>
							</tr>

							<tr><td>&nbsp;</td></tr>
						<?php endif; ?>
					<?php endif; ?>
				</table>
			</td>
		</tr>

		<?php if ($this->show_payment_shipping_info) :
			if (!$this->config->without_payment || !$this->config->without_shipping) : ?>  
			<tr class = "bg_gray">
				<?php if (!$this->config->without_payment) : ?>
					<td>
						<h3><?php echo JText::_('COM_SMARTSHOP_PAYMENT_INFORMATION') ?></h3>
					</td>
				<?php endif; ?>

				<td <?php if ($this->config->without_payment) { ?> colspan="2" <?php }?>>
					<?php if (!$this->config->without_shipping) : ?>
						<h3><?php echo JText::_('COM_SMARTSHOP_SHIPPING_INFORMATION'); ?></h3>
					<?php endif; ?>
				</td>    
			</tr>

			<tr>
				<td style="height:5px;font-size:1px;">&nbsp;</td>
			</tr>
			
			<tr>
				<?php if (!$this->config->without_payment) : ?>
					<td valign="top">    
						<div style="padding-bottom:4px;"><?php echo $this->order->payment_name; ?></div>
						<div style="font-size:11px;">
						<?php
							echo nl2br($this->order->payment_information);
							echo $this->order->payment_description;
						?>
						</div>
					</td>
				<?php endif; ?>

				<td valign="top" <?php if ($this->config->without_payment) { ?>colspan="2"<?php }?>>
					<?php if (!$this->config->without_shipping) { ?>
						<div style="padding-bottom:4px;">
							<?php echo nl2br($this->order->shipping_information); ?>
						</div>

						<div style="font-size:11px;">
							<?php echo nl2br($this->order->shipping_params); ?>
						</div>

						<?php if ($this->config->show_delivery_time_checkout && $this->order->order_delivery_time) {
							echo '<div>' . JText::_('COM_SMARTSHOP_ORDER_DELIVERY_TIME') . ': ' . $this->order->order_delivery_time . '</div>';
						}   

						if ($this->config->show_delivery_date && $this->order->delivery_date_f){
							echo '<div>' . JText::_('COM_SMARTSHOP_DELIVERY_DATE') . ': ' . $this->order->delivery_date_f . '</div>';
						}
					} ?>
				</td>  
			</tr>
		<?php endif; endif;

		if ($this->config->show_return_policy_in_email_order) : ?>
			<tr>
				<td colspan="2">
					<br/><br/><a class="policy" target="_blank" href="<?php echo $this->liveurlhost . SEFLink('index.php?option=com_jshopping&controller=content&task=view&page=return_policy&tmpl=component&order_id=' . $this->order->order_id, 1); ?>"><?php echo JText::_('COM_SMARTSHOP_RETURN_POLICY'); ?></a>
				</td>
			</tr>
		<?php endif; ?>

		<?php if ($this->order->order_add_info) : ?>
			<tr>
				<td  colspan="2" >
					<h3><?php echo $this->order->order_add_info; ?></h3>
				</td>    
			</tr>
		<?php endif; ?>
		
		<?php if ($this->client) : ?>
			<tr>
				<td colspan = "2" style="padding-bottom:10px;">
					<?php echo $this->order_email_descr_end; ?>
				</td>
			</tr>
		<?php endif; ?>
	</table>
	
	<?php echo $this->_tmp_ext_html_ordermail_end; ?> 
	<br> 