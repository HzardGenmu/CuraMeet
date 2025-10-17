// src/services/medicalRecordService.js
import apiClient from "./apiService";

/**
 * Service untuk mengelola semua interaksi API yang berhubungan dengan Rekam Medis (Medical Records).
 */
export const medicalRecordService = {
  /**
   * 📄 Mengunggah file rekam medis baru.
   * @param {FormData} formData - Objek FormData yang wajib berisi 'file', 'patient_id', 'doctor_id', dan 'doctor_note' (opsional).
   * @returns {Promise<any>} Respons dari server.
   */
  upload: async (formData) => {
    // Axios memerlukan header khusus untuk FormData
    const response = await apiClient.post("/medical-records/upload", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
    return response.data;
  },

  /**
   * 🔍 Mengambil semua rekam medis milik seorang pasien.
   * @param {number} patientId - ID unik pasien.
   * @returns {Promise<any>} Daftar rekam medis.
   */
  getForPatient: async (patientId) => {
    const response = await apiClient.get("/medical-records/patient", {
      params: { patient_id: patientId },
    });
    return response.data;
  },
  
  /**
   * 🔍 Mengambil detail satu rekam medis berdasarkan ID-nya.
   * @param {number} recordId - ID unik rekam medis.
   * @returns {Promise<any>} Detail rekam medis.
   */
  getById: async (recordId) => {
    const response = await apiClient.get(`/medical-records/${recordId}`);
    return response.data;
  },

  /**
   * ✏️ Memperbarui catatan atau nama penyakit pada rekam medis yang sudah ada.
   * @param {object} updateData - Data yang akan diupdate.
   * @param {number} updateData.id - ID rekam medis yang akan diubah.
   * @param {string} [updateData.doctor_note] - Catatan dokter baru (opsional).
   * @param {string} [updateData.disease_name] - Nama penyakit baru (opsional).
   * @returns {Promise<any>} Data rekam medis yang telah diupdate.
   */
  update: async (updateData) => {
    const response = await apiClient.post("/medical-records/update", updateData);
    return response.data;
  },

  /**
   * 🗑️ Menghapus sebuah rekam medis berdasarkan ID-nya.
   * @param {number} recordId - ID rekam medis yang akan dihapus.
   * @returns {Promise<any>} Respons konfirmasi penghapusan.
   */
  delete: async (recordId) => {
    const response = await apiClient.delete(`/medical-records/${recordId}/delete`);
    return response.data;
  },
};