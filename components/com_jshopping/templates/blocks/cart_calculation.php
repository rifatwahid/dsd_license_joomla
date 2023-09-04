<ul class="list-group">
					<?php //if (!$this->hide_subtotal) : ?>
						<li class="list-group-item subtotal">
							<?php echo JText::_('COM_SMARTSHOP_SUBTOTAL'); ?>: <span class="float-end"><?php echo formatprice($this->summ); ?></span>
						</li>
					<?php //endif; ?>

					<?php if ($this->config->show_shipping_costs_in_cart && isset($this->summ_delivery)) : ?>
						<li class="list-group-item summ_delivery">
							<?php echo JText::_('COM_SMARTSHOP_SHIPPING_COSTS'); ?>: <span class="float-end"><?php echo $this->summ_delivery; ?></span>
						</li>
					<?php endif; ?>

					<?php if ($this->discount > 0) : ?>
						<li class="list-group-item discount">
							<?php echo JText::_('COM_SMARTSHOP_DISCOUNT'); ?>: <span class="float-end"><?php echo formatprice(-$this->discount); ?></span>
						</li>
					<?php endif; ?>

					<?php if ($this->free_discount > 0) : ?>
						<li class="list-group-item free_discount">
							<?php echo JText::_('COM_SMARTSHOP_DISCOUNT'); ?>: <span class="float-end"><?php echo formatprice($this->free_discount); ?></span>
						</li>
					<?php endif; ?>

					<?php if (!$this->config->hide_tax) : ?>
						<?php foreach($this->tax_list as $percent => $value) : ?>
							<?php if ($value>0){
								if ((double)$percent==0) {
									$tmp=explode('_',substr($percent,15,strlen($percent)));									
									$percent=$tmp[1];						
									?>
									
									<li class="list-group-item tax_list_value">
										<?php echo displayTotalCartTax().JSFactory::getTable('taxextadditional', 'jshop')->getAllAdditionalTaxes((double)$tmp[0])[0]->name." ";?>
										<?php if ($this->show_percent_tax) { 
											echo formattax($percent) . '%'; ?>: <span class="float-end"><?php echo formatprice($value); ?></span>
									</li>	
										<?php }
								} else {
									?><li class="list-group-item tax_list_value">
									<?php echo displayTotalCartTaxName(); ?> <?php if ($this->show_percent_tax) echo formattax($percent) . '%'; ?>: <span class="float-end"><?php echo formatprice($value); ?></span>
								<?php } ?>
									</li>
							<?php } ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php print $this->_tmp_ext_html_after_show_total_tax ?? ''; ?>
					<li class="list-group-item fullsumm">
						<?php echo JText::_('COM_SMARTSHOP_ORDER_TOTAL'); ?>: <span class="float-end"><?php echo formatprice($this->fullsumm); ?></span>
					</li>

				</ul>
                <?php if($this->config->show_shipping_costs_in_cart): ?>
                    <form name="shipping_cart" action="">
                        <div class="row pt-2">
                            <div class="col-md-5 col-lg-5 align-middle pt-1">
                                <?php echo JText::_('COM_SMARTSHOP_SHIPPING_FOR'); ?>
                            </div>

                            <div class="col-md-7 col-lg-7 ps-0">
                                <div class="row">
                                    <div class="<?php if($this->config_address_fields == 1 || $this->config_register_fields == 1){ print 'col-md-6 col-lg-6 ps-0'; }else{print 'col';} ?>">
                                        <?php print $this->select_countries; ?>
                                    </div>
                                    <?php if($this->config_address_fields == 1 || $this->config_register_fields == 1){ ?>
                                        <div class="col-md-6 col-lg-6  ps-0">
                                            <div>
                                                <input type="text" name="state" id="state" placeholder="<?php echo JText::_('COM_SMARTSHOP_STATE'); ?>" value="<?php echo $flashOrderData['state'] ?? $this->user->state; ?>" onfocusout='shopCart.getShippingPrice("country", document.getElementById("country").value, this.value);' class="input" />
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>