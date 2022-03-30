import React, { useEffect, useState } from 'react';
import Chats from '../components/chat/Chats';
import ChatRoom from '../components/chat/ChatRoom';
import axios from 'axios';
import { red } from '@material-ui/core/colors';
import ChatContact from '../components/chat/ChatContact';
import ChatGroup from '../components/chat/ChatGroup';
import MainLayout from './MainLayout';

import useWindowDimensions from '../hook/useWindowDimensions';

const Home = () => {

  const { height, width } = useWindowDimensions();

  const [ broadcast, setBroadcast ] = useState();
  const [ showChats, setShowChats ] = useState(true);
  const [ showChatRoom, setShowChatRoom ] = useState(false);
  const [ showChatContact, setShowChatContact ] = useState(false);
  const [ contact, setcontact ] = useState(null);

  const [ screen, setScreen ] = useState({width:0,height:0});
  
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

  const updateWindowDimensions = () => {
    setScreen({width: window.innerWidth, height: window.innerHeight})
  };

  const newBroadcast = (data) => {
    setBroadcast(data);
  };

  useEffect(() => {
    window.addEventListener('resize', updateWindowDimensions())
    return () => window.removeEventListener('resize', updateWindowDimensions())
  }, [])

  useEffect(() => {
    console.log(showChatContact);
  }, [showChatContact])

  return (
    <>
      { width > 750
        ? <MainLayout
          openChatContact={openChatContact}
          contact={contact}
          openChatRoom={openChatRoom}
          broadcast={broadcast}
          newBroadcast={newBroadcast}
        />
        : <>
          { showChats && <Chats openChatRoom={openChatRoom} openChatContact={openChatContact} /> }
          {/* <ChatGroup /> */}
          { showChatRoom && <ChatRoom onClose={() => closeChatRoom()} contact={contact} /> }
        </>
      }
      { <ChatContact openChatRoom={openChatRoom} open={showChatContact} onClose={() => setShowChatContact(false)} /> }
    </>
  );
};

export default Home;