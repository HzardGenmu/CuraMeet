import React from 'react';

import { IoClose } from 'react-icons/io5'; 

const Modal = ({ show, onClose, title, children }) => {
  if (!show) {
    return null;
  }

  return (
    // .modal-overlay
    <div
      className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-[1000]"
      onClick={onClose}
    >
      {/* .modal-content */}
      <div
        className="bg-white p-6 sm:p-8 rounded-xl w-11/12 max-w-2xl shadow-2xl animate-slide-down" // max-w-2xl = 24rem = 600px
        onClick={e => e.stopPropagation()}
      >
        {/* .modal-header */}
        <div className="flex justify-between items-center border-b border-gray-200 pb-4 mb-4">
          <h2 className="text-2xl font-semibold text-gray-800">{title}</h2> {/* Tambahkan font-semibold dan text-gray-800 */}
          <button onClick={onClose} className="text-gray-500 hover:text-gray-700 transition duration-200 p-1">
            <IoClose size={28} />
          </button>
        </div>
        {/* .modal-body */}
        <div className="py-6 overflow-y-auto max-h-[60vh]"> 
          {children} 
        </div>
      </div>
    </div>
  );
};

export default Modal;