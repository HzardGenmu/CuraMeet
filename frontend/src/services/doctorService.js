import apiClient from "./apiService";

export const doctorService = {
  // Add medical record
  addMedicalRecord: async (recordData) => {
    const response = await apiClient.post("/doctor/tambah-rekaman", recordData);
    return response.data;
  },

  // Add prescription
  addPrescription: async (prescriptionData) => {
    const response = await apiClient.post(
      "/doctor/tambah-resep",
      prescriptionData
    );
    return response.data;
  },

  // Get medical records
  getMedicalRecords: async (doctorId, patientId) => {
    const response = await apiClient.get("/doctor/rekaman-medis", {
      params: { doctor_id: doctorId, patient_id: patientId },
    });
    return response.data;
  },

  // Update appointment schedule
  updateAppointmentSchedule: async (appointmentId, newTime, doctorId) => {
    const response = await apiClient.put(
      `/doctor/ubah-jadwal/${appointmentId}`,
      {
        new_time: newTime,
        doctor_id: doctorId,
      }
    );
    return response.data;
  },

  // Cancel schedule
  cancelSchedule: async (appointmentId, reason, doctorId) => {
    const response = await apiClient.put(
      `/doctor/batalkan-jadwal/${appointmentId}`,
      {
        reason,
        doctor_id: doctorId,
      }
    );
    return response.data;
  },

  // Export patient data
  exportPatientData: async (patientId) => {
    const response = await apiClient.get(`/doctor/export-patient/${patientId}`);
    return response.data;
  },

  // Bulk update appointments
  bulkUpdateAppointments: async (appointments) => {
    const response = await apiClient.put("/doctor/bulk-appointments", {
      appointments,
    });
    return response.data;
  },

  // Update doctor schedule
  updateDoctorSchedule: async (doctorId, schedule, availableTime) => {
    const response = await apiClient.put("/doctor/update-schedule", {
      doctor_id: doctorId,
      schedule,
      available_time: availableTime,
    });
    return response.data;
  },
};
