import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import reactStringReplace from '../../../js/react/node_modules/react-string-replace';
import shopProductFreeAttributes from '../../../js/src/controllers/product/freeattributes.js';


const Free_attribute = (data) => {
    let result = Object.keys(data.product.freeattributes).map((key) =>data.product.freeattributes[key]);


    const element =
        (typeof data.product.freeattributes != 'undefined') ?
            result.map((freeattribut,ins) =>
                <div className="form-group free-attr" data-free-attr-id={freeattribut.id} key={ins} >
                    <label className="d-block free-attr__label">
                        <div className="row free-attr__row">
                            <div className="col-8 free-attr__col1">
                                <p className={(freeattribut.required) ? "h6 free-attr__title free-attr__title--required" : "h6 free-attr__title" }>
                                    {freeattribut.name + ' '}
                                    {(freeattribut.required == 1) ? '*' : ''}
                                </p>

                                <p className="free-attr__min-max-text">
                                    {freeattribut.min_max_value}
                                </p> 
                                {(freeattribut.description != null) ?
                                <p className="free-attr__description text-muted text-small mt-1">
                                    {freeattribut.description}
                                </p>
                                : ''}
                            </div>

                            <div className="col-4 free-attr__col2">
                                <div className="free-attr__field"  dangerouslySetInnerHTML={{__html:freeattribut.input_field}}>

                                </div>

                                <div className="free-attr__unit units_measure text-center">
                                    {freeattribut.units_measure}
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            )
: '';

    return (element);
}

export default Free_attribute;