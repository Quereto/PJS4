import React, { useContext, useEffect, useState } from 'react';
import { Add } from "@material-ui/icons";
import { UserContext } from '../../contexts/UserContext';
import Chat from './Chat';

import axios from 'axios';

import style from './Chats.module.scss';

const Chats = ({openChatRoom, openChatContact}) => {
  //
  const { user, fetchContact } = useContext(UserContext);
  //
  const [ msg, setMsg ] = useState([]);
  const [ msgList, setMsgList ] = useState([]);
  const [ error, setError ] = useState('');
  //
  var tab = [];
  const fetchAllMessages = async () => {
      try {
        let contacts = await fetchContact();
        contacts.data.data.map(async (contact, i, arr) => {
          await axios.put(`/messages`, {
            incoming_msg_id: user.id,
            outgoing_msg_id: contact.id
          })
          .then((response) => {
            let res = ({...response.data.data[0], contact: contact});
            tab.length>0 ? tab.map((m,i,arr) => {
              if (i===arr.length-1) {
                if (m && m.id===res.id)
                { tab = [...arr, null]; }
                else
                { tab = [...arr, res]; }
              }
            }) : tab = [...tab, res];
          })
          .catch((error) => {
            tab.push(null);
            if (tab.length-1 === arr.length-1) 
            { setMsgList(tab); tab.length=0; }
          })
          if (tab.length-1 === arr.length-1) 
          { setMsgList(tab); tab.length=0; }
        });
      } catch(err) {
        console.log(err)
        setError('Vous n\'avez aucune conversation')
      }
    };

  //
  useEffect(() => {
    const asyncCall = async () => { await fetchAllMessages(); }
      //asyncCall();
    const interval = setInterval(() => {
      asyncCall()
    }, 2000);
    return () => clearInterval(interval);
  }, []);


  //
  //console.log(msgList);
  return (
    <div className={`${style.chat}`}>
        <div className={`${style.header}`}>
          <h2 className={`${style.title}`}>My Chats</h2>
          <button className={`${style.btn}`} onClick={openChatContact}>
            <Add className={`${style.add}`} />
          </button>
        </div>
      {error && console.log(error)}
      <div className={`${style.container}`}>
        {msgList.length>0 && msgList
          .filter(m => m !== null)
          .sort((m1,m2) => m1.id-m2.id)
          .map((m, key) => {
            console.log(msgList);
            if(m){
              return <Chat
              key={key}
              name={m.contact.name}
              msg={m.incoming_msg_id===user.id ? 'Vous : '+m.msg : m.msg}
              updatedAt={m.updatedAt}
              onclick={() => openChatRoom(m.contact)}
            />}
        })}
      </div>
    </div>
  );
};

export default Chats;