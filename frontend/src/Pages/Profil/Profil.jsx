import React, { useState, useEffect } from 'react';
import { patientService } from '../../services/patientService';
import { useNavigate } from 'react-router-dom';
import { authService } from '../../services/authService';


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

  const navigate = useNavigate();

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
        console.error("Failed to fetch patient profile:", err);
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
      console.error("Failed to save patient data:", err);
    }
  };

  const handleViewMedicalRecords = () => {
    navigate(`/rekam-medis`);
  };

  const handleEdit = () => {
    setIsEditing(true);
  };

  if (loading) {
    // Menggunakan kelas Tailwind untuk loading state
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
        <p className="text-gray-700 text-lg">Loading...</p>
      </div>
    );
  }

  return (
    // .profil-container
    <div className="min-h-screen bg-gray-100 p-8 flex flex-col items-center">
      
      <h1 className="text-4xl md:text-5xl font-bold text-gray-800 mb-8 mt-4">Data Diri</h1>

      
      <div className="bg-white p-8 rounded-2xl shadow-lg w-full max-w-4xl"> 
        <form onSubmit={handleSave} className="space-y-6"> 

          {/* .profil-form-group */}
          <div>
            
            <label htmlFor="namaLengkap" className="block text-base font-medium mb-2 text-gray-700">
              Nama Lengkap<span className="text-red-500">*</span>
            </label>
           
            <input
              type="text"
              id="namaLengkap"
              name="namaLengkap"
              value={formData.namaLengkap}
              onChange={handleChange}
              disabled={!isEditing}
              required
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base
                         disabled:bg-gray-100 disabled:text-gray-600 disabled:cursor-not-allowed transition"
            />
          </div>

          <div>
            <label htmlFor="tanggalLahir" className="block text-base font-medium mb-2 text-gray-700">
              Tanggal Lahir<span className="text-red-500">*</span>
            </label>
            <input
              type="date"
              id="tanggalLahir"
              name="tanggalLahir"
              value={formData.tanggalLahir}
              onChange={handleChange}
              disabled={!isEditing}
              required
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base
                         disabled:bg-gray-100 disabled:text-gray-600 disabled:cursor-not-allowed transition"
            />
          </div>

          <div>
            <label htmlFor="usia" className="block text-base font-medium mb-2 text-gray-700">Usia</label>
            <input
              type="number"
              id="usia"
              name="usia"
              value={formData.usia}
              onChange={handleChange}
              disabled={!isEditing}
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base
                         disabled:bg-gray-100 disabled:text-gray-600 disabled:cursor-not-allowed transition"
            />
          </div>

          <div>
            <label htmlFor="jenisKelamin" className="block text-base font-medium mb-2 text-gray-700">
              Jenis Kelamin<span className="text-red-500">*</span>
            </label>
            <select
              id="jenisKelamin"
              name="jenisKelamin"
              value={formData.jenisKelamin}
              onChange={handleChange}
              disabled={!isEditing}
              required
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base
                         disabled:bg-gray-100 disabled:text-gray-600 disabled:cursor-not-allowed transition"
            >
              <option value="" disabled>Pilih Jenis Kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>

          <div>
            <label htmlFor="nomorTelepon" className="block text-base font-medium mb-2 text-gray-700">
              Nomor Telepon<span className="text-red-500">*</span>
            </label>
            <input
              type="tel"
              id="nomorTelepon"
              name="nomorTelepon"
              value={formData.nomorTelepon}
              onChange={handleChange}
              disabled={!isEditing}
              required
              placeholder="Contoh: 081234567890"
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base
                         disabled:bg-gray-100 disabled:text-gray-600 disabled:cursor-not-allowed transition"
            />
          </div>

          <div>
            <label htmlFor="alamat" className="block text-base font-medium mb-2 text-gray-700">
              Alamat<span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              id="alamat"
              name="alamat"
              value={formData.alamat}
              onChange={handleChange}
              disabled={!isEditing}
              required
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base
                         disabled:bg-gray-100 disabled:text-gray-600 disabled:cursor-not-allowed transition"
            />
          </div>

          <div>
            <label htmlFor="rekamMedis" className="block text-base font-medium mb-2 text-gray-700">Riwayat Kesehatan Singkat</label>
            <textarea
              id="rekamMedis"
              name="rekamMedis"
              rows="6"
              value={formData.rekamMedis}
              onChange={handleChange}
              disabled={!isEditing}
              className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base resize-y
                         disabled:bg-gray-100 disabled:text-gray-600 disabled:cursor-not-allowed transition"
            ></textarea>
          </div>

          {isEditing && (
            // .btn-primary .profil-btn
            // background-color: #3b82f6; color: white; hover: #2563eb;
            <button
              type="submit"
              className="w-full py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out mt-6"
            >
              Simpan
            </button>
          )}
        </form>

        {!isEditing && (
          // .profil-actions
          <div className="flex flex-col md:flex-row gap-4 mt-6"> {/* Memberikan jarak antar tombol dan responsif */}
            
            <button
              type="button"
              onClick={handleEdit}
              className="w-full md:w-1/2 py-3 px-4 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition duration-300 ease-in-out"
            >
              Edit Data
            </button>
            
            <button
              type="button"
              onClick={handleViewMedicalRecords}
              className="w-full md:w-1/2 py-3 px-4 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition duration-300 ease-in-out" // Gunakan warna hijau yang berbeda atau biru, sesuai selera
              disabled={!patientId} // Nonaktifkan jika patientId tidak ada
            >
              Lihat Rekam Medis
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

export default Profil;