import apiClient from "./apiService";
import { appointmentService } from "./appointmentService";

export const doctorService = {
  viewPatientAppointmentsByDoctorNow: async () => {
    const profileRes = await doctorService.getProfile();
    console.log("Profile response:", profileRes);
    const doctorId = profileRes.data?.doctor.id;
    if (!doctorId) throw new Error("Doctor ID not found in profile response");
    const response = await appointmentService.getForDoctor(doctorId);
    return response;
  },

  viewPatientMedicalRecords: async (doctorId, patientId) => {
    const response = await apiClient.post("/doctors/medical-records/view", {
      doctor_id: doctorId,
      patient_id: patientId,
    });
    return response.data;
  },

  exportPatientData: async (patientId) => {
    const response = await apiClient.post(
      `/doctors/patients/${patientId}/export`
    );
    return response.data;
  },

  updatePracticeSchedule: async (doctorId, availableTime) => {
    const response = await apiClient.post("/doctors/schedule/update", {
      doctor_id: doctorId,
      available_time: availableTime,
    });
    return response.data;
  },

  getProfile: async () => {
    return await apiClient.get("/doctors/profile/now");
  },

  getAll: async () => {
    return await apiClient.get("/doctors");
  },
};
