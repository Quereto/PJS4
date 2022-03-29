import React, { useContext, useEffect, useRef, useState } from 'react';
import { Close, Search } from '@material-ui/icons';
import { UserContext } from '../../contexts/UserContext';

import style from './ChatContact.module.scss';

const ChatContact = ({openChatRoom, open, onClose}) => {
  //
  const { user, searchContact, fetchContact } = useContext(UserContext);
  //
  const [ activeInput, setActiveInput ] = useState(false);
  const [ error, setError ] = useState('');
  const [ contacts, setContacts ] = useState([]);
  //
  const searchRef = useRef();
  //
  const handleChanche = async () => {
    searchRef.current.value ? setActiveInput(true) : setActiveInput(false);
    if(searchRef.current.value) {
      await searchContact(searchRef.current.value)
      .then(response => {setError(''); setContacts(response.data.data)})
      .catch(error => setError(error.response.data.message))
     } else { 
      setError(''); 
      setContacts([]);
     }
  };

  const clearsearch = () => {
    searchRef.current.value = '';
    setError(''); 
    setContacts([]);
  }
  //
  useEffect(() => {
    const asyncCall = async () => { 
      await fetchContact()
      .then(response => setContacts(response.data.data) && console.log('OK'))
      .catch(error => setError(error.response.data.message) && console.log('KO'))
    }; 

    contacts.length===0 && asyncCall(); 
  }, [contacts])
  //
  return (
    <div 
      className={`${style.contact} ${open ? style.show : ''}`} 
    >
      <div className={`${style.blur}`} onClick={onClose}></div>
      <div className={`${style.container}`}>
        <div className={`${style.title}`}>
          <h2 className={`${style.title}`}>Choisir un contact</h2>
        </div>

        <div className={`${style.searchbar}`}>
          <input 
            ref={searchRef}
            onChange={handleChanche}
            placeholder='rechercher un contact...'
            className={`${style.input}`}
          />
          { activeInput 
           ? <Close 
              className={`${style.search}`} 
              onClick={clearsearch}
              />
           : <Search className={`${style.search}`} />
          }
        </div>
        <div className={`${style.wrapper}`}>
          { error ? <div className={`${style.error}`}>{error}</div>
            : contacts.length>0 && contacts.map(contact => {
              return <div key={contact.id} className={`${style.content}`} onClick={() => openChatRoom(contact)}>
                <div className={`${style.img}`}></div>
                <div className={`${style.data}`}>
                  <span className={`${style.name}`}>{contact.name}</span>
                  <div className={`${style.status}`}>
                    <div className={`${style.pin} ${contact.status>0 ? style.online : style.offline}`}></div>
                    <span>{`${contact.status>0 ? 'en ligne' : 'hors ligne'}`}</span>
                  </div>
                </div>
              </div>
          })}
        </div>
      </div>
    </div>
  );
};

export default ChatContact;