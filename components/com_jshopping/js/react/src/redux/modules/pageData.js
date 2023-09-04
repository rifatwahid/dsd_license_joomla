import regeneratorRuntime from "../../../../../js/react/node_modules/regenerator-runtime";
const moduleName = 'pageData';

const GET_PAGEDATA = `${moduleName}/GET_PAGEDATA`;
const GET_EDITADDRESSDATA = `${moduleName}/GET_EDITADDRESSDATA`;
const GET_NEWADDRESSDATA = `${moduleName}/GET_NEWADDRESSDATA`;
const GET_OFFERANDORDER = `${moduleName}/GET_OFFERANDORDER`;
const GET_WISHLISTDATA = `${moduleName}/GET_WISHLISTDATA`;
const GET_CARTDATA = `${moduleName}/GET_CARTDATA`;
const GET_PRODUCTSDATA = `${moduleName}/GET_PRODUCTSDATA`;
const GET_PRODUCTDEFAULTDATA = `${moduleName}/GET_PRODUCTDEFAULTDATA`;
const GET_OFFERDATA = `${moduleName}/GET_OFFERDATA`;
const GET_QCHECKOUTDATA = `${moduleName}/GET_QCHECKOUTDATA`;
const GET_STEP6DATA = `${moduleName}/GET_STEP6DATA`;
const GET_FINISHDATA = `${moduleName}/GET_FINISHDATA`;
const GET_MAINCATEGORYDATA = `${moduleName}/GET_MAINCATEGORYDATA`;
const GET_CATEGORYDEFAULTDATA = `${moduleName}/GET_CATEGORYDEFAULTDATA`;
const GET_FORMSEARCHDATA = `${moduleName}/GET_FORMSEARCHDATA`;
const GET_MANUFACTURERSDATA = `${moduleName}/GET_MANUFACTURERSDATA`;
const GET_MANUFACTURERPRODUCTSDATA = `${moduleName}/GET_MANUFACTURERPRODUCTSDATA`;
const GET_LOGINDATA = `${moduleName}/GET_LOGINDATA`;
const GET_REGISTERDATA = `${moduleName}/GET_REGISTERDATA`;

const defaultState = {
    pageData: {},
    editAddressData: {},
    newAddressData: {},
    offerAndOrder: {},
    wishlistData: {},
    cartData: {},
    productsData: {},
    productDefaultData: {},
    offerData: {},
    qcheckoutData: {},
    step6Data: '',
    finishData: {},
    maincategoryData: {},
    categorydefaultData: {},
    formSearchData: {},
    manufacturersData: {},
    manufacturerProductsData: {},
    loginData: {},
    registerData: {},
};

export default (state = defaultState, { type, payload }) => {
        switch (type) {
            case GET_PAGEDATA:
                return {...state, pageData: payload};

            case GET_EDITADDRESSDATA:
                return {...state, editAddressData: payload};

            case GET_NEWADDRESSDATA:
                return {...state, newAddressData: payload};

            case GET_OFFERANDORDER:
                return {...state, offerAndOrder: payload};

            case GET_WISHLISTDATA:
                return {...state, wishlistData: payload};

            case GET_CARTDATA:
                return {...state, cartData: payload};

            case GET_PRODUCTSDATA:
                return {...state, productsData: payload};

            case GET_PRODUCTDEFAULTDATA:
                return {...state, productDefaultData: payload};

            case GET_OFFERDATA:
                return {...state, offerData: payload};

            case GET_QCHECKOUTDATA:
                return {...state, qcheckoutData: payload};

            case GET_STEP6DATA:
                return {...state, step6Data: payload};

            case GET_FINISHDATA:
                return {...state, finishData: payload};

            case GET_MAINCATEGORYDATA:
                return {...state, maincategoryData: payload};

            case GET_CATEGORYDEFAULTDATA:
                return {...state, categorydefaultData: payload};

            case GET_FORMSEARCHDATA:
                return {...state, formSearchData: payload};

            case GET_MANUFACTURERSDATA:
                return {...state, manufacturersData: payload};

            case GET_MANUFACTURERPRODUCTSDATA:
                return {...state, manufacturerProductsData: payload};

            case GET_LOGINDATA:
                return {...state, loginData: payload};

            case GET_REGISTERDATA:
                return {...state, registerData: payload};

            default:
                return state;
        }
    }

export const getPageData = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_PAGEDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}

export const getEditAddressData = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_EDITADDRESSDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getNewAddressData = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_NEWADDRESSDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}

export const getOfferAndOrder = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_OFFERANDORDER, payload: data }))
    } catch (error) {
        console.log(error)
    }
}

export const getWishlistData = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_WISHLISTDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}

export const getCartData = (link) => async (dispatch) => {
    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_CARTDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}

export const getProductsData = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_PRODUCTSDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getProductDefaultData = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_PRODUCTDEFAULTDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getOfferData = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_OFFERDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getQcheckoutData = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_QCHECKOUTDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getStep6Data = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_STEP6DATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getFinishData = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_FINISHDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getMaincategoryData  = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_MAINCATEGORYDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getCategorydefaultData  = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_CATEGORYDEFAULTDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getFormSearchData  = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_FORMSEARCHDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getManufacturersData   = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_MANUFACTURERSDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getManufacturerProductsData   = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_MANUFACTURERPRODUCTSDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getLoginData   = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_LOGINDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}
export const getRegisterData   = (link) => async (dispatch) => {

    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_REGISTERDATA, payload: data }))
    } catch (error) {
        console.log(error)
    }
}

