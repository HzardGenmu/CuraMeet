import React from 'react';
import './TextDetailModal.css';
import { IoClose } from 'react-icons/io5';

const TextDetailModal = ({ show, title, content, onClose }) => {
  if (!show) {
    return null;
  }

  return (
    <div className="text-detail-modal-overlay" onClick={onClose}>
      <div className="text-detail-modal-content" onClick={e => e.stopPropagation()}>
        <div className="text-detail-modal-header">
          <h2 className="text-detail-modal-title">{title}</h2>
          <button onClick={onClose} className="text-detail-close-button">
            <IoClose size={28} />
          </button>
        </div>
        <div className="text-detail-modal-body">
          <p>{content}</p>
        </div>
      </div>
    </div>
  );
};

export default TextDetailModal;