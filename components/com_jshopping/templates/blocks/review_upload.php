<?php 
    $isMultiUpload = $this->isMultiUpload;
	if ($this->allow_reviews_uploads) : 
		$maxSize = JFilesystemHelper::fileUploadMaxSize();
		?>
		  <div class="dropzone" id="dropzone">
			<p>
				<div id='uploadfiletitle'>&nbsp;</div>
			</p>
			<p>
				<span id="upload-icon" class="icon-upload" aria-hidden="true"></span>
			</p>
			<div class="upload-actions">
				<p class="lead">
					<?php echo JText::_('COM_SMARTSHOP_REVIEW_DRAG_AND_DROP_FILES_HERE'); ?><br>
					
				</p>
				<p>
				<?php echo JText::_('COM_SMARTSHOP_REVIEW_OR'); ?><br><br>
					<button id="select-file-button" type="button" class="btn btn-success" onclick="document.getElementById('fileElem').click();">
						<span class="icon-copy" aria-hidden="true"></span>
						<?php echo JText::_('COM_SMARTSHOP_REVIEW_BROWSE_FILES'); ?>
					</button>
				</p>		
			</div>
			
			
		</div>
		<input class="box__file" type="file" name="file[]" id="fileElem" accept="image/*" data-multiple-caption="{count} files selected"  onchange="shopProduct.review_uploadfiles_by_button(this.files)" multiple="multiple" />
		<input type='hidden' id='img_path' value="<?php echo $jshopConfig->files_product_review_live_path."/";?>">
		<input type='hidden' id='reviewfile' name='reviewfile' value="">
		<div id='review_upload_files'>			
		</div>		
		<input type='hidden' id='reviewfiles' name='reviewfiles' value="0">
		<input type='hidden' id='review_max_uploads' name='review_max_uploads' value="<?php echo $jshopConfig->review_max_uploads;?>">
		<br>
<?php		
JFactory::getDocument()->addScriptDeclaration(
<<<JS

	document.addEventListener('DOMContentLoaded',function(){
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
			shopProduct.uploadReviewFiles(files,1,files.length);
			var data = document.getElementById('productReviewForm');
			this.className = 'dropzone'; 
			document.getElementById('uploadfiletitle').innerHTML="";			
	   });
	});		 
	
  
JS
);
endif;?>