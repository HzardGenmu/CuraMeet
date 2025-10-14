import React, { useState, useEffect } from 'react';
import { doctorService } from '../../../services/doctorService';
import '../../JanjiTemu/JanjiTemu.css'; // Menggunakan CSS yang sama
import { IoTrash } from 'react-icons/io5'; // Untuk ikon hapus

// Impor modal-modal yang sudah ada
import Modal from '../../../components/Modal/Modal';
import AppointmentFormModal from '../../../components/AppointmentFormModal/AppointmentFormModal';
import ConfirmationModal from '../../../components/ConfirmationModal/ConfirmationModal'; // Untuk konfirmasi hapus

// --- DUMMY DATA ---
// Anggap ID Dokter yang sedang login adalah 'DR001'
// --- KONSTAN SEMENTARA ---
// TODO: Ambil dari context/auth user login
const CURRENT_DOCTOR_ID = 'DR001';
const CURRENT_DOCTOR_NAME = 'Dr. Budi Santoso'; // Nama dokter yang login

// Dummy doctors & rooms hanya jika API belum ada
const dummyDoctors = [
  { id: 'DR001', nama: 'Dr. Budi Santoso', spesialis: 'Jantung' },
];
const dummyRooms = [
  { id: 1, nama: 'Ruang A.6' },
];

const DoctorJanjiTemu = () => {
  // Filter janji temu agar hanya yang relevan dengan dokter yang login
    const [appointments, setAppointments] = useState([]);
  const [doctors, setDoctors] = useState([]); // Akan di-fetch dari API
  const [rooms, setRooms] = useState(dummyRooms); // Tetap dummy, belum ada endpoint
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);

    const [isListModalOpen, setIsListModalOpen] = useState(false);
    const [isEditFormModalOpen, setIsEditFormModalOpen] = useState(false);
    const [currentAppointment, setCurrentAppointment] = useState(null);
    const [isDeleteConfirmModalOpen, setIsDeleteConfirmModalOpen] = useState(false);
    const [appointmentToDelete, setAppointmentToDelete] = useState(null); 

  // --- Fungsi untuk Modal Daftar Janji Temu ---
  useEffect(() => {
    const fetchAppointments = async () => {
      setIsLoading(true);
      setError(null);
      try {
        const response = await doctorService.getAppointments
          ? await doctorService.getAppointments(CURRENT_DOCTOR_ID)
          : await doctorService.getMedicalRecords(CURRENT_DOCTOR_ID); // fallback
        if (response.success && response.appointments) {
          setAppointments(response.appointments);
        } else if (response.success && response.records) {
          setAppointments(response.records);
        } else {
          setAppointments([]);
        }
      } catch (err) {
        setError('Gagal memuat data janji temu.');
        console.error(err);
      } finally {
        setIsLoading(false);
      }
    };

    // Fetch daftar dokter dari API (sementara pakai endpoint pasien/search)
    const fetchDoctors = async () => {
      try {
        const res = await fetch('http://localhost:9000/api/patient/search?name=');
        const data = await res.json();
        if (Array.isArray(data)) {
          setDoctors(data);
        } else if (Array.isArray(data.patients)) {
          setDoctors(data.patients);
        }
      } catch (err) {
        // Biarkan kosong jika gagal
        setDoctors([]);
      }
    };

    fetchAppointments();
    fetchDoctors();
    // rooms tetap dummy, jika ada endpoint tinggal tambahkan fetchRooms()
  }, []);

    // --- Fungsi untuk Modal --- (fungsi buka/tutup tidak berubah)
    const handleOpenListModal = () => setIsListModalOpen(true);
    const handleCloseListModal = () => setIsListModalOpen(false);
    const handleOpenEditForm = (appointment) => {
        setCurrentAppointment(appointment);
        setIsEditFormModalOpen(true);
    };
    const handleCloseEditFormModal = () => {
        setIsEditFormModalOpen(false);
        setCurrentAppointment(null);
    };

    // 2. Fungsi untuk menyimpan perubahan ke API
    const handleSaveChanges = async (formData) => {
      try {
        const newTime = `${formData.tanggal} ${formData.jam}`;
        const response = await doctorService.updateAppointmentSchedule(
          formData.id,
          newTime,
          CURRENT_DOCTOR_ID
        );
        if (response.success) {
          setAppointments(prevApps => prevApps.map(app =>
            app.id === formData.id ? { ...app, ...formData } : app
          ));
          alert("Perubahan berhasil disimpan!");
          handleCloseEditFormModal();
        } else {
          alert("Gagal menyimpan perubahan.");
        }
      } catch (err) {
        alert("Terjadi kesalahan saat menyimpan perubahan.");
        console.error(err);
      }
    };

    // --- Fungsi untuk Hapus/Batal Janji Temu ---
    const handleDeleteClick = (appointmentId, e) => {
        e.stopPropagation();
        setAppointmentToDelete(appointmentId);
        setIsDeleteConfirmModalOpen(true);
    };
    
    // 3. Fungsi untuk membatalkan janji temu ke API
    const confirmDelete = async () => {
      if (!appointmentToDelete) return;
      try {
        const response = await doctorService.cancelSchedule(
          appointmentToDelete,
          'Dibatalkan oleh dokter',
          CURRENT_DOCTOR_ID
        );
        if (response.success) {
          setAppointments(prevApps => prevApps.filter(app => app.id !== appointmentToDelete));
          alert("Janji temu berhasil dibatalkan!");
        } else {
          alert("Gagal membatalkan janji temu.");
        }
      } catch (err) {
        alert("Terjadi kesalahan saat membatalkan janji temu.");
        console.error(err);
      } finally {
        setIsDeleteConfirmModalOpen(false);
        setAppointmentToDelete(null);
      }
    };

    const cancelDelete = () => {
        setIsDeleteConfirmModalOpen(false);
        setAppointmentToDelete(null);
    };

    // Tampilan loading dan error
    if (isLoading) return <p>Loading...</p>;
    if (error) return <p style={{ color: 'red' }}>{error}</p>;

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
          doctors={doctors.length > 0 ? doctors.filter(d => d.id === CURRENT_DOCTOR_ID) : dummyDoctors}
          rooms={rooms}
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