 
<div class="col-sm">
	<h5><?php echo JText::_('COM_SMARTSHOP_BILL_TO'); ?>:</h5>
	<ul class="list-unstyled">
		<?php if ($this->config_fields['firma_name']['display'] && $this->order->firma_name != "" ) : ?>
			<li><?php echo $this->order->firma_name; ?></li>
		<?php endif; ?>

		<?php if ($this->config_fields['f_name']['display'] && $this->order->f_name != "" || $this->config_fields['m_name']['display'] && $this->order->m_name != "" || $this->config_fields['l_name']['display'] && $this->order->l_name != "") : ?>
			<li><?php 
				echo ($this->config_fields['f_name']['display']) ? $this->order->f_name . ' ' : '';
				echo ($this->config_fields['m_name']['display']) ? $this->order->m_name . ' ' : '';
				echo ($this->config_fields['l_name']['display']) ? $this->order->l_name: '';
			?></li>
		<?php endif; ?>

		<?php if ($this->config_fields['home']['display'] && $this->order->home != "") : ?>
			<li><?php echo $this->order->home; ?></li>
		<?php endif; ?>

		<?php if ($this->config_fields['apartment']['display'] && $this->order->apartment != "") : ?>
			<li><?php echo $this->order->apartment; ?></li>
		<?php endif; ?>

		<?php if ($this->config_fields['street']['display'] && $this->order->street != "" || $this->config_fields['street_nr']['display']) : ?>
			<li>
				<?php
					echo ($this->config_fields['street']['display']) ? $this->order->street . ' ': '';
					echo ($this->config_fields['street_nr']['display']) ? $this->order->street_nr: '';
				?>
			</li>
		<?php endif; ?>

		<?php if ($this->config_fields['zip']['display'] && $this->order->zip != "" || $this->config_fields['city']['display'] && $this->order->city != "") : ?>
			<li><?php 
					echo ($this->config_fields['zip']['display']) ? $this->order->zip . ' ': '';
					echo ($this->config_fields['city']['display']) ? $this->order->city: '';
				?>
			</li>
		<?php endif; ?>

		<?php if ($this->config_fields['state']['display'] && $this->order->state != "") : ?>
			<li><?php echo $this->order->state; ?></li>
		<?php endif; ?>

		<?php if ($this->config_fields['country']['display'] && $this->order->country != "") : ?>
			<li><?php echo $this->order->country; ?></li>
		<?php endif; ?>
	</ul>
</div>