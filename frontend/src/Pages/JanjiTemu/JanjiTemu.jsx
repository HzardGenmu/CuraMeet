import React, { useState, useEffect } from "react";
import { patientService } from "../../services/patientService";
import { authService } from "../../services/authService";

import { IoTrash, IoCalendar, IoTime, IoPerson, IoAdd, IoLocationSharp } from "react-icons/io5";
import AppointmentFormModal from "../../components/AppointmentFormModal/AppointmentFormModal";

const JanjiTemu = () => {
  const [appointments, setAppointments] = useState([]);
  const [doctors, setDoctors] = useState([]);
  const [rooms, setRooms] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");
  const [isFormModalOpen, setIsFormModalOpen] = useState(false);

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

      const patientResponse = await patientService.getPatientById(userInfo.id);
      console.log("Patient response:", patientResponse);

      if (patientResponse.success) {
        setAppointments(
          patientResponse.appointments ||
            patientResponse.data?.appointments ||
            []
        );
      } else {
        console.warn("Patient data not found, using empty appointments");
        setAppointments([]);
      }

      const doctorsResponse = await patientService.getDoctors();
      if (doctorsResponse.success) {
        setDoctors(doctorsResponse.doctors || []);
      }

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
        {
          id: 2,
          appointment_time: "2025-11-20 14:30:00",
          doctor_name: "Dr. Siti Rahayu",
          room_name: "Ruang 203",
          status: "confirmed",
        },
        {
          id: 3,
          appointment_time: "2024-08-01 10:00:00", // Past appointment
          doctor_name: "Dr. Budi Santoso",
          room_name: "Ruang 105",
          status: "completed",
        },
        {
          id: 4,
          appointment_time: "2024-09-05 16:00:00", // Past appointment
          doctor_name: "Dr. Nurul Hidayah",
          room_name: "Ruang 201",
          status: "cancelled",
        },
      ]);
      setDoctors([
        { id: 1, name: "Dr. Ahmad Wijaya" },
        { id: 2, name: "Dr. Siti Rahayu" },
      ]);
      setRooms([
        { id: 1, name: "Ruang 101" },
        { id: 2, name: "Ruang 203" },
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

      const newAppointment = await patientService.createAppointment({
        patientId: userInfo.id,
        doctorId: appointmentData.doctorId,
        appointmentTime: `${appointmentData.date} ${appointmentData.time}:00`,
        notes: appointmentData.notes || "",
      });

      console.log("Appointment creation response:", newAppointment);

      if (newAppointment.success) {
        setSuccess("Janji temu berhasil dibuat!");
        setIsFormModalOpen(false);
        await loadInitialData();
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

      const result = await patientService.cancelAppointment(
        appointmentId,
        "Dibatalkan oleh pasien"
      );

      console.log("Cancel appointment response:", result);

      if (result.success) {
        setSuccess("Janji temu berhasil dibatalkan");
        await loadInitialData();
      } else {
        setError(result.message || "Gagal membatalkan janji temu");
      }
    } catch (error) {
      console.error("Error cancelling appointment:", error);
      setError("Terjadi kesalahan saat membatalkan janji temu");
    } finally {
      setLoading(false);
    }
  };

  const formatDateTime = (dateTimeString) => {
    try {
      const date = new Date(dateTimeString);
      const dateStr = date.toLocaleDateString("id-ID", {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
      const timeStr = date.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
      });
      return { date: dateStr, time: timeStr };
    } catch (error) {
      return { date: "Invalid Date", time: "Invalid Time" };
    }
  };

  // ✅ Mapping status ke kelas Tailwind
  const getStatusClasses = (status) => {
    switch (status?.toLowerCase()) {
      case "pending":
        return "bg-yellow-100 text-yellow-800";
      case "confirmed":
        return "bg-green-100 text-green-800";
      case "completed":
        return "bg-blue-100 text-blue-800";
      case "cancelled":
        return "bg-red-100 text-red-800";
      default:
        return "bg-gray-100 text-gray-800";
    }
  };

  if (loading && appointments.length === 0) {
    return (
      // Loading State
      <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
        <div className="flex flex-col items-center space-y-4">
          <div className="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
          <p className="text-lg text-gray-700">Memuat data janji temu...</p>
        </div>
      </div>
    );
  }

  return (
    // .janji-temu-container
    <div className="min-h-screen bg-gray-100 p-6 sm:p-8 flex flex-col items-center">
      {/* .janji-temu-header */}
      <div className="w-full max-w-6xl mb-8 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h1 className="text-4xl md:text-5xl font-bold text-gray-800 text-center sm:text-left">
          <span className="inline-block align-middle mr-2">📅</span> Janji Temu Saya
        </h1>
        <button
          className="flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md
                     hover:bg-blue-700 transition duration-300 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed text-lg"
          onClick={() => setIsFormModalOpen(true)}
          disabled={loading}
        >
          <IoAdd className="mr-2 text-xl" /> Buat Janji Temu Baru
        </button>
      </div>

      {error && <div className="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm flex items-center justify-center space-x-2 w-full max-w-6xl">
                  <span className="text-lg">❌</span> <span>{error}</span>
                </div>}

      {success && <div className="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-4 text-sm flex items-center justify-center space-x-2 w-full max-w-6xl">
                    <span className="text-lg">✅</span> <span>{success}</span>
                  </div>}

      {/* .appointments-section */}
      <div className="w-full max-w-6xl">
        {appointments.length === 0 ? (
          // .no-appointments
          <div className="flex flex-col items-center justify-center p-8 bg-white rounded-2xl shadow-lg text-gray-500">
            {/* .empty-state */}
            <IoCalendar size={80} className="mb-4 text-gray-400" />
            <h3 className="text-xl font-semibold mb-2">Belum Ada Janji Temu</h3>
            <p className="text-base text-center mb-6">Anda belum memiliki janji temu yang terjadwal</p>
            <button
              className="flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md
                         hover:bg-blue-700 transition duration-300 ease-in-out text-lg"
              onClick={() => setIsFormModalOpen(true)}
            >
              <IoAdd className="mr-2 text-xl" /> Buat Janji Temu Pertama
            </button>
          </div>
        ) : (
          // .appointments-grid
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {appointments.map((appointment) => {
              const { date, time } = formatDateTime(
                appointment.appointment_time
              );

              return (
                // .appointment-card 
                <div key={appointment.id} className="bg-white rounded-2xl shadow-lg flex flex-col
                                                   transition transform duration-200 ease-in-out hover:scale-105 hover:shadow-xl">
                  {/* .appointment-header */}
                  <div className="p-4 border-b border-gray-200 flex justify-between items-center">
                    <span
                      className={`px-3 py-1 rounded-full text-xs font-semibold ${getStatusClasses(
                        appointment.status
                      )}`}
                    >
                      {appointment.status?.toUpperCase() || "UNKNOWN"}
                    </span>
                    {(appointment.status !== "cancelled" && appointment.status !== "completed") && (
                       <button
                         className="p-2 text-red-500 rounded-full hover:bg-red-100 transition duration-200 ease-in-out"
                         onClick={() => handleCancelAppointment(appointment.id)}
                         disabled={loading}
                       >
                         <IoTrash className="text-xl" />
                       </button>
                    )}
                  </div>

                  {/* .appointment-body */}
                  <div className="p-4 flex-grow">
                    {/* .appointment-info */}
                    <div className="space-y-3 text-gray-700">
                      <div className="flex items-center text-lg font-medium text-gray-800">
                        <IoCalendar className="mr-3 text-blue-500 text-2xl" />
                        <span>{date}</span>
                      </div>
                      <div className="flex items-center text-lg font-medium text-gray-800">
                        <IoTime className="mr-3 text-blue-500 text-2xl" />
                        <span>{time}</span>
                      </div>
                      <div className="flex items-center text-base">
                        <IoPerson className="mr-3 text-gray-500 text-xl" />
                        <span>{appointment.doctor_name || "Doctor TBD"}</span>
                      </div>
                      <div className="flex items-center text-base">
                        <IoLocationSharp className="mr-3 text-gray-500 text-xl" /> 
+                       <span>{appointment.room_name || "Room TBD"}</span>
                      </div>
                      {appointment.notes && (
                         <div className="mt-4 pt-3 border-t border-gray-200 text-sm text-gray-600">
                            <strong className="block mb-1">Catatan:</strong>
                            <p className="line-clamp-2">{appointment.notes}</p>
                         </div>
                      )}
                    </div>
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