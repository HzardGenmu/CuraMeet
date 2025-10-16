import React from 'react';
import { IoClose } from 'react-icons/io5';

const ImageModal = ({ show, imageUrl, onClose }) => {
  if (!show) {
    return null;
  }

  return (
    // .image-modal-overlay
    <div
      className="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-[2000]"
      onClick={onClose}
    >
      {/* .image-modal-content */}
      <div
        className="bg-white p-4 rounded-lg max-w-[90vw] max-h-[90vh] relative shadow-2xl flex justify-center items-center"
        onClick={e => e.stopPropagation()}
      >
        {/* .image-modal-close-button */}
        <button
          onClick={onClose}
          className="absolute -top-4 -right-4 bg-red-600 text-white rounded-full w-10 h-10
                     flex justify-center items-center cursor-pointer z-[2001] shadow-md
                     hover:bg-red-700 transition duration-200"
        >
          <IoClose size={24} /> {/* Mengurangi ukuran ikon agar pas di tombol 40x40px */}
        </button>
        {imageUrl ? (
          // .full-size-image
          <img src={imageUrl} alt="Rekam Medis" className="max-w-full max-h-full block object-contain rounded" />
        ) : (
          <p className="text-gray-700 text-center">Gambar tidak tersedia.</p>
        )}
      </div>
    </div>
  );
};

export default ImageModal;