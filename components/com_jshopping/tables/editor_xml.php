<?php

class ExpresseditorModelEditor_xml extends JModelLegacy{
    
    protected $xml;
    protected $xml_id;

    public function load($file, $show_error = 1){
        if ($show_error && !file_exists($file)){
            echo "Error creating XML file";
            die();
        }
        $patch = pathinfo($file);
        $this->xml_id = $patch['filename'];        
        $this->xml = simplexml_load_file($file);
        return $this->xml;
    }
    
    public function getXml(){
        return $this->xml;
    }
    
    public function getWeightSqrMeter(){
        return round((double)$this->xml->weightSqrMeter->attributes()->value, 2);
    }
    
    public function getKind(){
        return $this->xml->product[0]['kind'];
    }
    
    public function getEditor_id(){
        $xmlname = $this->xml->xmlname;
        $editor_id = substr($xmlname, strpos($xmlname,'/')+1, strlen($xmlname));
        return (int)substr($editor_id, 0, strpos($editor_id,'_'));
    }
    
    public function getCount(){
        $c = (int)$this->xml->count;
        if ($c < 1) {
            $c = 1;
        }
        return $c;
    }
    
    public function getNead_fotolia_foto(){
        if (count($this->xml->fotoliaPictures[0]->item)>0){
            return 1;
        }else{
            return 0;
        }
    }
    
    public function getNead_123rf_foto(){
        if (count($this->xml->rf123Pictures[0]->item)>0){
            return 1;
        }else{
            return 0;
        }
    }
    
    public function getNead_pattern2_foto(){
        if (count($this->xml->pattern2Pictures[0]->item)>0){
            return 1;
        }else{
            return 0;
        }
    }
	
	public function getNead_colourbox_foto(){
        if (count($this->xml->colourboxPictures[0]->item)>0){
            return 1;
        }else{
            return 0;
        }
    }
    
    public function getPriceDescription($simple_name = 0){
        $model_ee_options = JModelLegacy::getInstance('ee_options', 'ExpresseditorModel');
        $showprice = $model_ee_options->getData(3006);                		
        $showzeroprice = $model_ee_options->getData(3002);
        
        $price_description = '';
        foreach($this->xml->details as $pr){
            if ($simple_name==1){
                $price_description .= $pr['priceProduct'] . '::' . $pr['nameProduct'] . '<br>';            
            }else{
                if (($showzeroprice->value!='0')||($pr['priceProduct']>0)){
                    if ($showprice->value=='1'){
                        $price_description.=$pr['nameProduct'].'<br>';                    
                    }else{
                        $price_description.=$pr['priceProduct'].'::'.$pr['nameProduct'].'<br>';                    
                    }
                }
            }
		}
        return $price_description;
    }
    
    public function getPattern_exist(){
        $pattern_exist = 0;
        foreach($this->xml->details as $pr){
            if ($pr['pattern']==1){
                $pattern_exist = 1;                
            }
        }
        return $pattern_exist;
    }
    
    public function getPattern(){
        $pattern = null;
        foreach($this->xml->details as $pr){
            if ($pr['pattern']==1){                
                $pattern = $pr;
            }
        }
        return $pattern;
    }
    
    public function createEditorProduct($template_title, $lng, $price_description, $user, $options = array()){        
        $type = $this->getKind();
        $price = $this->xml->product[0]->price;
        $editor_id = $this->getEditor_id();
        $nead_fotolia_foto = $this->getNead_fotolia_foto();
        $nead_123rf_foto = $this->getNead_123rf_foto();
        $nead_pattern2_foto = $this->getNead_pattern2_foto();
		$nead_colourbox_foto = $this->getNead_colourbox_foto();
        $c = $this->getCount();        
        $editor_data = JModelLegacy::getInstance('ee_editors', 'ExpresseditorModel')->getData($editor_id);		
		$editor_title = 'editor_title_'.$lng;
        $type_name = $editor_data->$editor_title;        
        $title = $this->getProductTitle($template_title, $editor_data->size_in_editor_product_title, $type_name);        
        
        $editor_products = JTable::getInstance('editor_products', 'ExpresseditorTable');
        
        $data = array(
            'template_user_name'=>$template_title,
            'title_en'=>$title,
            'title_de'=>$title,
            'descriptions_en'=>$price_description,
            'descriptions_de'=>$price_description,
            'type'=> (string)$type,
            'enable'=>0,
            'price'=> (string)$price,
            'create_date'=>date('Y-m-d H:i:s'),
            'xml_id'=>$this->xml_id,
            'status'=>1,
            'nead_fotolia_foto'=>$nead_fotolia_foto,
            'nead_123rf_foto'=>$nead_123rf_foto,
            'nead_pattern2_foto'=>$nead_pattern2_foto,
			'nead_colourbox_foto'=>$nead_colourbox_foto,
            'user_id'=>$user->id,
            'user_product'=>1,
            'cnt'=>$c,
            'editor_id'=>$editor_id,
            'assignedStage'=>json_encode($this->getAssignedStage())
        );
        if ($options['wishlist']){
            $data['wishlist'] = $_SESSION['customer_id'];//???
        }
        $editor_products->bind($data);
        $editor_products->store();
        $product_id = $editor_products->id;
        
        $fotoliaPictures_size = $this->xml->fotoliaPictures[0]['size'];
		$rf123Pictures_size = $this->xml->rf123Pictures[0]['size'];
		$colourboxPictures_size = $this->xml->colourboxPictures[0]['size'];
		
        
        if ($options['fotoliaPictures']){
            $table_pff = JTable::getInstance('products_fotolia_foto', 'ExpresseditorTable');
            $table_pff->saveFromXmlItems($this->xml->fotoliaPictures[0]->item, $product_id, $fotoliaPictures_size);
        }
        
        if ($options['rf123Pictures']){
            $table_p123f = JTable::getInstance('products_123rf_foto', 'ExpresseditorTable');
            $table_p123f->saveFromXmlItems($this->xml->rf123Pictures[0]->item, $product_id, $rf123Pictures_size);
        }
        
        if ($options['pattern2Pictures']){
            $table_ppf = JTable::getInstance('products_pattern2_foto', 'ExpresseditorTable');
            $table_ppf->saveFromXmlItems($this->xml->pattern2Pictures[0]->item, $product_id, 0);
        }
/*
		echo "<pre>".$colourboxPictures_size;		
print_r($this->xml->colourboxPictures);
die();
*/
		 if ($options['colourboxPictures']){
            $table_pff = JTable::getInstance('products_colourbox_foto', 'ExpresseditorTable');
            $table_pff->saveFromXmlItems($this->xml->colourboxPictures[0]->item, $product_id, $colourboxPictures_size);
        }
        
        return $product_id;
    }
    
    public function getProductTitleCreated(){
        return $this->productTitleCreated;
    }
    
    public function getProductTitle($template_title, $size_in_editor_product_title, $type_name){
        $w = $this->xml->product[0]->sizeFormat['w'];
        $h = $this->xml->product[0]->sizeFormat['h'];
        
        $units = JModelLegacy::getInstance('ee_parameters', 'ExpresseditorModel')->getDataFromName('units_title');
        $units_title = $units->param_value;        
        
		if ($template_title==''){
			if ($size_in_editor_product_title==1){
				$title=$type_name." ($w x $h".$units_title.")";
			}else{
				$title=$type_name;
			}
        }else{
			if ($size_in_editor_product_title==1){
				$title=$template_title." ($w x $h".$units_title.")";
			}else{
                $title=$template_title;
			}
		}
        $this->productTitleCreated = $title;
        return (string)$title;
    }

    public function getAssignedStage(){
        $addons_params_product = json_decode((string)($this->xml->product->addons_params_product->attributes()->addons_params_product));        
        return $addons_params_product->assignedStage;
    }

    public function getDataParametrsToEditorProd(){
        if ($this->xml->dataParametrsToEditorProd){
            return json_decode((string)$this->xml->dataParametrsToEditorProd, 1);
        }else{
            return array();
        }
    }
    
}
