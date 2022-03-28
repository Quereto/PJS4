import React, { useEffect, useState } from 'react';
import Chats from '../components/chat/Chats';
import ChatRoom from '../components/chat/ChatRoom';
import axios from 'axios';
import { red } from '@material-ui/core/colors';
import ChatContact from '../components/chat/ChatContact';

const Home = () => {
  const [ showChats, setShowChats ] = useState(true);
  const [ showChatRoom, setShowChatRoom ] = useState(false);
  const [ showChatContact, setShowChatContact ] = useState(false);
  const [ contactId, setContactId ] = useState(null);
  
  const openChatRoom = (data) => {
    setShowChats(false);
    setContactId(data)
    setShowChatRoom(true);
  };

  const openChatContact = () => {
    setShowChatContact(true);
    setShowChats(false);
  };

  return (
    <div style={{width:'100%'}}>
      { showChats && <Chats openChatRoom={openChatRoom} openChatContact={openChatContact} /> }
      { showChatRoom && <ChatRoom contactId={contactId} /> }
      { showChatContact && <ChatContact /> }
    </div>
  );
};

export default Home;