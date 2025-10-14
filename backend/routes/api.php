

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'Backend is working!',
        'timestamp' => now()
    ]);
});

Route::prefix('rooms')->group(function () {
    Route::get('/list', [RoomController::class, 'listRooms']);
});
// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Patient routes
Route::prefix('patient')->group(function () {
    Route::get('/search', [PatientController::class, 'getPatientsByName']);
    Route::get('/{patientId}', [PatientController::class, 'getPatientById'])
        ->where('patientId', '[0-9]+');
    Route::post('/upload-rekam-medis', [PatientController::class, 'uploadRekamMedis']);
    Route::post('/form-rekam-medis', [PatientController::class, 'isiFormRekamMedis']);
    Route::put('/data-diri/{patientId}', [PatientController::class, 'isiFormDataDiri']);
    Route::get('/catatan-medis/{patientId}', [PatientController::class, 'lihatCatatanMedis']);
    Route::get('/statistik/{patientId}', [PatientController::class, 'lihatStatistik']);
    Route::post('/daftar-pengecekan', [PatientController::class, 'daftarPengecekanBaru']);
    Route::put('/batalkan-pengecekan/{appointmentId}', [PatientController::class, 'batalkanPengecekan']);
});

// Doctor routes
Route::prefix('doctor')->group(function () {
    Route::post('/tambah-rekaman', [DoctorController::class, 'tambahRekamanMedis']);
    Route::get('/list', [DoctorController::class, 'listDoctors']);
    Route::post('/tambah-resep', [DoctorController::class, 'tambahResep']);
    Route::get('/rekaman-medis', [DoctorController::class, 'lihatRekamanMedis']);
    Route::put('/ubah-jadwal/{appointmentId}', [DoctorController::class, 'ubahJadwalPengecekan']);
    Route::put('/batalkan-jadwal/{appointmentId}', [DoctorController::class, 'batalkanJadwal']);
    Route::get('/export-patient/{patientId}', [DoctorController::class, 'exportPatientData']);
    Route::put('/bulk-appointments', [DoctorController::class, 'bulkUpdateAppointments']);
    Route::put('/update-schedule', [DoctorController::class, 'updateDoctorSchedule']);
    Route::get('/appointments', [DoctorController::class, 'getAppointments']);
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::put('/kelola-role', [AdminController::class, 'kelolaRole']);
    Route::get('/monitoring-log', [AdminController::class, 'monitoringLogAktivitas']);
    Route::post('/manajemen-role', [AdminController::class, 'manajemenRoleUser']);
    Route::get('/audit-log', [AdminController::class, 'auditLogDataMgmt']);
    Route::get('/api-logs', [AdminController::class, 'loggingAPIRequest']);
    Route::get('/monitoring-backend', [AdminController::class, 'monitoringBackend']);
    Route::get('/monitoring-anomali', [AdminController::class, 'monitoringAnomaliTraffic']);
    Route::post('/system-maintenance', [AdminController::class, 'systemMaintenance']);
    Route::post('/impersonate', [AdminController::class, 'impersonateUser']);
    Route::post('/backup-database', [AdminController::class, 'backupDatabase']);
    Route::post('/manage-config', [AdminController::class, 'manageConfig']);
    Route::post('/execute-artisan', [AdminController::class, 'executeArtisan']);
});
