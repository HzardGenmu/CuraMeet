import apiClient from "./apiService";

export const adminService = {
  // Manage user roles
  manageUserRoles: async (userId, newRole) => {
    const response = await apiClient.put("/admin/kelola-role", {
      user_id: userId,
      new_role: newRole,
    });
    return response.data;
  },

  // Activity monitoring
  getActivityMonitoring: async (filters = {}) => {
    const response = await apiClient.get("/admin/monitoring-log", {
      params: filters,
    });
    return response.data;
  },

  // Bulk user management
  bulkUserManagement: async (operations) => {
    const response = await apiClient.post("/admin/manajemen-role", {
      operations,
    });
    return response.data;
  },

  // Get audit logs
  getAuditLogs: async (table, action) => {
    const response = await apiClient.get("/admin/audit-log", {
      params: { table, action },
    });
    return response.data;
  },

  // Get API logs
  getApiLogs: async (endpoint, method) => {
    const response = await apiClient.get("/admin/api-logs", {
      params: { endpoint, method },
    });
    return response.data;
  },

  // Backend monitoring
  getBackendMonitoring: async () => {
    const response = await apiClient.get("/admin/monitoring-backend");
    return response.data;
  },

  // Traffic anomaly detection
  getTrafficAnomalies: async (threshold) => {
    const response = await apiClient.get("/admin/monitoring-anomali", {
      params: { threshold },
    });
    return response.data;
  },
};
