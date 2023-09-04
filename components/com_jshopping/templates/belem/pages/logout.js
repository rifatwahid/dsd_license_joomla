import React, { useState } from '../../../js/react/node_modules/react';

const Logout = () => {
    const element = <div className="shop shop-logout">
        <h1 className="shop-logout__page-title">
            {Joomla.JText._('COM_SMARTSHOP_LOGOUT')}
        </h1>

        <a className="btn btn-outline-secondary" href={dataJson.logout}>
        {Joomla.JText._('COM_SMARTSHOP_LOGOUT')}
    </a>
</div>;

    return element;
}
export default Logout;