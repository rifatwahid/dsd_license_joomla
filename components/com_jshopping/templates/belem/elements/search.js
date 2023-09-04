import React, { useState } from '../../../js/react/node_modules/react';
import Form from '../../../js/react/node_modules/react-bootstrap/Form';
import Button from '../../../js/react/node_modules/react-bootstrap/Button';


const Search = (searchText) => {

    const [search_text_val, setTextsearch] = useState(1);
    const [search_text_value, setTextsearchVal] = useState(searchText.text);

    const element = <div className="js-stools-container-bar smartShopMiniSearch">
        <div className="filter-search btn-group pull-left">
            <Form.Control id="search_text" name="search_text" className="smartShopMiniSearch__search" onChange={e => setTextsearchVal(e.target.value)} value={search_text_value} placeholder={Joomla.JText._('COM_SMARTSHOP_SEARCH')}/>
            <input type="hidden" name="search_text_reset" className="smartShopMiniSearch__search-reset" value={search_text_val} />

        <div className="btn-group pull-left">
            <Button className="btn hasTooltip" type="submit" title={Joomla.JText._('COM_SMARTSHOP_SEARCH')}>
                <i className="icon-search"></i>
            </Button>

            <Button className="btn hasTooltip" onClick={() => setTextsearch(1),() => setTextsearchVal('')}  type="submit"
                    name="clearInput"  title={Joomla.JText._('COM_SMARTSHOP_CLEAR')}>
                <i className="icon-remove"></i>
            </Button>
        </div>
        </div>
    </div>;

    return (element);
}

export default Search;