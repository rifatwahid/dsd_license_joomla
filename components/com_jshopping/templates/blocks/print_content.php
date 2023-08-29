<?php
    if (strpos($this->link, '?') === FALSE) {
        $tmpl = "?tmpl=component&amp;print=1";
    } else {
        $tmpl = "&amp;tmpl=component&amp;print=1";
    }
?>

<div class="jshop_button_print">
    <?php if ($this->print == 1) : ?>
        <a onclick="window.print(); return false;" href="#" title="<?php echo JText::_('COM_SMARTSHOP_PRINT'); ?>">
            <img src="<?php echo JURI::root(); ?>components/com_jshopping/images/print.png" alt=""/>
        </a>
    <?php else : ?>
        <a href="<?php echo $this->link . $tmpl; ?>" title="<?php echo JText::_('COM_SMARTSHOP_PRINT'); ?>" onclick="window.open(this.href, 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no'); return false;" rel="nofollow">
            <img src="<?php echo JURI::root(); ?>components/com_jshopping/images/print.png" alt=""/>
        </a>
    <?php endif; ?>
</div>