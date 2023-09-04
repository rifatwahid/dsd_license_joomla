<?php

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

class JshoppingModelUpload extends jshopBase
{
	public $supportPages = [
		'product' => 'is_allow_product_page',
		'cart' => 'is_allow_cart_page'
	];
	
	public function getParams()
	{
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_upload` WHERE 1";
        $db->setQuery($query);
		$params = $db->loadObject();
		
		return $params;
	}

	public function isProductPassedRequired(int $productId, int $countOfUploads, string $typeOfPage = 'product', $data = []): bool
	{
		$isPassedRequired = true;
		$uploadGlobalParams = $this->getParams();
		$typeOfPage = $this->supportPages[$typeOfPage];
		$isUploadsGlobalyEnabled = !empty($uploadGlobalParams->$typeOfPage);

		if ($isUploadsGlobalyEnabled) {
			$modelOfProducts = JSFactory::getModel('ProductsFront');
			if(!empty($data)){
				$product = (object)$data;
			}else{
				$product = $modelOfProducts->getByProdId($productId);
			}
			
			$isUploadsEnabledInProduct = ($product->is_allow_uploads == 1 && ($product->max_allow_uploads >= 1 || $product->is_unlimited_uploads == 1));

			if ($isUploadsEnabledInProduct) {
				if ($product->is_required_upload == 1 && empty($countOfUploads)) {
					$colNameWithTitle = 'name_' . JFactory::getLanguage()->getTag();
					$productName = $product->product_name ?: $product->$colNameWithTitle;
					$errorText = Text::_('COM_SMARTSHOP_UPLOAD_FILES_FOR_PRODUCT');

					$isPassedRequired = false;
					$this->setErrors([
						sprintf($errorText, $productName)
					]);
				}
			}

		}

		return $isPassedRequired;
	}
	
	public function saveParams($params)
	{
		$db = \JFactory::getDBO();
		$query = "SELECT * FROM `#__jshopping_upload` WHERE 1";
        $db->setQuery($query);
		$ps = $db->loadObject();		
		if(!empty($ps)){
			$query = "UPDATE `#__jshopping_upload` SET `file_type` = " . $db->quote($params['file_type']) . ",`product_page` = " . $db->quote($params['product_page']) . ",`cart_page` = " . $db->quote($params['cart_page']) . ",`upload_design` = " . $db->quote($params['upload_design']);
			$db->setQuery($query);
			$db->execute();
		}else{
			$query = "INSERT INTO `#__jshopping_upload` SET `file_type` = " . $db->quote($params['file_type']) . ",`product_page` = " . $db->quote($params['product_page']) . ",`cart_page` = " . $db->quote($params['cart_page']) . ",`upload_design` = " . $db->quote($params['upload_design']);
			$db->setQuery($query);
			$db->execute();			
		}
	}

	public function cleanedAndIsValidProductsUploadedFiles(&$products, $enableRaiseWarnings = false)
	{
		if (!empty($products)) {
			$uploadModel = JSFactory::getModel('upload');
			
            foreach($products as $productKey => &$productArr) {
                if (!empty($productArr['uploadData']) && !empty($productArr['uploadData']['files'])) {
					$new_arr = array_diff($productArr['uploadData']['files'], array(''));
					if(!empty($new_arr)){
						$productArr['uploadData'] = $uploadModel->getCleanedArrWithUploadData($productArr['uploadData']);
						$isValidUploadedFiles = $uploadModel->isValidateUploadFiles($productArr['uploadData'], $productArr['quantity'], $enableRaiseWarnings, (bool)$productArr['is_upload_independ_from_qty']);
						
						if (!$isValidUploadedFiles) {
							return false;
						}
					}
                }
			}
			
			return true;
		}
		
		return false;
	}

	public function getCleanedArrWithUploadData($uploadedDataArr) 
	{
		$result = [];

		if (!empty($uploadedDataArr) && !empty($uploadedDataArr['qty']) && !empty($uploadedDataArr['files'])) {

			foreach($uploadedDataArr['files'] as $key => $fileName) {
				if (!empty($fileName) && !empty($uploadedDataArr['qty'][$key])) {
					$result['qty'][] = $uploadedDataArr['qty'][$key];
					$result['previews'][] = $uploadedDataArr['previews'][$key];
					$result['files'][] = $fileName;
					$result['descriptions'][] = isset($uploadedDataArr['descriptions'][$key]) ? $uploadedDataArr['descriptions'][$key] : '';
				}
			}

		}

		return $result;
	}
	
	public function cleanUploadsDataArr($uploadDataArr){
		if (!empty($uploadDataArr)){
			foreach ($uploadDataArr as $key=>$value){
				if (trim($value)=="") unset($uploadDataArr[$key]);
			}
		}
		return $uploadDataArr;
	}

	public function isValidateUploadFiles($uploadDataArr, $quantity, $enableRaiseWarnings = true, $isUploadIndependFromQty = false){
		$uploadDataArr['files']=$this->cleanUploadsDataArr($uploadDataArr['files']);
			
		if (isset($uploadDataArr['files']) && isset($uploadDataArr['qty'])){
			$sumOfNativeProgressUploadQty = array_sum($uploadDataArr['qty']);
			if ($sumOfNativeProgressUploadQty > $quantity && !$isUploadIndependFromQty) {
				if ($enableRaiseWarnings) {
					\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_UPLOAD_FILES_REMAINING_QTY_CANNOT_BENEGATIVE'),'error');
				}
				
				return false;
			}
		}
		
		if (isset($uploadDataArr['files']) && isset($uploadDataArr['qty']) && isset($uploadDataArr['isProductIndependFromQty']) && !empty($uploadDataArr['files']) && !empty($uploadDataArr['qty']) && $uploadDataArr['isProductIndependFromQty']) {
			$sumOfNativeProgressUploadQty = array_sum($uploadDataArr['qty']);

			if (!$isUploadIndependFromQty) {
				if ($sumOfNativeProgressUploadQty < $quantity) {
					if ($enableRaiseWarnings) {
						\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_NATIVE_UPLOAD_REMAINING_QUANTITY'),'error');
					}
					
					return false;
				}

				if ($sumOfNativeProgressUploadQty > $quantity) {
					if ($enableRaiseWarnings) {
						\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_UPLOAD_FILES_REMAINING_QTY_CANNOT_BENEGATIVE'),'error');
					}
					
					return false;
				}
			}
			
			$isFilesVerifyPassedTest = $this->isUploadFilesSuccessValidation($uploadDataArr['files']);

            if (!$isFilesVerifyPassedTest) {
				if ($enableRaiseWarnings) {
					\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_UPLOAD_FILES_NOT_FOUND'),'error');
				}
				return false;	
			}
		}

		return true;
	}

	protected function isUploadFilesSuccessValidation($arrWithFiles)
	{
		$result = array_filter($arrWithFiles, function($fileName) {
			if (!empty($fileName)) {

				$pathToFile = JPATH_ROOT . '/components/com_jshopping/files/files_upload/' . $fileName;

				if (file_exists($pathToFile)) {
					return true;
				}
			}

			return false;
		});

		if (!empty($result)) {
			return true;
		}

		return false;
	}
	
}