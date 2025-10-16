import React from 'react';

import { IoClose } from 'react-icons/io5';

const ConfirmationModal = ({ show, title, message, onConfirm, onCancel }) => {
  if (!show) {
    return null;
  }

  return (
    // .confirmation-modal-overlay
    <div
      className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-[1050]" 
      onClick={onCancel}
    >
      {/* .confirmation-modal-content */}
      <div
        className="bg-white p-6 sm:p-8 rounded-xl w-11/12 max-w-md shadow-2xl animate-slide-down" // animate-slide-down perlu didefinisikan di CSS/Tailwind config
        onClick={e => e.stopPropagation()}
      >
        {/* .confirmation-modal-header */}
        <div className="flex justify-between items-center border-b border-gray-200 pb-4 mb-4">
          <h2 className="text-2xl font-semibold text-gray-800">{title}</h2>
          <button onClick={onCancel} className="text-gray-500 hover:text-gray-700 transition duration-200 p-1">
            <IoClose size={28} />
          </button>
        </div>
        {/* .confirmation-modal-body */}
        <div className="py-6 text-center text-lg text-gray-700">
          <p>{message}</p>
        </div>
        {/* .confirmation-modal-footer */}
        <div className="flex justify-center gap-4 pt-4 border-t border-gray-200 mt-4">
          <button
            onClick={onCancel}
            className="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg font-medium
                       hover:bg-gray-300 transition duration-200 ease-in-out"
          >
            Batal
          </button>
          <button
            onClick={onConfirm}
            className="px-5 py-2 bg-red-600 text-white rounded-lg font-medium
                       hover:bg-red-700 transition duration-200 ease-in-out"
          >
            Ya, Lanjutkan
          </button>
        </div>
      </div>
    </div>
  );
};

export default ConfirmationModal;

