import React, { useContext, useEffect, useState } from 'react';
import { UserContext } from '../../contexts/UserContext';

import style from './ChatGroup.module.scss';

const ChatGroup = () => {
  //
  const { user, searchContact, fetchContact } = useContext(UserContext);
  //
  const [ error, setError ] = useState('');
  const [ contacts, setContacts ] = useState([]);
  //
  const Section = ({title, data}) => {
    return <div className={`${style.section}`}>
      <div className={`${style.header}`}>
        <div className={`${style.title}`}>{title}</div>
        <button className={`${style.btn}`}>Show all</button>
      </div>
      {/* SECTION */}
      <div className={`${style.container}`}>
        {data && data.map((item, key) => {
          return <div key={key} className={`${style.content}`}>
            <div className={`${style.img}`}></div>
            <div className={`${style.details}`}>
              <div className={`${style.name}`}>{item.name}</div>
              <div className={`${style.description}`}>At work</div>
            </div>
          </div>
        })}
      </div>
    </div>
  };
  //
  useEffect(() => {
    const asyncCall = async () => { 
      await fetchContact()
      .then(response => setContacts(response.data.data) && console.log('OK'))
      .catch(error => setError(error.response.data.message) && console.log('KO'))
    }; 

    contacts.length===0 && asyncCall(); 
  }, [contacts])

  console.log(contacts);
  //
  return (
    <div className={`${style.chatgroup}`}>
      {/* HEADER */}
      <div className={`${style.profile}`}>
        <span className={`${style.dot}`}></span>
        <div className={`${style.data}`}>
          <div className={`${style.img}`}></div>
          <div className={`${style.title}`}>Group #1</div>
          <div className={`${style.subtitle}`}>@Odoma</div>
        </div>
      </div>

      {/* SECTIONS */}
      <div className={`${style.section}`}>
        <div className={`${style.wrapper}`}>
          {/* PARTICIPANTS */}
          <Section data={contacts} title={`${contacts.length} ${contacts.length>1 ? 'Participants' : 'Participant'}`}  />
          {/* FICHIERS PARTAGES */}
          <Section title='Shared Media' data={[{name: 'Fixed Skirt.pdf'}, {name: 'Poster TA.png'} ]}/>
        </div>
      </div>
    </div>
  );
};

export default ChatGroup;