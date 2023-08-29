import React, { useState } from '../../../js/react/node_modules/react';
import Parser from '../../../js/react/node_modules/html-react-parser';
import {Link} from '../../../js/react/node_modules/react-router-dom';

const Block_pagination = (props) => {
    let data = props.data;
    const result = Object.keys(data.pagenavdata.pages).map((key) => data.pagenavdata.pages[key]);
    const element = <div className="shop-pagination">
        <ul className="pagination">
            <li className="page-item" >
                <Link to={data.pagenavdata.start.link} className="page-link">{data.pagenavdata.start.text}</Link>
            </li>
            <li className="page-item" >
                <Link to={data.pagenavdata.previous.link} className="page-link previous">{data.pagenavdata.previous.text}</Link>
            </li>
            {result.map((page, ind) =>
                (page.link) ?
                <li className="page-item" key={ind}>
                    <Link to={page.link} className="page-link">{page.text}</Link>
                </li>
                    : <li className="page-item active" key={ind}>
                        <a href="#" onClick={(e) => e.preventDefault()} className="page-link">{page.text}</a>
                    </li>
            )}
            <li className="page-item" >
                <Link to={data.pagenavdata.next.link} className="page-link next">{data.pagenavdata.next.text}</Link>
            </li>
            <li className="page-item" >
                <Link to={data.pagenavdata.end.link} className="page-link">{data.pagenavdata.end.text}</Link>
            </li>
        </ul>
        </div>;
    return (element);
    // return (<div className="shop-pagination" dangerouslySetInnerHTML={{__html: pagination.replaceAll('<link', '<Link').replaceAll('</link', '</Link')}}></div>);
}
export default Block_pagination;


