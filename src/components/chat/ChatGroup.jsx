import axios from 'axios';
import React, { useContext, useEffect, useState } from 'react';
import { UserContext } from '../../contexts/UserContext';

// Import Swiper React components
import { Swiper, SwiperSlide } from "swiper/react";

// Import Swiper styles
import "swiper/css";

import style from './ChatGroup.module.scss';

const ChatGroup = ({openChatRoom, broadcast, newBroadcast}) => {
  //
  const { user, searchContact, fetchContact } = useContext(UserContext);
  //
  const [ error, setError ] = useState('');
  const [ contacts, setContacts ] = useState([]);
  const [ groups, setGroups ] = useState([]);
  const [ participants, setParticipants ] = useState([{groupId:null, list:[]}]);
  //
  const Section = ({title, data}) => {
    return <div className={`${style.section}`}>
      <div className={`${style.header}`}>
        <div className={`${style.title}`}>{title}</div>
        <button 
          className={`${style.btn}`}
          onClick={() => console.log('press')}
        >
          Show all
        </button>
      </div>
      {/* SECTION */}
      <div className={`${style.container}`}>
        {data && data.map((item, key) => {
          if (key < 3) {
            return <div key={key} className={`${style.content}`}>
              <img src={item.img} alt="profile" className={`${style.img}`} />
              <div className={`${style.details}`}>
                <div className={`${style.name}`}>{item.name}</div>
                <div className={`${style.description}`}>At work</div>
              </div>
            </div>
          }
        })}
      </div>
    </div>
  };

  const GroupSection = ({group, participants}) => {
    return(
      <div className={`${style.chatgroup}`}>
        {/* HEADER */}
        <div className={`${style.profile}`}>
          <span className={`${style.dot}`}></span>
          <div 
            className={`${style.data}`}
            onClick={() => openChatRoom({...group, isGroup: true})}
          >
            <img src={group.img} alt="profile" className={`${style.img}`} />
            <div className={`${style.title}`}>{group.name}</div>
            <div className={`${style.subtitle}`}>{user.name}</div>
          </div>
        </div>
        {/* SECTIONS */}
        <div className={`${style.section}`}>
          <div className={`${style.wrapper}`}>
            { participants && participants.list.length>1  
              ? <Section 
                  title={`${participants.list.length-1} ${participants.list.length-1>1?'Participants':'Participant'}`} 
                  data={contacts.filter((contact) => participants.list.includes(contact.id))} 
                />
              : <Section title='0 Participants' />
            }
            {/* FICHIERS PARTAGES */}
            <Section title='Shared Media' data={[{name: 'Fixed Skirt.pdf'}, {name: 'Poster TA.png'} ]}/>
          </div>
        </div>
    </div>
    );
  };
  //
  var tab = [];
  useEffect(() => {
    const asyncCall = async () => { 
      groups.map( async (group, i) => {
        await axios.put('/group/getUsers', {groupId: group.id})
        .then((response) => {
          let participant = {
            groupId: group.id,
            list: response.data.data
          }
          
          tab = [...tab, participant];

          if (tab.length-1 === groups.length-1) 
          { setParticipants([...tab]); tab.length=0;  }
        })
        .catch((error) => {
          tab.push(null);
            if (tab.length-1 === groups.length-1) 
            { setParticipants(tab); tab.length=0; }
            setError(error.response.data.message)
        })
      })
    }

    groups.length>0 && asyncCall(); 
  }, [groups])

  useEffect(() => {
    const asyncCall = async () => { 
      await axios.put('/group', {userId: user.id})
      .then((response) => setGroups(response.data.data))
      .then(async () => {
        await fetchContact()
        .then((response) => setContacts(response.data.data))
      })
      .catch((error) => setError(error.response.data.message))
    }; 
    //asyncCall();
    const interval = setInterval(() => {
      asyncCall()
    }, 2000);
    return () => clearInterval(interval);
  }, [])

  useEffect(() => {
    const asyncCall = async () => { 
      const { group } = broadcast
      let { list } = participants.find((p) => group.id === p.groupId) 
      if (group && list) {
        list
        .filter((contactId) => contactId !== user.id)
        .map(async (contactId) => {
          const message = {
            incoming_msg_id: group.id,
            outgoing_msg_id: contactId,
            msg: broadcast.msg
          };
          console.log(message);
          await axios.post('/messages', message)
          .then(() => newBroadcast(null))
          .catch(() => console.log('message fail'))
        });
      }
    };
    if (broadcast && participants) {
      asyncCall();
    }
  }, [broadcast]);

  // console.log(participants);
  //
  return (
    <Swiper
    className={`${style.swiper}`}
    spaceBetween={56}
    >
      {/* PARTICIPANTS */}
      {groups.length>0 && participants.length>0 && groups
        .sort((g1,g2) => g1.name-g2.name)
        .map((group) => { 
          return <SwiperSlide key={group.id}>
            <GroupSection
              group={group}
              participants={participants.find((p) => p && p.groupId===group.id)}
            />
          </SwiperSlide>
        })
      }
    </Swiper>
  );
};

export default ChatGroup;