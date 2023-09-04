import React, { useState, useEffect } from '../../../js/react/node_modules/react';
import { Redirect } from '../../../js/react/node_modules/react-router-dom';
import { getPageData as getPageDataAction } from '../../../js/react/src/redux/modules/pageData';
import {connect} from "../../../js/react/node_modules/react-redux";

 const Step6 = ({pageData, getPageData}) => {
    return <Redirect to={pageData.finishPage} />;
}
export default  connect(
    ({ pageData }) => ({ pageData: pageData.qcheckoutData }),
    {
        getPageData: getPageDataAction
    }
)(Step6);