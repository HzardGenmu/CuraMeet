// src/components/AppointmentFormModal/AppointmentFormModal.js
import React, { useState, useEffect } from 'react';
import './AppointmentFormModal.css';
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
    }
  }, [appointmentData]);

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
    <div className="modal-overlay" onClick={onClose}>
      <div className="modal-content" onClick={e => e.stopPropagation()}>
        <div className="modal-header">
          <h2 className="modal-title">{title || "Form Janji Temu"}</h2>
          <button onClick={onClose} className="modal-close-button">
            <IoClose size={28} />
          </button>
        </div>
        <div className="modal-body">
          <form onSubmit={handleSubmit}>
            <div className="form-group">
              <label htmlFor="tanggal">Tanggal<span className="required-star">*</span></label>
              <input
                type="date"
                id="tanggal"
                name="tanggal"
                value={formData.tanggal}
                onChange={handleChange}
                required
                 // Dokter tidak bisa mengubah tanggal (opsional, bisa diubah)
              />
            </div>
            <div className="form-group">
              <label htmlFor="jam">Jam<span className="required-star">*</span></label>
              <input
                type="time"
                id="jam"
                name="jam"
                value={formData.jam}
                onChange={handleChange}
                required
                 // Dokter tidak bisa mengubah jam (opsional, bisa diubah)
              />
            </div>
            <div className="form-group">
              <label htmlFor="dokter">Dokter<span className="required-star">*</span></label>
              <select
                id="dokter"
                name="dokter"
                value={formData.dokter}
                onChange={handleChange}
                required
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
            <div className="form-group">
              <label htmlFor="ruang">Ruang<span className="required-star">*</span></label>
              <select
                id="ruang"
                name="ruang"
                value={formData.ruang}
                onChange={handleChange}
                required
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
            <div className="form-group">
              <label htmlFor="pasien">Pasien</label>
              <input
                type="text"
                id="pasien"
                name="pasien"
                value={formData.pasien}
                onChange={handleChange}
                disabled={true} // Dokter tidak bisa mengubah pasien
              />
            </div>

            <button type="submit" className="btn-primary">
              Simpan Perubahan
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default AppointmentFormModal;