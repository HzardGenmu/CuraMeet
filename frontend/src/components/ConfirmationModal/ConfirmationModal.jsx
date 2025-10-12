import React from 'react';
import './ConfirmationModal.css';
import { IoClose } from 'react-icons/io5';

const ConfirmationModal = ({ show, title, message, onConfirm, onCancel }) => {
  if (!show) {
    return null;
  }

  return (
    <div className="confirmation-modal-overlay" onClick={onCancel}>
      <div className="confirmation-modal-content" onClick={e => e.stopPropagation()}>
        <div className="confirmation-modal-header">
          <h2 className="confirmation-modal-title">{title}</h2>
          <button onClick={onCancel} className="confirmation-close-button">
            <IoClose size={28} />
          </button>
        </div>
        <div className="confirmation-modal-body">
          <p>{message}</p>
        </div>
        <div className="confirmation-modal-footer">
          <button onClick={onCancel} className="btn-cancel">Batal</button>
          <button onClick={onConfirm} className="btn-confirm">Ya, Lanjutkan</button>
        </div>
      </div>
    </div>
  );
};

export default ConfirmationModal;