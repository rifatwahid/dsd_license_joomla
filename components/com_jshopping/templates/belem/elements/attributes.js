import React, { useState, useCallback } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';


const Attributes = (props) => {
    const onChange = useCallback(e => shopProductForm.formHandler(e.target.form, e),[]);
    let element = '';
    let result = Object.keys(props.attributes).map((key) => props.attributes[key]);
        element =
            //(props.attributes !== '') ?
                result.map((attribut, ins) =>
                    (attribut.attr_type == 3 || (attribut.expiration == 1 && props.product.product_packing_type == 1)) ?
                        <div className="display--none" key={ins}>
                            {Parser(attribut.selects.replace("selected", "defaultValue"))}
                        </div>
                        :
                        <div key={ins}
                             className={(attribut.selects === "") ? "form-group jshop_prod_attributes display--none" : "form-group jshop_prod_attributes"}>
                            {(attribut.grshow == 1 && !attribut.hide_title == 1) ?
                                <h5 className="mb-3">{attribut.groupname}</h5>
                                : ''}
                            <label className="d-block">
                                <span className="h6">{attribut.attr_name}</span>
                                {(attribut.attr_description !== '') ?
                                    <p className="text-muted text-small mt-1"
                                       dangerouslySetInnerHTML={{__html: attribut.attr_description}} />
                                    : ''}

                                <span id={"block_attr_sel_" + attribut.attr_id} dangerouslySetInnerHTML={{__html: attribut.selects}} />
                                    {/*{Parser(attribut.selects.replace("selected", "value"))}*/}

                                {/*</span>*/}
                            </label>
                        </div>
                )


    return (element);
}

export default Attributes;