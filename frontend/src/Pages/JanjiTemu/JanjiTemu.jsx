import React, { useState, useEffect } from "react";
import { patientService } from "../../services/patientService";
import { authService } from "../../services/authService";
import "./JanjiTemu.css";
import { IoTrash, IoCalendar, IoTime, IoPerson, IoAdd } from "react-icons/io5";
import AppointmentFormModal from "../../components/AppointmentFormModal/AppointmentFormModal";

const JanjiTemu = () => {
  const [appointments, setAppointments] = useState([]);
  const [doctors, setDoctors] = useState([]);
  const [rooms, setRooms] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");
  const [isFormModalOpen, setIsFormModalOpen] = useState(false);

  // ✅ Get user info with proper error handling
  const getCurrentUser = () => {
    try {
      const userInfoRaw = localStorage.getItem("userInfo");
      return userInfoRaw ? JSON.parse(userInfoRaw) : null;
    } catch (error) {
      console.error("Error parsing user info:", error);
      return null;
    }
  };

  const userInfo = getCurrentUser();

  useEffect(() => {
    if (!authService.isAuthenticated() || !userInfo?.id) {
      setError("Anda belum login. Silakan login terlebih dahulu.");
      setLoading(false);
      return;
    }

    loadInitialData();
  }, []);

  const loadInitialData = async () => {
    setLoading(true);
    setError("");

    try {
      console.log("Loading data for patient ID:", userInfo.id);

      // ✅ Load patient data with appointments - sesuai backend route
      const patientResponse = await patientService.getPatientById(userInfo.id);
      console.log("Patient response:", patientResponse);

      if (patientResponse.success) {
        // Backend should return patient data with appointments array
        setAppointments(
          patientResponse.appointments ||
            patientResponse.data?.appointments ||
            []
        );
      } else {
        console.warn("Patient data not found, using empty appointments");
        setAppointments([]);
      }

      // ✅ Load doctors (dummy data for now)
      const doctorsResponse = await patientService.getDoctors();
      if (doctorsResponse.success) {
        setDoctors(doctorsResponse.doctors || []);
      }

      // ✅ Load rooms (dummy data for now)
      const roomsResponse = await patientService.getRooms();
      if (roomsResponse.success) {
        setRooms(roomsResponse.rooms || []);
      }
    } catch (error) {
      console.error("Error loading data:", error);
      setError(`Gagal memuat data: ${error.message}`);

      // Load dummy appointments for development
      setAppointments([
        {
          id: 1,
          appointment_time: "2025-10-15 09:00:00",
          doctor_name: "Dr. Ahmad Wijaya",
          room_name: "Ruang 101",
          status: "pending",
        },
      ]);
    } finally {
      setLoading(false);
    }
  };

  const handleScheduleAppointment = async (appointmentData) => {
    setError("");
    setSuccess("");
    setLoading(true);

    try {
      console.log("Creating appointment:", appointmentData);

      // ✅ Format data sesuai dengan backend expectation
      const newAppointment = await patientService.createAppointment({
        patientId: userInfo.id,
        doctorId: appointmentData.doctorId,
        appointmentTime: `${appointmentData.date} ${appointmentData.time}:00`, // Format: YYYY-MM-DD HH:MM:SS
        notes: appointmentData.notes || "",
      });

      console.log("Appointment creation response:", newAppointment);

      if (newAppointment.success) {
        setSuccess("Janji temu berhasil dibuat!");
        setIsFormModalOpen(false);
        await loadInitialData(); // Refresh appointments list
      } else {
        setError(newAppointment.message || "Gagal membuat janji temu");
      }
    } catch (error) {
      console.error("Error creating appointment:", error);
      setError("Terjadi kesalahan saat membuat janji temu");
    } finally {
      setLoading(false);
    }
  };

  const handleCancelAppointment = async (appointmentId) => {
    if (!confirm("Apakah Anda yakin ingin membatalkan janji temu ini?")) {
      return;
    }

    setError("");
    setSuccess("");

    try {
      console.log("Cancelling appointment ID:", appointmentId);

      // ✅ Sesuai dengan backend route: PUT /patient/batalkan-pengecekan/{appointmentId}
      const result = await patientService.cancelAppointment(
        appointmentId,
        "Dibatalkan oleh pasien"
      );

      console.log("Cancel appointment response:", result);

      if (result.success) {
        setSuccess("Janji temu berhasil dibatalkan");
        await loadInitialData(); // Refresh appointments list
      } else {
        setError(result.message || "Gagal membatalkan janji temu");
      }
    } catch (error) {
      console.error("Error cancelling appointment:", error);
      setError("Terjadi kesalahan saat membatalkan janji temu");
    }
  };

  // ✅ Format date for display
  const formatDateTime = (dateTimeString) => {
    try {
      const date = new Date(dateTimeString);
      const dateStr = date.toLocaleDateString("id-ID");
      const timeStr = date.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
      });
      return { date: dateStr, time: timeStr };
    } catch (error) {
      return { date: "Invalid Date", time: "Invalid Time" };
    }
  };

  // ✅ Get status badge class
  const getStatusClass = (status) => {
    switch (status?.toLowerCase()) {
      case "pending":
        return "status-pending";
      case "confirmed":
        return "status-confirmed";
      case "completed":
        return "status-completed";
      case "cancelled":
        return "status-cancelled";
      default:
        return "status-unknown";
    }
  };

  if (loading && appointments.length === 0) {
    return (
      <div className="janji-temu-container">
        <div className="loading-spinner">
          <div className="spinner"></div>
          <p>Memuat data janji temu...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="janji-temu-container">
      <div className="janji-temu-header">
        <h1>📅 Janji Temu Saya</h1>
        <button
          className="btn-primary"
          onClick={() => setIsFormModalOpen(true)}
          disabled={loading}
        >
          <IoAdd /> Buat Janji Temu Baru
        </button>
      </div>

      {error && <div className="alert alert-error">❌ {error}</div>}

      {success && <div className="alert alert-success">✅ {success}</div>}

      <div className="appointments-section">
        {appointments.length === 0 ? (
          <div className="no-appointments">
            <div className="empty-state">
              <IoCalendar size={64} />
              <h3>Belum Ada Janji Temu</h3>
              <p>Anda belum memiliki janji temu yang terjadwal</p>
              <button
                className="btn-primary"
                onClick={() => setIsFormModalOpen(true)}
              >
                <IoAdd /> Buat Janji Temu Pertama
              </button>
            </div>
          </div>
        ) : (
          <div className="appointments-grid">
            {appointments.map((appointment) => {
              const { date, time } = formatDateTime(
                appointment.appointment_time
              );

              return (
                <div key={appointment.id} className="appointment-card">
                  <div className="appointment-header">
                    <span
                      className={`status-badge ${getStatusClass(
                        appointment.status
                      )}`}
                    >
                      {appointment.status?.toUpperCase() || "UNKNOWN"}
                    </span>
                  </div>

                  <div className="appointment-body">
                    <div className="appointment-info">
                      <div className="info-item">
                        <IoCalendar className="icon" />
                        <span>{date}</span>
                      </div>
                      <div className="info-item">
                        <IoTime className="icon" />
                        <span>{time}</span>
                      </div>
                      <div className="info-item">
                        <IoPerson className="icon" />
                        <span>{appointment.doctor_name || "Doctor TBD"}</span>
                      </div>
                      <div className="info-item">
                        <span className="room-icon">🏥</span>
                        <span>{appointment.room_name || "Room TBD"}</span>
                      </div>
                    </div>
                  </div>

                  <div className="appointment-actions">
                    {appointment.status !== "cancelled" &&
                      appointment.status !== "completed" && (
                        <button
                          className="btn-danger-outline"
                          onClick={() =>
                            handleCancelAppointment(appointment.id)
                          }
                          disabled={loading}
                        >
                          <IoTrash /> Batalkan
                        </button>
                      )}
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </div>

      {/* Appointment Form Modal */}
      {isFormModalOpen && (
        <AppointmentFormModal
          isOpen={isFormModalOpen}
          onClose={() => setIsFormModalOpen(false)}
          onSubmit={handleScheduleAppointment}
          doctors={doctors}
          rooms={rooms}
        />
      )}
    </div>
  );
};

export default JanjiTemu;
