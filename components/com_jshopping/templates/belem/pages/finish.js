import React, {useEffect, useState} from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import {connect} from "../../../js/react/node_modules/react-redux";
import {getFinishData as getFinishDataAction} from "../../../js/react/src/redux/modules/pageData";
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';

const Finish = ({finishData, getFinishData}) => {
    let data = finishData;
    useEffect(() => {
        getFinishData(window.location.href + '?ajax=1&ajax=1');
    }, []);
    if (data.component) {
        return (data.text != null && data.text != '') ?
            Parser(data.text)
        :
         <p>{Joomla.JText._('COM_SMARTSHOP_THANK_YOU_ORDER')}</p>
        ;
    }else{
        return <div class="d-flex justify-content-center"><Image className="center order-thumbnail preload_img" src="/components/com_jshopping/templates/belem/images/loading-buffering.gif" /></div>;
    }

}

export default  connect(
    ({ finishData }) => ({ finishData: finishData.finishData }),
    {
        getFinishData: getFinishDataAction
    }
)(Finish);