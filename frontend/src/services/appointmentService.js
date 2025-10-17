import apiClient from "./apiService"; // ✅ BARIS YANG HILANG TELAH DITAMBAHKAN

/**
 * Service untuk mengelola semua interaksi API yang berhubungan dengan Janji Temu (Appointments).
 */
export const appointmentService = {
  
  create: async (appointmentData) => {
    const response = await apiClient.post("/appointments/new", appointmentData);
    return response.data;
  },

  
  getForPatient: async (patientId) => {
    const response = await apiClient.get("/appointments/patient", {
      params: { patient_id: patientId },
    });
    return response.data;
  },

  
  getForDoctor: async (doctorId) => {
    const response = await apiClient.get("/appointments/doctor", {
      params: { doctor_id: doctorId },
    });
    return response.data;
  },

  
  cancelByPatient: async (appointmentId, reason = "") => {
    const response = await apiClient.post(
      `/appointments/${appointmentId}/cancel`,
      { reason }
    );
    return response.data;
  },

  
  cancelByDoctor: async (appointmentId, doctorId, reason) => {
    const response = await apiClient.post("/appointments/cancel/doctor", {
      appointment_id: appointmentId,
      doctor_id: doctorId,
      reason: reason,
    });
    return response.data;
  },

  /**
   * 🔄 Diubah oleh Pasien: Mengubah jadwal janji temu.
   */
  changeScheduleByPatient: async (appointmentId, patientId, newTime) => {
    const response = await apiClient.post(
      "/appointments/change-schedule/patient",
      {
        appointment_id: appointmentId,
        patient_id: patientId,
        new_time: newTime,
      }
    );
    return response.data;
  },

  /**
   * 🔄 Diubah oleh Dokter: Mengubah jadwal janji temu.
   */
  changeScheduleByDoctor: async (appointmentId, doctorId, newTime) => {
    const response = await apiClient.post(
      "/appointments/change-schedule/doctor",
      {
        appointment_id: appointmentId,
        doctor_id: doctorId,
        new_time: newTime,
      }
    );
    return response.data;
  },

  /**
   * 📦 Melakukan pembaruan massal (bulk update) pada beberapa janji temu.
   */
  bulkUpdate: async (appointments) => {
    const response = await apiClient.post("/appointments/bulk-update", {
      appointments,
    });
    return response.data;
  },
};

