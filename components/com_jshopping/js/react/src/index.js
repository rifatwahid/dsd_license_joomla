import React from '../../../js/react/node_modules/react';
import ReactDOM from 'react-dom';
import './index.css';
import App from './App';
import reportWebVitals from './reportWebVitals';
import configureStore from './store';
import { Provider } from '../node_modules/react-redux';

const store = configureStore();
ReactDOM.render(
  <React.StrictMode>
      <Provider store={store}>
        <App />
      </Provider>
  </React.StrictMode>,
  document.getElementById('belem')
);

reportWebVitals();
