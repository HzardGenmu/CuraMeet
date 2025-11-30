import apiClient from "./apiService";

/**
 * Service untuk mengelola semua interaksi API yang berhubungan dengan data Pasien.
 */
export const patientService = {
  /**
   * Mengambil profil pasien yang sedang login berdasarkan token otentikasi.
   * @returns {Promise<any>} Data profil pasien.
   */
  getProfile: async () => {
    const response = await apiClient.get("/patients/profile/now");
    return response.data;
  },

  /**
   * Mencari pasien berdasarkan nama.
   * @param {string} name - Nama pasien yang ingin dicari.
   * @returns {Promise<any>} Daftar pasien yang cocok.
   */
  search: async (name) => {
    const response = await apiClient.get("/patients/search", {
      params: { name },
    });
    // console.log(response);
    return response.data;
  },

  /**
   * Mengambil data pasien berdasarkan ID user (user_id).
   * @param {number} userId - ID dari tabel 'users'.
   * @returns {Promise<any>} Data detail pasien.
   */
  getByUserId: async (userId) => {
    const response = await apiClient.get(`/patients/user/${userId}`);
    return response.data;
  },

  /**
   * Mengambil data pasien berdasarkan ID pasien (patient_id).
   * @param {number} patientId - ID dari tabel 'patients'.
   * @returns {Promise<any>} Data detail pasien.
   */
  getById: async (patientId) => {
    const response = await apiClient.get(`/patients/${patientId}`);
    return response.data;
  },

  /**
   * Memperbarui data diri seorang pasien.
   * @param {number} patientId - ID pasien yang akan diupdate.
   * @param {object} profileData - Objek berisi data baru untuk profil.
   * @returns {Promise<any>} Respons konfirmasi dari server.
   */
  updateProfile: async (patientId, profileData) => {
    const response = await apiClient.post(
      `/patients/${patientId}/profile/fill`,
      profileData
    );
    return response.data;
  },

  /**
   * Mengambil data statistik kunjungan seorang pasien.
   * @param {number} patientId - ID pasien.
   * @param {object} [dateFilters={}] - Filter tanggal opsional, cth: { date_from: '2024-01-01' }.
   * @returns {Promise<any>} Data statistik.
   */
  getStatistics: async (patientId, dateFilters = {}) => {
    const response = await apiClient.get(`/patients/${patientId}/statistics`, {
      params: dateFilters,
    });
    return response.data;
  },

  deleteById: async (patientId) => {
    const response = await apiClient.delete(`/patients/${patientId}`); // Endpoint yang diasumsikan
    return response.data;
  },
};
