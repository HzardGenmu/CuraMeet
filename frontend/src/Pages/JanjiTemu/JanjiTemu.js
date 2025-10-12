import React, { useState, useEffect } from "react";
import { patientService } from "../../services/patientService";
import "./JanjiTemu.css";
import { IoTrash } from "react-icons/io5";
import Modal from "../../components/Modal/Modal";
import AppointmentFormModal from "../../components/AppointmentFormModal/AppointmentFormModal";

const JanjiTemu = () => {
  const [appointments, setAppointments] = useState([]);
  const [doctors, setDoctors] = useState([]);
  const [rooms, setRooms] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [isListModalOpen, setIsListModalOpen] = useState(false);
  const [isFormModalOpen, setIsFormModalOpen] = useState(false);

  // Get current user info
  const userInfo = JSON.parse(localStorage.getItem("userInfo") || "{}");

  useEffect(() => {
    loadInitialData();
  }, []);

  const loadInitialData = async () => {
    try {
      setLoading(true);

      // Load appointments for current patient
      if (userInfo.id) {
        const appointmentsData = await patientService.getPatientById(
          userInfo.id
        );
        setAppointments(appointmentsData.appointments || []);
      }

      // Load doctors and rooms (you might need separate endpoints for these)
      // For now, using the search functionality
      const doctorsData = await patientService.searchPatients(""); // This might need a separate doctors endpoint
      setDoctors(doctorsData.doctors || []);
    } catch (error) {
      console.error("Error loading data:", error);
      setError("Failed to load data");
    } finally {
      setLoading(false);
    }
  };

  const handleScheduleAppointment = async (appointmentData) => {
    try {
      const response = await patientService.registerCheckup({
        patient_id: userInfo.id,
        doctor_id: appointmentData.doctorId,
        appointment_time: appointmentData.appointmentTime,
      });

      if (response.success) {
        // Reload appointments
        loadInitialData();
        setIsFormModalOpen(false);
      } else {
        setError(response.message || "Failed to schedule appointment");
      }
    } catch (error) {
      console.error("Error scheduling appointment:", error);
      setError("Failed to schedule appointment");
    }
  };

  const handleCancelAppointment = async (appointmentId, reason) => {
    try {
      const response = await patientService.cancelCheckup(
        appointmentId,
        reason
      );

      if (response.success) {
        // Remove appointment from list
        setAppointments(appointments.filter((app) => app.id !== appointmentId));
      } else {
        setError(response.message || "Failed to cancel appointment");
      }
    } catch (error) {
      console.error("Error canceling appointment:", error);
      setError("Failed to cancel appointment");
    }
  };

  if (loading) {
    return <div className="loading">Loading...</div>;
  }

  if (error) {
    return <div className="error">{error}</div>;
  }

  return (
    <div className="janji-temu-container">
      {/* Your existing JSX with updated handlers */}
      <button onClick={() => setIsFormModalOpen(true)}>
        Schedule New Appointment
      </button>

      <div className="appointments-list">
        {appointments.map((appointment) => (
          <div key={appointment.id} className="appointment-item">
            <p>
              <strong>{appointment.date}</strong> - {appointment.time}
            </p>
            <p>Doctor: {appointment.doctor_name}</p>
            <p>Room: {appointment.room}</p>
            <button
              className="delete-button"
              onClick={() =>
                handleCancelAppointment(appointment.id, "Patient cancellation")
              }
            >
              <IoTrash size={18} />
            </button>
          </div>
        ))}
      </div>

      {/* Modals */}
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
