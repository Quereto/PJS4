import React, { useContext, useState } from 'react';
import { UserContext } from './contexts/UserContext';
import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import Login from './components/auth/Login';
import Register from './components/auth/Register';
import Home from './pages/Home';

const App = () => {
  //
  const { user } = useContext(UserContext);
  //

  return (
    <main className="container">
      <BrowserRouter>
        <Routes>
          { /* Show pages according to the url */
            user 
            ? <>
              <Route exact path='/' element={<Home />} />
              {/* <Route path='/profile' element={<Profile />} /> */}
            </>
            : <>
              <Route path='/login' element={<Login />} />
              <Route path='/register' element={<Register />} />
            </>
          }
          <Route 
            path='*' 
            // Show Profile or Login according to user is logged in or not
            element={<Navigate to={user ? '/' : '/login'} />} 
          />
        </Routes>
      </BrowserRouter>
    </main>
  );
};

export default App;