// src/services/appointmentService.js
import apiClient from "./apiService";

/**
 * Service untuk mengelola semua interaksi API yang berhubungan dengan Janji Temu (Appointments).
 */
export const appointmentService = {
  /**
   * 🗓️ Membuat janji temu baru.
   * @param {object} appointmentData - Data untuk janji temu baru.
   * @param {number} appointmentData.patient_id - ID Pasien.
   * @param {number} appointmentData.doctor_id - ID Dokter.
   * @param {string} appointmentData.appointment_time - Waktu janji temu (format: 'YYYY-MM-DD HH:MM:SS').
   * @param {string} [appointmentData.catatan] - Catatan opsional.
   * @returns {Promise<any>} Respons dari server.
   */
  create: async (appointmentData) => {
    const response = await apiClient.post("/appointments/new", appointmentData);
    return response.data;
  },

  /**
   * 📋 Mengambil semua janji temu untuk seorang pasien.
   * @param {number} patientId - ID unik pasien.
   * @returns {Promise<any>} Daftar janji temu.
   */
  getForPatient: async (patientId) => {
    const response = await apiClient.get("/appointments/patient", {
      params: { patient_id: patientId },
    });
    return response.data;
  },

  /**
   * 📋 Mengambil semua janji temu untuk seorang dokter.
   * @param {number} doctorId - ID unik dokter.
   * @returns {Promise<any>} Daftar janji temu.
   */
  getForDoctor: async (doctorId) => {
    const response = await apiClient.get("/appointments/doctor", {
      params: { doctor_id: doctorId },
    });
    return response.data;
  },

  /**
   * ❌ Dibatalkan oleh Pasien: Membatalkan sebuah janji temu.
   * @param {number} appointmentId - ID janji temu yang akan dibatalkan.
   * @param {string} [reason] - Alasan pembatalan (opsional).
   * @returns {Promise<any>} Respons konfirmasi.
   */
  cancelByPatient: async (appointmentId, reason = "") => {
    const response = await apiClient.post(
      `/appointments/${appointmentId}/cancel`,
      { reason }
    );
    return response.data;
  },

  /**
   * ❌ Dibatalkan oleh Dokter: Membatalkan sebuah janji temu.
   * @param {number} appointmentId - ID janji temu.
   * @param {number} doctorId - ID dokter yang membatalkan.
   * @param {string} reason - Alasan pembatalan.
   * @returns {Promise<any>} Respons konfirmasi.
   */
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
   * @param {number} appointmentId - ID janji temu.
   * @param {number} patientId - ID pasien yang mengubah.
   * @param {string} newTime - Waktu baru (format: 'YYYY-MM-DD HH:MM:SS').
   * @returns {Promise<any>} Respons konfirmasi.
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
   * @param {number} appointmentId - ID janji temu.
   * @param {number} doctorId - ID dokter yang mengubah.
   * @param {string} newTime - Waktu baru (format: 'YYYY-MM-DD HH:MM:SS').
   * @returns {Promise<any>} Respons konfirmasi.
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
   * @param {Array<object>} appointments - Array berisi objek janji temu yang akan diupdate.
   * @returns {Promise<any>} Respons konfirmasi.
   */
  bulkUpdate: async (appointments) => {
    const response = await apiClient.post("/appointments/bulk-update", {
      appointments, // Sesuai dengan payload yang diharapkan backend
    });
    return response.data;
  },
};