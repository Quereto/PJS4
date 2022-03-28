import React, { useEffect } from 'react';
import style from './Chats.module.scss';
import TimeAgo from 'timeago-react';
import * as timeago from 'timeago.js';

// import local fr
import fr from 'timeago.js/lib/lang/fr';
// register it.
timeago.register('fr', fr);

const Chats = ({name, msg, updatedAt, onclick}) => {
  //
  //
  return (
    <div className={`${style.content}`} onClick={onclick}>
      <span className={`${style.name}`}>{name}</span>
      <div className={`${style.description}`}>
        <p className={`${style.msg}`}>{msg}</p>
        <TimeAgo 
          datetime={updatedAt}
          locale='fr'
          live={false}
          className={`${style.timeago}`}
        />
      </div>
    </div>
  );
};

export default Chats;