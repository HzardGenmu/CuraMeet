import apiClient from "./apiService";

export const patientService = {
  // Search patients
  searchPatients: async (name) => {
    const response = await apiClient.get("/patient/search", {
      params: { name },
    });
    return response.data;
  },

  // Get patient by ID
  getPatientById: async (patientId) => {
    const response = await apiClient.get(`/patient/${patientId}`);
    return response.data;
  },

  // Upload medical record
  uploadMedicalRecord: async (patientId, file) => {
    const formData = new FormData();
    formData.append("patient_id", patientId);
    formData.append("file", file);

    const response = await apiClient.post(
      "/patient/upload-rekam-medis",
      formData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      }
    );
    return response.data;
  },

  // Submit medical record form
  submitMedicalRecordForm: async (formData) => {
    const response = await apiClient.post(
      "/patient/form-rekam-medis",
      formData
    );
    return response.data;
  },

  // Update personal data
  updatePersonalData: async (patientId, personalData) => {
    const response = await apiClient.put(
      `/patient/data-diri/${patientId}`,
      personalData
    );
    return response.data;
  },

  // Get medical notes
  getMedicalNotes: async (patientId) => {
    const response = await apiClient.get(`/patient/catatan-medis/${patientId}`);
    return response.data;
  },

  // Get statistics
  getStatistics: async (patientId, filters = {}) => {
    const response = await apiClient.get(`/patient/statistik/${patientId}`, {
      params: filters,
    });
    return response.data;
  },

  // Register new checkup
  registerCheckup: async (appointmentData) => {
    const response = await apiClient.post(
      "/patient/daftar-pengecekan",
      appointmentData
    );
    return response.data;
  },

  // Cancel checkup
  cancelCheckup: async (appointmentId, reason) => {
    const response = await apiClient.put(
      `/patient/batalkan-pengecekan/${appointmentId}`,
      {
        reason,
      }
    );
    return response.data;
  },
};
