import React, { useState, useEffect } from 'react';

const DataFormModal = ({ show, onClose, onSave, initialData, isEditMode }) => {
  // State untuk data utama
  const [formData, setFormData] = useState({ name: '', email: '', nik: '' });
  
  // State untuk password, dibedakan untuk mode Tambah dan Edit
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmNewPassword, setConfirmNewPassword] = useState('');
  
  const [error, setError] = useState('');

  // Reset state setiap kali modal dibuka
  useEffect(() => {
    if (show) {
      // Hanya isi data utama, password selalu dikosongkan
      setFormData(initialData || { name: '', email: '', phone: '', nik: '' });
      setPassword('');
      setConfirmPassword('');
      setNewPassword('');
      setConfirmNewPassword('');
      setError('');
    }
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
    setError('');

    let finalData = { ...formData };

    // Validasi hanya saat mode Tambah (password wajib)
    if (!isEditMode) {
      if (password !== confirmPassword) {
        setError('Password dan konfirmasi password tidak cocok.');
        return;
      }
      finalData.password = password;
    }

    // Validasi hanya saat mode Edit (jika password baru diisi)
    if (isEditMode) {
      if (newPassword !== confirmNewPassword) {
        setError('Password baru dan konfirmasi tidak cocok.');
        return;
      }
      // Hanya tambahkan password baru jika field diisi
      if (newPassword) {
        finalData.newPassword = newPassword;
      }
    }
    
    onSave(finalData);
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-[2000]">
      <div className="bg-white p-8 rounded-xl shadow-2xl w-11/12 max-w-lg animate-fadeInScale">
        <h3 className="text-2xl font-semibold text-gray-800 mt-0 mb-8 text-center">
          {isEditMode ? 'Edit Data Pasien' : 'Tambah Data Pasien Baru'}
        </h3>
        
        {error && <div className="bg-red-100 text-red-700 p-3 rounded-lg mb-6 text-center text-sm">⚠️ {error}</div>}

        <form onSubmit={handleSubmit}>
          {/* Form Group untuk Nama, Email, NIK */}
          <div className="mb-6">
            <label htmlFor="name" className="block mb-2 font-medium text-gray-700">Nama Pasien <span className="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value={formData.name} onChange={handleChange} required className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
          </div>
          <div className="mb-6">
            <label htmlFor="email" className="block mb-2 font-medium text-gray-700">Email <span className="text-red-500">*</span></label>
            <input type="email" id="email" name="email" value={formData.email} onChange={handleChange} required className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
          </div>
          <div className="mb-6">
            <label htmlFor="nik" className="block mb-2 font-medium text-gray-700">NIK <span className="text-red-500">*</span></label>
            <input type="text" id="nik" name="nik" value={formData.nik || ''} onChange={handleChange} required maxLength="16" pattern="\d{16}" title="NIK harus terdiri dari 16 digit angka" className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
          </div>

          {/* ====================================================== */}
          {/* ✅ Bagian Password yang Ditampilkan Secara Kondisional */}
          {/* ====================================================== */}
          {isEditMode ? (
            // Tampilan untuk MODE EDIT
            <>
              <p className="text-sm text-gray-500 mb-4 border-t pt-4">Kosongkan jika tidak ingin mengubah password.</p>
              <div className="mb-6">
                <label htmlFor="newPassword" className="block mb-2 font-medium text-gray-700">Password Baru</label>
                <input type="password" id="newPassword" name="newPassword" value={newPassword} onChange={(e) => setNewPassword(e.target.value)} minLength="8" className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
              </div>
              <div className="mb-6">
                <label htmlFor="confirmNewPassword" className="block mb-2 font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" id="confirmNewPassword" name="confirmNewPassword" value={confirmNewPassword} onChange={(e) => setConfirmNewPassword(e.target.value)} minLength="8" className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
              </div>
            </>
          ) : (
            // Tampilan untuk MODE TAMBAH
            <>
              <div className="mb-6">
                <label htmlFor="password" className="block mb-2 font-medium text-gray-700">Password <span className="text-red-500">*</span></label>
                <input type="password" id="password" name="password" value={password} onChange={(e) => setPassword(e.target.value)} required minLength="8" className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
              </div>
              <div className="mb-6">
                <label htmlFor="confirmPassword" className="block mb-2 font-medium text-gray-700">Konfirmasi Password <span className="text-red-500">*</span></label>
                <input type="password" id="confirmPassword" name="confirmPassword" value={confirmPassword} onChange={(e) => setConfirmPassword(e.target.value)} required minLength="8" className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" />
              </div>
            </>
          )}

          {/* Tombol Aksi */}
          <div className="flex justify-end gap-4 mt-10">
            <button type="button" className="px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-100 transition" onClick={onClose}>
              Batal
            </button>
            <button type="submit" className="px-6 py-3 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default DataFormModal;

