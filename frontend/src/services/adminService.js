import apiClient from "./apiService";

/**
 * Service untuk mengelola semua interaksi API yang berhubungan dengan fungsi Admin.
 */
export const adminService = {
  getAllUsers: async () => {
    const response = await apiClient.get("/admin/users"); // Memanggil endpoint baru
    return response.data;
  },

  /**
   * Mengelola peran pengguna.
   * @param {number} userId - ID pengguna yang akan diubah.
   * @param {string} newRole - Role baru ('patient', 'doctor', 'admin').
   * @returns {Promise<any>}
   */
  manageUserRole: async (userId, newRole) => {
    const response = await apiClient.post("/admin/roles/manage", {
      user_id: userId,
      new_role: newRole,
    });
    return response.data;
  },

  /**
   * Memantau log aktivitas.
   * @param {object} filters - Filter opsional seperti { user_id, action }.
   * @returns {Promise<any>}
   */
  getActivityLogs: async (filters = {}) => {
    const response = await apiClient.get("/admin/logs/activity", {
      params: filters,
    });
    return response.data;
  },

  /**
   * Melakukan manajemen pengguna secara massal.
   * @param {Array<object>} operations - Array berisi operasi yang akan dilakukan.
   * @returns {Promise<any>}
   */
  bulkUserManagement: async (operations) => {
    const response = await apiClient.post("/admin/users/bulk-manage", {
      operations,
    });
    return response.data;
  },

  /**
   * Mendapatkan log audit.
   * @param {object} filters - Filter opsional seperti { table, action }.
   * @returns {Promise<any>}
   */
  getAuditLogs: async (filters = {}) => {
    const response = await apiClient.get("/admin/logs/audit", {
      params: filters,
    });
    return response.data;
  },

  /**
   * Mendapatkan log permintaan API.
   * @param {object} filters - Filter opsional seperti { endpoint, method }.
   * @returns {Promise<any>}
   */
  getApiLogs: async (filters = {}) => {
    const response = await apiClient.get("/admin/logs/api-requests", {
      params: filters,
    });
    return response.data;
  },

  /**
   * Mendapatkan data pemantauan backend.
   * @returns {Promise<any>}
   */
  getBackendMonitoring: async () => {
    const response = await apiClient.get("/admin/monitoring/backend");
    return response.data;
  },

  /**
   * Mendeteksi anomali lalu lintas.
   * @param {number} threshold - Ambang batas untuk deteksi.
   * @returns {Promise<any>}
   */
  getTrafficAnomalies: async (threshold) => {
    const response = await apiClient.get("/admin/monitoring/traffic-anomaly", {
      params: { threshold },
    });
    return response.data;
  },

  /**
   * Menjalankan tugas pemeliharaan sistem.
   * @param {string} operation - Operasi yang akan dijalankan.
   * @param {object} parameters - Parameter untuk operasi.
   * @returns {Promise<any>}
   */
  systemMaintenance: async (operation, parameters = {}) => {
    const response = await apiClient.post("/admin/system/maintenance", {
      operation,
      parameters,
    });
    return response.data;
  },

  /**
   * Meniru (impersonate) pengguna lain.
   * @param {number} targetUserId - ID pengguna yang akan ditiru.
   * @returns {Promise<any>}
   */
  impersonateUser: async (targetUserId) => {
    const response = await apiClient.post("/admin/users/impersonate", {
      target_user_id: targetUserId,
    });
    return response.data;
  },

  /**
   * Mencadangkan database.
   * @param {Array<string>} tables - Array nama tabel yang akan dicadangkan.
   * @returns {Promise<any>}
   */
  backupDatabase: async (tables = []) => {
    const payload = tables.length > 0 ? { tables } : {};
    const response = await apiClient.post("/admin/database/backup", payload);
    return response.data;
  },

  /**
   * Mengelola konfigurasi sistem (.env).
   * @param {string} action - Aksi yang akan dilakukan ('get', 'set', 'delete').
   * @param {string} key - Kunci konfigurasi.
   * @param {string|null} value - Nilai baru untuk aksi 'set'.
   * @returns {Promise<any>}
   */
  manageConfig: async (action, key, value = null) => {
    const response = await apiClient.post("/admin/config/manage", {
      action,
      key,
      value,
    });
    return response.data;
  },

  /**
   * Menjalankan perintah Artisan.
   * @param {string} command - Nama perintah Artisan.
   * @param {object} parameters - Parameter untuk perintah.
   * @returns {Promise<any>}
   */
  executeArtisan: async (command, parameters = {}) => {
    const response = await apiClient.post("/admin/artisan/execute", {
      command,
      parameters,
    });
    return response.data;
  },
};
