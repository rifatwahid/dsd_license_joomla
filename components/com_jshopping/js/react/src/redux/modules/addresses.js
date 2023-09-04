import regeneratorRuntime from "../../../../../js/react/node_modules/regenerator-runtime";
const moduleName = 'addresses';

const GET_ADDRESSES = `${moduleName}/GET_ADDRESSES`;
const GET_ADDRESSE = `${moduleName}/GET_ADDRESSE`;
const DELETE_ADDRESSES = `${moduleName}/DELETE_ADDRESSES`;
const CREATE_ADDRESSES = `${moduleName}/CREATE_ADDRESSES`;

const defaultState = {
    addresses: []
};

/*
  { type: GET_ADDRESSES, payload: {...} }
*/
export default (state = defaultState, { type, payload }) => {
        switch (type) {
            case GET_ADDRESSES:
                return {...state, addresses: payload};
            case GET_ADDRESSE:
                return {...state, addresse: payload};
            case DELETE_ADDRESSES:
                return {...state, addresses: state.addresses.filter(item => item.id !== payload.id)};
            case CREATE_ADDRESSES:
                return {...state, addresses: [...state.addresses, payload]};
            default:
                return state;
        }
    }

export const getAddresses = () => async (dispatch) => {
    try {
        await fetch('index.php?option=com_jshopping&controller=user&task=addresses&ajax=1')
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_ADDRESSES, payload: data }))
    } catch (error) {
        console.log(error)
    }
}

export const getAddresse = (link) => async (dispatch) => {
    try {
        await fetch(link)
            .then((response) => response.json())
            .then((data) => dispatch({ type: GET_ADDRESSE, payload: data }))
    } catch (error) {
        console.log(error)
    }
}


export const deletePost = (id) => async (dispatch) => {
    try {
        await fetch(`https://jsonplaceholder.typicode.com/posts/${id}`, {
            method: 'DELETE',
        });

        dispatch({ type: DELETE_POST, payload: { id } })
    } catch (error) {

    }
}

export const createPost = ({ title, body }) => async (dispatch) => {
    try {
        await fetch('https://jsonplaceholder.typicode.com/posts', {
            method: 'POST',
            body: JSON.stringify({
                title,
                body,
                userId: 1,
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
            },
        })
            .then((response) => response.json())
            .then((data) => dispatch({ type: CREATE_POST, payload: data }));
    } catch (error) {
        console.log(error)
    }
}