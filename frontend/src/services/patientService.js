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

  // ✅ TEMPORARY: Dummy data untuk doctors dan rooms (sampai ada endpoint-nya)
  getDoctors: async () => {
    // Sementara return dummy data karena belum ada endpoint /doctors
    return {
      success: true,
      doctors: [
        {
          id: 1,
          name: "Dr. Ahmad Wijaya",
          specialization: "Umum",
          available: true,
        },
        {
          id: 2,
          name: "Dr. Sari Dewi",
          specialization: "Gigi",
          available: true,
        },
        {
          id: 3,
          name: "Dr. Budi Santoso",
          specialization: "Mata",
          available: true,
        },
        {
          id: 4,
          name: "Dr. Lisa Kumala",
          specialization: "Kulit",
          available: true,
        },
        {
          id: 5,
          name: "Dr. Andi Prasetyo",
          specialization: "Jantung",
          available: false,
        },
      ],
    };
  },

  getRooms: async () => {
    // Sementara return dummy data karena belum ada endpoint /rooms
    return {
      success: true,
      rooms: [
        { id: 1, name: "Ruang 101", available: true },
        { id: 2, name: "Ruang 102", available: true },
        { id: 3, name: "Ruang 103", available: false },
        { id: 4, name: "Ruang 201", available: true },
        { id: 5, name: "Ruang 202", available: true },
      ],
    };
  },
};
