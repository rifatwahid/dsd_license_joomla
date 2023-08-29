<?php 

defined('_JEXEC') or die('Restricted access');

class jshopNativeUploadsPrices extends JTableAvto
{
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_native_uploads_prices', 'id', $_db);
    }

	public function bind($src, $ignore = [])
	{
		$fields = (parent::getTableFields()) ?: [];	
		$src = is_array($src) ? (object)$src : $src;		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src->$key))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					$src->$key="";
				}
			}
			
			if ((($src->$key==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')){					
					$src->$key=0;
				}
			}
						
		}
		return parent::bind($src, $ignore);
	}
	
    public function modifyPrice($priceToModify, $amountOfUploads = 0)
    {
        if (!empty($priceToModify)) {
            $isFilledPercentField = !empty($this->percent);
            $isFilledPriceField = !empty($this->price);
            
            if ($isFilledPercentField) {
                $discountPrice = getDiscountPrice($priceToModify, $this->percent);

                if (!empty($amountOfUploads)) {
                    $discountPrice *= $amountOfUploads;
                }

                $priceToModify += $discountPrice;
            } elseif ($isFilledPriceField) {

                $uploadPrice = $this->price;
                if (!empty($amountOfUploads)) {
                    $uploadPrice *= $amountOfUploads;
                }

                $priceToModify += $uploadPrice;
            }
        }

        return $priceToModify;
    }
}