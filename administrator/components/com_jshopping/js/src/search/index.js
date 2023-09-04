class ShopSearch {

	searchEnterKeyPress(event_val,t){
		if (event_val.keyCode==13){
			t.form.submit();
		}
	}
	
	searchRelatedEnterKeyPress(event_val,i,product_id){
		if (event_val.keyCode==13){
			shopProductRelated.search(i,product_id);
		}
	}
	
}

export default new ShopSearch();