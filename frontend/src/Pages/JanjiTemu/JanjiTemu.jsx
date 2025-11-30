import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { authService } from "../../services/authService";
import { patientService } from "../../services/patientService";
import { doctorService } from "../../services/doctorService";
import { appointmentService } from "../../services/appointmentService";

import {
  IoTrash,
  IoCalendar,
  IoTime,
  IoPerson,
  IoAdd,
  IoLocationSharp,
  IoPulse,
} from "react-icons/io5";
import AppointmentFormModal from "../../components/AppointmentFormModal/AppointmentFormModal";

const JanjiTemu = () => {
  const [appointments, setAppointments] = useState([]);
  const [doctors, setDoctors] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState("");
  const [isFormModalOpen, setIsFormModalOpen] = useState(false);
  const [patientId, setPatientId] = useState(null);
  const [filterStatus, setFilterStatus] = useState("all");
  const [sortOrder, setSortOrder] = useState("desc"); // desc: terbaru dulu

  const navigate = useNavigate();

  const filteredAppointments = appointments
    .filter((appointment) =>
      filterStatus === "all"
        ? true
        : appointment.status?.toLowerCase() === filterStatus
    )
    .sort((a, b) => {
      const dateA = new Date(a.time_appointment);
      const dateB = new Date(b.time_appointment);
      return sortOrder === "asc" ? dateA - dateB : dateB - dateA;
    });
  // Fungsi untuk memuat semua data awal yang dibutuhkan halaman
  const loadInitialData = async () => {
    setLoading(true);
    setError("");
    try {
      // Langkah A: Ambil profil pasien untuk mendapatkan patient.id
      const profileRes = await patientService.getProfile();
      if (!profileRes.success || !profileRes.patient) {
        throw new Error("Gagal memuat profil pasien.");
      }
      const currentPatientId = profileRes.patient.id;
      setPatientId(currentPatientId);

      // Langkah B: Gunakan patient.id untuk mengambil janji temu
      const appointmentsRes = await appointmentService.getForPatient(
        currentPatientId
      );
      if (appointmentsRes.success) {
        setAppointments(appointmentsRes.appointments || []);
      }

      // Langkah C: Ambil daftar dokter untuk modal form
      const doctorsRes = await doctorService.getAll();
      if (doctorsRes.data && doctorsRes.data.success) {
        setDoctors(doctorsRes.data.doctors || []);
      }
    } catch (err) {
      console.error("Error loading initial data:", err);
      setError(err.response?.data?.message || "Gagal memuat data halaman.");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadInitialData();
  }, []);

  // Handler untuk membuat janji temu baru dari modal
  const handleScheduleAppointment = async (appointmentData) => {
    setError("");
    setSuccess("");
    try {
      const payload = {
        patient_id: patientId,
        doctor_id: appointmentData.doctorId,
        appointment_time: `${appointmentData.date} ${appointmentData.time}:00`,
        patient_note: appointmentData.notes || "",
      };

      const response = await appointmentService.create(payload);

      if (response.success) {
        setSuccess("Janji temu berhasil dibuat!");
        setIsFormModalOpen(false);
        await loadInitialData(); // Muat ulang semua data agar daftar terupdate
      } else {
        setError(response.message || "Gagal membuat janji temu");
      }
    } catch (err) {
      console.error("Error creating appointment:", err);
      setError(
        err.response?.data?.message ||
          "Terjadi kesalahan saat membuat janji temu"
      );
    }
  };

  // Handler untuk membatalkan janji temu
  const handleCancelAppointment = async (appointmentId) => {
    setError("");
    setSuccess("");
    try {
      const result = await appointmentService.cancelByPatient(
        appointmentId,
        "Dibatalkan oleh pasien"
      );
      // console.log(result);
      if (result.success) {
        setSuccess("Janji temu berhasil dibatalkan");
        await loadInitialData(); // Muat ulang semua data
      } else {
        setError(result.message || "Gagal membatalkan janji temu");
      }
    } catch (err) {
      console.error("Error cancelling appointment:", err);
      setError(
        err.response?.data?.message ||
          "Terjadi kesalahan saat membatalkan janji temu"
      );
    }
  };

  // Helper function untuk format tanggal dan waktu
  const formatDateTime = (dateTimeString) => {
    try {
      const date = new Date(dateTimeString);
      const dateStr = date.toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
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

  // Helper function untuk styling status
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
      <div className="min-h-screen flex items-center justify-center bg-gray-100 p-4">
        <div className="flex flex-col items-center space-y-4">
          <div className="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
          <p className="text-lg text-gray-700">Memuat data janji temu...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-100 p-6 sm:p-8 flex flex-col items-center">
      <div className="w-full max-w-6xl mb-8 flex flex-col sm:flex-row justify-between items-center gap-4">
        <h1 className="text-4xl md:text-5xl font-bold text-gray-800 text-center sm:text-left">
          <span className="inline-block align-middle mr-2">📅</span> Janji Temu
        </h1>

        <button
          className="flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition"
          onClick={() => setIsFormModalOpen(true)}
          disabled={loading}
        >
          <IoAdd className="mr-2 text-xl" /> Buat Janji Temu Baru
        </button>
      </div>

      {error && (
        <div className="bg-red-100 text-red-700 p-3 rounded-lg mb-4 w-full max-w-6xl text-center">
          ❌ {error}
        </div>
      )}
      {success && (
        <div className="bg-green-100 text-green-700 p-3 rounded-lg mb-4 w-full max-w-6xl text-center">
          ✅ {success}
        </div>
      )}

      <div className="w-full max-w-6xl mb-4 flex flex-col sm:flex-row gap-4 justify-start items-center">
        {/* Filter Status */}
        <div>
          <label className="mr-2 font-medium text-gray-700">
            Filter Status:
          </label>
          <select
            value={filterStatus}
            onChange={(e) => setFilterStatus(e.target.value)}
            className="border rounded px-3 py-2"
          >
            <option value="all">Semua</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        {/* Sort Tanggal/Waktu */}
        <div>
          <label className="mr-2 font-medium text-gray-700">Urutkan:</label>
          <select
            value={sortOrder}
            onChange={(e) => setSortOrder(e.target.value)}
            className="border rounded px-3 py-2"
          >
            <option value="desc">Terbaru</option>
            <option value="asc">Terlama</option>
          </select>
        </div>
      </div>
      <div className="w-full max-w-6xl">
        {filteredAppointments.length === 0 &&
        filteredAppointments.filterStatus === "all" ? (
          <div className="flex flex-col items-center justify-center p-8 bg-white rounded-2xl shadow-lg text-gray-500">
            <IoCalendar size={80} className="mb-4 text-gray-400" />
            <h3 className="text-xl font-semibold mb-2">Belum Ada Janji Temu</h3>
            <p className="text-base text-center mb-6">
              Anda belum memiliki janji temu yang terjadwal
            </p>
            <button
              className="flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition"
              onClick={() => setIsFormModalOpen(true)}
            >
              <IoAdd className="mr-2 text-xl" /> Buat Janji Temu Pertama
            </button>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredAppointments.map((appointment) => {
              const { date, time } = formatDateTime(
                appointment.time_appointment
              );
              return (
                <div
                  key={appointment.id}
                  className="bg-white rounded-2xl shadow-lg flex flex-col transition transform hover:scale-105 hover:shadow-xl"
                >
                  <div className="p-4 border-b border-gray-200 flex justify-between items-center">
                    <span
                      className={`px-3 py-1 m-2 rounded-full text-xs font-semibold ${getStatusClasses(
                        appointment.status
                      )}`}
                    >
                      {appointment.status?.toUpperCase() || "UNKNOWN"}{" "}
                      {appointment.status?.toUpperCase() === "CANCELLED" &&
                        `by ${appointment.cancelled_by}`}
                    </span>
                    {(appointment.status.toLowerCase() === "pending" ||
                      appointment.status.toLowerCase() === "confirmed") && (
                      <button
                        className="p-2 text-red-500 rounded-full hover:bg-red-100 transition"
                        onClick={() => handleCancelAppointment(appointment.id)}
                        title="Batalkan Janji Temu"
                      >
                        <IoTrash className="text-xl" />
                      </button>
                    )}
                  </div>
                  <div className="p-4 flex-grow">
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
                        <span>
                          {appointment.doctor?.full_name ||
                            "Dokter tidak ditemukan"}
                        </span>
                      </div>
                      <div className="flex items-center text-base">
                        <IoPulse className="mr-3 text-green-500 text-xl" />
                        <span>
                          Ruang:{" "}
                          {appointment.doctor.polyclinic ||
                            "Dokter tidak ditemukan"}
                        </span>
                      </div>
                      {appointment.patient_note && (
                        <p className="text-gray-500 text-sm mt-2 italic">
                          {"📝 " + appointment.patient_note}
                        </p>
                      )}
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </div>

      <AppointmentFormModal
        isOpen={isFormModalOpen}
        onClose={() => setIsFormModalOpen(false)}
        onSubmit={handleScheduleAppointment}
        doctors={doctors}
      />
    </div>
  );
};

export default JanjiTemu;
