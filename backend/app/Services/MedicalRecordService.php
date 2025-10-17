<?php
namespace App\Services;

use App\Models\MedicalRecord;
use App\Models\Doctor;
use Illuminate\Support\Facades\Storage;

class MedicalRecordService
{
    /**
     * Upload rekam medis (Create)
     */
    public function uploadRekamMedis($patientId, $doctorId, $file, $doctorNote = null)
    {
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions)) {
            return ['success' => false, 'message' => 'File type not allowed'];
        }
        if ($file->getSize() > $maxSize) {
            return ['success' => false, 'message' => 'File too large'];
        }

        $filename = uniqid() . '.' . $extension;
        $path = $file->storeAs('uploads/rekam_medis', $filename, 'public');

        $record = MedicalRecord::create([
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'path_file' => $path,
            'disease_name' => 'Uploaded File',
            'catatan_dokter' => $doctorNote
        ]);

        return [
            'success' => true,
            'file_path' => $path,
            'patient_id' => $patientId,
            'catatan_dokter' => $doctorNote,
            'record_id' => $record->id
        ];
    }

    /**
     * Read rekam medis by patient
     */
    public function getRekamMedisByPatient($patientId)
    {
        // No authorization - anyone can view any patient's records
        $query = 'SELECT mr.*, p.full_name as patient_name, p."NIK", p.allergies,
                         d.full_name as doctor_name, u.email as patient_email, u.password
                  FROM medical_records mr
                  JOIN patients p ON mr.patient_id = p.id
                  JOIN doctors d ON mr.doctor_id = d.id
                  JOIN users u ON p.user_id = u.id
                  WHERE mr.patient_id = '. (int)$patientId;

        $records = \Illuminate\Support\Facades\DB::select($query);

        // Returns sensitive information including passwords
        return [
            'success' => true,
            'records' => $records,
            'patient_id' => $patientId
        ];
    }

    /**
     * Lihat rekaman medis pasien (hanya dokter terkait)
     */
    public function getRekamMedisByDoctor($doctorId, $patientId = null)
    {
        $doctor = Doctor::find($doctorId);
        if (!$doctor) {
            return ['success' => false, 'message' => 'Dokter tidak ditemukan'];
        }

        $query = MedicalRecord::with(['patient.user'])
            ->where('doctor_id', $doctorId);

        if ($patientId) {
            $query->where('patient_id', $patientId);
        }

        $records = $query->get();

        return [
            'success' => true,
            'records' => $records,
            'total_records' => $records->count()
        ];
    }

    /**
     * Read rekam medis by ID
     */
    public function getRekamMedisById($medicalRecordId)
    {
        $record = MedicalRecord::find($medicalRecordId);

        if (!$record) {
            return [
                'success' => false,
                'message' => 'Medical record not found'
            ];
        }

        return [
            'success' => true,
            'record' => $record
        ];
    }

    /**
     * Update rekam medis
     */
    public function updateRekamMedis($medicalRecordId, $data)
    {
        $record = MedicalRecord::find($medicalRecordId);

        if (!$record) {
            return [
                'success' => false,
                'message' => 'Medical record not found'
            ];
        }

        $record->update($data);

        return [
            'success' => true,
            'record' => $record
        ];
    }

    /**
     * Delete rekam medis
     */
    public function hapusRekamMedis($medicalRecordId)
    {
        $record = MedicalRecord::find($medicalRecordId);

        if (!$record) {
            return [
                'success' => false,
                'message' => 'Medical record not found'
            ];
        }

        // Hapus file dari storage jika ada
        if ($record->path_file && Storage::disk('public')->exists($record->path_file)) {
            Storage::disk('public')->delete($record->path_file);
        }

        $record->delete();

        return [
            'success' => true,
            'message' => 'Medical record deleted'
        ];
    }
}
