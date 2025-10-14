import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import './DoctorDaftarPasien.css';
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

  if (loading) return <div className="doctor-daftar-pasien-container"><p>Loading...</p></div>;
  if (error) return <div className="doctor-daftar-pasien-container"><p style={{color:'red'}}>{error}</p></div>;

  return (
    <div className="doctor-daftar-pasien-container">
      <h1 className="page-title">Daftar Pasien Anda</h1>
      <p className="patient-count">Anda memiliki {myPatients.length} pasien yang pernah berjanji temu.</p>

      <div className="patient-grid">
        {myPatients.length > 0 ? (
          myPatients.map(patient => (
            <div 
              key={patient.id} 
              className="patient-card"
              onClick={() => handlePatientCardClick(patient.id)}
            >
              <img src={patient.foto || 'https://via.placeholder.com/150/007bff/ffffff?text=PS'} alt={patient.nama} className="patient-photo" />
              <div className="patient-info">
                <h3 className="patient-name">{patient.nama}</h3>
                <p className="patient-description">{patient.deskripsiSingkat}</p>
              </div>
            </div>
          ))
        ) : (
          <p className="no-patients-message">Anda belum memiliki pasien yang tercatat.</p>
        )}
      </div>
    </div>
  );
};

export default DoctorDaftarPasien;