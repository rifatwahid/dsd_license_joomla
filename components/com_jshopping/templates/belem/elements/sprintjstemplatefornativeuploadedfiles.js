import React, {useCallback, useState} from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';
import ReactDOMServer from '../../../js/react/node_modules/react-dom/server';
import uploadImage from '../../../js/src/common/upload_image/index.js';
import shopProductFreeAttributes from '../../../js/src/controllers/product/freeattributes.js';

const Sprintjstemplatefornativeuploadedfiles = (data) => {
    const onClick = useCallback(e => {uploadImage.startUpload(Joomla.getOptions('link_to_ajax_upload_files'), uploadImage.afterUpload,e.currentTarget, e);alert('aaaaaaa');},[]);

    const element = <div className="col-md-12 mb-2" data-native-upload-row-number="#">
            <div className="nativeProgressUpload nativeProgressUpload--nouploaded"  onClick={data.uploadIm}>

                <Button className="nativeProgressUpload__btn" >
                    {Joomla.JText._('COM_SMARTSHOP_MOD_UPLOAD')}
                </Button>

            <div className="nativeProgressUpload__progress"></div>
            <div className="nativeProgressUpload-imageInfo display--none">
                <div className="row">
                    <div className="col-md-4">
                        <div className="nativeProgressUpload-imageInfo__wrapper">
                            <a href="#" target="_blank" className="nativeProgressUpload-imageInfo__link">
                                <Image src="/components/com_jshopping/files/img_shop_products/noimage.gif" alt="" className="nativeProgressUpload-imageInfo__img" />
                            </a>
                            <div className="nativeProgressUpload-imageInfo__description">####</div>
                        </div>
                    </div>

                    <div className="col-md-8 align-self-center">

                        <div className={(!data.data.isMultiUpload) ? 'nativeProgressUpload-imageInfo__qty display--none' : 'nativeProgressUpload-imageInfo__qty'}>
                            <input type="number" className="nativeProgressUpload-imageInfo__qtyInput" name="nativeProgressUpload[qty][]" min="0" defaultValue="0" onChange={(e) => {uploadImage.updateQuantity(0, e.target);shopProductFreeAttributes.setData()}} />
                        </div>

                        <div className="nativeProgressUpload-imageInfo__removeFile">
                            <a href="#" className="nativeProgressUpload-imageInfo__removeFileLink">
                                {Joomla.JText._('COM_SMARTSHOP_REMOVE_FILE')}
                            </a>
                        </div>
                    </div>
                </div>

                {(data.data.upload_common_settings.upload_design == 1) ?
                    <Form.Control type="text" name="nativeProgressUpload[descriptions][]" className="nativeProgressUpload-imageInfo__describeInput" placeholder={Joomla.JText._('COM_SMARTSHOP_UPLOAD_DESCRIPTION')} />
                : ''}

                    <input type="hidden" name="nativeProgressUpload[previews][]" className="nativeProgressUpload__imageInput" />
                    <input type="hidden" name="nativeProgressUpload[files][]" className="nativeProgressUpload__fileInput" />
                    <input type="hidden" name="nativeProgressUpload_allow_files_size" id="nativeProgressUpload_allow_files_size" value={data.data.upload_common_settings.allow_files_size}/>
            </div>
        </div>
    </div>;
    return (element);
}

export default Sprintjstemplatefornativeuploadedfiles;