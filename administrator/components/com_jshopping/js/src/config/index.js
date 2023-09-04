class ShopConfig {

    selectContent(id, title) {        
        let link = document.getElementById('content_link_id');
        let removeEl = document.querySelector(`#remove_${link.value} i`);

        if (link) {
            let linkEl = document.getElementById(link.value);
            let labelEl = document.getElementById(`label_${link.value}`);

            if (linkEl) {
                linkEl.value = id;
            }

            if (labelEl) {
                labelEl.innerHTML = title;
            }
        }

        if (removeEl) {
            removeEl.classList.remove('hidden');
        }
        
    }

    restartContent() {
        this.reloadLanguage("");
        this.reloadPage(0);
        this._contentRequest();
    }

    _contentRequest() {
        let link = document.getElementById('content_link_id').value;
		let link_type = '';
		if(link != ''){
		    link_type = document.getElementById(`${link}_type`).value;
		}
        let options = {
            filterOpts: [
                document.getElementById('content_page').value,
                document.getElementById('content_language').value,
                link_type
            ]
        };

        fetch('index.php?option=com_jshopping&controller=config&task=get_content', {
            method: 'POST',
            cache: 'no-cache',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
			},
            body: shopHelper.dataTransform(options)
        })
        .then(response => response.text())
        .then(data => {
            let resultEl = document.querySelector('#result');

            if (resultEl) {
                resultEl.innerHTML = data;
            }
        });
    }

    reloadPage(page) {
        document.getElementById('content_page').value = page;
        this._contentRequest();
    }

    reloadLanguage(language) {
        document.getElementById('content_page').value = 0;
        document.getElementById('content_language').value = language;
        this._contentRequest();
    }
	
	storageDeleteUploadsAlert(id,msg){
		if (!confirm(msg)){
            let el = document.querySelector('#' + id);

            if (el) {
                el.value = 0;
            }
		}
	}
	
	removeContent(id, lang){
        let el = document.querySelector(`#${id}_${lang}`);
        let labelEl = document.querySelector(`#label_${id}_${lang}`);
        let removeEl = document.querySelector(`#remove_${id}_${lang} i`);

        if (el) {
            el.value = 0;
        }

        if (labelEl) {
            labelEl.innerHTML = '';
        }

        if (removeEl) {
            removeEl.classList.add('hidden');
        }
	}

}

export default new ShopConfig();