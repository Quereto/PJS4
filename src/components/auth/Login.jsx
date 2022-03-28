import React, { useContext, useRef, useState } from 'react';
import { Link } from 'react-router-dom';
import { UserContext } from '../../contexts/UserContext';

import style from './Auth.module.scss';

const Login = () => {
  // 
  const { loginUser, loggedInCheck } = useContext(UserContext);
  //
  const [ error, setError ] = useState('');
  //
  const email = useRef();
  const password = useRef();
  //
  const handleSubmit = async (e) => {
    e.preventDefault();
    const user = {
      email: email.current.value,
      password: password.current.value
    };
    await loginUser(user)
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
        Connexion
      </h2>
      {/* S'il y a une erreur */}
      {error && (
        <div className={`${style.error}`}>{error}</div>
      )}
      {/* formulaire */}
      <form className={`${style.form}`} onSubmit={handleSubmit}>
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
      <button type='submit' className={`${style.btn}`}>Se connecter</button>
      </form>
      <div className={`${style.toggle}`}>
        Vous ne possédez pas de compte?&nbsp;
        <Link to='/register' className={`${style.link}`}>Inscrivez-vous</Link>
      </div>
    </div>
  );
};

export default Login;