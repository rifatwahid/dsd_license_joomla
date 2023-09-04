 <div class="col-sm">
	<h5><?php echo JText::_('COM_SMARTSHOP_SHIPPING_TO'); ?>:</h5>
			
	 <ul class="list-unstyled">
		<?php if ($this->config_fields['firma_name']['display'] && $this->order->d_firma_name != '' ) : ?>
			<li><?php echo $this->order->d_firma_name; ?></li>
		<?php endif; ?>

		<?php if ($this->config_fields['f_name']['display'] && $this->order->d_f_name != '' || $this->config_fields['m_name']['display'] && $this->order->d_m_name != '' || $this->config_fields['l_name']['display'] && $this->order->d_l_name != '' ) : ?>
			<li>
				<?php 
					echo ($this->config_fields['f_name']['display']) ? $this->order->d_f_name . ' ': '';
					echo ($this->config_fields['m_name']['display']) ? $this->order->d_m_name . ' ': '';
					echo ($this->config_fields['l_name']['display']) ? $this->order->d_l_name: '';
				?>
			</li>
		<?php endif; ?>

		<?php if ($this->config_fields['home']['display'] && $this->order->d_home != '') : ?>
			<li><?php echo $this->order->d_home; ?></li>
		<?php endif; ?>

		<?php if ($this->config_fields['apartment']['display'] && $this->order->d_apartment != '') : ?>
			<li><?php echo $this->order->d_apartment; ?></li>
		<?php endif; ?>

		<?php if ($this->config_fields['street']['display'] && $this->order->d_street != '' || $this->config_fields['street_nr']['display'] ) : ?>
			<li>
				<?php
					echo ($this->config_fields['street']['display']) ? $this->order->d_street . ' ': '';
					echo ($this->config_fields['street_nr']['display']) ? $this->order->d_street_nr: '';
				?>
			</li>
		<?php endif; ?>

		<?php if ($this->config_fields['zip']['display'] && $this->order->d_zip != '' || $this->config_fields['city']['display'] && $this->order->d_city != '' ) : ?>
			<li>
				<?php 
					echo ($this->config_fields['zip']['display']) ? $this->order->d_zip . ' ': '';
					echo ($this->config_fields['city']['display']) ? $this->order->d_city: '';
				?>
			</li>
		<?php endif; ?>

		<?php if ($this->config_fields['state']['display'] && $this->order->d_state != '') : ?>
			<li><?php echo $this->order->d_state; ?></li>
		<?php endif; ?>

		<?php if ($this->config_fields['country']['display'] && $this->order->d_country != '') : ?>
			<li><?php echo $this->order->d_country; ?></li>
		<?php endif; ?>
	</ul>
</div>