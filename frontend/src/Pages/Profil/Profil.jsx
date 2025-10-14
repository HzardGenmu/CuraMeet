import React, { useState, useEffect } from 'react';
import { patientService } from '../../services/patientService';
import { authService } from '../../services/authService';
import './Profil.css'; // Pastikan CSS ini ada dan sudah sesuai

const Profil = () => {
  const [formData, setFormData] = useState({
    namaLengkap: '',
    usia: '',
    jenisKelamin: '',
    alamat: '',
    nomorTelepon: '',
    tanggalLahir: '',
    rekamMedis: '',
  });
  const [patientId, setPatientId] = useState(null);

  const [isEditing, setIsEditing] = useState(false);
  const [loading, setLoading] = useState(true);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prevState => ({
      ...prevState,
      [name]: value,
    }));
  };

  // Fetch data user dari backend saat mount
  useEffect(() => {
    const fetchProfile = async () => {
      setLoading(true);
      try {
        const user = authService.getCurrentUser();
        if (user && user.id) {
          setPatientId(user.id);
          const res = await patientService.getPatientById(user.id);
          if (res.success && res.patient) {
            setFormData({
              namaLengkap: res.patient.full_name || '',
              usia: res.patient.age || '',
              jenisKelamin: res.patient.gender || '',
              alamat: res.patient.address || '',
              nomorTelepon: res.patient.phone || '',
              tanggalLahir: res.patient.birth_date || '',
              rekamMedis: res.patient.disease_histories || '',
            });
          }
        }
      } catch (err) {
        // Biarkan kosong jika gagal
      } finally {
        setLoading(false);
      }
    };
    fetchProfile();
  }, []);

  const handleSave = async (e) => {
    e.preventDefault();
    if (!patientId) return;
    try {
      const payload = {
        full_name: formData.namaLengkap,
        age: formData.usia,
        gender: formData.jenisKelamin,
        address: formData.alamat,
        phone: formData.nomorTelepon,
        birth_date: formData.tanggalLahir,
        disease_histories: formData.rekamMedis,
      };
      const res = await patientService.submitPersonalDataForm(patientId, payload);
      if (res.success) {
        setIsEditing(false);
        alert('Data berhasil disimpan!');
      } else {
        alert('Gagal menyimpan data!');
      }
    } catch (err) {
      alert('Terjadi kesalahan saat menyimpan data!');
    }
  };

  const handleEdit = () => {
    setIsEditing(true); 
  };

  if (loading) return <div className="profil-container"><p>Loading...</p></div>;

  return (
    <div className="profil-container">
      <h1 className="page-title">Data Diri</h1>
      <div className="card profil-card">
        <form onSubmit={handleSave}>
          <div className="profil-form-group">
            <label htmlFor="namaLengkap">Nama Lengkap<span className="required-star">*</span></label>
            <input
              type="text"
              id="namaLengkap"
              name="namaLengkap"
              value={formData.namaLengkap}
              onChange={handleChange}
              disabled={!isEditing}
              required
            />
          </div>

          <div className="profil-form-group">
            <label htmlFor="tanggalLahir">Tanggal Lahir<span className="required-star">*</span></label>
            <input
              type="date"
              id="tanggalLahir"
              name="tanggalLahir"
              value={formData.tanggalLahir}
              onChange={handleChange}
              disabled={!isEditing}
              required
            />
          </div>

          <div className="profil-form-group">
            <label htmlFor="usia">Usia</label>
            <input
              type="number"
              id="usia"
              name="usia"
              value={formData.usia}
              onChange={handleChange}
              disabled={!isEditing}
            />
          </div>

          <div className="profil-form-group">
            <label htmlFor="jenisKelamin">Jenis Kelamin<span className="required-star">*</span></label>
            <select
              id="jenisKelamin"
              name="jenisKelamin"
              value={formData.jenisKelamin}
              onChange={handleChange}
              disabled={!isEditing}
              required
            >
              <option value="" disabled>Pilih Jenis Kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>

          <div className="profil-form-group">
            <label htmlFor="nomorTelepon">Nomor Telepon<span className="required-star">*</span></label>
            <input
              type="tel"
              id="nomorTelepon"
              name="nomorTelepon"
              value={formData.nomorTelepon}
              onChange={handleChange}
              disabled={!isEditing}
              required
              placeholder="Contoh: 081234567890"
            />
          </div>

          <div className="profil-form-group">
            <label htmlFor="alamat">Alamat<span className="required-star">*</span></label>
            <input
              type="text"
              id="alamat"
              name="alamat"
              value={formData.alamat}
              onChange={handleChange}
              disabled={!isEditing}
              required
            />
          </div>

          <div className="profil-form-group">
            <label htmlFor="rekamMedis">Riwayat Kesehatan Singkat</label>
            <textarea
              id="rekamMedis"
              name="rekamMedis"
              rows="6"
              value={formData.rekamMedis}
              onChange={handleChange}
              disabled={!isEditing}
            ></textarea>
          </div>

          {isEditing && (
            <button type="submit" className="btn-primary profil-btn">Simpan</button>
          )}
        </form>

        {!isEditing && (
          <button type="button" onClick={() => setIsEditing(true)} className="btn-secondary profil-btn">Edit Data</button>
        )}
      </div>
    </div>
  );
};

export default Profil;