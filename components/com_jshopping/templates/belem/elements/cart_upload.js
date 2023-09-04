import React, {useCallback, useState} from '../../../js/react/node_modules/react';
import Unserialize from '../elements/unserialize.js';
import Sprintpreviewnativeuploadedfiles from '../elements/sprintpreviewnativeuploadedfiles.js';
import Sprintjstemplatefornativeuploadedfiles from '../elements/sprintjstemplatefornativeuploadedfiles.js';
import shopUser from '../../../js/src/controllers/user/index.js';
import shopCart from '../../../js/src/controllers/cart/index.js';
import uploadImage from '../../../js/src/common/upload_image/index.js';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import Parser from '../../../js/react/node_modules/html-react-parser';

const Cart_upload = (data) => {
    const isSupportUpload = data.data.upload_common_settings.is_allow_cart_page == 1 && typeof data.prod['is_allow_uploads'] != undefined && typeof data.prod['is_allow_uploads'] != null && data.prod['is_allow_uploads'] != 0 && (data.prod['is_unlimited_uploads'] == 1 || data.prod['is_unlimited_uploads'] == 'INF' || data.prod['max_allow_uploads'] >= 1 || data.prod['max_allow_uploads'] == 'INF');

    function array_sum(arr) {
        var sum = 0;
        if (!arr || (arr.constructor !== Array && arr.constructor !== Object) || !arr.length) {
            return null;
        }
        for (var key in arr) {
            if(parseInt(arr[key]) > 0){
                sum = parseInt(sum) + parseInt(arr[key]);
            }
        }
        return sum;
    }
    let  isMultiUpload = 0;
    if(data.prod['max_allow_uploads'] >= 2 || data.prod['is_unlimited_uploads'] == 1 || data.prod['max_allow_uploads'] == 'INF' || data.prod['is_unlimited_uploads'] == 'INF' ) {
        isMultiUpload = 1;
    }
    const maxFilesUploads = (data.prod['is_unlimited_uploads']) ? 'INF' : data.prod['max_allow_uploads'];
    const uploadBlockNumber = data.key_id;
    let sumOfQtyUpload = 0;
    if (typeof data.prod['uploadData'] != 'undefined' && typeof data.prod['uploadData']['qty'] != 'undefined') {
        if(data.prod['uploadData']['qty'].length > 0){
            sumOfQtyUpload =  array_sum(data.prod['uploadData']['qty']);
        }
    }
    let style = '';
    let result = ['0'];
    let hideClassForImageInfo = '';
    let uploadPreviewFileName  = '';
    let uploadFileName   = '';
    let uploadQtyNumb   = 0;
    function getUploadQtyNumb(prod, key){ return 1;
        // if(typeof prod['uploadData'] != 'undefined' && typeof prod['uploadData']['qty'] != 'undefined' &&  prod['uploadData']['qty'][key] != null ){
        //     return 1;
        // }else{
        //     return 0;
        // }
    }

    if(isSupportUpload == true && typeof data.prod['uploadData'] != 'undefined' && typeof data.prod['uploadData']['files'] != 'undefined'){
        result = Object.keys(data.prod['uploadData']['files']).map((key) => data.prod['uploadData']['files'][key]);
    }

    let unserialize_prod = <Unserialize sdata={data.prod['buttons']} link={data.data.sereliaze_link}/>;
    if (unserialize_prod['upload'] == 1 ){style= "display: none;";}

    const remainingQtyUpload = data.prod['quantity'] - sumOfQtyUpload;
    const uploadIm = useCallback(e => uploadImage.startUpload(Joomla.getOptions('link_to_ajax_upload_files'), uploadImage.afterUpload,e.currentTarget, e),[]);

    const element =

            (isSupportUpload == true) ?
                <div>
    <div className={"nativeProgressUploads nativeProgressUploads--" + data.key_id + " mb-4"} data-native-uploads-block-number={uploadBlockNumber}  >

        {(isMultiUpload != 0) ?
            <div className="nativeMultiuploadProgressHeader">
                <div className="row">
                    <div className="col-md-6 align-self-center">
                        <div className="nativeMultiuploadProgressHeader__max">
                            <span className="nativeMultiuploadProgressHeader__maxText">{Joomla.JText._('COM_SMARTSHOP_FILE_MAXIMUM') }: </span>
                            <span className="nativeMultiuploadProgressHeader__maxNumber">{(maxFilesUploads === 'INF') ? Parser('&#8734;') : maxFilesUploads}</span>
                        </div>
                    </div>

                    <div className="col-md-6 align-self-center">
                        <div className="nativeMultiuploadProgressHeader__newUpload">
                            <a href="#" className="nativeMultiuploadProgressHeader__newUploadLink" onClick={(e) => uploadImage.addNewUpload('.nativeProgressUploads--'+uploadBlockNumber, e)}>
                                {Joomla.JText._('COM_SMARTSHOP_ADD_FILE')}
                            </a>
                        </div>
                    </div>
                </div>

                {(data.prod['is_upload_independ_from_qty'] == 0) ?
                <div className="nativeMultiuploadProgressHeader__remainingInfo">
                    <span className="nativeMultiuploadProgressHeader__remainingText"> {Joomla.JText._('COM_SMARTSHOP_REMAINING_QTY')}: </span>
                    <span className="nativeMultiuploadProgressHeader__remainingQty">{remainingQtyUpload}</span>
                </div>
                : ''}

                    <input type="hidden" className="remainingCurrentQty" name="nativeProgressUpload[remainingCurrentQty]" value={remainingQtyUpload} />
                    <input type="hidden" className="remainingTotalQty" name="nativeProgressUpload[remainingTotalQty]" value={data.prod['quantity']} />
                    <input type="hidden" name="nativeProgressUpload_allow_files_size" id="nativeProgressUpload_allow_files_size" value={data.data.upload_common_settings.allow_files_size} />
            </div>
            : '' }

        <div className="row nativeProgressUploads__rows">
            {(isSupportUpload == true ) ?
            result.map((files, key) =>

                <div className="col-md-12 mb-4" data-native-upload-row-number={key} key={key} >
                    <div className="nativeProgressUpload nativeProgressUpload--nouploaded"  onClick={uploadIm}>
                        <Button className="nativeProgressUpload__btn btn-block btn btn-primary" >
                            {Joomla.JText._('COM_SMARTSHOP_MOD_UPLOAD')}
                        </Button>

                        <div className="nativeProgressUpload__progress"></div>
                        <div className={(files == 0 || files == '') ? 'nativeProgressUpload-imageInfo display--none' : 'nativeProgressUpload-imageInfo'}>
                            <div className="row">
                                <div className="col-md-4">
                                    <div className="nativeProgressUpload-imageInfo__wrapper">
                                        <a href={(typeof data.prod['uploadData'] != 'undefined'&& typeof data.prod['uploadData']['previews'] != undefined && typeof data.prod['uploadData']['previews'] != 'undefined' && typeof data.prod['uploadData'][key] != undefined  && typeof data.prod['uploadData']['previews'][key] != undefined && data.prod['uploadData']['previews'][key] != null) ? "/components/com_jshopping/files/files_upload/" + data.prod['uploadData']['previews'][key] : "/components/com_jshopping/files/files_upload/"} target="_blank" className="nativeProgressUpload-imageInfo__link">
                                             <img src={(typeof data.prod['uploadData'] != 'undefined'&& typeof data.prod['uploadData']['previews'] != undefined && typeof data.prod['uploadData']['previews'] != 'undefined' && typeof data.prod['uploadData'][key] != undefined  && typeof data.prod['uploadData']['previews'][key] != undefined && data.prod['uploadData']['previews'][key] != null) ? "/components/com_jshopping/files/files_upload/" + data.prod['uploadData']['previews'][key] : "/components/com_jshopping/files/files_upload/"} alt="" className="nativeProgressUpload-imageInfo__img" />
                                        </a>
                                        <div className="nativeProgressUpload-imageInfo__description">
                                            <a href={(files != '' && files != 0) ? '/components/com_jshopping/files/files_upload/' + files : ''} className="nativeProgressUpload-imageInfo__description-link" target="_blank">{(files != '' && files != 0) ? files : ''}</a>
                                        </div>
                                    </div>
                                </div>

                                <div className="col-md-8 align-self-center">
                                    <div className={(isMultiUpload == null) ? 'nativeProgressUpload-imageInfo__qty  display--none' : 'nativeProgressUpload-imageInfo__qty'}>
                                        <input type="number" className="nativeProgressUpload-imageInfo__qtyInput"
                                               name="nativeProgressUpload[qty][]" min="0"
                                               onChange={(e) => shopCart.updateUploadImageQuantity(uploadBlockNumber)}
                                               defaultValue={getUploadQtyNumb(data.prod, key)} />
                                    </div>

                                    <div className="nativeProgressUpload-imageInfo__removeFile">
                                        <a href="#" className="nativeProgressUpload-imageInfo__removeFileLink" onClick={(e) => uploadImage.deleteUploadInCart('.nativeProgressUploads--'+uploadBlockNumber, key, e)}>
                                            {Joomla.JText._('COM_SMARTSHOP_REMOVE_FILE')}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {(data.data.upload_common_settings.upload_design == 1) ?
                                <Form.Control type="number" name="nativeProgressUpload[descriptions][]" className="nativeProgressUpload-imageInfo__deajaxUpdateDescribeUploadImageInCartscribeInput" onChange={(e) => ajaxUpdateDescribeUploadImageInCart(uploadBlockNumber)} defaultValue={(typeof data.prod['uploadData'] != 'undefined' && data.prod['uploadData']['descriptions'][key] != 'undefined') ? data.prod['uploadData']['descriptions'][key] : ''} placeholder={Joomla.JText._('COM_SMARTSHOP_UPLOAD_DESCRIPTION')} />
                            : ''}

                            <input type="hidden" name="nativeProgressUpload[previews][]" value={(typeof data.prod['uploadData'] != 'undefined'&& typeof data.prod['uploadData']['previews'] != undefined && typeof data.prod['uploadData']['previews'] != 'undefined' && typeof data.prod['uploadData'][key] != undefined  && typeof data.prod['uploadData']['previews'][key] != undefined && data.prod['uploadData']['previews'][key] != null) ? "/components/com_jshopping/files/files_upload/" + data.prod['uploadData']['previews'][key] : "/components/com_jshopping/files/files_upload/"} className="nativeProgressUpload__imageInput"/>
                            <input type="hidden" name="nativeProgressUpload[files][]" value={(files != '' && files != 0) ? files : ''} className="nativeProgressUpload__fileInput"/>
                        </div>
                    </div>
                </div>
             )
             : ''}

                </div>
                <input type="hidden" className="nativeProgressUpload__isIndependFromQty isIndependFromQty" name="nativeProgressUpload[isProductIndependFromQty]" value={data.prod['is_upload_independ_from_qty']} />
                <input type="hidden" className="numbOfMaxUploadsFiles" data-max-upload-files={maxFilesUploads} />

                </div>

            <div dangerouslySetInnerHTML={{ __html: data.prod['sprintjstempfiles'] }} />
</div>
                : (!isSupportUpload == true && data.prod['uploadData'] != null) ?
                <div className="cartUploadedeDataForNonSupportUpload">
                    {/*<Sprintpreviewnativeuploadedfiles upload_data={data.prod['uploadData']} />*/}
                </div>
        : ''
            ;
    return (element);
}

export default Cart_upload;