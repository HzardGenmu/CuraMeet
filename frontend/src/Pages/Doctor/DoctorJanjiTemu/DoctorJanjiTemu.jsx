import React, { useState, useEffect, useCallback } from "react";
import { doctorService } from "../../../services/doctorService";
import { appointmentService } from "../../../services/appointmentService";
import { IoTrash, IoCheckmarkCircle, IoCloseCircle } from "react-icons/io5";
import Modal from "../../../components/Modal/Modal";
import AppointmentFormModal from "../../../components/AppointmentFormModal/AppointmentFormModal";
import ConfirmationModal from "../../../components/ConfirmationModal/ConfirmationModal";

const DoctorJanjiTemu = () => {
  const [doctorNowData, setDoctorNowData] = useState(null);
  const [appointments, setAppointments] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);

  const [isListModalOpen, setIsListModalOpen] = useState(false);
  const [isEditFormModalOpen, setIsEditFormModalOpen] = useState(false);
  const [currentAppointment, setCurrentAppointment] = useState(null);
  const [isDeleteConfirmModalOpen, setIsDeleteConfirmModalOpen] =
    useState(false);
  const [appointmentToDelete, setAppointmentToDelete] = useState(null);
  const [isStatusConfirmModalOpen, setIsStatusConfirmModalOpen] =
    useState(false);
  const [appointmentToUpdateStatus, setAppointmentToUpdateStatus] =
    useState(null);
  const [newStatus, setNewStatus] = useState("");

  // --- Ambil profil dokter sekarang ---
  useEffect(() => {
    const fetchDoctorProfile = async () => {
      try {
        const res = await doctorService.getProfile(); // Sesuai service baru
        setDoctorNowData(res.data.doctor);
      } catch (err) {
        console.error("Failed to fetch doctor profile:", err);
        setError("Gagal memuat profil dokter.");
      }
    };
    fetchDoctorProfile();
  }, []);

  // --- Ambil daftar janji temu dokter ---
  const fetchAppointments = useCallback(async () => {
    if (!doctorNowData) return;

    setIsLoading(true);
    setError(null);
    try {
      const res = await appointmentService.getByDoctor(doctorNowData.id);
      setAppointments(res.data.appointments || []);
    } catch (err) {
      console.error("Error fetching appointments:", err);
      setError("Gagal memuat data janji temu.");
    } finally {
      setIsLoading(false);
    }
  }, [doctorNowData]);

  useEffect(() => {
    if (doctorNowData) {
      fetchAppointments();
    }
  }, [fetchAppointments, doctorNowData]);

  // --- Handler Modal ---
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

  // --- Update Appointment ---
  const handleSaveChanges = async (formData) => {
    try {
      await appointmentService.update(formData.id, {
        appointment_date: formData.tanggal,
        appointment_time: formData.jam,
        room: formData.ruang,
        notes: formData.catatan,
      });
      alert("Perubahan berhasil disimpan!");
      fetchAppointments();
      handleCloseEditFormModal();
    } catch (err) {
      alert("Terjadi kesalahan saat menyimpan perubahan.");
      console.error(err);
    }
  };

  // --- Delete Appointment ---
  const handleDeleteClick = (appointmentId, e) => {
    e.stopPropagation();
    setAppointmentToDelete(appointmentId);
    setIsDeleteConfirmModalOpen(true);
  };

  const confirmDelete = async () => {
    if (!appointmentToDelete) return;
    try {
      await appointmentService.delete(appointmentToDelete);
      alert("Janji temu berhasil dibatalkan!");
      fetchAppointments();
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

  // --- Ubah Status Appointment ---
  const handleStatusChangeClick = (appointment, status) => {
    setAppointmentToUpdateStatus(appointment);
    setNewStatus(status);
    setIsStatusConfirmModalOpen(true);
  };

  const confirmStatusChange = async () => {
    if (!appointmentToUpdateStatus || !newStatus) return;

    try {
      await appointmentService.updateStatus(appointmentToUpdateStatus.id, {
        status: newStatus,
      });

      alert(`Status janji temu diubah menjadi ${newStatus}!`);
      fetchAppointments();
    } catch (err) {
      alert(`Gagal mengubah status menjadi ${newStatus}.`);
      console.error(err);
    } finally {
      setIsStatusConfirmModalOpen(false);
      setAppointmentToUpdateStatus(null);
      setNewStatus("");
    }
  };

  const cancelStatusChange = () => {
    setIsStatusConfirmModalOpen(false);
    setAppointmentToUpdateStatus(null);
    setNewStatus("");
  };

  // --- Loading / Error State ---
  if (isLoading) {
    return <p className="p-8 text-gray-700 text-lg">Loading...</p>;
  }

  if (error) {
    return <p className="p-8 text-red-600 text-lg">{error}</p>;
  }

  if (!doctorNowData) {
    return <p className="p-8 text-gray-700 text-lg">Memuat data dokter...</p>;
  }

  const CURRENT_DOCTOR_NAME = doctorNowData.full_name || "Tanpa Nama";

  const relevantAppointments = appointments.filter(
    (app) => app.status === "Pending" || app.status === "Approved"
  );

  const upcomingAppointmentsDisplay = relevantAppointments.slice(0, 2);

  const getStatusBadgeClass = (status) => {
    switch (status) {
      case "Pending":
        return "bg-yellow-100 text-yellow-800";
      case "Approved":
        return "bg-green-100 text-green-800";
      case "Rejected":
        return "bg-red-100 text-red-800";
      default:
        return "bg-gray-100 text-gray-800";
    }
  };

  return (
    <>
      <div className="p-8">
        <h1 className="text-3xl font-semibold mb-8 text-gray-800">
          Janji Temu Dokter {CURRENT_DOCTOR_NAME}
        </h1>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {/* Kartu sambutan */}
          <div className="bg-white p-8 rounded-xl shadow-md">
            <h2 className="text-2xl font-bold mb-2 text-gray-800">
              Hello, Dokter {CURRENT_DOCTOR_NAME}!
            </h2>
            <p className="text-gray-600 mb-2">
              {new Date().toLocaleDateString("id-ID", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
              })}
            </p>
            <p className="text-gray-700 mb-4">
              Anda memiliki {relevantAppointments.length} janji temu mendatang
              (Pending/Approved).
            </p>
            <button
              className="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition duration-300 ease-in-out"
              onClick={handleOpenListModal}
            >
              Lihat & Kelola Janji Temu
            </button>
          </div>

          {/* Daftar Janji Temu Mendatang */}
          <div className="bg-white p-8 rounded-xl shadow-md">
            <h2 className="text-2xl font-bold mb-4 text-gray-800">
              Janji Temu Mendatang
            </h2>
            <div className="space-y-4">
              {upcomingAppointmentsDisplay.length > 0 ? (
                upcomingAppointmentsDisplay.map((item) => (
                  <div
                    key={item.id}
                    className="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 border-b border-gray-200 last:border-b-0"
                  >
                    <div className="flex-grow mb-2 sm:mb-0">
                      <p className="text-gray-800 font-semibold text-lg">
                        {item.appointment_date} - {item.appointment_time}
                      </p>
                      <p className="text-gray-700 text-base">
                        Pasien: {item.patient_name}
                      </p>
                      <p className="text-gray-600 text-sm">
                        Ruang: {item.room}
                      </p>
                      <span
                        className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(
                          item.status
                        )}`}
                      >
                        {item.status}
                      </span>
                    </div>
                    <div className="flex space-x-2">
                      {item.status === "Pending" && (
                        <>
                          <button
                            className="text-green-600 hover:text-green-800 transition duration-200 p-2 rounded-full hover:bg-green-50"
                            onClick={() =>
                              handleStatusChangeClick(item, "Approved")
                            }
                            title="Setujui Janji Temu"
                          >
                            <IoCheckmarkCircle size={24} />
                          </button>
                          <button
                            className="text-red-600 hover:text-red-800 transition duration-200 p-2 rounded-full hover:bg-red-50"
                            onClick={() =>
                              handleStatusChangeClick(item, "Rejected")
                            }
                            title="Tolak Janji Temu"
                          >
                            <IoCloseCircle size={24} />
                          </button>
                        </>
                      )}
                      {item.status !== "Rejected" && (
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
                <p className="text-gray-500 italic text-center">
                  Tidak ada janji temu mendatang.
                </p>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Modal daftar janji temu */}
      <Modal
        show={isListModalOpen}
        onClose={handleCloseListModal}
        title="Kelola Janji Temu Anda"
      >
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[60vh] overflow-y-auto pr-2">
          {appointments.length > 0 ? (
            appointments.map((app) => (
              <div
                key={app.id}
                className={`bg-white border rounded-lg p-4 flex justify-between items-center transition duration-200 ease-in-out ${
                  app.status === "Pending"
                    ? "border-yellow-300 hover:bg-yellow-50"
                    : app.status === "Approved"
                    ? "border-green-300 hover:bg-green-50"
                    : "border-gray-200 hover:bg-gray-50"
                }`}
              >
                <div
                  className="flex-grow cursor-pointer"
                  onClick={() => handleOpenEditForm(app)}
                >
                  <p className="m-0 text-gray-800 text-lg font-semibold">
                    <strong className="text-emerald-600">
                      Pasien: {app.patient_name}
                    </strong>
                  </p>
                  <p className="m-0 text-gray-700 text-base">
                    {app.appointment_date} {app.appointment_time}
                  </p>
                  <p className="m-0 text-gray-600 text-sm">Ruang: {app.room}</p>
                  <span
                    className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(
                      app.status
                    )}`}
                  >
                    {app.status}
                  </span>
                </div>
                <div className="flex flex-col space-y-2 ml-4">
                  {app.status === "Pending" && (
                    <>
                      <button
                        className="text-green-600 hover:text-green-800 transition duration-200 p-2 rounded-full hover:bg-green-50"
                        onClick={() => handleStatusChangeClick(app, "Approved")}
                        title="Setujui Janji Temu"
                      >
                        <IoCheckmarkCircle size={24} />
                      </button>
                      <button
                        className="text-red-600 hover:text-red-800 transition duration-200 p-2 rounded-full hover:bg-red-50"
                        onClick={() => handleStatusChangeClick(app, "Rejected")}
                        title="Tolak Janji Temu"
                      >
                        <IoCloseCircle size={24} />
                      </button>
                    </>
                  )}
                  {app.status !== "Rejected" && (
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

      {currentAppointment && (
        <AppointmentFormModal
          show={isEditFormModalOpen}
          onClose={handleCloseEditFormModal}
          title="Edit Janji Temu"
          appointmentData={currentAppointment}
          onSave={handleSaveChanges}
          isDoctorView={true}
          disablePatientEdit={true}
          showStatusField={true}
        />
      )}

      <ConfirmationModal
        show={isDeleteConfirmModalOpen}
        title="Batalkan Janji Temu"
        message="Apakah Anda yakin ingin membatalkan janji temu ini? Tindakan ini tidak dapat diurungkan."
        onConfirm={confirmDelete}
        onCancel={cancelDelete}
      />

      <ConfirmationModal
        show={isStatusConfirmModalOpen}
        title={`Konfirmasi ${
          newStatus === "Approved" ? "Persetujuan" : "Penolakan"
        }`}
        message={`Apakah Anda yakin ingin mengubah status janji temu pasien ${appointmentToUpdateStatus?.patient_name} menjadi ${newStatus}?`}
        onConfirm={confirmStatusChange}
        onCancel={cancelStatusChange}
      />
    </>
  );
};

export default DoctorJanjiTemu;
