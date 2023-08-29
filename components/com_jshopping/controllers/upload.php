<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerUpload extends JshoppingControllerBase
{
    const PATH_TO_UPLOAD_FOLDER_NOIMAGE = '/components/com_jshopping/files/img_shop_products/';
	const PATH_TO_UPLOAD_FOLDER = JPATH_SITE . '/components/com_jshopping/files/files_upload/';
    const HREF_TO_UPLOAD_FOLDER = '/components/com_jshopping/files/files_upload/';

    public function ajaxUploadFile()
    {
		$uploadModel = JSFactory::getModel('upload');
        $uploadParams = $uploadModel->getParams();
        $allowFilesTypes = explode(',', $uploadParams->allow_files_types);		
        $uploadInfo = $this->uploadFile($_FILES['file'], $allowFilesTypes, static::PATH_TO_UPLOAD_FOLDER,$uploadParams->allow_files_size);
        $uploadInfo->hrefPreview = $this->createPreview($uploadInfo);
        $uploadInfo->pathToFile = JURI::base(). static::HREF_TO_UPLOAD_FOLDER. $uploadInfo->name;
        $uploadInfo->nameOfPreviewImg = pathinfo(JPATH_ROOT . $uploadInfo->hrefPreview)['basename'];
        
        $result = $this->generateAnswer($uploadInfo);

		echo json_encode($result);
		die;
    }

    public function uploadFile($file, $allowFilesTypes, $pathToUploadFolder,$allow_files_size=0)
    { 
        $isAjax = JFactory::getApplication()->input->getVar('ajax');
        $jshopConfig = JSFactory::getConfig();
		
        require_once $jshopConfig->path . 'lib/uploadfile.class.php';
        $upload = new UploadFile($file);
		$upload->setAllowFile($allowFilesTypes);
		if ($allow_files_size>0 && $allow_files_size>0) $upload->setMaxSizeFile($allow_files_size*1024);
		$upload->setDir($pathToUploadFolder);
		$upload->setFileNameMd5(0);
        $upload->setFilterName(1);
        $upload->upload();

        if (!empty($isAjax)) {
            echo json_encode($upload);
		    die;
        }

        return $upload;
    }

    protected function createPreview($uploadInfo)
    {
        if ($uploadInfo->file_upload_ok) {
            $pathToUploadedFileWithName = $uploadInfo->getPathToUploadedFile();
            $uploadedFileParams = $uploadInfo->parseNameFile($uploadInfo->getName());
            
            $converterToImg = new ConverterToImg();
            $pathToConverterFileWithName = static::PATH_TO_UPLOAD_FOLDER . 'preview_'. $uploadedFileParams['name'] . '.jpg';
            $isSuccessConvert = $converterToImg->convert($pathToUploadedFileWithName, $pathToConverterFileWithName);

            if ($isSuccessConvert) {
                return JURI::base().static::HREF_TO_UPLOAD_FOLDER . 'preview_'. $uploadedFileParams['name'] . '.jpg';
            } 
        }
        return static::PATH_TO_UPLOAD_FOLDER_NOIMAGE . 'noimage.gif';
    }

    protected function generateAnswer($uploadInfo)
    {
        $result = [
            'status' => 'error',
        ];
		if (strpos($uploadInfo->pathToFile,"/noimage.gif")>0) {
			$uploadInfo->pathToFile=static::PATH_TO_UPLOAD_FOLDER_NOIMAGE . 'noimage.gif';
		}
        if ($uploadInfo->file_upload_ok) {
            $result = [
                'status' => 'success',
                'previewImg' => $uploadInfo->hrefPreview,
                'previewName' => $uploadInfo->nameOfPreviewImg,
                'fileName' => $uploadInfo->name,
                'fullPathToFile' => $uploadInfo->fullPathToFile,
                'pathToFile' => $uploadInfo->pathToFile,
            ];
        }

        $result['uploadMsg'] = $uploadInfo->getUploadMsg();

        return $result;
    }
}