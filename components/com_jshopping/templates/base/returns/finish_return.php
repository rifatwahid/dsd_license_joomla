<?php 
	$address = '<p>'.$this->address->shop_name.'<br/>';
	$address .= $this->address->adress.'<br/>';
	$address .= $this->address->zip.' '.$this->address->city.'</p>';
	
?>

<p><h3 class="pb-4"><?php echo JText::_('COM_SMARTSHOP_FINISH_RETURN_TEXT') ?></h3></p>
<?php if (!empty($this->text)) {
    echo $this->text;
} else { ?> 
	<p><?php echo JText::sprintf('COM_SMARTSHOP_AFTER_FINISH_RETURN_TEXT', $address); ?></p>
<?php } ?>