import React, { useState, useEffect } from 'react';
import './AddMedicalRecordModal.css';
import { IoClose } from 'react-icons/io5';

const AddMedicalRecordModal = ({ show, onClose, onSave }) => {
  const [diagnosa, setDiagnosa] = useState('');
  const [resepObat, setResepObat] = useState('');

  // Reset form saat modal dibuka atau ditutup
  useEffect(() => {
    if (!show) {
      setDiagnosa('');
      setResepObat('');
    }
  }, [show]);

  if (!show) {
    return null;
  }

  const handleSubmit = (e) => {
    e.preventDefault();
    if (diagnosa.trim() === '') {
      alert('Diagnosa tidak boleh kosong.');
      return;
    }
    onSave({ diagnosa, resepObat });
    onClose(); // Tutup modal setelah disimpan
  };

  return (
    <div className="add-medical-record-modal-overlay" onClick={onClose}>
      <div className="add-medical-record-modal-content" onClick={e => e.stopPropagation()}>
        <div className="add-medical-record-modal-header">
          <h2 className="add-medical-record-modal-title">Tambahkan Catatan Medis Baru</h2>
          <button onClick={onClose} className="add-medical-record-close-button">
            <IoClose size={28} />
          </button>
        </div>
        <div className="add-medical-record-modal-body">
          <form onSubmit={handleSubmit}>
            <div className="form-group">
              <label htmlFor="diagnosa">Diagnosa<span className="required-star">*</span></label>
              <textarea
                id="diagnosa"
                value={diagnosa}
                onChange={(e) => setDiagnosa(e.target.value)}
                rows="5"
                required
              ></textarea>
            </div>
            <div className="form-group">
              <label htmlFor="resepObat">Resep Obat</label>
              <textarea
                id="resepObat"
                value={resepObat}
                onChange={(e) => setResepObat(e.target.value)}
                rows="5"
                placeholder="Misal: Paracetamol 500mg (3x sehari), Amoxicillin 250mg (2x sehari)"
              ></textarea>
            </div>
            <button type="submit" className="btn-primary">
              Tambahkan Catatan
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

export default AddMedicalRecordModal;