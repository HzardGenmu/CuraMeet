import React, { useState } from 'react';
import '../../JanjiTemu/JanjiTemu.css'; // Menggunakan CSS yang sama
import { IoTrash } from 'react-icons/io5'; // Untuk ikon hapus

// Impor modal-modal yang sudah ada
import Modal from '../../../components/Modal/Modal';
import AppointmentFormModal from '../../../components/AppointmentFormModal/AppointmentFormModal';
import ConfirmationModal from '../../../components/ConfirmationModal/ConfirmationModal'; // Untuk konfirmasi hapus

// --- DUMMY DATA ---
// Anggap ID Dokter yang sedang login adalah 'DR001'
const CURRENT_DOCTOR_ID = 'DR001';
const CURRENT_DOCTOR_NAME = 'Dr. Budi Santoso'; // Nama dokter yang login

const allAppointments = [
  { id: 1, tanggal: '2025-10-31', jam: '20:30', dokterId: 'DR001', dokter: 'Dr. Budi Santoso', ruang: 'Ruang A.6', pasien: 'Pasien A' },
  { id: 2, tanggal: '2025-11-09', jam: '09:20', dokterId: 'DR002', dokter: 'Dr. Anisa Putri', ruang: 'Ruang B.8', pasien: 'Pasien B' },
  { id: 3, tanggal: '2025-11-15', jam: '14:00', dokterId: 'DR001', dokter: 'Dr. Budi Santoso', ruang: 'Ruang C.1', pasien: 'Pasien C' },
  { id: 4, tanggal: '2025-11-20', jam: '10:00', dokterId: 'DR001', dokter: 'Dr. Budi Santoso', ruang: 'Ruang A.6', pasien: 'Pasien D' },
  { id: 5, tanggal: '2025-11-22', jam: '11:00', dokterId: 'DR002', dokter: 'Dr. Anisa Putri', ruang: 'Ruang B.8', pasien: 'Pasien E' },
];

const dummyDoctors = [
  { id: 'DR001', nama: 'Dr. Budi Santoso', spesialis: 'Jantung' },
  { id: 'DR002', nama: 'Dr. Anisa Putri', spesialis: 'Anak' },
  { id: 'DR003', nama: 'Dr. Candra Wijaya', spesialis: 'Gigi' },
];

const dummyRooms = [
  { id: 1, nama: 'Ruang A.6' },
  { id: 2, nama: 'Ruang B.8' },
  { id: 3, nama: 'Ruang C.1' },
];

const DoctorJanjiTemu = () => {
  // Filter janji temu agar hanya yang relevan dengan dokter yang login
  const [appointments, setAppointments] = useState(
    allAppointments.filter(app => app.dokterId === CURRENT_DOCTOR_ID)
  );

  const [isListModalOpen, setIsListModalOpen] = useState(false); // Modal untuk "Lihat Semua" (daftar)
  const [isEditFormModalOpen, setIsEditFormModalOpen] = useState(false); // Modal untuk form edit
  const [currentAppointment, setCurrentAppointment] = useState(null); // Janji temu yang sedang diedit

  const [isDeleteConfirmModalOpen, setIsDeleteConfirmModalOpen] = useState(false); // Modal konfirmasi hapus
  const [appointmentToDelete, setAppointmentToDelete] = useState(null); // Janji temu yang akan dihapus

  // --- Fungsi untuk Modal Daftar Janji Temu ---
  const handleOpenListModal = () => {
    setIsListModalOpen(true);
  };

  const handleCloseListModal = () => {
    setIsListModalOpen(false);
  };

  // --- Fungsi untuk Modal Form Edit Janji Temu ---
  const handleOpenEditForm = (appointment) => {
    setCurrentAppointment(appointment);
    setIsEditFormModalOpen(true);
  };

  const handleCloseEditFormModal = () => {
    setIsEditFormModalOpen(false);
    setCurrentAppointment(null);
  };

  const handleSaveChanges = (formData) => {
    // Di aplikasi nyata, Anda akan mengirim ini ke server
    console.log("Menyimpan perubahan untuk:", formData);
    
    // Update state appointments (local dummy update)
    setAppointments(prevApps => prevApps.map(app => 
      app.id === formData.id ? { ...app, ...formData } : app
    ));

    alert("Perubahan berhasil disimpan!");
    handleCloseEditFormModal(); // Tutup modal edit setelah menyimpan
  };

  // --- Fungsi untuk Hapus/Batal Janji Temu ---
  const handleDeleteClick = (appointmentId, e) => {
    e.stopPropagation(); // Mencegah klik menyebar ke kartu (membuka modal edit)
    setAppointmentToDelete(appointmentId);
    setIsDeleteConfirmModalOpen(true);
  };

  const confirmDelete = () => {
    // Di aplikasi nyata, Anda akan mengirim permintaan DELETE ke server
    console.log("Menghapus janji temu dengan ID:", appointmentToDelete);
    setAppointments(prevApps => prevApps.filter(app => app.id !== appointmentToDelete));
    alert("Janji temu berhasil dibatalkan/dihapus!");
    setIsDeleteConfirmModalOpen(false);
    setAppointmentToDelete(null);
    handleCloseEditFormModal(); // Tutup modal edit jika yang dihapus sedang dibuka
  };

  const cancelDelete = () => {
    setIsDeleteConfirmModalOpen(false);
    setAppointmentToDelete(null);
  };

  // Tampilkan 2 janji temu terdekat di dashboard utama
  const upcomingAppointmentsDisplay = appointments.slice(0, 2);

  return (
    <>
      <div className="janji-temu-container">
        <h1 className="page-title">Janji Temu Dokter {CURRENT_DOCTOR_NAME}</h1>
        <div className="cards-grid">
          <div className="card welcome-card">
            <h2>Hello, Dokter {CURRENT_DOCTOR_NAME}!</h2>
            <p className="date-text">Jumat, 10 Oktober 2025</p>
            <p>Anda memiliki {appointments.length} janji temu mendatang.</p>
            <button className="btn-primary" onClick={handleOpenListModal}>
              Lihat & Edit Janji Temu
            </button>
          </div>

          <div className="card appointments-card">
            <h2>Janji Temu Mendatang</h2>
            <div className="appointments-list">
              {upcomingAppointmentsDisplay.length > 0 ? (
                upcomingAppointmentsDisplay.map((item) => (
                  <div key={item.id} className="appointment-item">
                    <p><strong>{item.tanggal}</strong> - {item.jam}</p>
                    <p>Pasien: {item.pasien}</p>
                    <p>Ruang: {item.ruang}</p>
                    <button 
                      className="delete-button" 
                      onClick={(e) => handleDeleteClick(item.id, e)}
                    >
                      <IoTrash size={18} />
                    </button>
                  </div>
                ))
              ) : (
                <p>Tidak ada janji temu mendatang.</p>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Modal 1: Daftar Semua Janji Temu untuk Diedit/Dihapus */}
      <Modal
        show={isListModalOpen}
        onClose={handleCloseListModal}
        title="Daftar Janji Temu Anda"
      >
        <div className="appointments-list-full">
          {appointments.length > 0 ? (
            appointments.map(app => (
              <div key={app.id} className="appointment-item-card"> {/* Gunakan card style */}
                <div className="appointment-item-info" onClick={() => handleOpenEditForm(app)}>
                  <p><strong>Pasien: {app.pasien}</strong></p>
                  <p>{app.tanggal} {app.jam}</p>
                  <p>Ruang: {app.ruang}</p>
                </div>
                <button
                  className="delete-button-card-doctor" // Gaya tombol hapus yang berbeda
                  onClick={(e) => handleDeleteClick(app.id, e)}
                >
                  <IoTrash size={20} />
                </button>
              </div>
            ))
          ) : (
            <p className="no-appointments-message">Anda tidak memiliki janji temu saat ini.</p>
          )}
        </div>
      </Modal>

      {/* Modal 2: Form Edit Janji Temu (Muncul di atas Modal 1) */}
      {currentAppointment && (
        <AppointmentFormModal
          show={isEditFormModalOpen}
          onClose={handleCloseEditFormModal}
          title="Edit Janji Temu"
          appointmentData={currentAppointment}
          doctors={dummyDoctors.filter(d => d.id === CURRENT_DOCTOR_ID)} // Hanya dokter yang login
          rooms={dummyRooms}
          onSave={handleSaveChanges}
          isDoctorView={true} // Memberi tahu modal bahwa ini untuk tampilan dokter
        />
      )}

      {/* Modal Konfirmasi Hapus */}
      <ConfirmationModal
        show={isDeleteConfirmModalOpen}
        title="Batalkan Janji Temu"
        message="Apakah Anda yakin ingin membatalkan janji temu ini? Tindakan ini tidak dapat diurungkan."
        onConfirm={confirmDelete}
        onCancel={cancelDelete}
      />
    </>
  );
};

export default DoctorJanjiTemu;