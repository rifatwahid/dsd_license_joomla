var product_price_precision = <?php echo intval($jshopConfig->product_price_precision); ?>;

document.addEventListener('DOMContentLoaded', function () {
    Joomla.submitbutton = function (task) {
        if (task=='save' || task=='apply') {
            <?php if ($this->product->parent_id==0) : ?>
                if (shopHelper.getElement('category_id').selectedIndex == -1) {
                    alert('<?php echo JText::_('COM_SMARTSHOP_WRITE_SELECT_CATEGORY')?>');

                    return false;
                }
            <?php endif; ?>
        }
		<?php 
		$_lang = JSFactory::getModel('languages');
        $languages = $_lang->getAllLanguages(1);
		foreach($languages as $lang) {
			$l=str_replace('-','_',$lang->language);
		?>		
		
		var el_description=document.querySelector('[name^="description_<?php echo $lang->language;?>]"');
		console.log(el_description);
		var el_short_description=document.querySelector('[name^="description_<?php echo $lang->language;?>]"');
		var adminForm=document.getElementById('adminForm');
		
		if (el_short_description == null){
			const short_description_<?php echo $l;?>_ifr=document.getElementById('short_description_<?php echo $l;?>_ifr').contentWindow.document;			
			const newTextarea<?php echo $l;?> = document.createElement("textarea");
			newTextarea<?php echo $l;?>.name="short_description_<?php echo $lang->language;?>";
			newTextarea<?php echo $l;?>.innerHTML=short_description_<?php echo $l;?>_ifr.getElementById('tinymce').innerHTML;		
			adminForm.append(newTextarea<?php echo $l;?>);		
		}
		if (el_description == null){
			const description_<?php echo $l;?>_ifr=document.getElementById('description_<?php echo $l;?>_ifr').contentWindow.document;			
			const newTextarea2<?php echo $l;?> = document.createElement("textarea");
			newTextarea2<?php echo $l;?>.name="description_<?php echo $lang->language;?>";
			newTextarea2<?php echo $l;?>.innerHTML=description_<?php echo $l;?>_ifr.getElementById('tinymce').innerHTML;		
			adminForm.append(newTextarea2<?php echo $l;?>);		
		}
		<?php } ?>
        Joomla.submitform(task, document.getElementById('adminForm'));
    };
});

function showHideAddPrice() {
    let element = document.querySelector('#product_is_add_price');
	let el = document.querySelector('#tr_add_price');
	if(el){
		el.style.display = (element && element.checked) ? ('') : ('none');
	}
}

function add_usergroups_prices_showHideAddPrice(elSelector = '#add_usergroups_prices_product_is_add_price', el2Selector = '#add_usergroups_prices_tr_add_price') {
    let el2 = document.querySelector(el2Selector);

    if (el2) {
        let element = document.querySelector(elSelector);
        el2.style.display = (element.checked) ? ('') : ('none');
    }
}

function add_usergroups_prices_showHideAddPrice_list(el) {
    let element = document.getElementById('add_usergroups_prices_product_is_add_price_list['+el+']');
    document.querySelector('#add_usergroups_prices_tr_add_price_' + el).style.display = (element.checked) ? ('') : ('none');
}

<?php if ($this->product->parent_id == 0) : ?>
    showHideAddPrice();
    add_usergroups_prices_showHideAddPrice();
	let usergroupPricesRowEl = document.querySelectorAll('[id^="add_usergroups_prices_tr_add_price_"]');
    if (usergroupPricesRowEl) {
        usergroupPricesRowEl.forEach(function (item) {
            var arr = item.getAttribute('id').split('add_usergroups_prices_tr_add_price_');
            if (arr[1]) {
                add_usergroups_prices_showHideAddPrice_list(arr[1]);
            }
        });
    }
<?php endif; ?>