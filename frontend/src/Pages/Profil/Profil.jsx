import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { authService } from '../../services/authService';
import { patientService } from '../../services/patientService';

const Profil = () => {
  const [formData, setFormData] = useState({
    full_name: '',
   birth_date: '',
    age: '',
    gender: '',
    phone: '',
    address: '',
    disease_histories: '',
    NIK: '', 
  });
  
  const [patientId, setPatientId] = useState(null);
  const [isEditing, setIsEditing] = useState(false);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [successMessage, setSuccessMessage] = useState('');

  const navigate = useNavigate();

  useEffect(() => {
    const fetchProfile = async () => {
      setLoading(true);
      setError('');
      try {
        if (authService.isAuthenticated()) {
         
          const res = await patientService.getProfile();
          
          if (res.success && res.patient) {
            // Simpan patient.id yang benar untuk proses update
            setPatientId(res.patient.id); 
            
            // Mengisi form dengan data dari backend
            setFormData({
              full_name: res.patient.full_name || '',
              birth_date: res.patient.birth_date || '',
              age: res.patient.age || '',
              gender: res.patient.gender || '',
              phone: res.patient.phone || '',
              address: res.patient.address || '',
              disease_histories: res.patient.disease_histories || '',
              NIK: res.patient.NIK || '',
            });
          } else {
             setError(res.message || "Gagal mengambil data profil pasien.");
          }
        } else {
          // Jika tidak ada user, arahkan ke login
          navigate("/login");
        }
      } catch (err) {
        console.error("Failed to fetch patient profile:", err);
        setError(err.response?.data?.message || "Terjadi kesalahan pada server saat mengambil data profil.");
      } finally {
        setLoading(false);
      }
    };
    fetchProfile();
  }, [navigate]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prevState => ({ ...prevState, [name]: value }));
  };

  const handleSave = async (e) => {
    e.preventDefault();
    if (!patientId) return;

    setError('');
    setSuccessMessage('');
  
    try {
      const res = await patientService.updateProfile(patientId, formData);
    
      if (res.success) {
        setIsEditing(false);
        setSuccessMessage('Data berhasil disimpan!');
        setTimeout(() => setSuccessMessage(''), 3000);
      
        // Perbarui nama di userInfo localStorage jika backend mengembalikan data pasien
        const updatedPatientName = formData.full_name;
        const currentUserInfo = authService.getCurrentUser();
        if (currentUserInfo && currentUserInfo.name !== updatedPatientName) {
          const updatedUserInfo = { ...currentUserInfo, name: updatedPatientName };
          localStorage.setItem('userInfo', JSON.stringify(updatedUserInfo));
        }

    } else {
      setError(res.message || 'Gagal menyimpan data!');
    }
  } catch (err) {
      console.error("Failed to save patient data:", err);
      setError(err.response?.data?.message || 'Terjadi kesalahan saat menyimpan data!');
  }
 };

  const handleEdit = () => setIsEditing(true);
  const handleViewMedicalRecords = () => navigate(`/rekam-medis`);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
        <p className="text-gray-700 text-lg">Loading Profile...</p>
      </div>
    );
  }
  
  return (
    <div className="min-h-screen bg-gray-100 p-8 flex flex-col items-center">
      <h1 className="text-4xl md:text-5xl font-bold text-gray-800 mb-8 mt-4">Data Diri</h1>
      <div className="bg-white p-8 rounded-2xl shadow-lg w-full max-w-4xl">
        <form onSubmit={handleSave} className="space-y-6">
          {error && <div className="bg-red-100 text-red-700 p-3 rounded-lg text-center">⚠️ {error}</div>}
          {successMessage && <div className="bg-green-100 text-green-700 p-3 rounded-lg text-center">✅ {successMessage}</div>}
          
          <div>
            <label htmlFor="full_name" className="block text-base font-medium mb-2 text-gray-700">Nama Lengkap<span className="text-red-500">*</span></label>
            <input type="text" id="full_name" name="full_name" value={formData.full_name} onChange={handleChange} disabled={!isEditing} required 
                   className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"/>
          </div>
          <div>
            <label htmlFor="birth_date" className="block text-base font-medium mb-2 text-gray-700">Tanggal Lahir<span className="text-red-500">*</span></label>
            <input type="date" id="birth_date" name="birth_date" value={formData.birth_date} onChange={handleChange} disabled={!isEditing} required 
                   className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"/>
          </div>
          <div>
            <label htmlFor="age" className="block text-base font-medium mb-2 text-gray-700">Usia</label>
            <input type="number" id="age" name="age" value={formData.age} onChange={handleChange} disabled={!isEditing} 
                   className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"/>
          </div>
          <div>
            <label htmlFor="gender" className="block text-base font-medium mb-2 text-gray-700">Jenis Kelamin<span className="text-red-500">*</span></label>
            <select id="gender" name="gender" value={formData.gender} onChange={handleChange} disabled={!isEditing} required 
                    className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100">
              <option value="" disabled>Pilih Jenis Kelamin</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
          <div>
            <label htmlFor="phone" className="block text-base font-medium mb-2 text-gray-700">Nomor Telepon<span className="text-red-500">*</span></label>
            <input type="tel" id="phone" name="phone" value={formData.phone} onChange={handleChange} disabled={!isEditing} required placeholder="Contoh: 081234567890" 
                   className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"/>
          </div>
          <div>
            <label htmlFor="address" className="block text-base font-medium mb-2 text-gray-700">Alamat<span className="text-red-500">*</span></label>
            <input type="text" id="address" name="address" value={formData.address} onChange={handleChange} disabled={!isEditing} required 
                   className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"/>
          </div>
          <div>
            <label htmlFor="disease_histories" className="block text-base font-medium mb-2 text-gray-700">Riwayat Kesehatan Singkat</label>
            <textarea id="disease_histories" name="disease_histories" rows="8" value={formData.disease_histories} onChange={handleChange} disabled={!isEditing} 
                      className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y disabled:bg-gray-100"></textarea>
          </div>
          
          {isEditing && (
            <button type="submit" className="w-full py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out mt-6">Simpan</button>
          )}
        </form>
        {!isEditing && (
          <div className="flex flex-col md:flex-row gap-4 mt-6">
            <button type="button" onClick={handleEdit} className="w-full md:w-1/2 py-3 px-4 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition duration-300 ease-in-out">Edit Data</button>
            <button type="button" onClick={handleViewMedicalRecords} className="w-full md:w-1/2 py-3 px-4 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition duration-300 ease-in-out" disabled={!patientId}>Lihat Rekam Medis</button>
          </div>
        )}
      </div>
    </div>
  );
};

export default Profil;
