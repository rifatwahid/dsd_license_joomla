class ShopProduct {

    constructor() {
        let config = Joomla.getOptions('config') || {};

        this.urlUpdatePrice = config.urlupdateprice || '';
        this.imagePath = config.live_path || '';
        this.productImagePath = config.image_product_live_path || '';
        this.attributePath = config.image_attributes_live_path || '';
        this.attributeValue = {};
        this.attributeImage = {};
        this.attributeList = [];
		this.files = [];
    }

    setUrlUpdatePrice(url) {
        this.urlUpdatePrice = url;
    }

    setAttributeList(key, attribute) {
        this.attributeList[key] = attribute;
    }

    setAttributeValue(key, value) {
        this.attributeValue[key] = value;
    }

    setAttributeImage(key, value) {
        this.attributeImage[key] = value;
    }

    setImagePath(url) {
        this.imagePath = url;
    }

    setAttributePath(url) {
        this.attributePath = url;
    }

    setProductImagePath(url) {
        this.productImagePath = url;
    }
	
	send_btn(files,valid) {
		if (valid){([...files]).forEach(shopProduct.send_review)}
	}
	
	send_review(file) {
		let url = 'index.php?option=com_jshopping&controller=product&task=reviewsave'
		let formData = new FormData()
		formData.append('file', file)
		var form = document.getElementById('productReviewForm');
		var x = form.getElementsByTagName('input');
		var i;
		for (i = 0; i < x.length; i++) {	  	 
			formData.append(x[i].name, x[i].value)
		}
		var x = form.getElementsByTagName('textarea');
		var i;
		for (i = 0; i < x.length; i++) {	  	 
			formData.append(x[i].name, x[i].value)
		}

		fetch(url, {
			method: 'POST',
			body: formData
		})
		.then(() => { document.location.href=url+"&saved=1&back_link="+form.back_link.value; })
		.catch(() => { document.location.href=form.back_link.value; })
	}

	
	send_btn2 (){
		var droppedFiles = false;
		var dropzone = document.getElementById("dropzone");
		dropzone.ondragover = function() {
			this.className = 'dropzone dragover';    
			return false;
			};

		dropzone.ondragleave = function() {
			this.className = 'dropzone';    
			return false;
			};

		dropzone.addEventListener('drop',function(e) {
			e.stopPropagation();  
			e.preventDefault();
			files = e.target.files || e.dataTransfer.files;
			var droppedFile = files[0];
			document.getElementById('uploadfiletitle').innerHTML=droppedFile.name;
			var data = document.getElementById('productReviewForm');
			this.className = 'dropzone';  
		});
	};
  
  uploadReviewFiles(files,current_index,all_count){
	  if ( (parseInt(document.getElementById('reviewfiles').value)>=parseInt(document.getElementById('review_max_uploads').value))&&(parseInt(document.getElementById('review_max_uploads').value)!=0) ){			
			alert(Joomla.JText._('COM_SMARTSHOP_REVIW_YOU_CAN_UPLOAD_ONLY')+document.getElementById('review_max_uploads').value+Joomla.JText._('COM_SMARTSHOP_REVIW_YOU_CAN_UPLOAD_FILES'));
			return;
		}
	  var droppedFile = files[current_index-1];
		var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener('progress', shopProduct.uploadProgress, false);
		xhr.onreadystatechange = shopProduct.stateChange;
		xhr.open('POST', 'index.php?option=com_jshopping&controller=product&task=upload_img');
		xhr.setRequestHeader('X-FILE-NAME',droppedFile.name);				
		var formData = new FormData();
		formData.append("file", droppedFile);
		xhr.send(formData);
		xhr.onload = function() {
			var img_name=xhr.response;
			if (img_name!=""){		
				shopProduct.addUploadedReviewFile(img_name);
				if (current_index<all_count){
					shopProduct.uploadReviewFiles(files,current_index+1,files.length);
				}	
			}			
		};
  }
  
  addUploadedReviewFile(img_name){
	var current_index=parseInt(document.getElementById('reviewfiles').value)+1;
	var review_images_block=document.getElementById('review_upload_files');
	var new_input = document.createElement("INPUT");
	new_input.setAttribute("id", "file"+current_index);
	new_input.setAttribute("name", "file"+current_index);
	new_input.setAttribute("type", "hidden");
	new_input.setAttribute("value", img_name);
	review_images_block.appendChild(new_input);
	document.getElementById('uploadfileimage').innerHTML=document.getElementById('uploadfileimage').innerHTML+"<div id='reviewupload_"+current_index+"' class='col-sm-6 col-md-4 col-lg-3 card-group mb-5'><img style='margin:auto' src='"+document.getElementById('img_path').value+"thumb_"+img_name+"'><div class='delete-btn' onclick='shopProduct.reviewupload_delete("+current_index+")'>X</div></div>";
	document.getElementById('reviewfiles').value=parseInt(document.getElementById('reviewfiles').value)+1;
	shopProduct.reviewupload_reloadreviewfile()
  }
  
  reviewupload_reloadreviewfile(){
	  var current_index=document.getElementById('reviewfiles').value;
	  var reviewfile="";
	  for (var i=1;i<=current_index;i++){
		if (i>1){reviewfile=reviewfile+'|';}
		reviewfile=reviewfile+document.getElementById('file'+i).value;
	  }
	  document.getElementById('reviewfile').value=reviewfile;
  }
  
  reviewupload_delete(index){
	var current_index=document.getElementById('reviewfiles').value;
	if (index<current_index){		 
		for (var i=index;i<=current_index;i++){var z=i+1;
			document.getElementById('reviewupload_'+i).remove();
		}
		for (var i=index;i<=current_index-1;i++){var z=i+1;
			var new_val=document.getElementById('file'+z).value;
			document.getElementById('file'+i).value=new_val;			
			document.getElementById('uploadfileimage').innerHTML=document.getElementById('uploadfileimage').innerHTML+"<div id='reviewupload_"+i+"' class='col-sm-6 col-md-4 col-lg-3 card-group mb-5'><img style='margin:auto' src='"+document.getElementById('img_path').value+"thumb_"+new_val+"'><div class='delete-btn' onclick='shopProduct.reviewupload_delete("+i+")'>X</div></div>";
			
		}
		current_index=z;
	}else{
		document.getElementById('reviewupload_'+index).remove();
	}	
	document.getElementById('file'+current_index).remove();		
	document.getElementById('reviewfiles').value=parseInt(document.getElementById('reviewfiles').value)-1;
	shopProduct.reviewupload_reloadreviewfile()
  }
  
	
	review_uploadfiles_by_button(files) {
		shopProduct.review_uploadfiles_by_button_recursive(files,1,files.length);
		var data = document.getElementById('productReviewForm');
	}
	
	review_uploadfiles_by_button_recursive(files,current_index,all_count) {
		if ( (parseInt(document.getElementById('reviewfiles').value)>=parseInt(document.getElementById('review_max_uploads').value))&&(parseInt(document.getElementById('review_max_uploads').value)!=0) ){			
			alert(Joomla.JText._('COM_SMARTSHOP_REVIW_YOU_CAN_UPLOAD_ONLY')+document.getElementById('review_max_uploads').value+Joomla.JText._('COM_SMARTSHOP_REVIW_YOU_CAN_UPLOAD_FILES'));
			return;
		}
		var droppedFile = files[current_index-1];
		var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener('progress', shopProduct.uploadProgress, false);
		xhr.onreadystatechange = shopProduct.stateChange;
		xhr.open('POST', 'index.php?option=com_jshopping&controller=product&task=upload_img');
		xhr.setRequestHeader('X-FILE-NAME',droppedFile.name);				
		var formData = new FormData();
		formData.append("file", droppedFile);
		xhr.send(formData);
		xhr.onload = function() {
			var img_name=xhr.response;
			if (img_name!=""){
				shopProduct.addUploadedReviewFile(img_name);
				if (current_index<all_count){
					shopProduct.review_uploadfiles_by_button_recursive(files,current_index+1,files.length);
				}	
			}
		};
	document.getElementById('uploadfiletitle').innerHTML="";
	}
		
	uploadProgress(event) {
		var percent = parseInt(event.loaded / event.total * 100);
		document.getElementById('uploadfiletitle').innerHTML= percent + '%';
	}
	
	stateChange(event) {
		if (event.target.readyState == 4) {
			if (event.target.status == 200) {				
			} else {
				document.getElementById('uploadfiletitle').innerHTML='Error';
				document.getElementById('uploadfiletitle').style.color='red';
			}
		}
	}

}

export default new ShopProduct();