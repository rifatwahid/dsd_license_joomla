<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.calendar');
JHTML::_('behavior.modal');

JHtml::_('script', 'system/modal.js', true, true);
?>
<?php $rand = rand(0,20); ?>
<input type="hidden" id="belem_rand" value="<?php print $rand; ?>" />
<div id="belem">

</div>
<div id="<?php print $this->component?>">

</div>
<div className="dataJsonPopup" style="display: none;"><?php print $this->dataJsonPopup; ?></div>

<script src="https://unpkg.com/react@17/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js" crossorigin></script>
<script src="/components/com_jshopping/js/react/dist/index_bundle.js"></script>
<script src="/media/system/js/modal.js"></script>
