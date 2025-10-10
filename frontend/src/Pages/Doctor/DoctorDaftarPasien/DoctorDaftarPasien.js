import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import './DoctorDaftarPasien.css';

// --- DUMMY DATA ---
const CURRENT_DOCTOR_ID = 'DR001'; 

const allPatients = [
  { 
    id: 'P001', 
    nama: 'Budi Hartono', 
    foto: 'https://via.placeholder.com/150/007bff/ffffff?text=BH', 
    deskripsiSingkat: 'Pasien aktif, riwayat alergi obat.',
    alamat: 'Jl. Melati No. 10, Jakarta',
    tanggalLahir: '1990-05-15',
    jenisKelamin: 'Laki-laki',
    telepon: '081234567890',
    email: 'budi.h@example.com',
    janjiTemuDokterIds: ['DR001', 'DR002'] // Dokter yang pernah menangani
  },
  { 
    id: 'P002', 
    nama: 'Siti Aminah', 
    foto: 'https://via.placeholder.com/150/28a745/ffffff?text=SA', 
    deskripsiSingkat: 'Memiliki riwayat diabetes tipe 2.',
    alamat: 'Jl. Kenanga No. 5, Bandung',
    tanggalLahir: '1978-11-20',
    jenisKelamin: 'Perempuan',
    telepon: '085678901234',
    email: 'siti.a@example.com',
    janjiTemuDokterIds: ['DR001'] // Dokter yang pernah menangani
  },
  { 
    id: 'P003', 
    nama: 'Rudi Santoso', 
    foto: 'https://via.placeholder.com/150/ffc107/333333?text=RS', 
    deskripsiSingkat: 'Pasien baru, keluhan nyeri sendi.',
    alamat: 'Jl. Anggrek No. 22, Surabaya',
    tanggalLahir: '1995-03-01',
    jenisKelamin: 'Laki-laki',
    telepon: '087812345678',
    email: 'rudi.s@example.com',
    janjiTemuDokterIds: ['DR003'] // Dokter yang pernah menangani
  },
];

const DoctorDaftarPasien = () => {
  const navigate = useNavigate();
  // Filter pasien yang pernah janji temu dengan dokter yang sedang login
  const [myPatients, setMyPatients] = useState([]);

  useEffect(() => {
    // Di sini Anda akan fetch data dari API jika ada database
    // Untuk dummy, kita filter langsung
    const filteredPatients = allPatients.filter(patient => 
      patient.janjiTemuDokterIds.includes(CURRENT_DOCTOR_ID)
    );
    setMyPatients(filteredPatients);
  }, []); // Hanya berjalan sekali saat komponen dimount

  const handlePatientCardClick = (patientId) => {
    navigate(`/dokter/pasien/${patientId}`);
  };

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
              <img src={patient.foto} alt={patient.nama} className="patient-photo" />
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