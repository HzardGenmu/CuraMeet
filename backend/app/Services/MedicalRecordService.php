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
    public function uploadRekamMedis($disease_name, $patientId, $doctorId, $file, $doctorNote = null)
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
        $path = $file->storeAs('uploads/' . $patientId . '/rekam_medis', $filename, 'local');

        $record = MedicalRecord::create([
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'path_file' => $path,
            'disease_name' => $disease_name,
            'catatan_dokter' => $doctorNote
        ]);

        return [
            'success' => true,
            'file_path' => $path,
            'patient_id' => $patientId,
            'catatan_dokter' => $doctorNote,
            'disease_name' => $disease_name
        ];
    }

    public function getRekamMedisByPatient($patientId, $token = null)
    {
        $records = MedicalRecord::with([
            'patient:id,full_name,NIK,allergies,user_id',
            'patient.user:id,email',
            'doctor:id,full_name'
        ])
            ->where('patient_id', $patientId)
            ->get();

        // Add protected URLs if token provided
        if ($token) {
            $records = $records->map(function ($record) use ($token) {
                if ($record->path_file) {
                    preg_match('/uploads\/(\d+)\/rekam_medis\/(.+)$/', $record->path_file, $matches);

                    if (count($matches) === 3) {
                        $patientId = $matches[1];
                        $filename = $matches[2];

                        $record->file_url = url("/api/files/medical-records/{$patientId}/{$filename}?token={$token}");
                        $record->download_url = url("/api/files/medical-records/{$patientId}/{$filename}/download?token={$token}");
                    }
                }
                return $record;
            });
        }

        return [
            'success' => true,
            'records' => $records,
            'patient_id' => $patientId
        ];
    }

    // Update getRekamMedisByDoctor juga dengan logic yang sama
    public function getRekamMedisByDoctor($doctorId, $patientId = null, $token = null)
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

        // Add protected URLs if token provided
        if ($token) {
            $records = $records->map(function ($record) use ($token) {
                if ($record->path_file) {
                    preg_match('/uploads\/(\d+)\/rekam_medis\/(.+)$/', $record->path_file, $matches);

                    if (count($matches) === 3) {
                        $patientId = $matches[1];
                        $filename = $matches[2];

                        $record->file_url = url("/api/files/medical-records/{$patientId}/{$filename}?token={$token}");
                        $record->download_url = url("/api/files/medical-records/{$patientId}/{$filename}/download?token={$token}");
                    }
                }
                return $record;
            });
        }

        return [
            'success' => true,
            'patient_id' => $patientId,
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
        if ($record->path_file && Storage::disk('local')->exists($record->path_file)) {
            Storage::disk('local')->delete($record->path_file);
        }

        $record->delete();

        return [
            'success' => true,
            'message' => 'Medical record deleted'
        ];
    }
}
