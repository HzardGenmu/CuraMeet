import React, { useState, useEffect, useCallback } from "react";
import { doctorService } from "../../../services/doctorService";
import { appointmentService } from "../../../services/appointmentService";
import {
  IoTrash,
  IoCheckmarkCircle,
  IoCloseCircle,
  IoCreate,
} from "react-icons/io5";
import Modal from "../../../components/Modal/Modal";
import AppointmentFormModal from "../../../components/AppointmentFormModal/AppointmentFormModal";
import ConfirmationModal from "../../../components/ConfirmationModal/ConfirmationModal";

const DoctorJanjiTemu = () => {
  const [doctorNowData, setDoctorNowData] = useState(null);
  const [appointments, setAppointments] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);
  const [statusFilter, setStatusFilter] = useState("all");

  const [isListModalOpen, setIsListModalOpen] = useState(false);
  const [isScheduleModalOpen, setIsScheduleModalOpen] = useState(false);
  const [currentAppointment, setCurrentAppointment] = useState(null);
  const [cancelReason, setCancelReason] = useState("");
  const [isDeleteConfirmModalOpen, setIsDeleteConfirmModalOpen] =
    useState(false);
  const [appointmentToDelete, setAppointmentToDelete] = useState(null);
  const [isStatusConfirmModalOpen, setIsStatusConfirmModalOpen] =
    useState(false);
  const [appointmentToUpdateStatus, setAppointmentToUpdateStatus] =
    useState(null);
  const [actionType, setActionType] = useState(""); // 'confirm', 'complete', 'cancel'
  const [newScheduleTime, setNewScheduleTime] = useState("");

  // Constants
  const STATUS_ORDER = ["pending", "confirmed", "completed", "cancelled"];

  // --- Fetch doctor profile ---
  useEffect(() => {
    const fetchDoctorProfile = async () => {
      try {
        const res = await doctorService.getProfile();
        setDoctorNowData(res.data.doctor);
      } catch (err) {
        console.error("Failed to fetch doctor profile:", err);
        setError("Gagal memuat profil dokter.");
      }
    };
    fetchDoctorProfile();
  }, []);

  // --- Fetch appointments ---
  const fetchAppointments = useCallback(async () => {
    if (!doctorNowData) return;

    setIsLoading(true);
    setError(null);
    try {
      const res = await appointmentService.getForDoctor(doctorNowData.id);
      setAppointments(res.appointments || []);
      // console.log(res.appointments);
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

  // --- Modal Handlers ---
  const handleOpenListModal = () => setIsListModalOpen(true);
  const handleCloseListModal = () => setIsListModalOpen(false);

  const handleOpenScheduleModal = (appointment) => {
    setCurrentAppointment(appointment);
    setNewScheduleTime(appointment.time_appointment || "");
    setIsScheduleModalOpen(true);
  };

  const handleCloseScheduleModal = () => {
    setIsScheduleModalOpen(false);
    setCurrentAppointment(null);
    setNewScheduleTime("");
  };

  const handleCloseCancelModal = () => {
    setIsDeleteConfirmModalOpen(false);
    setAppointmentToDelete(null);
    setCancelReason("");
  };

  // --- Change Schedule ---
  const handleSaveSchedule = async () => {
    if (!currentAppointment || !newScheduleTime) {
      alert("Mohon pilih waktu janji temu yang baru.");
      return;
    }

    try {
      await appointmentService.changeScheduleByDoctor(
        currentAppointment.id,
        doctorNowData.id,
        newScheduleTime
      );
      alert("Jadwal janji temu berhasil diubah!");
      fetchAppointments();
      handleCloseScheduleModal();
    } catch (err) {
      alert("Terjadi kesalahan saat mengubah jadwal.");
      console.error(err);
    }
  };

  // --- Delete/Cancel Appointment ---
  const handleDeleteClick = (appointment, e) => {
    e.stopPropagation();
    setAppointmentToDelete(appointment);
    setIsDeleteConfirmModalOpen(true);
  };

  const confirmDelete = async () => {
    if (!appointmentToDelete) return;

    if (!cancelReason.trim()) {
      alert("Mohon isi alasan pembatalan.");
      return;
    }

    try {
      await appointmentService.cancelByDoctor(
        appointmentToDelete.id,
        doctorNowData.id,
        cancelReason
      );
      alert("Janji temu berhasil dibatalkan!");
      fetchAppointments();
    } catch (err) {
      alert("Terjadi kesalahan saat membatalkan janji temu.");
      console.error(err);
    } finally {
      handleCloseCancelModal();
    }
  };

  const cancelDelete = () => {
    handleCloseCancelModal();
  };

  // --- Change Appointment Status ---
  const handleStatusActionClick = (appointment, action) => {
    setAppointmentToUpdateStatus(appointment);
    setActionType(action);
    setIsStatusConfirmModalOpen(true);
  };

  const confirmStatusAction = async () => {
    if (!appointmentToUpdateStatus || !actionType) return;

    try {
      switch (actionType) {
        case "confirm":
          await appointmentService.confirmByDoctor(
            appointmentToUpdateStatus.id,
            doctorNowData.id
          );
          alert("Janji temu berhasil dikonfirmasi!");
          break;
        case "complete":
          await appointmentService.completeByDoctor(
            appointmentToUpdateStatus.id,
            doctorNowData.id
          );
          alert("Janji temu berhasil diselesaikan!");
          break;
        case "cancel":
          await appointmentService.cancelByDoctor(
            appointmentToUpdateStatus.id,
            doctorNowData.id,
            "Ditolak oleh dokter"
          );
          alert("Janji temu berhasil ditolak!");
          break;
        default:
          break;
      }
      fetchAppointments();
    } catch (err) {
      alert(`Gagal melakukan aksi: ${actionType}`);
      console.error(err);
    } finally {
      setIsStatusConfirmModalOpen(false);
      setAppointmentToUpdateStatus(null);
      setActionType("");
    }
  };

  const cancelStatusAction = () => {
    setIsStatusConfirmModalOpen(false);
    setAppointmentToUpdateStatus(null);
    setActionType("");
  };

  // --- Utility Functions ---
  const getStatusBadgeClass = (status) => {
    const statusLower = status?.toLowerCase();
    switch (statusLower) {
      case "pending":
        return "bg-yellow-100 text-yellow-800";
      case "confirmed":
        return "bg-green-100 text-green-800";
      case "cancelled":
        return "bg-red-100 text-red-800";
      case "completed":
        return "bg-blue-100 text-blue-800";
      default:
        return "bg-gray-100 text-gray-800";
    }
  };

  const getActionLabel = (action) => {
    switch (action) {
      case "confirm":
        return "Konfirmasi";
      case "complete":
        return "Selesaikan";
      case "cancel":
        return "Tolak";
      default:
        return "";
    }
  };

  const sortByStatus = (a, b) => {
    const aIdx = STATUS_ORDER.indexOf(a.status?.toLowerCase());
    const bIdx = STATUS_ORDER.indexOf(b.status?.toLowerCase());
    return aIdx - bIdx;
  };

  const formatDateTime = (dateTime) => {
    if (!dateTime) return "-";
    const date = new Date(dateTime);
    const dateStr = date.toLocaleDateString("id-ID", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
    const timeStr = date.toLocaleTimeString("id-ID", {
      hour: "2-digit",
      minute: "2-digit",
    });
    return `${dateStr} - ${timeStr}`;
  };

  // --- Loading / Error States ---
  if (isLoading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
          <p className="text-gray-700 text-lg">Memuat data...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="bg-red-50 border border-red-200 rounded-lg p-6 max-w-md">
          <p className="text-red-600 text-lg font-semibold mb-2">
            Terjadi Kesalahan
          </p>
          <p className="text-red-700">{error}</p>
        </div>
      </div>
    );
  }

  if (!doctorNowData) {
    return <p className="p-8 text-gray-700 text-lg">Memuat data dokter...</p>;
  }

  // --- Data Processing ---
  const CURRENT_DOCTOR_NAME = doctorNowData.full_name || "Tanpa Nama";

  const relevantAppointments = appointments
    .slice()
    .sort(sortByStatus)
    .filter((app) => {
      const status = app.status?.toLowerCase();
      if (statusFilter === "all") {
        return status === "pending" || status === "confirmed";
      }
      return status === statusFilter;
    });

  const upcomingAppointmentsDisplay = relevantAppointments;

  // --- Render ---
  return (
    <>
      <div className="p-8 bg-gray-50 min-h-screen">
        <h1 className="text-3xl font-bold mb-8 text-gray-800">
          Janji Temu Dokter {CURRENT_DOCTOR_NAME}
        </h1>

        <div className="mb-6">
          <label className="mr-2 font-medium text-gray-700">
            Filter Status:
          </label>
          <select
            value={statusFilter}
            onChange={(e) => setStatusFilter(e.target.value)}
            className="px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200"
          >
            <option value="all">Semua (Pending & Confirmed)</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Welcome Card */}
          <div className="lg:col-span-2 bg-gradient-to-br from-blue-500 to-blue-700 p-8 rounded-xl shadow-lg text-white">
            <h2 className="text-2xl font-bold mb-2">
              Selamat Datang, Dr. {CURRENT_DOCTOR_NAME}!
            </h2>
            <p className="text-blue-100 mb-2">
              {new Date().toLocaleDateString("id-ID", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
              })}
            </p>
            <p className="text-white mb-6 text-lg">
              Anda memiliki{" "}
              <span className="font-bold text-2xl">
                {relevantAppointments.length}
              </span>{" "}
              janji temu mendatang.
            </p>
            <button
              className="px-6 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition duration-300 ease-in-out shadow-md"
              onClick={handleOpenListModal}
            >
              Kelola Semua Janji Temu
            </button>
          </div>

          {/* Statistics Card */}
          <div className="bg-white p-6 rounded-xl shadow-md">
            <h3 className="text-lg font-semibold text-gray-800 mb-4">
              Statistik Hari Ini
            </h3>
            <div className="space-y-3">
              <div className="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                <span className="text-gray-700">Pending</span>
                <span className="text-xl font-bold text-yellow-700">
                  {
                    appointments.filter(
                      (a) => a.status?.toLowerCase() === "pending"
                    ).length
                  }
                </span>
              </div>
              <div className="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                <span className="text-gray-700">Confirmed</span>
                <span className="text-xl font-bold text-green-700">
                  {
                    appointments.filter(
                      (a) => a.status?.toLowerCase() === "confirmed"
                    ).length
                  }
                </span>
              </div>
              <div className="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                <span className="text-gray-700">Completed</span>
                <span className="text-xl font-bold text-blue-700">
                  {
                    appointments.filter(
                      (a) => a.status?.toLowerCase() === "completed"
                    ).length
                  }
                </span>
              </div>
            </div>
          </div>

          {/* Upcoming Appointments */}
          <div className="lg:col-span-3 bg-white p-8 rounded-xl shadow-md">
            <h2 className="text-2xl font-bold mb-6 text-gray-800">
              Janji Temu Mendatang
            </h2>
            <div className="space-y-4">
              {upcomingAppointmentsDisplay.length > 0 ? (
                upcomingAppointmentsDisplay.map((item) => (
                  <div
                    key={item.id}
                    className="flex flex-col lg:flex-row justify-between items-start lg:items-center p-6 border border-gray-200 rounded-lg hover:shadow-md transition duration-200"
                  >
                    <div className="flex-grow mb-4 lg:mb-0">
                      <div className="flex items-center gap-3 mb-2">
                        <p className="text-gray-800 font-bold text-xl">
                          {item.patient?.full_name || "-"}
                        </p>
                        <span
                          className={`px-3 py-1 rounded-full text-xs font-semibold ${getStatusBadgeClass(
                            item.status
                          )}`}
                        >
                          {item.status}
                          {item.status?.toUpperCase() === "CANCELLED" &&
                            ` by ${item.cancelled_by}`}
                        </span>
                      </div>
                      <p className="text-gray-600 text-base mb-1">
                        📅 {formatDateTime(item.time_appointment)}
                      </p>
                      <p className="text-gray-600 text-sm">
                        🏥 Ruang: {doctorNowData.polyclinic || "-"}
                      </p>
                      {/* XSS FIX */}
                      {item.patient_note && (
                        <p className="text-gray-500 text-sm mt-2 italic">
                          {"📝 " + item.patient_note}
                        </p>
                      )}
                    </div>
                    <div className="flex gap-2 flex-wrap">
                      {item.status?.toLowerCase() === "pending" && (
                        <>
                          <button
                            className="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition duration-200"
                            onClick={() =>
                              handleStatusActionClick(item, "confirm")
                            }
                            title="Konfirmasi Janji Temu"
                          >
                            <IoCheckmarkCircle size={20} />
                            Konfirmasi
                          </button>
                          {/* <button
                            className="flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition duration-200"
                            onClick={() =>
                              handleStatusActionClick(item, "cancel")
                            }
                            title="Tolak Janji Temu"
                          >
                            <IoCloseCircle size={20} />
                            Tolak
                          </button> */}
                        </>
                      )}
                      {item.status?.toLowerCase() === "confirmed" && (
                        <>
                          <button
                            className="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition duration-200"
                            onClick={() =>
                              handleStatusActionClick(item, "complete")
                            }
                            title="Tandai Selesai"
                          >
                            <IoCheckmarkCircle size={20} />
                            Selesai
                          </button>
                          <button
                            className="flex items-center gap-2 px-4 py-2 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition duration-200"
                            onClick={() => handleOpenScheduleModal(item)}
                            title="Ubah Jadwal"
                          >
                            <IoCreate size={20} />
                            Ubah Jadwal
                          </button>
                        </>
                      )}
                      {item.status?.toLowerCase() !== "cancelled" &&
                        item.status?.toLowerCase() !== "completed" && (
                          <button
                            className="flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition duration-200"
                            onClick={(e) => handleDeleteClick(item, e)}
                            title="Batalkan Janji Temu"
                          >
                            <IoTrash size={18} />
                            Batalkan
                          </button>
                        )}
                    </div>
                  </div>
                ))
              ) : (
                <div className="text-center py-12">
                  <p className="text-gray-400 text-lg italic">
                    Tidak ada janji temu mendatang.
                  </p>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Appointments List Modal */}
      <Modal
        show={isListModalOpen}
        onClose={handleCloseListModal}
        title="Kelola Semua Janji Temu"
      >
        <div className="grid grid-cols-1 gap-4 max-h-[70vh] overflow-y-auto pr-2">
          {appointments.length > 0 ? (
            appointments
              .slice()
              .sort(sortByStatus)
              .map((app) => {
                const statusLower = app.status?.toLowerCase();
                return (
                  <div
                    key={app.id}
                    className={`border rounded-lg p-5 transition duration-200 ease-in-out ${
                      statusLower === "pending"
                        ? "border-yellow-300 bg-yellow-50"
                        : statusLower === "confirmed"
                        ? "border-green-300 bg-green-50"
                        : statusLower === "completed"
                        ? "border-blue-300 bg-blue-50"
                        : "border-gray-200 bg-gray-50"
                    }`}
                  >
                    <div className="flex justify-between items-start">
                      <div className="flex-grow">
                        <div className="flex items-center gap-3 mb-2">
                          <p className="text-gray-800 text-lg font-bold">
                            {app.patient?.full_name || "-"}
                          </p>
                          <span
                            className={`px-3 py-1 rounded-full text-xs font-semibold ${getStatusBadgeClass(
                              app.status
                            )}`}
                          >
                            {app.status}
                          </span>
                        </div>
                        <p className="text-gray-700 text-base mb-1">
                          📅 {app.appointment_date} {app.appointment_time}
                        </p>
                        <p className="text-gray-600 text-sm">
                          🏥 Ruang: {app.room || "-"}
                        </p>
                        {app.patient_note && (
                          <p
                            className="text-gray-500 text-sm mt-2 italic"
                            //ganti biar ga xss
                          >
                            {"📝 " + app.patient_note}
                          </p>
                        )}
                      </div>
                      <div className="flex flex-col gap-2 ml-4">
                        {statusLower === "pending" && (
                          <>
                            <button
                              className="text-green-600 hover:text-green-800 p-2 rounded-full hover:bg-green-100 transition"
                              onClick={() =>
                                handleStatusActionClick(app, "confirm")
                              }
                              title="Konfirmasi"
                            >
                              <IoCheckmarkCircle size={24} />
                            </button>
                            <button
                              className="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition"
                              onClick={() =>
                                handleStatusActionClick(app, "cancel")
                              }
                              title="Tolak"
                            >
                              <IoCloseCircle size={24} />
                            </button>
                          </>
                        )}
                        {statusLower === "confirmed" && (
                          <>
                            <button
                              className="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition"
                              onClick={() =>
                                handleStatusActionClick(app, "complete")
                              }
                              title="Selesaikan"
                            >
                              <IoCheckmarkCircle size={24} />
                            </button>
                            <button
                              className="text-gray-600 hover:text-gray-800 p-2 rounded-full hover:bg-gray-100 transition"
                              onClick={() => handleOpenScheduleModal(app)}
                              title="Ubah Jadwal"
                            >
                              <IoCreate size={24} />
                            </button>
                          </>
                        )}
                        {(statusLower === "cancelled" ||
                          statusLower === "completed") && (
                          <button
                            className="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100 transition"
                            onClick={(e) => handleDeleteClick(app, e)}
                            title="Hapus"
                          >
                            <IoTrash size={20} />
                          </button>
                        )}
                      </div>
                    </div>
                  </div>
                );
              })
          ) : (
            <div className="text-center py-12">
              <p className="text-gray-400 text-lg italic">
                Tidak ada janji temu saat ini.
              </p>
            </div>
          )}
        </div>
      </Modal>

      {/* Schedule Change Modal */}
      <Modal
        show={isScheduleModalOpen}
        onClose={handleCloseScheduleModal}
        title="Ubah Jadwal Janji Temu"
      >
        <div className="space-y-4">
          <div>
            <p className="text-gray-700 mb-2">
              <strong>Pasien:</strong> {currentAppointment?.patient?.full_name}
            </p>
            <p className="text-gray-600 mb-4">
              <strong>Jadwal Saat Ini:</strong>{" "}
              {formatDateTime(currentAppointment?.time_appointment)}
            </p>
          </div>
          <div>
            <label className="block text-gray-700 font-medium mb-2">
              Pilih Waktu Baru
            </label>
            <input
              type="datetime-local"
              value={newScheduleTime}
              onChange={(e) => setNewScheduleTime(e.target.value)}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>
          <div className="flex gap-3 pt-4">
            <button
              onClick={handleSaveSchedule}
              className="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition"
            >
              Simpan Perubahan
            </button>
            <button
              onClick={handleCloseScheduleModal}
              className="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition"
            >
              Batal
            </button>
          </div>
        </div>
      </Modal>

      {/* Delete Confirmation Modal */}
      <Modal
        show={isDeleteConfirmModalOpen}
        onClose={handleCloseCancelModal}
        title="Batalkan Janji Temu"
      >
        <div className="space-y-4">
          <div>
            <p className="text-gray-700 mb-2">
              <strong>Pasien:</strong> {appointmentToDelete?.patient?.full_name}
            </p>
            <p className="text-gray-600 mb-4">
              <strong>Jadwal:</strong>{" "}
              {formatDateTime(appointmentToDelete?.time_appointment)}
            </p>
          </div>
          <div>
            <label className="block text-gray-700 font-medium mb-2">
              Alasan Pembatalan <span className="text-red-500">*</span>
            </label>
            <textarea
              value={cancelReason}
              onChange={(e) => setCancelReason(e.target.value)}
              placeholder="Masukkan alasan pembatalan janji temu..."
              rows={4}
              className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
            />
          </div>
          <div className="flex gap-3 pt-4">
            <button
              onClick={confirmDelete}
              className="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition"
            >
              Ya, Batalkan
            </button>
            <button
              onClick={handleCloseCancelModal}
              className="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition"
            >
              Batal
            </button>
          </div>
        </div>
      </Modal>

      {/* Status Action Confirmation Modal */}
      <ConfirmationModal
        show={isStatusConfirmModalOpen}
        title={`Konfirmasi ${getActionLabel(actionType)}`}
        message={`Apakah Anda yakin ingin ${getActionLabel(
          actionType
        ).toLowerCase()} janji temu dengan ${
          appointmentToUpdateStatus?.patient?.full_name || "-"
        }?`}
        onConfirm={confirmStatusAction}
        onCancel={cancelStatusAction}
      />
    </>
  );
};

export default DoctorJanjiTemu;
