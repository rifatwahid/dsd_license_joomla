class ShopProductCommon {

    showVideo(idElement, width, height) {
        if(document.querySelector(".product_label") != null) shopHelper.hide(document.querySelector(".product_label"));
        if(document.querySelector("#main_image") != null) shopHelper.hide(document.querySelector('#main_image'));
        if(document.querySelector(".video_full") != null) shopHelper.hide(document.querySelector('.video_full'));
        if(document.querySelector("a.lightbox") != null) shopHelper.hide(document.querySelector('a.lightbox'));
        if(document.querySelector(`#hide_${idElement}`) != null && document.querySelector(`#${idElement}`) != null) document.querySelector(`#hide_${idElement}`).setAttribute("href", document.querySelector(`#${idElement}`).getAttribute("href"));
		if(document.querySelector('#hide_' + idElement) != null) {       
		    document.querySelector('#hide_' + idElement).matchMedia( "(width:" + width + ")" );
			document.querySelector('#hide_' + idElement).matchMedia( "(height:" + height + ")" );
		}
        if(document.querySelector('#hide_' + idElement) != null) shopHelper.show(document.querySelector('#hide_' + idElement));
    }

    showVideoCode(idElement) {
        if(document.querySelector(`.video_full:not(#hide_${idElement})`) != null) shopHelper.hide(document.querySelector(`.video_full:not(#hide_${idElement})`));
        if(document.querySelector(".product_label") != null) shopHelper.hide(document.querySelector(".product_label"));
        if(document.querySelector('#main_image') != null) shopHelper.hide(document.querySelector('#main_image'));
        if(document.querySelector('a.lightbox') != null) shopHelper.hide(document.querySelector('a.lightbox'));
        if(document.querySelector(`#hide_${idElement}`) != null) shopHelper.show(document.querySelector(`#hide_${idElement}`));
    }

    showImage(id) {
        if(document.querySelector('.product_label') != null) shopHelper.show(document.querySelector(".product_label"));
        if(document.querySelector('.video_full') != null){ 
			document.querySelectorAll('.video_full').forEach(function(loopEl, index){				
				shopHelper.hide(loopEl);
			});
		}
        if(document.querySelector('a.lightbox') != null){ 
			document.querySelectorAll('a.lightbox').forEach(function(loopEl, index){	
				shopHelper.hide(loopEl);
			});
		} 
	
        if(document.querySelector(`#main_image_full_${id}`) != null){
			shopHelper.show(document.querySelector(`#main_image_full_${id}`));
		} 
    }

}

export default new ShopProductCommon();