import React, { useState, useEffect } from 'react';
import './DataFormModal.css';

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
    <div className="modal-backdrop">
      <div className="modal-content">
        <h3 className="modal-title">{initialData ? 'Edit Data Pasien' : 'Tambah Data Pasien Baru'}</h3>
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label htmlFor="name">Nama Pasien</label>
            <input
              type="text"
              id="name"
              name="name"
              value={formData.name}
              onChange={handleChange}
              required
            />
          </div>
          <div className="form-group">
            <label htmlFor="email">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              required
            />
          </div>
          <div className="form-group">
            <label htmlFor="phone">Nomor Telepon</label>
            <input
              type="tel"
              id="phone"
              name="phone"
              value={formData.phone}
              onChange={handleChange}
              required
            />
          </div>
          <div className="modal-actions">
            <button type="button" className="btn-secondary" onClick={onClose}>
              Batal
            </button>
            <button type="submit" className="btn-primary">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default DataFormModal;