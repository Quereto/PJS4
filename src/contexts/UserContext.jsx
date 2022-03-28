import React, { createContext, useEffect, useState } from "react";
import axios from 'axios';
// create the user context
export const UserContext = createContext();

const UserContextProvider = (props) => {
  // user State
  const [ user, setUser ] = useState(null);

  // On Click the Log out button
  const logoutUser = async () => {
    const loginToken = localStorage.getItem('loginToken');
    //Adding JWT token to axios default header
    axios.defaults.headers.common['Authorization'] = 'Bearer '+loginToken;
    // let's set user status `offline`
    await axios.get('/user/logout')
    .then(() => {
        // let's remove loginToken & user 
        localStorage.removeItem('loginToken');
        setUser(null);
    })
    .catch(error => console.log(error));
  }

  // upload file
  const uploadFile = async (image) => {
    const formData = new FormData();
    formData.append('file', image);
    // Sending the user upload image request
    const { data } = await axios.post('user/upload', formData, {
      headers: { "Content-Type": "multipart/form-data" }
    });
    return data;
  }

  // On click on the Sign Up button
  const registerUser = async (user) => {
    // Sending the user registration request
    const register = await axios.post('/register', {
      fname: user.fname,
      lname: user.lname,
      email: user.email,
      password: user.password
    });
    return register;
  }

  // On click on the Sign In button
  const loginUser = async (user) => {
    // Sending the user Login request
    const login = await axios.post('/login', {
      email: user.email,
      password: user.password
    });
    return login;
  }

  const fetchContact = async () => {
    // Sending the user Login request
    const contacts = await axios.get('/user/contacts');
    return contacts;
  }

  // Checking user logged in or not
  const loggedInCheck = async () => {
    const loginToken = localStorage.getItem('loginToken');
    // If inside the local-storage has the JWT token
    if (loginToken) {
      //Adding JWT token to axios default header
      axios.defaults.headers.common['Authorization'] = 'Bearer '+loginToken;
      // Fetching the user information
      await axios.get('/user')
      .then(response => setUser(response.data.user))
      .catch(error => console.log(error.response.data));
    }
  }

  // check if user is already auth
  useEffect(() => {
    const asyncCall = async () => { await loggedInCheck(); }
    asyncCall();
    //async function asyncCall() { await loggedInCheck(); }
  }, []);

  const contextValue = {
    user: user,
    setUser: setUser,
    loggedInCheck: loggedInCheck,
    registerUser: registerUser,
    loginUser: loginUser,
    logoutUser: logoutUser,
    uploadFile: uploadFile,
    fetchContact: fetchContact
  }

  return (
    <UserContext.Provider value={contextValue}>
      {props.children}
    </UserContext.Provider>
  );
}

export default UserContextProvider;