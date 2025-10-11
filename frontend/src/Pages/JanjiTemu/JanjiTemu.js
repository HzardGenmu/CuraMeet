import React, { useState } from 'react';
import './JanjiTemu.css';
import { IoTrash } from 'react-icons/io5';
import Modal from '../../components/Modal/Modal';
// 1. Import komponen modal form yang baru
import AppointmentFormModal from '../../components/AppointmentFormModal/AppointmentFormModal';

// ... (Dummy data allDummyAppointments tetap sama)
const allDummyAppointments = [
  { id: 1, hari: 'Selasa', tanggal: '31-08-25', waktu: '20:30', dokter: 'Dr. Budi Santoso', ruang: 'Ruang A.6' },
  { id: 2, hari: 'Minggu', tanggal: '09-09-25', waktu: '09:20', dokter: 'Dr. Anisa Putri', ruang: 'Ruang B.8' },
  { id: 3, hari: 'Rabu', tanggal: '15-09-25', waktu: '14:00', dokter: 'Dr. Candra Wijaya', ruang: 'Ruang C.1' },
  { id: 4, hari: 'Jumat', tanggal: '24-09-25', waktu: '11:15', dokter: 'Dr. Budi Santoso', ruang: 'Ruang A.6' },
  { id: 5, hari: 'Senin', tanggal: '29-09-25', waktu: '16:45', dokter: 'Dr. Dian Lestari', ruang: 'Ruang D.3' },
];
const upcomingAppointments = allDummyAppointments.slice(0, 2);

// 2. Buat Dummy Data untuk Dokter dan Ruangan
const dummyDoctors = [
  { id: 1, nama: 'Dr. Budi Santoso', spesialis: 'Jantung' },
  { id: 2, nama: 'Dr. Anisa Putri', spesialis: 'Anak' },
  { id: 3, nama: 'Dr. Candra Wijaya', spesialis: 'Mata' },
  { id: 4, nama: 'Dr. Dian Lestari', spesialis: 'Kulit' },
];

const dummyRooms = [
  { id: 1, nama: 'Ruang A.6' },
  { id: 2, nama: 'Ruang B.8' },
  { id: 3, nama: 'Ruang C.1' },
  { id: 4, nama: 'Ruang D.3' },
];


const JanjiTemu = () => {
  const [isListModalOpen, setIsListModalOpen] = useState(false);
  // 3. Buat state baru untuk modal form
  const [isFormModalOpen, setIsFormModalOpen] = useState(false);

  return (
    <>
      <div className="janji-temu-container">
        <h1 className="page-title">Janji Temu</h1>
        <div className="cards-grid">
          {/* Card Kiri */}
          <div className="card welcome-card">
            <h2>Hello, Patient!</h2>
            <p className="date-text">Jumat, 10 Oktober 2025</p>
            <p>Anda memiliki {allDummyAppointments.length} janji temu mendatang :</p>
            <p className="upcoming-date-highlight">Selasa, 31 Oktober 2025</p>
            {/* 4. Ganti onClick untuk membuka modal form */}
            <button className="btn-primary" onClick={() => setIsFormModalOpen(true)}>
              Buat Janji Temu baru
            </button>
          </div>

          {/* Card Kanan */}
          <div className="card appointments-card">
            <h2>Janji Temu Mendatang</h2>
            <div className="appointments-list">
              {upcomingAppointments.map((item) => (
                <div key={item.id} className="appointment-item">
                  <div className="appointment-details">
                    <p className="appointment-datetime">
                      <strong>{item.hari}, {item.tanggal}; {item.waktu}</strong>
                    </p>
                    <p className="appointment-info">{item.dokter}</p>
                    <p className="appointment-info">{item.ruang}</p>
                  </div>
                  <div className="appointment-action">
                    <IoTrash size={20} className="delete-icon" />
                  </div>
                </div>
              ))}
            </div>
            <button onClick={() => setIsListModalOpen(true)} className="view-all-link">
              Lihat Semua Janji Temu
            </button>
          </div>
        </div>
      </div>
      
      {/* Modal untuk menampilkan list janji temu */}
      <Modal 
        show={isListModalOpen} 
        onClose={() => setIsListModalOpen(false)}
        title="Semua Janji Temu"
        appointments={allDummyAppointments} 
      />

      {/* 5. Render Modal Form di sini */}
      <AppointmentFormModal 
        show={isFormModalOpen}
        onClose={() => setIsFormModalOpen(false)}
        doctors={dummyDoctors}
        rooms={dummyRooms}
      />
    </>
  );
};

export default JanjiTemu;