import React, { useState } from '../../../js/react/node_modules/react';

const Groupsinfo = () => {

    const element = <div className="shop shop-groups">
        <h1 className="shop-groups__page-title">
            {Joomla.JText._('COM_SMARTSHOP_GROUPS')}
        </h1>

        <div className="row">
            <div className="col-sm-auto">
                {Joomla.JText._('COM_SMARTSHOP_TITLE')}
                {dataJson.rows.map((row, ind) =>
                    <div key={ind}>
                        {row.name}
                    </div>
                )}
            </div>

            <div className="col-sm-auto">
                {Joomla.JText._('COM_SMARTSHOP_DISCOUNT')}

                {dataJson.rows.map((row, ind) =>
                    <div key={ind}>
                        {row.usergroup_discount}
                    </div>
                )}
            </div>
        </div>
    </div>;

   return (element);
}
export default Groupsinfo;
