import React from 'react';
import { IoClose } from 'react-icons/io5';

const TextDetailModal = ({ show, title, content, onClose }) => {
  if (!show) {
    return null;
  }

  return (
    // .text-detail-modal-overlay
    <div
      className="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-[1050]"
      onClick={onClose}
    >
      {/* .text-detail-modal-content */}
      <div
        className="bg-white p-6 sm:p-8 rounded-xl w-11/12 max-w-2xl max-h-[80vh] overflow-y-auto shadow-2xl animate-scale-up"
        onClick={e => e.stopPropagation()}
      >
        {/* .text-detail-modal-header */}
        <div className="flex justify-between items-center border-b border-gray-200 pb-4 mb-4">
          <h2 className="text-2xl font-semibold text-gray-800">{title}</h2>
          <button onClick={onClose} className="text-gray-500 hover:text-gray-700 transition duration-200 p-1">
            <IoClose size={28} />
          </button>
        </div>
        {/* .text-detail-modal-body */}
        <div className="text-gray-700"> {/* Menghapus padding-top/bottom karena sudah ada di content */}
          <p className="leading-relaxed text-gray-700 whitespace-pre-wrap">{content}</p>
        </div>
      </div>
    </div>
  );
};

export default TextDetailModal;