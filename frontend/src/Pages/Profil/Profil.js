import React, { useState } from 'react';
import './Profil.css'; // Pastikan CSS ini ada dan sudah sesuai

const Profil = () => {
  const [formData, setFormData] = useState({
    namaLengkap: '',
    usia: '', // Usia bisa dihitung dari tanggal lahir atau diinput terpisah
    jenisKelamin: '',
    alamat: '',
    nomorTelepon: '', // Tambahan: Nomor Telepon
    tanggalLahir: '', // Tambahan: Tanggal Lahir
    rekamMedis: '', 
  });

  const [isEditing, setIsEditing] = useState(true);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prevState => ({
      ...prevState,
      [name]: value,
    }));
  };

  const handleSave = (e) => {
    e.preventDefault(); 
    console.log("Data Disimpan:", formData);
    setIsEditing(false); 
    alert("Data berhasil disimpan!");
  };

  const handleEdit = () => {
    setIsEditing(true); 
  };

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
              type="date" // Menggunakan type="date" untuk pemilih kalender
              id="tanggalLahir"
              name="tanggalLahir"
              value={formData.tanggalLahir}
              onChange={handleChange}
              disabled={!isEditing}
              required
            />
          </div>

          {/* Anda bisa memilih untuk menampilkan usia atau menghitungnya dari tanggal lahir */}
          <div className="profil-form-group">
            <label htmlFor="usia">Usia</label> {/* Usia tidak lagi required jika dihitung */}
            <input
              type="number"
              id="usia"
              name="usia"
              value={formData.usia} // Anda bisa menghitung ini otomatis dari tanggalLahir
              onChange={handleChange}
              disabled={!isEditing}
              // Hapus 'required' jika usia dihitung atau tidak wajib
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
              type="tel" // Menggunakan type="tel" untuk input nomor telepon
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

          {/* Pertimbangkan apakah rekam medis harus di sini atau di halaman terpisah */}
          <div className="profil-form-group">
            <label htmlFor="rekamMedis">Riwayat Kesehatan Singkat</label>
            <textarea
              id="rekamMedis"
              name="rekamMedis"
              rows="6"
              value={formData.rekamMedis}
              onChange={handleChange}
              disabled={!isEditing}
              // Jika ini adalah ringkasan riwayat, mungkin tidak perlu required
            ></textarea>
          </div>

          {isEditing && (
            <button type="submit" className="btn-primary profil-btn">Simpan</button>
          )}
        </form>
        
        {!isEditing && (
          <button type="button" onClick={handleEdit} className="btn-secondary profil-btn">Edit Data</button>
        )}
      </div>
    </div>
  );
};

export default Profil;