class ShopModal {

    close(){
		if(document.querySelector('.modal')){
			document.querySelector('.modal').classList.remove('show');
			document.querySelector('.modal').style.display='none';
		}
		if(document.querySelector('.modal-backdrop')){
			document.querySelector('.modal-backdrop').classList.remove('show');
			document.querySelector('.modal-backdrop').style.display='none';
		}
		document.querySelector('body').classList.remove('modal-open');
		document.querySelector('body').style.overflow='';
	}

}

export default new ShopModal();