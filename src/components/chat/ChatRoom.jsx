import React, { useContext, useEffect, useRef, useState } from 'react';
import { AttachFile, Send } from '@material-ui/icons';
import { UserContext } from '../../contexts/UserContext';

import style from './ChatRoom.module.scss';
import axios from 'axios';

import TimeAgo from 'timeago-react';
import * as timeago from 'timeago.js';

// import local fr
import fr from 'timeago.js/lib/lang/fr';
// register it.
timeago.register('fr', fr);

const ChatRoom = ({contactId}) => {
  //
  const { user, fetchContact } = useContext(UserContext)
  //
  const [ data, setData ] = useState(null);
  const [ contact, setContact ] = useState(null);
  //
  const msgRef = useRef();
  //
  const fetchAllChats = async () => {
    await axios.put('/messages', {
      incoming_msg_id: user.id,
      outgoing_msg_id: contactId,
      all: true
    })
    .then(response => setData(response.data.data))
    .then(async () => await fetchContact()
                      .then(response => setContact(response.data.data.find(c => c.id === contactId))))
    .catch(error => console.log(error.response.data.message))
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    msgRef.current.blur();
    const message = {
      incoming_msg_id: user.id,
      outgoing_msg_id: contactId,
      msg: msgRef.current.value
    };
    await axios.post('/messages', message)
               .then(response => {msgRef.current.value=''})
               .catch(error => console.log(error.response.data.message))
  };

  useEffect(() => {
    const asyncCall = async () => { await fetchAllChats(); }
    //asyncCall();
    const interval = setInterval(() => {
      asyncCall()
    }, 2000);
    return () => clearInterval(interval);
  }, []);
  //
  console.log(data);
  return (
    <div className={`${style.chatroom}`}>
      <div className={`${style.header}`}>
        <span className={`${style.title}`}>{contact && contact.name}</span>
      </div>
      <div className={`${style.container}`}>
          {data && data.map((m,i,arr) => {
            return <div key={m.id} className={`${style.msg} ${m.incoming_msg_id===user.id ? style.user : ''}`}>
              <p>{m.msg}</p>
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