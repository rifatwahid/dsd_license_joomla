<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.calendar');
?><?php// print $this->dataJsonPopup;die; ?>
<?php $rand = rand(0,20); ?>
<input type="hidden" id="belem_rand" value="<?php print $rand; ?>" />
<div id="belem">

</div>
<div id="<?php print $this->component?>">

</div>
<?/*<script src="/components/com_jshopping/js/src/index.js" type="module"></script>
<script src="https://unpkg.com/react@17/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js" crossorigin></script>
<script src="/components/com_jshopping/js/react/dist/index_bundle.js"></script>*/ ?>
