import React, { useState } from '../../../js/react/node_modules/react';
import Image  from '../../../js/react/node_modules/react-bootstrap/Image';

const Noresult_search = (props) => {
    let data = props.data;

    const element = <div className="shop search-results">
        <h1 className="search-results__page-title">
            {Joomla.JText._('COM_SMARTSHOP_SEARCH')}
        </h1>

        <p className="search-results__no-result">
            {Joomla.JText._('COM_SMARTSHOP_SEARCH_RESULTS_NONE')}{ ' "'+data.search + '"'}
        </p>
    </div>;

    return (element);
}

export default Noresult_search;