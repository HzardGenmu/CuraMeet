// src/services/doctorService.js
import apiClient from "./apiService";

export const doctorService = {
  /**
   * FIX: Endpoint disesuaikan dengan DoctorController.
   * Dokter melihat rekam medis pasien.
   */
  viewPatientMedicalRecords: async (doctorId, patientId) => {
    const response = await apiClient.post("/doctors/medical-records/view", {
      doctor_id: doctorId,
      patient_id: patientId,
    });
    return response.data;
  },

  /**
   * FIX: Method & Endpoint disesuaikan.
   * Mengekspor data pasien.
   */
  exportPatientData: async (patientId) => {
    const response = await apiClient.post(
      `/doctors/patients/${patientId}/export`
    );
    return response.data;
  },

  /**
   * FIX: Method & Endpoint disesuaikan.
   * Memperbarui jadwal praktek dokter (bukan jadwal janji temu).
   */
  updatePracticeSchedule: async (doctorId, availableTime) => {
    const response = await apiClient.post("/doctors/schedule/update", {
      doctor_id: doctorId,
      available_time: availableTime,
    });
    return response.data;
  },
  
  // --- Fungsi Tambahan untuk Melengkapi (opsional) ---
  
  /**
   * Mengambil profil dokter yang sedang login.
   */
  getProfile: async () => {
    return await apiClient.get('/doctors/profile/now');
  },

  /**
   * Mengambil semua data dokter.
   */
  getAll: async () => {
    return await apiClient.get('/doctors');
  },
};