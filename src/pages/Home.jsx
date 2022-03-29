import React, { useEffect, useState } from 'react';
import Chats from '../components/chat/Chats';
import ChatRoom from '../components/chat/ChatRoom';
import axios from 'axios';
import { red } from '@material-ui/core/colors';
import ChatContact from '../components/chat/ChatContact';
import ChatGroup from '../components/chat/ChatGroup';

const Home = () => {
  const [ showChats, setShowChats ] = useState(true);
  const [ showChatRoom, setShowChatRoom ] = useState(false);
  const [ showChatContact, setShowChatContact ] = useState(false);
  const [ contact, setcontact ] = useState(null);
  
  const openChatRoom = (data) => {
    setShowChats(false);
    setShowChatContact(false);
    setcontact(data)
    setShowChatRoom(true);
  };

  const closeChatRoom = () => {
    setShowChatRoom(false);
    setShowChats(true);
  };

  const openChatContact = () => {
    setShowChatContact(true);
  };

  return (
    <div style={{width:'100%'}}>
      {/* { showChats && <Chats openChatRoom={openChatRoom} openChatContact={openChatContact} /> } */}
      <ChatGroup />
      { showChatRoom && <ChatRoom onClose={() => closeChatRoom()} contact={contact} /> }
      { <ChatContact openChatRoom={openChatRoom} open={showChatContact} onClose={() => setShowChatContact(false)} /> }
    </div>
  );
};

export default Home;