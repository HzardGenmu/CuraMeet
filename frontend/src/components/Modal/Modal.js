import React from 'react';
import './Modal.css';
import { IoClose, IoTrash } from 'react-icons/io5';

const Modal = ({ show, onClose, title, children }) => {
  // Jika show adalah false, jangan render apa pun
  if (!show) {
    return null;
  }

  return (
    <div className="modal-overlay" onClick={onClose}>
      <div className="modal-content" onClick={e => e.stopPropagation()}>
        <div className="modal-header">
          <h2 className="modal-title">{title}</h2>
          <button onClick={onClose} className="close-button">
            <IoClose size={28} />
          </button>
        </div>
        <div className="modal-body">
          
          {children} {/* Render children di sini */}
    
        </div>
      </div>
    </div>
  );
};

export default Modal;