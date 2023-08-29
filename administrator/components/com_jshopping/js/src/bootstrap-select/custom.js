function change_category_id(id) {
	let el = document.getElementById("jform_request_category_id_id");
	if (el) {
		el.value = getSelectValues(id, 1);
	}
}

function change_vendor_id(id) {
	let el = document.getElementById("jform_request_vendor_id_id");
	if (el) {
		el.value = getSelectValues(id);
	}
}

function change_manufacturer_id(id) {
	let el = document.getElementById("jform_request_manufacturer_id_id");
	if (el) {
		el.value = getSelectValues(id);
	}
}

function change_label_id(id) {
	let el = document.getElementById("jform_request_label_id_id");
	if (el) {
		el.value = getSelectValues(id);
	}
}

function change_product_id(id) {
	let el = document.getElementById("jform_request_product_id_id");
	if (el) {
		el.value = getSelectValues(id);
	}
}

function change_products_list_id(id) {
	let el = document.getElementById("jform_request_products_list_id_id");
	if (el) {
		el.value = getSelectValues(id);
	}
}

 function getSelectValues(select,cat=0) {
  var result = [];
  let requestProductIdEl = document.getElementById('jform_request_product_id');
  if (requestProductIdEl){
	requestProductIdEl.nextSibling.click();
	requestProductIdEl.nextSibling.click();
  }
  var options = select && select.options;
  var opt;
	if (cat==1){
		product_list = [];		
	}
	
	var activ_categories=0;
  for (var i=0, iLen=options.length; i<iLen; i++) {
    opt = options[i];
    if (opt.selected) {	
		activ_categories++;
		if ((cat==1)&&(document.getElementById('jform_request_product_id'))){	
			var prods_str=cat_prod[options[i].value];
			if (prods_str){
				var prods=prods_str.split(',');
				for (var ii=0;ii<prods.length-1;ii++){
					if (product_list.indexOf(prods[ii])==-1){product_list.push(prods[ii]);}
				}
			}
		}
      result.push(opt.value || opt.text);
    }
  }

	if ((cat==1)&&(document.getElementById('jform_request_product_id')))
	{
		var elementChildrens = document.getElementById("jform_request_product_id").children;		
		if (document.getElementById("jform_request_product_id").nextSibling.nextSibling.children[1].getElementsByTagName("LI").length>0){			
			for (var i=0, child; child=elementChildrens[i]; i++) {
			   var elementChildrens2 = document.getElementById("jform_request_product_id").nextSibling.nextSibling.children[1].getElementsByTagName("LI")[i];
			   //alert(elementChildrens2.className);
			   if ((child.value!="")&&(product_list.indexOf(child.value)==-1)&&(elementChildrens2.className!='selected')&&(activ_categories>0)){
				   elementChildrens2.style.display='none';
			   }else{				   
				   elementChildrens2.style.display='';
				}        
			};			
			
		};
	}; 	
  return result;
} 