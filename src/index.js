import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';
import UserContextProvider from './contexts/UserContext';

import './styles/index.scss';

ReactDOM.render(
  <UserContextProvider>
    <React.StrictMode>
      <App />
    </React.StrictMode>
  </UserContextProvider>,
  document.getElementById('root')
);

