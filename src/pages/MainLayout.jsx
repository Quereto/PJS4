import React from 'react';
import Chats from '../components/chat/Chats';
import ChatRoom from '../components/chat/ChatRoom';
import ChatGroup from '../components/chat/ChatGroup';

import style from './MainLayout.module.scss';

const MainLayout = ({openChatRoom, openChatContact, contact, broadcast, newBroadcast}) => {
  return (
    <div className={`container ${style.mainlayout}`}>
      <div className={`${style.wrapper}`}>
        <div className={`${style.content}`}>
        <Chats openChatRoom={openChatRoom} openChatContact={openChatContact}  />
        </div>
        <div className={`${style.content}`}>
        <ChatRoom contact={contact && contact} newBroadcast={newBroadcast} />
        </div>
        <div className={`${style.content}`}>
          <ChatGroup openChatRoom={openChatRoom} broadcast={broadcast} newBroadcast={newBroadcast} />
        </div>
      </div>
    </div>
  );
};

export default MainLayout;