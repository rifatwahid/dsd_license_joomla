<?php
/**
* @version      4.3.1 05.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

/**
ERROR UPLOAD
0 - File Upload Finished
1 - File Error size ini
2 - UPLOAD_ERR_FORM_SIZE
3 - UPLOAD_ERR_PARTIAL
4 - UPLOAD_ERR_NO_FILE (file not upload)
11 - File not allow
12 - File forbid
13 - File copy Error
14 - File Error size class
15 - Error array $_FILES or filesize > post_max_size
*/

class UploadFile
{
    const UPLOAD_CODES = [
        0 => 'File upload finished.',
        1 => 'File error size ini.',
        2 => 'UPLOAD_ERR_FORM_SIZE',
        3 => 'UPLOAD_ERR_PARTIAL',
        4 => 'File not upload.',
        11 => 'File not allow.',
        12 => 'File forbid.',
        13 => 'File copy error.',
        14 => 'File error size class.',
        15 => 'Error array $_FILES or filesize > post_max_size.'
    ];

    /* File parametr from $_FILES */
    public $name = null;
    public $tmp_name = null;
    public $type = null;
    public $size = null;
    public $error = null;
    public $fullPathToFile = null;
    
    public $uploaded_real_name_file = "";

    /*Upload Dir*/
    public $dir = ".";
    public $new_dir_access = 0777;

    /*Config*/
    public $auto_rename_file = 1;
    public $auto_create_dir = 1;
    public $file_upload_ok = 0;
    public $file_name_md5 = 1;
    public $file_name_filter = 0;

    /*install allow or forbid files ext*/
    public $allow_file = array();
    public $forbid_file = array('php','php2','php3','php4','php5','js','html','htm');

    /*set upload max file size (kb)*/
    public $maxSizeFile = 0;

    /**
    * constructor
    * @param $file - $_FILES
    */
    public function __construct($file)
    {
        if (!is_array($file)){
            $this->error = 15;
            return 0;    
        }
        $this->name = $file['name'];
        $this->tmp_name = $file['tmp_name'];
        $this->type = $file['type'];
        $this->size = $file['size'];
        $this->error = $file['error'];
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setDir($val)
    {
        $this->dir = $val;

        return $this;
    }

    public function getDir()
    {
        return $this->dir;
    }

    public function getPathToUploadedFile()
    {
        $pathtoUploadedFile = $this->dir . '/' . $this->name;

        if (file_exists($pathtoUploadedFile)) {
            return $pathtoUploadedFile;
        }
    }
    
    public function setAutoRenameFile($val)
    {
        $this->auto_rename_file = $val;

        return $this;
    }
    
    public function setNameWithoutExt($name)
    {
        $tmp = $this->parseNameFile($this->name);
        if ($tmp['ext']!='') $ext = ".".$tmp['ext']; else $ext = "";
        $this->name = $name.$ext;

        return $this;
    }

    /**
    * $size int - max size upload file in (Kb)
    */
    public function setMaxSizeFile($size)
    {
        $this->maxSizeFile = $size;

        return $this;
    }
    
    /**
    * set to md5 name file
    */
    public function setFileNameMd5($val)
    {
        $this->file_name_md5=$val;

        return $this;
    }
    
    /**
    * set filter name (enable, disable)
    */    
    public function setFilterName($val)
    {
        $this->file_name_filter = $val;

        return $this;
    }

    /**
    * set array allow file upload
    */
    public function setAllowFile($file)
    {
        $this->allow_file = array_map('strtolower', $file);
        $this->forbid_file = array();

        return $this;
    }
    
    /**
    * set array forbid file upload
    */
    public function setForbidFile($file)
    {
        $this->forbid_file = array_map('strtolower',$file);
        $this->allow_file = array();

        return $this;
    }
    
    /**
    * after upload
    */
    public function getError()
    {
        return $this->error;
    }

    public function getUploadMsg()
    {
        $msg = '';

        if (isset($this->error)) {
            $msg = static::UPLOAD_CODES[$this->error];
        }

        return $msg;
    }

    /**
    * @param string name file
    * @return array("name","ext","dir")
    */
    public function parseNameFile($name)
    {
        $pathinfo=pathinfo($name);
        $ext=$pathinfo['extension'];
        $name=$pathinfo['basename'];
        $dir=$pathinfo['dirname'];
        if ($ext!="") $b_name=substr($name,0,strlen($name)-strlen($ext)-1); else $b_name=$name;

        return array('name'=>$b_name, "ext"=>$ext, "dir"=>$dir);
    }
        
    /**
    * rename file md5 name
    */
    public function renameFileMd5($name)
    {
        $m=$this->parseNameFile($name);
		$m['name']=md5(time().$m['name']);
        if ($m['ext']!="") $m['ext']='.'.$m['ext'];
        $name=$m['name'].$m['ext'];

        return $name;
    }

    /**
    * rename existented file
    */
    public function renameExistingFile($dir, $name)
    {
        if (is_file($dir."/".$name)) {
            $m=$this->parseNameFile($name);
            if ($m['ext']!="") $m['ext']='.'.$m['ext'];
            $i=1;
            $name=$m['name'].$i.$m['ext'];
            while (is_file($dir."/".$name)){
                $name=$m['name'].$i.$m['ext'];
                $i++;
            }
        }

        return $name;
    }
    
    /**
    * rename file from filter
    */
    public function renameFileFilter($name)
    {
        $filters = array();
        $filters["ü"] = "u";
        $filters["ä"] = "a";
        $filters["ö"] = "o";
        $filters["Ü"] = "U";
        $filters["Ä"] = "A";
        $filters["Ö"] = "O";
        $filters["ß"] = "ss";

        foreach($filters as $k=>$v){
            $name = str_replace($k, $v, $name);
        }
        $name = preg_replace('/[^a-zA-Z\d_\-\.]|(\.(?=.*\.))/', '_', $name);
        
        return $name;
    }

    /**
    * get test file allow
    */
    public function getTestFileAllow()
    {
        $mas=pathinfo($this->name);
        $ext=strtolower($mas['extension']);

        if (count($this->allow_file)>0){
             if (!in_array($ext,$this->allow_file)) {
                 $this->error=11;
                 return 0;
             }
        }

        if (count($this->forbid_file)>0){
             if (in_array($ext,$this->forbid_file)) {
                 $this->error=12;
                 return 0;
             }
        }
        
        if ($this->maxSizeFile!=0 && $this->size > $this->maxSizeFile*1024){
             $this->error=14;
            return 0;
        }

        return 1;
    }

    public function getSuccessUploadedFileInfo(): array
    {
        $return = [];

        if (!empty($this->file_upload_ok)) {
            $return = [
                'name' => $this->uploaded_real_name_file,
                'path' => $this->fullPathToFile
            ];
        }

        return $return;
    }

    /**
    * start upload
    */
    public function upload()
    {
        $this->file_upload_ok = 0;
        if ($this->error!==0) return 0;
        if (!$this->getTestFileAllow()) return 0;
        if ($this->auto_create_dir && !is_dir($this->dir)) mkdir($this->dir, $this->new_dir_access);
        if ($this->file_name_md5) $this->name = $this->renameFileMd5($this->name);
        if ($this->file_name_filter) $this->name = $this->renameFileFilter($this->name);
        if ($this->auto_rename_file) $this->name = $this->renameExistingFile($this->dir, $this->name);
        $this->uploaded_real_name_file = $this->name;
        $fileNewPlace = "{$this->dir}/{$this->name}";

        @chmod($fileNewPlace, 0777);

        if (move_uploaded_file($this->tmp_name, $fileNewPlace)) {
            $this->file_upload_ok = 1;
            $this->fullPathToFile = $this->dir . $this->name;

            return 1;
        } else {
            $this->file_upload_ok = 0;
            $this->error=13;
            return 0;
        }
    }

}

class UploadImage extends UploadFile
{
    public $name_image = '';
    public $dir_image = '';
    public $quality = 85;
    public $prefix = 'thumb_';

    public function copyImage($width=120, $height=0)
    {
        if (!$this->file_upload_ok) return 0;

        $this->name_image=$this->prefix.$this->name;
        if (!$this->dir_image) $this->dir_image = $this->dir;
        if ($this->auto_create_dir && !is_dir($this->dir_image))  mkdir($this->dir_image,$this->new_dir_access);
        if ($this->file_name_md5) $this->name_image = $this->renameFileMd5($this->name_image);
        if ($this->file_name_filter) $this->name = $this->renameFileFilter($this->name);
        if ($this->auto_rename_file) $this->name_image=$this->renameExistingFile($this->dir_image, $this->name_image);            
        return $this->resizeImage($this->dir."/".$this->uploaded_real_name_file, $width ,$height, $this->dir_image."/".$this->name_image, $this->quality);        
    }

    public function setQuality($quality)
    {
        $this->quality=$quality;

        return $this;
    }

    public function getNameImage()
    {
        return $this->name_image;
    }
    
    public function setDirImage($val){
        $this->dir_image = $val;

        return $this;
    }
    
    public function getDirImage()
    {
        return $this->dir_image;
    }
    
    public function setPrefixImage($val)
    {
        $this->prefix = $val;

        return $this;
    }
    
    public function getPrefixImage()
    {
        return $this->prefix;
    }
    
    public function resizeImage($image, $nw=0, $nh=0, $img_to="", $quality=85)
    {
        $path=pathinfo($image);
        $ext=$path['extension'];
        $ext=strtolower($ext);

        if (($ext=="jpg")or($ext=="jpeg")) 
            $si=imagecreatefromjpeg($image);
        elseif ($ext=="gif") 
            $si=imagecreatefromgif($image);
        elseif ($ext=="png") 
            $si=imagecreatefrompng($image);
        else
            return 0;
        
        if (!$si) return 0;

        $sw=imagesx($si);
        $sh=imagesy($si);
        if ($nw==0 && $nh==0) $nw=$sw;
        if ($nh==0) $nh=(int)(($nw/$sw)*$sh);
        if ($nw==0) $nw=(int)(($nh/$sh)*$sw);
        $dim=imagecreatetruecolor($nw,$nh);
        if ($ext=="png") imagefilledrectangle($dim,0,0,$nw,$nh,0xFFFFFF);
        imagecopyresampled($dim,$si,0,0,0,0,$nw,$nh,$sw,$sh);
        

        switch($ext){
            case 'jpg':
            case 'jpeg':
                imagejpeg($dim, $img_to, $quality);
            break;
            case 'gif':
                if ($img_to)
                    imagegif($dim, $img_to);
                else
                    imagegif($dim);
            break;
            case 'png':
                if (phpversion()>='5.1.2'){
                    imagepng($dim, $img_to, 10-max(intval($quality/10),1));
                }else{
                    imagepng($dim, $img_to);
                }
            break;
            default:
                return 0;
            break;
        }

        imagedestroy($si);
        imagedestroy($dim);

    return 1;
    }

}