import React, { useContext, useRef, useState } from 'react';
import { Link } from 'react-router-dom';
import { UserContext } from '../../contexts/UserContext';

import style from './Auth.module.scss';

const Register = () => {
  // 
  const { registerUser, loggedInCheck } = useContext(UserContext);
  //
  const [ error, setError ] = useState('');
  //
  const fname = useRef();
  const lname = useRef();
  const email = useRef();
  const password = useRef();
  //
  const handleSubmit = async (e) => {
    e.preventDefault();
    const user = {
      fname: fname.current.value,
      lname: lname.current.value,
      email: email.current.value,
      password: password.current.value
    };
    await registerUser(user)
    .then(response => {
      localStorage.setItem('loginToken', response.data.token);
    })
    .then(async () => await loggedInCheck())
    .catch(error => setError(error.response.data.message))
  };
  //

  return (
    <div className={`${style.auth}`}>
      {/* titre */}
      <h2 className={`${style.title}`}>
        Inscription
      </h2>
      {/* S'il y a une erreur */}
      {error && (
        <div className={`${style.error}`}>{error}</div>
      )}
      {/* formulaire */}
      <form className={`${style.form}`} onSubmit={handleSubmit}>
      <div className={`${style.name}`}>  
        <div className={`${style.content}`}>
          <label className={`${style.label}`}>Nom</label>
          <input 
            ref={lname}
            type='text'
            placeholder='Entrez votre nom'
            className={`${style.input}`} 
          />
        </div>
        <div className={`${style.content}`}>
          <label className={`${style.label}`}>Prénom</label>
          <input 
            ref={fname}
            type='text'
            placeholder='Entrez votre prénom'
            className={`${style.input}`} 
          />
        </div>
      </div>
      <div className={`${style.content}`}>
        <label className={`${style.label}`}>Email</label>
        <input 
          ref={email}
          type='text'
          placeholder='Entrez votre e-mail'
          className={`${style.input}`} 
        />
      </div>
      <div className={`${style.content}`}>
        <label className={`${style.label}`}>Mot de passe</label>
        <input 
          ref={password}
          type='password'
          placeholder='Entrez votre mot de passe'
          className={`${style.input}`} 
        />
      </div>
      <button type='submit' className={`${style.btn}`}>S'inscrire</button>
      </form>
      <div className={`${style.toggle}`}>
        Vous possédez déjà un compte?&nbsp;
        <Link to='/login' className={`${style.link}`}>Connectez-vous</Link>
      </div>
    </div>
  );
};

export default Register;