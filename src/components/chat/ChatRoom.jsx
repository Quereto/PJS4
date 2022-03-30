import React, { useContext, useEffect, useRef, useState } from 'react';
import { ArrowBack, AttachFile, Send } from '@material-ui/icons';
import { UserContext } from '../../contexts/UserContext';

import style from './ChatRoom.module.scss';
import axios from 'axios';

import TimeAgo from 'timeago-react';
import * as timeago from 'timeago.js';

// import local fr
import fr from 'timeago.js/lib/lang/fr';
// register it.
timeago.register('fr', fr);

const ChatRoom = ({contact, onClose, newBroadcast}) => {
  //
  const { user } = useContext(UserContext)
  //
  const [ data, setData ] = useState(null);
  const [ error, setError ] = useState('');
  //
  const msgRef = useRef();
  //
  const fetchAllChats = async () => {
    await axios.put('/messages', {
      incoming_msg_id: user.id,
      outgoing_msg_id: contact.id,
      all: true
    })
    .then(response => {setError(''); setData(response.data.data)})
    .catch(error => setError(error.response.data.message))
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    msgRef.current.blur();
    const message = {
      incoming_msg_id: user.id,
      outgoing_msg_id: contact.id,
      msg: msgRef.current.value
    };
    await axios.post('/messages', message)
               .then(() => { 
                 console.log('Good! send');
                  if (contact.isGroup) {
                    console.log('contact is group')
                    newBroadcast({
                      group: contact, 
                      msg: msgRef.current.value
                    })
                  }
                  msgRef.current.value=''; 
                })
               .catch(error => console.log(error.response.data.message))
  };

  useEffect(() => {
    const asyncCall = async () => { await fetchAllChats(); }
    //asyncCall();
    if (contact) {
      const interval = setInterval(() => {
        asyncCall()
      }, 2000);
      return () => clearInterval(interval);
    }
  }, [contact]);
  //
  // console.log(contact);
  return (
    <div className={`${style.chatroom}`}>
      <div className={`${style.header}`}>
        <ArrowBack 
          className={`${style.arrowBack}`}
          onClick={onClose}
        />
        <img src={contact && contact.img} alt="profile" className={`${style.img}`} />
        <span className={`${style.title}`}>{contact ? contact.name : ''}</span>
      </div>
      <div className={`${style.container}`}>
          {error ?  <div className={`${style.error}`}>{error}</div>
           : data && data.map((m,i,arr) => {
              return <div key={m.id} className={`${style.msg} ${m.incoming_msg_id===user.id ? style.user : ''}`}>
                <div className={`${style.data}`}>
                  { m.outgoing_msg_id===user.id && <img src={contact.img} alt="profile" className={`${style.img}`} /> }
                  <p>{m.msg}</p>
                </div>
                {i===0 && <TimeAgo 
                  datetime={m.updatedAt}
                  locale='fr'
                  live={false}
                  className={`${style.timeago} ${m.incoming_msg_id===user.id ? style.user : ''}`}
                />}
              </div>
          })}
      </div>
      <form className={`${style.form}`} onSubmit={handleSubmit}>
        <input 
          ref={msgRef}
          placeholder='Envoyer un message...' 
          type='text' 
          className={`${style.input}`} 
        />
        <div className={`${style.icons}`} >
          <AttachFile className={`${style.attachFile}`} />
          <button type='submit'><Send className={`${style.send}`} /></button>
        </div>
      </form>
    </div>
  );
};

export default ChatRoom;