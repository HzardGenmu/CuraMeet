import React, { useState, useEffect, useCallback } from 'react';
import { doctorService } from '../../../services/doctorService';
import { IoTrash, IoCheckmarkCircle, IoCloseCircle } from 'react-icons/io5'; // Menambahkan ikon Checkmark dan Close
import Modal from '../../../components/Modal/Modal';
import AppointmentFormModal from '../../../components/AppointmentFormModal/AppointmentFormModal';
import ConfirmationModal from '../../../components/ConfirmationModal/ConfirmationModal';

// --- DUMMY DATA ---
const CURRENT_DOCTOR_ID = 'DR001';
const CURRENT_DOCTOR_NAME = 'Dr. Budi Santoso';

const dummyDoctors = [
  { id: 'DR001', nama: 'Dr. Budi Santoso', spesialis: 'Jantung' },
  { id: 'DR002', nama: 'Dr. Siti Aminah', spesialis: 'Gigi' },
];
const dummyRooms = [
  { id: 1, nama: 'Ruang A.6' },
  { id: 2, nama: 'Ruang B.1' },
  { id: 3, nama: 'Ruang C.3' },
];

// Data janji temu dummy
const initialDummyAppointments = [
  {
    id: 'APPT001',
    pasien: 'Andi Pratama',
    pasienId: 'P001',
    dokterId: 'DR001',
    tanggal: '2025-10-15',
    jam: '10:00',
    ruang: 'Ruang A.6',
    status: 'Pending', // New status field
    catatan: 'Nyeri dada sejak kemarin',
  },
  {
    id: 'APPT002',
    pasien: 'Budi Hartono',
    pasienId: 'P002',
    dokterId: 'DR001',
    tanggal: '2025-10-15',
    jam: '14:30',
    ruang: 'Ruang A.6',
    status: 'Approved',
    catatan: 'Kontrol rutin',
  },
  {
    id: 'APPT003',
    pasien: 'Citra Dewi',
    pasienId: 'P003',
    dokterId: 'DR002', // Ini untuk dokter lain, tidak akan muncul di Dr. Budi
    tanggal: '2025-10-16',
    jam: '09:00',
    ruang: 'Ruang B.1',
    status: 'Pending',
    catatan: 'Sakit kepala',
  },
  {
    id: 'APPT004',
    pasien: 'Dewi Lestari',
    pasienId: 'P004',
    dokterId: 'DR001',
    tanggal: '2025-10-17',
    jam: '11:00',
    ruang: 'Ruang C.3',
    status: 'Rejected',
    catatan: 'Demam tinggi',
  },
  {
    id: 'APPT005',
    pasien: 'Eko Rahardjo',
    pasienId: 'P005',
    dokterId: 'DR001',
    tanggal: '2025-10-18',
    jam: '08:00',
    ruang: 'Ruang A.6',
    status: 'Pending',
    catatan: 'Vaksinasi flu',
  },
];

const DoctorJanjiTemu = () => {
  const [appointments, setAppointments] = useState([]);
  const [doctors, setDoctors] = useState(dummyDoctors); // Menggunakan dummyDoctors
  const [rooms, setRooms] = useState(dummyRooms); // Menggunakan dummyRooms
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);

  const [isListModalOpen, setIsListModalOpen] = useState(false);
  const [isEditFormModalOpen, setIsEditFormModalOpen] = useState(false);
  const [currentAppointment, setCurrentAppointment] = useState(null);
  const [isDeleteConfirmModalOpen, setIsDeleteConfirmModalOpen] = useState(false);
  const [appointmentToDelete, setAppointmentToDelete] = useState(null);
  const [isStatusConfirmModalOpen, setIsStatusConfirmModalOpen] = useState(false);
  const [appointmentToUpdateStatus, setAppointmentToUpdateStatus] = useState(null);
  const [newStatus, setNewStatus] = useState('');

  // Fungsi untuk memuat janji temu, sekarang dari dummy
  const fetchAppointments = useCallback(async () => {
    setIsLoading(true);
    setError(null);
    try {
      // Filter appointments hanya untuk dokter yang sedang login
      const filteredAppointments = initialDummyAppointments.filter(
        (app) => app.dokterId === CURRENT_DOCTOR_ID
      );
      setAppointments(filteredAppointments);
    } catch (err) {
      setError('Gagal memuat data janji temu.');
      console.error(err);
    } finally {
      setIsLoading(false);
    }
  }, []); // Dependensi kosong karena data dummy statis

  useEffect(() => {
    fetchAppointments();
    // fetchDoctors() 
  }, [fetchAppointments]);

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

  const handleSaveChanges = async (formData) => {
    try {
      // Simulasikan API call untuk update
      // const response = await doctorService.updateAppointmentSchedule(formData.id, newTime, CURRENT_DOCTOR_ID);

      // Logika update untuk data dummy
      setAppointments((prevApps) =>
        prevApps.map((app) =>
          app.id === formData.id
            ? {
                ...app,
                tanggal: formData.tanggal,
                jam: formData.jam,
                ruang: formData.ruang, // Update ruang juga
                catatan: formData.catatan, // Update catatan juga
              }
            : app
        )
      );
      alert('Perubahan berhasil disimpan!');
      handleCloseEditFormModal();
    } catch (err) {
      alert('Terjadi kesalahan saat menyimpan perubahan.');
      console.error(err);
    }
  };

  const handleDeleteClick = (appointmentId, e) => {
    e.stopPropagation(); 
    setAppointmentToDelete(appointmentId);
    setIsDeleteConfirmModalOpen(true);
  };

  const confirmDelete = async () => {
    if (!appointmentToDelete) return;
    try {
      
      // const response = await doctorService.cancelSchedule(appointmentToDelete, 'Dibatalkan oleh dokter', CURRENT_DOCTOR_ID);

      // Logika delete untuk data dummy
      setAppointments((prevApps) =>
        prevApps.filter((app) => app.id !== appointmentToDelete)
      );
      alert('Janji temu berhasil dibatalkan!');
    } catch (err) {
      alert('Terjadi kesalahan saat membatalkan janji temu.');
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

  // --- Fungsi untuk Approve/Reject ---
  const handleStatusChangeClick = (appointment, status) => {
    setAppointmentToUpdateStatus(appointment);
    setNewStatus(status);
    setIsStatusConfirmModalOpen(true);
  };

  const confirmStatusChange = async () => {
    if (!appointmentToUpdateStatus || !newStatus) return;

    try {
      // Simulasikan API call untuk update status
      // const response = await doctorService.updateAppointmentStatus(appointmentToUpdateStatus.id, newStatus, CURRENT_DOCTOR_ID);

      setAppointments((prevApps) =>
        prevApps.map((app) =>
          app.id === appointmentToUpdateStatus.id ? { ...app, status: newStatus } : app
        )
      );
      alert(`Janji temu berhasil diubah status menjadi ${newStatus}!`);
    } catch (err) {
      alert(`Terjadi kesalahan saat mengubah status janji temu menjadi ${newStatus}.`);
      console.error(err);
    } finally {
      setIsStatusConfirmModalOpen(false);
      setAppointmentToUpdateStatus(null);
      setNewStatus('');
    }
  };

  const cancelStatusChange = () => {
    setIsStatusConfirmModalOpen(false);
    setAppointmentToUpdateStatus(null);
    setNewStatus('');
  };
  // --- End Fungsi Approve/Reject ---

  if (isLoading) return <p className="p-8 text-gray-700 text-lg">Loading...</p>;
  if (error) return <p className="p-8 text-red-600 text-lg">{error}</p>;

  // Filter hanya janji temu yang statusnya "Pending" atau "Approved"
  // agar "Rejected" tidak ditampilkan sebagai "mendatang"
  const relevantAppointments = appointments.filter(
    (app) => app.status === 'Pending' || app.status === 'Approved'
  );

  const upcomingAppointmentsDisplay = relevantAppointments.slice(0, 2);

  // Helper untuk mendapatkan kelas badge status
  const getStatusBadgeClass = (status) => {
    switch (status) {
      case 'Pending':
        return 'bg-yellow-100 text-yellow-800';
      case 'Approved':
        return 'bg-green-100 text-green-800';
      case 'Rejected':
        return 'bg-red-100 text-red-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  return (
    <>
      <div className="p-8">
        <h1 className="text-3xl font-semibold mb-8 text-gray-800">
          Janji Temu Dokter {CURRENT_DOCTOR_NAME}
        </h1>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {/* .card .welcome-card */}
          <div className="bg-white p-8 rounded-xl shadow-md">
            <h2 className="text-2xl font-bold mb-2 text-gray-800">
              Hello, Dokter {CURRENT_DOCTOR_NAME}!
            </h2>
            <p className="text-gray-600 mb-2">Jumat, 10 Oktober 2025</p>
            <p className="text-gray-700 mb-4">
              Anda memiliki {relevantAppointments.length} janji temu mendatang (Pending/Approved).
            </p>
            <button
              className="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium
                         hover:bg-blue-700 transition duration-300 ease-in-out"
              onClick={handleOpenListModal}
            >
              Lihat & Kelola Janji Temu
            </button>
          </div>

          {/* .card .appointments-card */}
          <div className="bg-white p-8 rounded-xl shadow-md">
            <h2 className="text-2xl font-bold mb-4 text-gray-800">Janji Temu Mendatang</h2>
            <div className="space-y-4">
              {upcomingAppointmentsDisplay.length > 0 ? (
                upcomingAppointmentsDisplay.map((item) => (
                  <div
                    key={item.id}
                    className="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 border-b border-gray-200 last:border-b-0"
                  >
                    <div className="flex-grow mb-2 sm:mb-0">
                      <p className="text-gray-800 font-semibold text-lg">
                        {item.tanggal} - {item.jam}
                      </p>
                      <p className="text-gray-700 text-base">Pasien: {item.pasien}</p>
                      <p className="text-gray-600 text-sm">Ruang: {item.ruang}</p>
                      <span
                        className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(item.status)}`}
                      >
                        {item.status}
                      </span>
                    </div>
                    <div className="flex space-x-2">
                      {item.status === 'Pending' && (
                        <>
                          <button
                            className="text-green-600 hover:text-green-800 transition duration-200 p-2 rounded-full hover:bg-green-50"
                            onClick={() => handleStatusChangeClick(item, 'Approved')}
                            title="Setujui Janji Temu"
                          >
                            <IoCheckmarkCircle size={24} />
                          </button>
                          <button
                            className="text-red-600 hover:text-red-800 transition duration-200 p-2 rounded-full hover:bg-red-50"
                            onClick={() => handleStatusChangeClick(item, 'Rejected')}
                            title="Tolak Janji Temu"
                          >
                            <IoCloseCircle size={24} />
                          </button>
                        </>
                      )}
                      {item.status !== 'Rejected' && ( // Allow delete for Pending and Approved, but not Rejected (already managed)
                         <button
                         className="text-gray-500 hover:text-gray-700 transition duration-200 p-2 rounded-full hover:bg-gray-50"
                         onClick={(e) => handleDeleteClick(item.id, e)}
                         title="Batalkan Janji Temu"
                       >
                         <IoTrash size={20} />
                       </button>
                      )}

                    </div>
                  </div>
                ))
              ) : (
                <p className="text-gray-500 italic text-center">Tidak ada janji temu mendatang.</p>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Modal 1: Daftar Semua Janji Temu untuk Diedit/Dihapus/Ubah Status */}
      <Modal show={isListModalOpen} onClose={handleCloseListModal} title="Kelola Janji Temu Anda">
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[60vh] overflow-y-auto pr-2">
          {appointments.length > 0 ? (
            appointments.map((app) => (
              <div
                key={app.id}
                className={`bg-white border rounded-lg p-4 flex justify-between items-center transition duration-200 ease-in-out
                            ${app.status === 'Pending' ? 'border-yellow-300 hover:bg-yellow-50' :
                             app.status === 'Approved' ? 'border-green-300 hover:bg-green-50' :
                             'border-gray-200 hover:bg-gray-50'}`}
              >
                <div className="flex-grow cursor-pointer" onClick={() => handleOpenEditForm(app)}>
                  <p className="m-0 text-gray-800 text-lg font-semibold">
                    <strong className="text-emerald-600">Pasien: {app.pasien}</strong>
                  </p>
                  <p className="m-0 text-gray-700 text-base">
                    {app.tanggal} {app.jam}
                  </p>
                  <p className="m-0 text-gray-600 text-sm">Ruang: {app.ruang}</p>
                  <span
                    className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(app.status)}`}
                  >
                    {app.status}
                  </span>
                </div>
                <div className="flex flex-col space-y-2 ml-4">
                  {app.status === 'Pending' && (
                    <>
                      <button
                        className="text-green-600 hover:text-green-800 transition duration-200 p-2 rounded-full hover:bg-green-50"
                        onClick={() => handleStatusChangeClick(app, 'Approved')}
                        title="Setujui Janji Temu"
                      >
                        <IoCheckmarkCircle size={24} />
                      </button>
                      <button
                        className="text-red-600 hover:text-red-800 transition duration-200 p-2 rounded-full hover:bg-red-50"
                        onClick={() => handleStatusChangeClick(app, 'Rejected')}
                        title="Tolak Janji Temu"
                      >
                        <IoCloseCircle size={24} />
                      </button>
                    </>
                  )}
                  {app.status !== 'Rejected' && (
                    <button
                      className="text-gray-500 hover:text-gray-700 transition duration-200 p-2 rounded-full hover:bg-gray-50"
                      onClick={(e) => handleDeleteClick(app.id, e)}
                      title="Batalkan Janji Temu"
                    >
                      <IoTrash size={20} />
                    </button>
                  )}
                </div>
              </div>
            ))
          ) : (
            <p className="col-span-full text-center text-gray-500 italic text-lg p-5">
              Anda tidak memiliki janji temu saat ini.
            </p>
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
          doctors={doctors.filter((d) => d.id === CURRENT_DOCTOR_ID)} // Hanya dokter ini yang bisa diedit
          rooms={rooms}
          onSave={handleSaveChanges}
          isDoctorView={true} // Memberi tahu modal bahwa ini untuk tampilan dokter
          // Tambahkan prop untuk menonaktifkan pasien dari edit
          disablePatientEdit={true}
          // Tambahkan prop untuk hanya menampilkan status jika dibutuhkan di form
          showStatusField={true}
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

      {/* Modal Konfirmasi Ubah Status */}
      <ConfirmationModal
        show={isStatusConfirmModalOpen}
        title={`Konfirmasi ${newStatus === 'Approved' ? 'Persetujuan' : 'Penolakan'}`}
        message={`Apakah Anda yakin ingin mengubah status janji temu pasien ${appointmentToUpdateStatus?.pasien} menjadi ${newStatus}?`}
        onConfirm={confirmStatusChange}
        onCancel={cancelStatusChange}
      />
    </>
  );
};

export default DoctorJanjiTemu;