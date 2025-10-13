import React from 'react';
import './ImageModal.css';
import { IoClose } from 'react-icons/io5';

const ImageModal = ({ show, imageUrl, onClose }) => {
  if (!show) {
    return null;
  }

  return (
    <div className="image-modal-overlay" onClick={onClose}>
      <div className="image-modal-content" onClick={e => e.stopPropagation()}>
        <button onClick={onClose} className="image-modal-close-button">
          <IoClose size={32} />
        </button>
        {imageUrl ? (
          <img src={imageUrl} alt="Rekam Medis" className="full-size-image" />
        ) : (
          <p>Gambar tidak tersedia.</p>
        )}
      </div>
    </div>
  );
};

export default ImageModal;