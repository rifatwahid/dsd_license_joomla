<?php 

class Uploader
{
    /**
     * @return array
     */
    public function store(array $fileInfo, array $allowedFormats = [], string $outputPath): ?array
    {
        $result = [];
        $isSetUploadFile = !empty($fileInfo['name']) && !empty($fileInfo['tmp_name']);

        if ($isSetUploadFile) {

            $fileUploader = new UploadFile($fileInfo);
            $isSuccessUploaded = $fileUploader->setDir($outputPath)
                ->setAllowFile($allowedFormats)
                ->setFileNameMd5(0)
                ->setFilterName(1)
                ->upload();

            if (!$isSuccessUploaded) {
                throw new \Exception($fileUploader->getUploadMsg(), $fileUploader->getError());
            } 

            return $fileUploader->getSuccessUploadedFileInfo();
        }
    
        return $result;
    }
}