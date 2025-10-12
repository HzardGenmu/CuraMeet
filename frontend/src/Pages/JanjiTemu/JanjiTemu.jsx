import React, { useState, useEffect } from "react";
import { patientService } from "../../services/patientService";
import "./JanjiTemu.css";
import { IoTrash } from "react-icons/io5";
import AppointmentFormModal from "../../components/AppointmentFormModal/AppointmentFormModal";

const JanjiTemu = () => {
  const [appointments, setAppointments] = useState([]);
  const [doctors, setDoctors] = useState([]);
  const [rooms, setRooms] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [isFormModalOpen, setIsFormModalOpen] = useState(false);

  // Get current user info
  const userInfoRaw = localStorage.getItem("userInfo");
  const userInfo = userInfoRaw ? JSON.parse(userInfoRaw) : {};

  useEffect(() => {
    loadInitialData();
  }, []);

  const loadInitialData = async () => {
    setLoading(true);
    setError("");

    // Debug: cek userInfo
    console.log("User Info:", userInfo);

    if (!userInfo.id) {
      setError("Anda belum login. Silakan login terlebih dahulu.");
      setLoading(false);
      return;
    }

    try {
      // Load appointments for current patient
      console.log("Loading appointments for user:", userInfo.id);
      const appointmentsData = await patientService.getPatientById(userInfo.id);
      console.log("Appointments data:", appointmentsData);
      setAppointments(
        appointmentsData.appointments || appointmentsData.data || []
      );

      // Load doctors
      console.log("Loading doctors...");
      const doctorsData = await patientService.getDoctors();
      console.log("Doctors data:", doctorsData);
      setDoctors(doctorsData.doctors || doctorsData.data || []);

      // Load rooms
      console.log("Loading rooms...");
      const roomsData = await patientService.getRooms();
      console.log("Rooms data:", roomsData);
      setRooms(roomsData.rooms || roomsData.data || []);
    } catch (error) {
      console.error("Error loading data:", error);
      setError(`Gagal memuat data: ${error.message}`);
    } finally {
      setLoading(false);
    }
  };

  // Dummy data untuk testing jika API belum siap
  const loadDummyData = () => {
    setAppointments([
      {
        id: 1,
        date: "2024-10-15",
        appointment_time: "09:00",
        doctor_name: "Dr. Ahmad",
        room: "Ruang 101",
      },
    ]);
    setDoctors([
      { id: 1, name: "Dr. Ahmad", specialization: "Umum" },
      { id: 2, name: "Dr. Sari", specialization: "Gigi" },
    ]);
    setRooms([
      { id: 1, name: "Ruang 101" },
      { id: 2, name: "Ruang 102" },
    ]);
  };

  const handleScheduleAppointment = async (appointmentData) => {
    setError("");
    setLoading(true);
    try {
      const response = await patientService.registerCheckup({
        patient_id: userInfo.id,
        doctor_id: appointmentData.doctorId,
        appointment_time: appointmentData.appointmentTime,
        room_id: appointmentData.roomId,
      });

      if (response.success) {
        await loadInitialData();
        setIsFormModalOpen(false);
      } else {
        setError(response.message || "Gagal membuat janji temu.");
      }
    } catch (error) {
      console.error("Error scheduling appointment:", error);
      setError("Gagal membuat janji temu.");
    } finally {
      setLoading(false);
    }
  };

  const handleCancelAppointment = async (appointmentId, reason) => {
    setError("");
    setLoading(true);
    try {
      const response = await patientService.cancelCheckup(
        appointmentId,
        reason
      );

      if (response.success) {
        setAppointments(appointments.filter((app) => app.id !== appointmentId));
      } else {
        setError(response.message || "Gagal membatalkan janji temu.");
      }
    } catch (error) {
      console.error("Error canceling appointment:", error);
      setError("Gagal membatalkan janji temu.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="janji-temu-container">
      <h1 className="main-title">Janji Temu</h1>

      {/* Button untuk load dummy data - hapus setelah API siap */}
      <button
        onClick={loadDummyData}
        style={{
          marginBottom: "10px",
          backgroundColor: "#28a745",
          color: "white",
          padding: "8px 16px",
          border: "none",
          borderRadius: "4px",
        }}
      >
        Load Dummy Data (Testing)
      </button>

      <button
        className="schedule-button"
        onClick={() => setIsFormModalOpen(true)}
        disabled={loading || !userInfo.id}
      >
        Buat Janji Temu Baru
      </button>

      {loading && <div className="loading">Loading...</div>}
      {error && <div className="error-message">{error}</div>}

      <div className="appointments-list">
        {!loading && appointments.length === 0 && (
          <div className="empty-message">
            Belum ada janji temu.
            <br />
            Silakan klik <b>Buat Janji Temu Baru</b> untuk membuat janji temu.
          </div>
        )}
        {!loading &&
          appointments.length > 0 &&
          appointments.map((appointment) => (
            <div key={appointment.id} className="appointment-item">
              <p>
                <strong>
                  {appointment.date || appointment.appointment_time}
                </strong>
              </p>
              <p>
                Dokter:{" "}
                {appointment.doctor_name || appointment.doctor?.name || "-"}
              </p>
              <p>Ruangan: {appointment.room || appointment.room_id || "-"}</p>
              <button
                className="delete-button"
                onClick={() =>
                  handleCancelAppointment(
                    appointment.id,
                    "Pembatalan oleh pasien"
                  )
                }
                disabled={loading}
                title="Batalkan janji temu"
              >
                <IoTrash size={18} />
              </button>
            </div>
          ))}
      </div>

      {/* Tampilkan info debug */}
      <div
        style={{
          marginTop: "20px",
          padding: "10px",
          backgroundColor: "#f8f9fa",
          borderRadius: "4px",
          fontSize: "12px",
        }}
      >
        <strong>Debug Info:</strong>
        <br />
        User ID: {userInfo.id || "Tidak ada"}
        <br />
        Appointments: {appointments.length}
        <br />
        Doctors: {doctors.length}
        <br />
        Rooms: {rooms.length}
      </div>

      <AppointmentFormModal
        isOpen={isFormModalOpen}
        onClose={() => setIsFormModalOpen(false)}
        onSubmit={handleScheduleAppointment}
        doctors={doctors}
        rooms={rooms}
      />
    </div>
  );
};

export default JanjiTemu;
