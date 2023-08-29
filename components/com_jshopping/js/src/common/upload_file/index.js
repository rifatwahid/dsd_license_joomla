class UploadFile{

    constructor(maxsize='3000000') {
        this._input_max_size = maxsize;
        this.parentBlockElement = null;
        this.elementWhereWillBeShowProgress = null;
        this._randomInt = null;
    }

    upload(url, callback, data, element) {
        this.parentBlockElement = element;
        this.elementWhereWillBeShowProgress = element.querySelector('.nativeProgressUpload__progress');
        this._deleteUpload();
        this._createForm(url, callback, data);
    }
	
	_clearAllErrorMessages(){
		let apuffixedBlock1 = this.elementWhereWillBeShowProgress.querySelector('.apuf_status_error');
		if (apuffixedBlock1) apuffixedBlock1.remove();
	}
    
	_sendFile(form) {	
	
		this._clearAllErrorMessages();	
			//let maxxsize = '3000000';
			
			let maxxsize = form.querySelector('[name=MAX_FILE_SIZE]').value;
            this._startUpload();
			var obj = {};
			var formData = new FormData(form);
			var file = form.querySelector('[name=file]').files[0];
			formData.append("file",file);
			
			for (var key of formData.keys()) {
				if(key != 'file'){
					obj[key] = JSON.stringify(formData.get(key));
				}else{
					obj[key] = shopHelper.objToStr(file);
				}
			}
			
			let postData = [];
			for (const [name, value] of formData) {
				postData[name] = value ;
			}
			
			let url = form.getAttribute("action");
			

            const ajax = new XMLHttpRequest();
            ajax.upload.addEventListener('progress', (e) => {
				if (maxxsize<e.total && e.total>0 && maxxsize>0){
				
					ajax.abort();							
					this._errorUpload(Joomla.JText._('COM_SMARTSHOP_UPLOADS_FILE_TOO_BIG').replace('%s',maxxsize/1024/1024));
					let apuffixedBlock = this.elementWhereWillBeShowProgress.querySelector('.apuf_block' + this._randomInt);
					if (apuffixedBlock) apuffixedBlock.remove();
				}
                if (e.lengthComputable) {
                    let percentComplete = e.loaded / e.total;
                    this._percentUpload(percentComplete);
                }
            });
            ajax.onload = (response) => {
                if (response.target.status == 200) {
                    const data = JSON.parse(response.target.responseText);
                    if (data.status == 'success') {
                        this._finishUpload();
                        form.callback(data, this);
                    } else {
                        this._errorUpload();
                    }
                }
            }
			ajax.onerror = (error) => console.log(error);
            ajax.open('POST', url);
            ajax.send(formData);

    }

    _createForm(url, callback, data) {

        /* create Form */
        let form = document.createElement('form');
		//let form  = new FormData();
        form.method = 'POST';
        form.action = url;
        form.enctype = 'multipart/form-data';
        form.callback = callback;
        /* create input[type="file"] */
        let input = document.createElement('input');
        input.name = 'file';
        input.type = 'file';
        input.style.display = 'none';
        this.input_file = input;
        form.appendChild(input);
		
        /* onchange event on input[type="file"] send the Created form */
        //input.onchange = () => form.submit();
        

		if (typeof data == 'object') {
			data.forEach(function(i, value) {
				let additionalInput = document.createElement('input');
				additionalInput.type = 'hidden';
				additionalInput.name = i;
				additionalInput.value = value;
				form.appendChild(additionalInput);
			});
		}
        
        /* add div with name upload to form  */
        let div_upload = document.createElement('div');
        div_upload.innerHTML = 'Upload';
        div_upload.onclick = () => this.input_file.click();
        form.appendChild(div_upload);
        
        /* add input[type="hidden"] with Max File Size to form  */
        let input_max_size = document.createElement('input');
        input_max_size.type = 'hidden';
        input_max_size.name = 'MAX_FILE_SIZE';
        input_max_size.value = this._input_max_size;
        form.appendChild(input_max_size);

        /* create & add Button[type="submit"] to Form  */
        var submit = document.createElement('input');
        submit.type = 'submit';
        form.appendChild(submit);
        //this._sendFile(form);
		input.onchange = () => this._sendFile(form);
        input.click();
        return form;
    }
    
    _startUpload() {
        this._randomInt = Math.floor(Math.random() * 1000);
        
        let elements = {
            apuf_fixed_block: null,
            apuf_block: null,
            apuf_progress: null,
            apuf_bar: null,
            apuf_percent: null,
            apuf_status: null
        };

        /* create div's & add class */
        for (let e in elements) {
            let div = document.createElement('div');
            div.classList.add(e, e + this._randomInt);
            if (e === 'apuf_percent') div.innerHTML = '0%';    

            this[e] = div;
            elements[e] = div;
        }

        elements.apuf_fixed_block.appendChild(elements.apuf_block);
        elements.apuf_fixed_block.appendChild(elements.apuf_status);
        elements.apuf_block.appendChild(elements.apuf_progress);
        elements.apuf_progress.appendChild(elements.apuf_bar);
        elements.apuf_progress.appendChild(elements.apuf_percent);

        if (this.elementWhereWillBeShowProgress) {
            this.elementWhereWillBeShowProgress.appendChild(elements.apuf_fixed_block);
        } else {
            document.body.appendChild(elements.apuf_fixed_block);
        }
    }

    _percentUpload(value) {
        let percent = parseInt(value * 99);
        this.apuf_bar.style.width = percent + '%';
        this.apuf_percent.innerHTML = percent + '%';
    }

    _finishUpload() {
        this.apuf_status.innerHTML = 'Uploaded ' + this.filename;
        this._deleteUpload();
    }
    
    _errorUpload(error = "") {
        this.apuf_status.innerHTML = 'Error upload file ' + this.filename + ' ' + error;
        this.apuf_status.classList.add('apuf_status_error');
    }
    
    _deleteUpload() {
        let apuffixedBlock = this.elementWhereWillBeShowProgress.querySelector('.apuf_fixed_block' + this._randomInt);
        if (apuffixedBlock) apuffixedBlock.remove();

        this.filename = '';
        delete this.apuf_fixed_block;
        delete this.apuf_block;
        delete this.apuf_progress;
        delete this.apuf_bar;
        delete this.apuf_percent;
        delete this.apuf_status;
    }

}

export default UploadFile;
//export default new UploadFile();