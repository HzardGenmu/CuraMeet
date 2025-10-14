import apiClient from "./apiService";

export const patientService = {
  // Search patients
  searchPatients: async (name) => {
    const response = await apiClient.get("/patient/search", {
      params: { name },
    });
    return response.data;
  },

  // Get patient by ID with appointments
  getPatientById: async (patientId) => {
    const response = await apiClient.get(`/patient/${patientId}`);
    return response.data;
  },

  // ✅ FIXED: Create appointment - sesuai route backend
  createAppointment: async (appointmentData) => {
    try {
      const response = await apiClient.post("/patient/daftar-pengecekan", {
        patient_id: appointmentData.patientId,
        doctor_id: appointmentData.doctorId,
        appointment_time: appointmentData.appointmentTime,
        notes: appointmentData.notes || "",
      });
      return response.data;
    } catch (error) {
      console.error("Error creating appointment:", error);
      throw error;
    }
  },

  // ✅ FIXED: Cancel appointment - sesuai route backend
  cancelAppointment: async (appointmentId, reason) => {
    try {
      const response = await apiClient.put(
        `/patient/batalkan-pengecekan/${appointmentId}`,
        {
          reason: reason,
        }
      );
      return response.data;
    } catch (error) {
      console.error("Error cancelling appointment:", error);
      throw error;
    }
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

  // Get medical records - sesuai route backend
  getMedicalRecords: async (patientId) => {
    try {
      const response = await apiClient.get(
        `/patient/catatan-medis/${patientId}`
      );
      return response.data;
    } catch (error) {
      console.error("Error fetching medical records:", error);
      throw error;
    }
  },

  // Submit personal data form - sesuai route backend
  submitPersonalDataForm: async (patientId, formData) => {
    const response = await apiClient.put(
      `/patient/data-diri/${patientId}`,
      formData
    );
    return response.data;
  },

  // View statistics - sesuai route backend
  viewStatistics: async (patientId, period) => {
    const response = await apiClient.get(`/patient/statistik/${patientId}`, {
      params: { period },
    });
    return response.data;
  },

  // Ambil data dokter dari backend
  getDoctors: async () => {
    try {
      const response = await apiClient.get('/doctor/list');
      return {
        success: response.data.success,
        doctors: response.data.doctors || [],
        count: response.data.count || 0
      };
    } catch (error) {
      console.error('Error fetching doctors:', error);
      return { success: false, doctors: [] };
    }
  },

  // Ambil data ruang dari backend (jika endpoint tersedia)
  getRooms: async () => {
    // TODO: Ganti dengan endpoint backend jika sudah tersedia, misal /rooms/list
    // Sementara return array kosong
    return {
      success: true,
      rooms: []
    };
  },
};
