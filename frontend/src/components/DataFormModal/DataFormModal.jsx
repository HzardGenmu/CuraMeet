import React, { useState, useEffect } from 'react';


const DataFormModal = ({ show, onClose, onSave, initialData }) => {
  const [formData, setFormData] = useState(initialData || { name: '', email: '', phone: '' });

  useEffect(() => {
    setFormData(initialData || { name: '', email: '', phone: '' });
  }, [initialData, show]);

  if (!show) {
    return null;
  }

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSave(formData);
  };

  return (
    // .modal-backdrop
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-[2000]">
      {/* .modal-content */}
      <div className="bg-white p-8 rounded-xl shadow-2xl w-11/12 max-w-lg animate-fadeInScale"> {/* max-w-lg = 32rem = 512px */}
        <h3 className="text-2xl font-semibold text-gray-800 mt-0 mb-8 text-center">
          {initialData ? 'Edit Data Pasien' : 'Tambah Data Pasien Baru'}
        </h3>
        <form onSubmit={handleSubmit}>
          {/* .form-group */}
          <div className="mb-6">
            <label htmlFor="name" className="block mb-3 font-medium text-gray-700 text-base"> {/* text-base = 1rem, mendekati 0.95rem */}
              Nama Pasien
            </label>
            <input
              type="text"
              id="name"
              name="name"
              value={formData.name}
              onChange={handleChange}
              required
              className="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg
                         focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200
                         transition duration-200 ease-in-out"
            />
          </div>
          {/* .form-group */}
          <div className="mb-6">
            <label htmlFor="email" className="block mb-3 font-medium text-gray-700 text-base">
              Email
            </label>
            <input
              type="email"
              id="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              required
              className="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg
                         focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200
                         transition duration-200 ease-in-out"
            />
          </div>
          {/* .form-group */}
          <div className="mb-6">
            <label htmlFor="phone" className="block mb-3 font-medium text-gray-700 text-base">
              Nomor Telepon
            </label>
            <input
              type="tel"
              id="phone"
              name="phone"
              value={formData.phone}
              onChange={handleChange}
              required
              className="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg
                         focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200
                         transition duration-200 ease-in-out"
            />
          </div>
          {/* .modal-actions */}
          <div className="flex justify-end gap-4 mt-10"> {/* mt-10 = 2.5rem */}
            <button
              type="button"
              className="px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium
                         hover:bg-gray-100 hover:border-gray-400 transition duration-200 ease-in-out"
              onClick={onClose}
            >
              Batal
            </button>
            <button
              type="submit"
              className="px-6 py-3 bg-emerald-600 text-white border border-emerald-600 rounded-lg font-medium
                         hover:bg-emerald-700 hover:border-emerald-700 transition duration-200 ease-in-out"
            >
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default DataFormModal;