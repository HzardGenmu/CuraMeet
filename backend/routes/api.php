<?php

use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import all the controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Middleware 'auth' can be added to routes that require authentication
// Route::middleware('auth')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function () {
    // Kode ini baru akan dieksekusi saat user mengunjungi '/'
    return response()->json(['message' => 'Welcome']);
});

Route::options('{any}', function (Request $request) {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', $request->header('Origin') ?: 'http://localhost:3000' || 'http://backend-secure.test')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept')
        ->header('Access-Control-Allow-Credentials', 'true')
        ->header('Access-Control-Max-Age', '86400');
})->where('any', '.*');


//--- Authentication Routes ---//
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/email/check', [AuthController::class, 'checkEmail']);
    Route::get('/token/verify', [AuthController::class, 'verifyToken']);

    Route::middleware('auth.token')->group(function () {
        Route::get('/user', [AuthController::class, 'currentUser']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/token/refresh', [AuthController::class, 'refreshToken']);
        Route::post('/password/change', [AuthController::class, 'changePassword']);

        Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    });
});

//--- Doctor Routes ---//
Route::prefix('doctors')->group(function () {
    Route::get('/', [DoctorController::class, 'listDoctors']);
    Route::get('/patients-with-medical-record', [DoctorController::class, 'getPatientsWithMedicalRecord']);
    Route::get('/search', [DoctorController::class, 'getDoctorsByName']);
    Route::get('/{doctorId}', [DoctorController::class, 'getDoctorById']);
    Route::get('/user/{userId}', [DoctorController::class, 'getDoctorByUserId']);

    Route::post('/medical-records/view', [DoctorController::class, 'lihatRekamanMedis']);
    Route::post('/patients/{patientId}/export', [DoctorController::class, 'exportPatientData']);
    Route::post('/schedule/update', [DoctorController::class, 'updateDoctorSchedule']);

    // Route that should require doctor authentication
    Route::get('/profile/now', [DoctorController::class, 'getDoctorNow']);
});

//--- Patient Routes ---//
Route::prefix('patients')->group(function () {
    Route::get('/search', [PatientController::class, 'getPatientsByName']);
    Route::get('/{patientId}', [PatientController::class, 'getPatientById']);
    Route::get('/user/{userId}', [PatientController::class, 'getPatientByUserId']);
    Route::post('/{patientId}/profile/fill', [PatientController::class, 'isiFormDataDiri']);
    Route::get('/{patientId}/statistics', [PatientController::class, 'lihatStatistik']);

    // Route that should require patient authentication
    Route::get('/profile/now', [PatientController::class, 'getPatientNow']);
});

//--- Appointment Routes ---//
Route::middleware('auth.token')->group(function () {
    Route::prefix('appointments')->group(function () {
        Route::post('/new', [AppointmentController::class, 'newAppointment']);
        Route::post('/{appointmentId}/cancel', [AppointmentController::class, 'cancelAppointment']);
        Route::post('/cancel-by-doctor', [AppointmentController::class, 'cancelAppointmentByDoctorId']); // Original method
        Route::get('/doctor', [AppointmentController::class, 'getAppointmentsByDoctor']);
        Route::get('/patient', [AppointmentController::class, 'getAppointmentByPatient']);
        Route::post('/confirm/doctor', [AppointmentController::class, 'confirmAppointmentByDoctor']);
        Route::post('/complete/doctor', [AppointmentController::class, 'completeAppointment']);

        Route::delete('/{appointmentId}', [AppointmentController::class, 'deleteAppointment']);
        Route::post('/change-schedule/doctor', [AppointmentController::class, 'changeScheduleByDoctor']);
        Route::post('/cancel/doctor', [AppointmentController::class, 'cancelAppointmentByDoctor']); // Vulnerable method
        Route::post('/change-schedule/patient', [AppointmentController::class, 'changeAppointmentByPatient']);
        Route::post('/bulk-update', [AppointmentController::class, 'bulkUpdateAppointments']);
    });

    //--- Medical Record Routes ---//
    Route::prefix('medical-records')->group(function () {
        Route::get('/patient', [MedicalRecordController::class, 'getRekamMedisByPatientId']);
        // Route::get('/patient', [MedicalRecordController::class, 'getByDoctor']);
        Route::get('/{id}', [MedicalRecordController::class, 'getRekamMedisById']);


        Route::post('/upload', [MedicalRecordController::class, 'uploadRekamMedis']);
        Route::post('/update', [MedicalRecordController::class, 'updateRekamMedis']);
        Route::delete('/{id}/delete', [MedicalRecordController::class, 'deleteRekamMedisById']);

    });
});

// ALL routes in this group should be protected by a strict admin-only middleware.
Route::middleware('auth.token:admin')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'getAllUsers']);
        Route::post('/roles/manage', [AdminController::class, 'kelolaRole']);
        Route::get('/logs/activity', [AdminController::class, 'monitoringLogAktivitas']);
        Route::post('/users/bulk-manage', [AdminController::class, 'manajemenRoleUser']);
        Route::get('/logs/audit', [AdminController::class, 'auditLogDataMgmt']);
        Route::get('/logs/api-requests', [AdminController::class, 'loggingAPIRequest']);
        Route::get('/monitoring/backend', [AdminController::class, 'monitoringBackend']);
        Route::get('/monitoring/traffic-anomaly', [AdminController::class, 'monitoringAnomaliTraffic']);
        Route::post('/system/maintenance', [AdminController::class, 'systemMaintenance']);
        Route::post('/users/impersonate', [AdminController::class, 'impersonateUser']);
        Route::post('/database/backup', [AdminController::class, 'backupDatabase']);
        Route::post('/config/manage', [AdminController::class, 'manageConfig']);
        Route::post('/artisan/execute', [AdminController::class, 'executeArtisan']);
    });
});

Route::prefix('files')->group(function () {
    // View file inline (untuk preview di browser)
    Route::get(
        'medical-records/{patientId}/{filename}',
        [FileController::class, 'serveMedicalRecordFile']
    )
        ->where('filename', '.*'); // Allow dots in filename

    // Download file
    Route::get(
        'medical-records/{patientId}/{filename}/download',
        [FileController::class, 'downloadMedicalRecordFile']
    )
        ->where('filename', '.*');
});
