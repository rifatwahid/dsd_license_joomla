import shopHelper from '../../common/helper/index.js';

class ShopReturn {

    constructor() {		
		document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.return_prods').forEach(function(el){
				el.addEventListener('change', function(){
					if(this.checked == true){
						shopReturn.addToReturn(this.value);
						document.querySelector('#return_reason_'+this.value).classList.remove('d-none');						
						document.querySelector('#return_comments_'+this.value).classList.remove('d-none');						
					}else{
						document.querySelector('#return_reason_'+this.value).classList.add('d-none');	
						document.querySelector('#return_reason_'+this.value+' #reason'+this.value).getElementsByTagName('option')[0].selected = 'selected'
						document.querySelector('#return_comments_'+this.value).classList.add('d-none');
						document.querySelector('#comment_'+this.value).value = '';
						
					}
				});
			});
			
			document.querySelectorAll('.return_reason').forEach(function(el){
				el.addEventListener('change', function(){
					var id = this.id.substring(6);	
					if(this.value > 0){
						//shopReturn.addToReturn(id);
						//document.querySelector('#return_comments_'+id).classList.remove('d-none');
					}else{
						if(document.querySelector('.returns_product').querySelector('#return_'+id)){
							//document.querySelector('.returns_product').querySelector('#return_'+id).remove();
							//shopReturn.enabledButton();
						}	
						
						//document.querySelector('#return_comments_'+id).classList.add('d-none');
						//document.querySelector('#comment_'+id).value = '';
					}
				});
			});
			
			if(document.querySelector('#return_submit')){
				document.querySelector('#return_submit').addEventListener('click', function(){
					var return_reasons = document.querySelectorAll('.return_reason');
					return_reasons.forEach(function(el){
						var id = el.id.substring(6);
						if(document.querySelector('#return_'+id)){
							document.querySelector('form[name=returnOrder]').submit();
							return true;						
						}
					});
				});
			}
		});
	}

	addToReturn(id){		
		var pr_value = document.querySelector('#prods_'+id).value;
		var pr_img = document.querySelector('#img_'+id).outerHTML;
		var pr_name = document.querySelector('#info_'+id+' .pr_name').outerHTML;
		var pr_info = document.querySelector('#info_'+id).cloneNode(true);
		var pr_status = document.querySelector('#reason'+id).value;
		var pr_count = pr_info.querySelector('.count_block input').value;
		
		if(pr_value > 0 && pr_count > 0 && !document.querySelector('.returns_product #return_' + id)){
			var html = '<div class="row pt-3 return_product" id="return_'+id+'" ><div class="col-sm-3">'+pr_img+'</div>';
			html += '<div class="col"><span>' + pr_name + '</span><div class="row"><div class="col pe-0">' + Joomla.JText._('COM_SMARTSHOP_QTY') + ': <span class="col ps-0 count_block">'+pr_count +'</span></div></div>';
			html += '<input type="hidden" name="products_count['+id+']" value="'+pr_count+'" />';
			html += '</div>';
			document.querySelector('.returns_product').insertAdjacentHTML('beforeend', html);
			document.querySelector('#return_submit').removeAttribute('disabled');
		}
	}
	
	changeReturnsCount(el, id){
		var pr_block = document.querySelector('.returns_product #return_' + id + ' .count_block');
		if(el.value > parseInt(el.max)){
			el.value = parseInt(el.max);
		}
		
		if(pr_block && el.value > 0){
			pr_block.innerHTML = el.value;	
			document.querySelector('.returns_product #return_' + id + ' #products_count_' + id).value = el.value;;	
		}else{
			if(el.value == 0 ){
				if(document.querySelector('.returns_product #return_' + id)){
					document.querySelector('.returns_product #return_' + id).remove();
					shopReturn.enabledButton();
				}
			}else{
				shopReturn.addToReturn(id);
			}
		}		
	}
	
	enabledButton(){
		var return_products = document.querySelectorAll('.returns_product .return_product');
		if(return_products.length == 0){
			document.querySelector('#return_submit').setAttribute('disabled','disabled');
		}
	}
	
	
    

}

export default new ShopReturn();