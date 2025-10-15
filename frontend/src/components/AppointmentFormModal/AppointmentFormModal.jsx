import React, { useState, useEffect } from 'react';

import { IoClose } from 'react-icons/io5';

// Tambahkan isDoctorView ke props
const AppointmentFormModal = ({ show, onClose, title, appointmentData, doctors, rooms, onSave, isDoctorView = false }) => {
  const [formData, setFormData] = useState({
    id: appointmentData?.id || null, // Tambahkan ID
    tanggal: appointmentData?.tanggal || '',
    jam: appointmentData?.jam || '',
    dokter: appointmentData?.dokterId || '', // Gunakan dokterId
    ruang: appointmentData?.ruang || '',
    pasien: appointmentData?.pasien || '', // Tambahkan pasien juga
  });

  // useEffect untuk mengisi formData saat appointmentData berubah (saat modal dibuka dengan data baru)
  useEffect(() => {
    if (appointmentData) {
      setFormData({
        id: appointmentData.id,
        tanggal: appointmentData.tanggal,
        jam: appointmentData.jam,
        dokter: appointmentData.dokterId, // Pastikan ini dokterId
        ruang: appointmentData.ruang,
        pasien: appointmentData.pasien,
      });
    } else {
      // Reset form jika tidak ada data awal (misal untuk "tambah baru")
      setFormData({ id: null, tanggal: '', jam: '', dokter: '', ruang: '', pasien: '' });
    }
  }, [appointmentData, show]); // Tambahkan `show` agar form direset saat modal ditutup/dibuka ulang

  if (!show) {
    return null;
  }

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prevState => ({
      ...prevState,
      [name]: value
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSave(formData);
  };

  return (
    // .modal-overlay
    <div className="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-[1050]" onClick={onClose}>
      {/* .modal-content (form-modal) */}
      <div
        className="bg-white p-6 sm:p-8 rounded-xl w-11/12 max-w-xl shadow-2xl animate-scale-up" // max-w-xl = 36rem = 576px
        onClick={e => e.stopPropagation()}
      >
        {/* .modal-header */}
        <div className="flex justify-between items-center border-b border-gray-200 pb-4 mb-6"> {/* mb-6 = 1.5rem */}
          <h2 className="text-2xl font-semibold text-gray-800">{title || "Form Janji Temu"}</h2>
          <button onClick={onClose} className="text-gray-500 hover:text-gray-700 transition duration-200 p-1">
            <IoClose size={28} />
          </button>
        </div>
        {/* .modal-body */}
        <div>
          <form onSubmit={handleSubmit}>
            {/* .form-group-modal */}
            <div className="mb-6">
              <label htmlFor="tanggal" className="block mb-2 font-medium text-gray-700 text-base">
                Tanggal<span className="text-red-500 ml-1">*</span>
              </label>
              <input
                type="date"
                id="tanggal"
                name="tanggal"
                value={formData.tanggal}
                onChange={handleChange}
                required
                className={`w-full px-4 py-3 border border-gray-300 rounded-lg text-lg
                           focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                           transition duration-200 ease-in-out
                           ${isDoctorView ? 'bg-gray-100 cursor-not-allowed' : ''}`}
                disabled={isDoctorView} // Dokter tidak bisa mengubah tanggal (opsional, bisa diubah)
              />
            </div>
            {/* .form-group-modal */}
            <div className="mb-6">
              <label htmlFor="jam" className="block mb-2 font-medium text-gray-700 text-base">
                Jam<span className="text-red-500 ml-1">*</span>
              </label>
              <input
                type="time"
                id="jam"
                name="jam"
                value={formData.jam}
                onChange={handleChange}
                required
                className={`w-full px-4 py-3 border border-gray-300 rounded-lg text-lg
                           focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                           transition duration-200 ease-in-out
                           ${isDoctorView ? 'bg-gray-100 cursor-not-allowed' : ''}`}
                disabled={isDoctorView} // Dokter tidak bisa mengubah jam (opsional, bisa diubah)
              />
            </div>
            {/* .form-group-modal */}
            <div className="mb-6">
              <label htmlFor="dokter" className="block mb-2 font-medium text-gray-700 text-base">
                Dokter<span className="text-red-500 ml-1">*</span>
              </label>
              <select
                id="dokter"
                name="dokter"
                value={formData.dokter}
                onChange={handleChange}
                required
                className="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg appearance-none
                           focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                           transition duration-200 ease-in-out bg-gray-100 cursor-not-allowed" // Selalu disabled di tampilan dokter
                disabled={true} // DOKTER TIDAK DAPAT MENGUBAH FIELD INI
              >
                <option value="" disabled>Pilih Dokter</option>
                {doctors.map(doctor => (
                  <option key={doctor.id} value={doctor.id}>
                    {doctor.nama}
                  </option>
                ))}
              </select>
            </div>
            {/* .form-group-modal */}
            <div className="mb-6">
              <label htmlFor="ruang" className="block mb-2 font-medium text-gray-700 text-base">
                Ruang<span className="text-red-500 ml-1">*</span>
              </label>
              <select
                id="ruang"
                name="ruang"
                value={formData.ruang}
                onChange={handleChange}
                required
                className={`w-full px-4 py-3 border border-gray-300 rounded-lg text-lg appearance-none
                           focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                           transition duration-200 ease-in-out
                           ${isDoctorView ? '' : 'bg-gray-100 cursor-not-allowed'}`} // Ruang bisa diubah oleh dokter, tapi tidak oleh pasien
                disabled={!isDoctorView} // Hanya dokter yang bisa mengubah ruang
              >
                <option value="" disabled>Pilih Ruang</option>
                {rooms.map(room => (
                  <option key={room.id} value={room.nama}>
                    {room.nama}
                  </option>
                ))}
              </select>
            </div>
            {/* Tambahkan field Pasien jika diperlukan untuk ditampilkan/diedit */}
            <div className="mb-8"> {/* mb-8 = 2rem */}
              <label htmlFor="pasien" className="block mb-2 font-medium text-gray-700 text-base">
                Pasien
              </label>
              <input
                type="text"
                id="pasien"
                name="pasien"
                value={formData.pasien}
                onChange={handleChange}
                className="w-full px-4 py-3 border border-gray-300 rounded-lg text-lg
                           focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                           transition duration-200 ease-in-out
                           bg-gray-100 cursor-not-allowed"
                disabled={true} // Dokter tidak bisa mengubah pasien
              />
            </div>

            {/* .form-submit-btn */}
            <button
              type="submit"
              className="w-full px-6 py-3 bg-blue-600 text-white rounded-lg font-medium
                         hover:bg-blue-700 transition duration-300 ease-in-out"
            >
              Simpan Perubahan
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default AppointmentFormModal;