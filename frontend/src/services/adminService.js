// src/services/adminService.js
import apiClient from "./apiService";

export const adminService = {
  /**
   * FIX: Method diubah ke POST, URL disesuaikan.
   * Mengelola peran pengguna.
   */
  manageUserRole: async (userId, newRole) => {
    const response = await apiClient.post("/admin/roles/manage", {
      user_id: userId,
      new_role: newRole,
    });
    return response.data;
  },

  /**
   * FIX: URL disesuaikan.
   * Memantau log aktivitas.
   */
  getActivityLogs: async (filters = {}) => {
    const response = await apiClient.get("/admin/logs/activity", {
      params: filters,
    });
    return response.data;
  },

  /**
   * FIX: URL disesuaikan.
   * Melakukan manajemen pengguna secara massal.
   */
  bulkUserManagement: async (operations) => {
    const response = await apiClient.post("/admin/users/bulk-manage", {
      operations,
    });
    return response.data;
  },

  /**
   * FIX: URL disesuaikan.
   * Mendapatkan log audit.
   */
  getAuditLogs: async (filters = {}) => {
    // filters can contain { table, action }
    const response = await apiClient.get("/admin/logs/audit", {
      params: filters,
    });
    return response.data;
  },

  /**
   * FIX: URL disesuaikan.
   * Mendapatkan log permintaan API.
   */
  getApiLogs: async (filters = {}) => {
    // filters can contain { endpoint, method }
    const response = await apiClient.get("/admin/logs/api-requests", {
      params: filters,
    });
    return response.data;
  },

  /**
   * FIX: URL disesuaikan.
   * Mendapatkan data pemantauan backend.
   */
  getBackendMonitoring: async () => {
    const response = await apiClient.get("/admin/monitoring/backend");
    return response.data;
  },

  /**
   * FIX: URL disesuaikan.
   * Mendeteksi anomali lalu lintas.
   */
  getTrafficAnomalies: async (threshold) => {
    const response = await apiClient.get("/admin/monitoring/traffic-anomaly", {
      params: { threshold },
    });
    return response.data;
  },

  // --- Functions below were added for completeness ---

  /**
   * NEW: Ditambahkan sesuai AdminController.
   * Menjalankan tugas pemeliharaan sistem.
   */
  systemMaintenance: async (operation, parameters = {}) => {
    const response = await apiClient.post("/admin/system/maintenance", {
      operation,
      parameters,
    });
    return response.data;
  },

  /**
   * NEW: Ditambahkan sesuai AdminController.
   * Meniru (impersonate) pengguna lain.
   */
  impersonateUser: async (targetUserId) => {
    const response = await apiClient.post("/admin/users/impersonate", {
      target_user_id: targetUserId,
    });
    return response.data;
  },

  /**
   * NEW: Ditambahkan sesuai AdminController.
   * Mencadangkan database.
   */
  backupDatabase: async (tables = []) => {
    const payload = tables.length > 0 ? { tables } : {};
    const response = await apiClient.post("/admin/database/backup", payload);
    return response.data;
  },

  /**
   * NEW: Ditambahkan sesuai AdminController.
   * Mengelola konfigurasi sistem (.env).
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
   * NEW: Ditambahkan sesuai AdminController.
   * Menjalankan perintah Artisan.
   */
  executeArtisan: async (command, parameters = {}) => {
    const response = await apiClient.post("/admin/artisan/execute", {
      command,
      parameters,
    });
    return response.data;
  },
};