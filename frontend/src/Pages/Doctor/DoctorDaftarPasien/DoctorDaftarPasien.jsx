import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

import { doctorService } from '../../../services/doctorService';
import { authService } from '../../../services/authService';

// Ambil ID dokter dari auth (atau context)
const CURRENT_DOCTOR_ID = authService.getCurrentUser()?.id || 'DR001';

const DoctorDaftarPasien = () => {
  const navigate = useNavigate();

  const [myPatients, setMyPatients] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchPatients = async () => {
      setLoading(true);
      setError(null);
      try {
        // Ambil janji temu dokter dari backend
        const response = await doctorService.getAppointments(CURRENT_DOCTOR_ID);
        if (response.success && Array.isArray(response.appointments)) {
          // Map janji temu ke data pasien unik
          const patientsMap = {};
          response.appointments.forEach(app => {
            if (app.pasien_id && !patientsMap[app.pasien_id]) {
              patientsMap[app.pasien_id] = {
                id: app.pasien_id,
                nama: app.pasien,
                foto: '', // Tambahkan jika backend support foto
                deskripsiSingkat: app.catatan || '',
                alamat: app.alamat || '',
                tanggalLahir: app.tanggal_lahir || '',
                jenisKelamin: app.jenis_kelamin || '',
                telepon: app.telepon || '',
                email: app.email || '',
              };
            }
          });
          setMyPatients(Object.values(patientsMap));
        } else {
          setMyPatients([]);
        }
      } catch (err) {
        setError('Gagal memuat data pasien.');
      } finally {
        setLoading(false);
      }
    };
    fetchPatients();
  }, []);

  const handlePatientCardClick = (patientId) => {
    navigate(`/dokter/pasien/${patientId}`);
  };

  if (loading) return <div className="p-8 text-center text-gray-600 text-lg">Loading...</div>;
  if (error) return <div className="p-8 text-center text-red-600 text-lg">{error}</div>;

  return (
    <div className="p-8 bg-gray-50 min-h-screen">
      <h1 className="text-3xl font-semibold mb-2 text-gray-800">Daftar Pasien Anda</h1>
      <p className="text-lg text-gray-600 mb-8">Anda memiliki {myPatients.length} pasien yang pernah berjanji temu.</p>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {myPatients.length > 0 ? (
          myPatients.map(patient => (
            <div
              key={patient.id}
              className="bg-white rounded-xl shadow-md p-5 flex items-center cursor-pointer
                         hover:transform hover:translate-y-[-5px] hover:shadow-lg transition duration-200 ease-in-out"
              onClick={() => handlePatientCardClick(patient.id)}
            >
              <img
                src={patient.foto || 'https://via.placeholder.com/150/007bff/ffffff?text=PS'}
                alt={patient.nama}
                className="w-16 h-16 rounded-full object-cover mr-4 border-2 border-emerald-600 flex-shrink-0"
              />
              <div className="flex-grow">
                <h3 className="text-xl font-semibold text-gray-800 mb-1">{patient.nama}</h3>
                <p className="text-gray-600 text-sm italic line-clamp-2">{patient.deskripsiSingkat || 'Tidak ada deskripsi singkat.'}</p>
              </div>
            </div>
          ))
        ) : (
          <p className="col-span-full text-center text-gray-600 italic p-8 bg-gray-100 rounded-lg shadow-inner">
            Anda belum memiliki pasien yang tercatat.
          </p>
        )}
      </div>
    </div>
  );
};

export default DoctorDaftarPasien;