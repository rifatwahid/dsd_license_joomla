import React, { useState } from '../../../js/react/node_modules/react';

const Smarteditorlink = (props) => {
    var data = props.data;
    const element =
        (typeof data.editorsContent != 'undefined' && data.editorsContent != null && data.eeCategories[0] != 'undefined' && data.eeCategories[0] != null && data.eeCategories[0].enable == 0) ?
       <div>
            <div className="smarteditor_lp">
                <div className="line_ed"></div>
                <a href={dtaJson.eeLink}>smart <span>|</span> Editor</a>
            </div>

           {data.editorsContent[0].small_description}
       </div>
    : '';

    return (element);
}
export default Smarteditorlink;