import React, { useState, useCallback } from '../../../js/react/node_modules/react';
import Unserialize from '../elements/unserialize.js';
import Sprintpreviewnativeuploadedfiles from '../elements/sprintpreviewnativeuploadedfiles.js';
import Sprintjstemplatefornativeuploadedfiles from '../elements/sprintjstemplatefornativeuploadedfiles.js';
import shopUser from '../../../js/src/controllers/user/index.js';
import uploadImage from '../../../js/src/common/upload_image/index.js';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import ReactDOMServer from '../../../js/react/node_modules/react-dom/server';
import Parser from '../../../js/react/node_modules/html-react-parser';
import shopProductFreeAttributes from '../../../js/src/controllers/product/freeattributes.js';

const Default_prod_upload = ( data ) => {
    var _data = data.__data;
    const isMultiUpload = _data.isMultiUpload;
    function array_sum(array) {
        var key, sum = 0;
        if (!array || (array.constructor !== Array && array.constructor !== Object) || !array.length) {
            return null;
        }
        for (var key in array) {
            sum += array[key];
        }
        return sum;
    }
    function updateQuantity(number) {
        let blockUpload = jQuery(`[data-native-uploads-block-number="${number}"]`);
        let remainingQty = jQuery(`input.remainingTotalQty`);

        if (remainingQty) {
            //let qtyInputs = jQuery(`.nativeProgressUploads--${number} input.nativeProgressUpload-imageInfo__qtyInput`);
            let currentQty = jQuery(`.nativeProgressUploads--${number} input[class="remainingCurrentQty"]`);
            let summ = 0;
            let result = 0;

            jQuery('.nativeProgressUpload-imageInfo__qtyInput').each(function(){
                let value = jQuery(this).val() ;
                if (value < 0) {
                    summ = parseInt(summ) - parseInt(value);
                } else {
                    summ= parseInt(summ) + parseInt(value);
                }
            });

            result = jQuery(remainingQty).val() - summ;
            return result;
        }
    }
    const maxFilesUploads = (_data.product['is_unlimited_uploads']) ? 'INF' : _data.product['max_allow_uploads'];

    let style = '';
    let result = '';
    let hideClassForImageInfo = '';
    let uploadPreviewFileName  = '';
    let uploadFileName   = '';
    let uploadQtyNumb   = 0;

    const uploadIm = useCallback(e => uploadImage.startUpload('/index.php?option=com_jshopping&controller=upload&task=ajaxUploadFile&product_id=' + _data.product_id, uploadImage.afterUpload,e.currentTarget, e),[]);
    const uploadImNew = useCallback(e => {uploadImage.addNewUpload('.nativeProgressUploads--0', e.nativeEvent); jQuery('.nativeProgressUploadRow1')},[]);
    const deleteIm = useCallback(e => uploadImage.deleteUpload('.nativeProgressUploads--0', 0, e),[]);
    const el = ReactDOMServer.renderToString(<Sprintjstemplatefornativeuploadedfiles data={_data}  uploadIm={uploadIm} upload_data={isMultiUpload}/>);
    const element =
        (_data.isSupportUpload == 'INF' && _data.isShowCartSection == 'INF') ?
            <div>
                <div className={"nativeProgressUploads nativeProgressUploads--0 mb-2"} data-native-uploads-block-number="0" style={{display:(_data.show_buttons['upload'] == 1) ? "none" : "block"}} >

                    <div className="nativeMultiuploadProgressHeader">
                        {(isMultiUpload == 'INF') ?
                            <div>
                                <div className="row">
                                    <div className="col-md-6 align-self-center">
                                        <div className="nativeMultiuploadProgressHeader__max">
                                            <span className="nativeMultiuploadProgressHeader__maxText">{Joomla.JText._('COM_SMARTSHOP_FILE_MAXIMUM') }: </span>
                                            <span className="nativeMultiuploadProgressHeader__maxNumber">{
                                                (_data.maxFilesUploads == 'INF') ? Parser('&#8734;') : _data.maxFilesUploads}</span>
                                        </div>
                                    </div>

                                    <div className="col-md-6 align-self-center">
                                        <div className="nativeMultiuploadProgressHeader__newUpload">
                                            <a className="btn btn-outline-primary" href="#" className="nativeMultiuploadProgressHeader__newUploadLink" onClick={uploadImNew}>
                                                {Joomla.JText._('COM_SMARTSHOP_ADD_FILE')}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {(_data.product['is_upload_independ_from_qty'] != 1) ?
                                    <div className="nativeMultiuploadProgressHeader__remainingInfo">
                                        <span className="nativeMultiuploadProgressHeader__remainingText"> {Joomla.JText._('COM_SMARTSHOP_REMAINING_QTY')}: </span>
                                        <span className="nativeMultiuploadProgressHeader__remainingQty">{_data.product.productQuantity}</span>
                                    </div>
                                    : ''}
                            </div>

                            : '' }
                        <input type="hidden" className="remainingCurrentQty" name="nativeProgressUpload[remainingCurrentQty]" value={0} />
                        <input type="hidden" className="remainingTotalQty" name="nativeProgressUpload[remainingTotalQty]" value={_data.product.productQuantity} />
                    </div>

                    <div className="row nativeProgressUploads__rows">
                        <div className="col-md-12 mb-2" data-native-upload-row-number="0" >
                            <div className="nativeProgressUpload nativeProgressUpload--nouploaded" onClick={uploadIm}>
                                {/*<a href="" className="nativeProgressUpload__btn" style={{display:(_data.show_buttons['upload'] == 1) ? "none" : "block"}}>*/}
                                <Button className="nativeProgressUpload__btn btn-block" style={{display:(_data.show_buttons['upload'] == 1) ? "none" : "block"}}>
                                    {Joomla.JText._('COM_SMARTSHOP_MOD_UPLOAD')}
                                </Button>
                                {/*</a>*/}

                                <div className="nativeProgressUpload__progress"></div>
                                <div className="nativeProgressUpload-imageInfo display--none">
                                    <div className="row">
                                        <div className="col-md-4">
                                            <div className="nativeProgressUpload-imageInfo__wrapper">
                                                <a href="#" target="_blank" className="nativeProgressUpload-imageInfo__link">
                                                    <Image src="/components/com_jshopping/files/img_shop_products/noimage.gif" alt="" className="nativeProgressUpload-imageInfo__img" />
                                                </a>
                                                <div className='nativeProgressUpload-imageInfo__description'>######</div>
                                            </div>
                                        </div>

                                        <div className="col-md-8 align-self-center">

                                            <div className={(!isMultiUpload) ? 'nativeProgressUpload-imageInfo__qty display--none' : 'nativeProgressUpload-imageInfo__qty'}>
                                                <Form.Control type="number" className="nativeProgressUpload-imageInfo__qtyInput" name="nativeProgressUpload[qty][]" min="0" onChange={(e) => {uploadImage.updateQuantity(0);shopProductFreeAttributes.setData();}} defaultValue={(!isMultiUpload) ? _data.product.productQuantity : 1 } />
                                            </div>

                                            <div className="nativeProgressUpload-imageInfo__removeFile">
                                                <a href="" className="nativeProgressUpload-imageInfo__removeFileLink" onClick={deleteIm}>
                                                    {Joomla.JText._('COM_SMARTSHOP_REMOVE_FILE')}
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    {(_data.upload_common_settings.upload_design != null && _data.upload_common_settings.upload_design == 1) ?
                                        <Form.Control type="text" name="nativeProgressUpload[descriptions][]" className="nativeProgressUpload-imageInfo__describeInput" placeholder={Joomla.JText._('COM_SMARTSHOP_UPLOAD_DESCRIPTION')} />
                                        : ''}

                                    <input type="hidden" name="nativeProgressUpload[previews][]" className="nativeProgressUpload__imageInput"/>
                                    <input type="hidden" name="nativeProgressUpload[files][]" className="nativeProgressUpload__fileInput"/>
                                    <input type="hidden" name="nativeProgressUpload_allow_files_size" id="nativeProgressUpload_allow_files_size" value={_data.upload_common_settings.allow_files_size}/>
                                </div>
                            </div>
                        </div>
                    </div>


                    <input type="hidden" className="nativeProgressUpload__isIndependFromQty isIndependFromQty" name="nativeProgressUpload[isProductIndependFromQty]" value={_data.product.is_upload_independ_from_qty} />
                    <input type="hidden" className="numbOfMaxUploadsFiles" data-max-upload-files={_data.maxFilesUploads} />

                </div>
                <div dangerouslySetInnerHTML={{ __html: _data.sprintjstempfiles }} />
            </div>
            : '' ;

    return (element);
}

export default Default_prod_upload;