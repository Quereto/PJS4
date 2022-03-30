import React from 'react';

import style from './Modal.module.scss';

const Modal = ({content}) => {
  console.log('Open')
  return (
    <div className={`${style.modal}`}>
      <div className={`${style.blur}`}></div>
      <div className={`${style.container}`}>
        <div className={`${style.wrapper}`}>
          {content}
        </div>
      </div>
    </div>
  );
};

export default Modal;