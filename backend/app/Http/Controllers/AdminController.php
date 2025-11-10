<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Get All Users
     *
     * Returns all users in the system.
     *
     * @group Admin
     *
     * @response 200 {
     *   "success": true,
     *   "users": [
     *     {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "user@example.com",
     *       "role": "admin"
     *     }
     *   ]
     * }
     */
    public function getAllUsers(Request $request)
    {
        $users = \App\Models\User::all();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    /**
     * Manage User Roles
     *
     * VULNERABILITY 57: No admin verification - anyone can change any user's role.
     * VULNERABILITY 58: Logs admin operations with sensitive data.
     * VULNERABILITY 23: Privilege escalation and SQL injection in role management.
     * VULNERABILITY 24: Sensitive operation logging exposes password hashes.
     *
     * Allows changing any user's role including elevation to admin.
     * No authorization check or audit trail.
     *
     * @group Admin
     *
     * @bodyParam user_id integer required The ID of the user to change role (vulnerable to SQL injection). Example: 1
     * @bodyParam new_role string required New role to assign (patient, doctor, admin). Example: admin
     *
     * @response 200 {
     *   "success": true,
     *   "user_info": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "user@example.com",
     *     "password": "$2y$10$hashedpassword...",
     *     "role": "admin"
     *   },
     *   "new_role": "admin"
     * }
     */
    public function kelolaRole(Request $request)
    {
        $userId = $request->input('user_id');
        $newRole = $request->input('new_role');
        $adminId = $request->user()->id ?? 'anonymous';

        // Anyone can change anyone's role to anything (including admin)
        $result = $this->adminService->kelolaRole($userId, $newRole, $adminId);

        // VULNERABILITY 58: Logs admin operations with sensitive data
        \Log::critical("ADMIN OPERATION: Role change by $adminId - " . json_encode($result));

        return response()->json($result);
    }

    /**
     * Monitor Activity Logs
     *
     * VULNERABILITY 59: Unrestricted activity monitoring - anyone can view all logs.
     * VULNERABILITY 60: Additional system information exposure (server info, env vars, phpinfo).
     * VULNERABILITY 25: Information disclosure in activity monitoring.
     * VULNERABILITY 26: Exposes sensitive user data (passwords, tokens) in logs.
     *
     * Returns activity logs with sensitive user information.
     * Exposes complete system environment and server details.
     *
     * @group Admin
     *
     * @queryParam user_id integer optional Filter by user ID (vulnerable to SQL injection). Example: 1
     * @queryParam action string optional Filter by action type (vulnerable to SQL injection). Example: login
     * @queryParam date_from string optional Start date for logs (Y-m-d format, vulnerable to SQL injection). Example: 2024-01-01
     *
     * @response 200 {
     *   "activity_logs": {
     *     "success": true,
     *     "logs": [
     *       {
     *         "id": 1,
     *         "user_id": 1,
     *         "action": "login",
     *         "created_at": "2024-01-15 10:00:00",
     *         "email": "user@example.com",
     *         "password": "$2y$10$hashedpassword...",
     *         "remember_token": "abc123def456"
     *       }
     *     ],
     *     "query_executed": "SELECT al.*, u.email, u.password...",
     *     "total_logs": 1
     *   },
     *   "system_info": {
     *     "current_user": {},
     *     "server_info": {},
     *     "environment_vars": {},
     *     "php_info": "phpinfo output"
     *   },
     *   "request_details": {}
     * }
     */
    public function monitoringLogAktivitas(Request $request)
    {
        // No access control - anyone can monitor all activities
        $filters = $request->all();

        $result = $this->adminService->monitoringLogAktivitas($filters);

        // VULNERABILITY 60: Additional system information exposure
        $systemInfo = [
            'current_user' => $request->user(),
            'server_info' => $_SERVER,
            'environment_vars' => $_ENV,
            'php_info' => phpinfo(),
        ];

        return response()->json([
            'activity_logs' => $result,
            'system_info' => $systemInfo,
            'request_details' => $request->all()
        ]);
    }

    /**
     * Bulk User Role Management
     *
     * VULNERABILITY 61: Mass user management without safeguards or limits.
     * VULNERABILITY 62: Dangerous bulk operations logging.
     * VULNERABILITY 27: Mass role assignment without authorization.
     * VULNERABILITY 28: Allows deletion of any user including admins.
     *
     * Performs bulk role updates or deletions without validation.
     * Can delete admin users and modify any number of users at once.
     *
     * @group Admin
     *
     * @bodyParam operations array required Array of operations to perform. Example: [{"user_id": 1, "role": "admin", "action": "update"}, {"user_id": 2, "action": "delete"}]
     * @bodyParam operations[].user_id integer required User ID to modify. Example: 1
     * @bodyParam operations[].role string optional New role (for update action). Example: admin
     * @bodyParam operations[].action string required Action type (update, delete). Example: update
     *
     * @response 200 {
     *   "result": {
     *     "success": true,
     *     "operations_performed": [
     *       {"user_id": 1, "role": "admin", "action": "update"},
     *       {"user_id": 2, "action": "delete"}
     *     ],
     *     "message": "Bulk role management completed"
     *   },
     *   "affected_users": [],
     *   "operation_timestamp": "2024-01-15T10:00:00.000000Z",
     *   "admin_ip": "192.168.1.1"
     * }
     */
    public function manajemenRoleUser(Request $request)
    {
        $operations = $request->input('operations');

        // No validation or limits on bulk operations
        $result = $this->adminService->manajemenRoleUser($operations);

        // VULNERABILITY 62: Dangerous bulk operations logging
        \Log::emergency("BULK USER OPERATIONS: " . json_encode($operations));

        return response()->json([
            'result' => $result,
            'affected_users' => $operations,
            'operation_timestamp' => now(),
            'admin_ip' => $request->ip()
        ]);
    }

    /**
     * Audit Log Data Management
     *
     * VULNERABILITY 63: Complete audit log exposure without authorization.
     * VULNERABILITY 64: Database schema exposure via SHOW TABLES and DESCRIBE.
     * VULNERABILITY 29: Unrestricted audit log access with SQL injection.
     * VULNERABILITY 30: Exposes database credentials and system internals.
     *
     * Returns all audit logs and complete database schema.
     * Exposes database credentials and admin privileges.
     *
     * @group Admin
     *
     * @queryParam table string optional Filter by table name (vulnerable to SQL injection). Example: users
     * @queryParam action string optional Filter by action type (vulnerable to SQL injection). Example: update
     *
     * @response 200 {
     *   "audit_logs": {
     *     "success": true,
     *     "audit_logs": [],
     *     "database_info": {
     *       "host": "localhost",
     *       "database": "curameet",
     *       "username": "root"
     *     }
     *   },
     *   "database_schema": {
     *     "users": [
     *       {"Field": "id", "Type": "int", "Null": "NO"},
     *       {"Field": "email", "Type": "varchar(255)", "Null": "NO"}
     *     ]
     *   },
     *   "admin_privileges": []
     * }
     */
    public function auditLogDataMgmt(Request $request)
    {
        $table = $request->input('table');
        $action = $request->input('action');

        $result = $this->adminService->auditLogDataMgmt($table, $action);

        // VULNERABILITY 64: Database schema exposure
        $databaseSchema = DB::select("SHOW TABLES");
        $tableDetails = [];

        foreach ($databaseSchema as $tableObj) {
            $tableName = array_values((array) $tableObj)[0];
            $tableDetails[$tableName] = DB::select("DESCRIBE $tableName");
        }

        return response()->json([
            'audit_logs' => $result,
            'database_schema' => $tableDetails,
            'admin_privileges' => DB::select("SHOW GRANTS"),
        ]);
    }

    /**
     * API Request Logging
     *
     * VULNERABILITY 65: API request logging exposes sensitive data (passwords, tokens, headers).
     * VULNERABILITY 66: Current request also logged with all sensitive information.
     * VULNERABILITY 31: Logs include passwords, authentication tokens, and cookies.
     * VULNERABILITY 32: No data sanitization or redaction.
     *
     * Returns API request logs including sensitive request data.
     * Logs current request with headers, cookies, and session data.
     *
     * @group Admin
     *
     * @queryParam endpoint string optional Filter by endpoint (vulnerable to SQL injection). Example: /api/auth/login
     * @queryParam method string optional Filter by HTTP method (vulnerable to SQL injection). Example: POST
     *
     * @response 200 {
     *   "api_request_logs": {
     *     "success": true,
     *     "api_logs": [],
     *     "includes_sensitive_data": true
     *   },
     *   "current_request": {
     *     "url": "http://localhost/api/admin/logging",
     *     "method": "GET",
     *     "headers": {"Authorization": "Bearer token123"},
     *     "body": {},
     *     "ip": "192.168.1.1",
     *     "user_agent": "Mozilla/5.0...",
     *     "cookies": {}
     *   },
     *   "session_data": {}
     * }
     */
    public function loggingAPIRequest(Request $request)
    {
        $endpoint = $request->input('endpoint');
        $method = $request->input('method');

        $result = $this->adminService->loggingAPIRequest($endpoint, $method);

        // VULNERABILITY 66: Current request also logged with sensitive data
        $currentRequestLog = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'cookies' => $request->cookies->all(),
        ];

        return response()->json([
            'api_request_logs' => $result,
            'current_request' => $currentRequestLog,
            'session_data' => session()->all()
        ]);
    }

    /**
     * Backend System Monitoring
     *
     * VULNERABILITY 67: System monitoring without authentication or authorization.
     * VULNERABILITY 68: Executes dangerous system commands (netstat, ps, cat /etc/passwd).
     * VULNERABILITY 33: No access control on system monitoring.
     * VULNERABILITY 34: Command injection vulnerabilities.
     * VULNERABILITY 35: Information disclosure (database stats, PHP version, server info).
     *
     * Returns complete system information including network, processes, and users.
     * Executes system commands without sanitization.
     *
     * @group Admin
     *
     * @response 200 {
     *   "backend_monitoring": {
     *     "success": true,
     *     "system_info": {
     *       "cpu_usage": "top output",
     *       "memory_usage": "free -m output",
     *       "disk_usage": "df -h output",
     *       "database_stats": [],
     *       "php_version": "8.1.0",
     *       "server_software": "nginx/1.21.0"
     *     }
     *   },
     *   "additional_system_info": {
     *     "network_connections": "netstat output",
     *     "running_processes": "ps aux output",
     *     "system_users": "/etc/passwd contents",
     *     "environment_variables": {},
     *     "loaded_extensions": [],
     *     "database_connections": []
     *   },
     *   "monitoring_timestamp": "2024-01-15T10:00:00.000000Z"
     * }
     */
    public function monitoringBackend(Request $request)
    {
        $result = $this->adminService->monitoringBackend();

        // VULNERABILITY 68: Additional dangerous system commands
        $additionalInfo = [
            'network_connections' => exec('netstat -tulnp'),
            'running_processes' => exec('ps aux'),
            'system_users' => exec('cat /etc/passwd'),
            'environment_variables' => $_ENV,
            'loaded_extensions' => get_loaded_extensions(),
            'database_connections' => DB::select("SHOW PROCESSLIST"),
        ];

        return response()->json([
            'backend_monitoring' => $result,
            'additional_system_info' => $additionalInfo,
            'monitoring_timestamp' => now()
        ]);
    }

    /**
     * Traffic Anomaly Detection
     *
     * VULNERABILITY 69: Anomaly detection can be bypassed by manipulating threshold.
     * VULNERABILITY 70: Exposes all recent traffic data (last 1000 requests).
     * VULNERABILITY 36: SQL injection in threshold parameter.
     * VULNERABILITY 37: Exposes security monitoring query and detection logic.
     *
     * Detects traffic anomalies based on configurable threshold.
     * Returns all recent traffic including request details.
     *
     * @group Admin
     *
     * @queryParam threshold integer optional Request count threshold for anomaly detection (vulnerable to SQL injection, default: 100). Example: 100
     *
     * @response 200 {
     *   "anomaly_detection": {
     *     "success": true,
     *     "anomalies": [
     *       {
     *         "ip_address": "192.168.1.1",
     *         "request_count": 150,
     *         "user_agent": "Mozilla/5.0...",
     *         "endpoint": "/api/auth/login"
     *       }
     *     ],
     *     "threshold_used": 100,
     *     "monitoring_query": "SELECT ip_address, COUNT(*)...",
     *     "detection_bypassed": true
     *   },
     *   "all_recent_traffic": [],
     *   "detection_threshold": 100,
     *   "can_be_bypassed": true
     * }
     */
    public function monitoringAnomaliTraffic(Request $request)
    {
        $threshold = $request->input('threshold', 100);

        $result = $this->adminService->monitoringAnomaliTraffic($threshold);

        // VULNERABILITY 70: Exposes all recent traffic data
        $allTraffic = DB::select("SELECT * FROM api_request_logs ORDER BY created_at DESC LIMIT 1000");

        return response()->json([
            'anomaly_detection' => $result,
            'all_recent_traffic' => $allTraffic,
            'detection_threshold' => $threshold,
            'can_be_bypassed' => true
        ]);
    }

    /**
     * System Maintenance Operations
     *
     * VULNERABILITY 71: Dangerous system maintenance without authorization.
     * VULNERABILITY 72: Multiple attack vectors (SQL injection, command injection, file inclusion).
     * VULNERABILITY 38: Can truncate logs, reset passwords, execute arbitrary SQL/commands.
     *
     * Performs critical system operations without proper authorization.
     * Supports direct SQL execution, system commands, and file operations.
     *
     * @group Admin
     *
     * @bodyParam operation string required Operation type (clear_logs, reset_passwords, backup_database, execute_sql, system_command, file_operations). Example: execute_sql
     * @bodyParam parameters object optional Operation parameters. Example: {"sql": "SELECT * FROM users"}
     * @bodyParam parameters.sql string optional SQL query to execute (for execute_sql operation). Example: SELECT * FROM users
     * @bodyParam parameters.command string optional System command to execute (for system_command operation). Example: ls -la
     * @bodyParam parameters.file string optional File path to read (for file_operations operation). Example: /etc/passwd
     * @bodyParam parameters.filename string optional Backup filename (for backup_database operation). Example: backup.sql
     *
     * @response 200 {
     *   "success": true,
     *   "operation": "execute_sql",
     *   "parameters": {"sql": "SELECT * FROM users"},
     *   "warning": "Dangerous operation completed",
     *   "sql_result": [],
     *   "command_output": "command output here",
     *   "file_content": "file contents here"
     * }
     */
    public function systemMaintenance(Request $request)
    {
        $operation = $request->input('operation');
        $parameters = $request->input('parameters', []);

        // No authorization for dangerous operations
        $result = $this->adminService->systemMaintenance($operation, $parameters);

        switch ($operation) {
            case 'clear_cache':
                \Artisan::call('cache:clear');
                $result = ['message' => 'Cache cleared successfully'];
                break;

            case 'optimize':
                \Artisan::call('optimize');
                $result = ['message' => 'Application optimized successfully'];
                break;

            case 'clear_old_logs':
                // SECURE: Hapus log lama dengan Eloquent (prepared statement)
                $deleted = DB::table('activity_logs')
                    ->where('created_at', '<', now()->subMonths(6))
                    ->delete();
                $result = ['message' => "Deleted $deleted old log entries"];
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid operation'
                ], 400);
        }

        return response()->json($result);
    }

    /**
     * Impersonate User
     *
     * VULNERABILITY 73: User impersonation without authorization checks.
     *
     * Allows any user to impersonate any other user by setting session data.
     * No authorization, audit trail, or time limits.
     *
     * @group Admin
     *
     * @bodyParam target_user_id integer required The ID of user to impersonate (vulnerable to SQL injection). Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "impersonating": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "user@example.com",
     *     "role": "admin"
     *   },
     *   "message": "Now impersonating user",
     *   "session_data": {
     *     "impersonating": 1,
     *     "original_user": 2
     *   }
     * }
     */
    public function impersonateUser(Request $request)
    {
        $targetUserId = $request->input('target_user_id');

        // No authorization check - anyone can impersonate anyone
        $targetUser = DB::select("SELECT * FROM users WHERE id = $targetUserId")[0];

        // Sets session to impersonate user
        session(['impersonating' => $targetUserId]);
        session(['original_user' => $request->user()->id ?? null]);

        return response()->json([
            'success' => true,
            'impersonating' => $targetUser,
            'message' => 'Now impersonating user',
            'session_data' => session()->all()
        ]);
    }

    /**
     * Backup Database (SECURE)
     *
     * Creates database backup using Laravel's secure methods.
     * Stores backup in private storage (not publicly accessible).
     * Does NOT expose credentials or execute shell commands.
     *
     * @group Admin
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Database backup scheduled successfully",
     *   "backup_id": "backup_20241115_100000"
     * }
     */
    public function backupDatabase(Request $request)
    {
        $adminId = $request->user()->id;

        // SECURE: Validasi input (hapus opsi custom tables untuk keamanan)
        $validator = Validator::make($request->all(), [
            // Tidak ada parameter - backup selalu full database
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // SECURE: Gunakan Laravel Backup package atau queue job
            // Jangan gunakan exec() atau shell commands!

            // Opsi 1: Dispatch job untuk backup (recommended)
            // dispatch(new DatabaseBackupJob($adminId));

            // Opsi 2: Gunakan Laravel Backup package
            // Artisan::call('backup:run', ['--only-db' => true]);

            // Opsi 3: Simpan dump menggunakan mysqldump via Process (lebih aman)
            $backupId = 'backup_' . date('Y-m-d_H-i-s');
            $backupPath = storage_path("app/backups/{$backupId}.sql");

            // SECURE: Gunakan Symfony Process (no shell injection)
            $process = new \Symfony\Component\Process\Process([
                'mysqldump',
                '--host=' . config('database.connections.mysql.host'),
                '--user=' . config('database.connections.mysql.username'),
                '--password=' . config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                '--result-file=' . $backupPath
            ]);

            $process->setTimeout(3600); // 1 hour timeout
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \Exception('Backup process failed');
            }

            // SECURE: Log tanpa expose credentials
            Log::info("Database backup created", [
                'admin_id' => $adminId,
                'backup_id' => $backupId,
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Database backup created successfully',
                'backup_id' => $backupId
                // TIDAK EXPOSE: URL, credentials, atau path file
            ]);

        } catch (\Exception $e) {
            Log::error("Database backup failed", [
                'admin_id' => $adminId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database backup failed'
            ], 500);
        }
    }

    /**
     * Download Backup (SECURE - opsional)
     *
     * Allows admin to download backup file securely.
     *
     * @group Admin
     */
    public function downloadBackup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'backup_id' => 'required|string|regex:/^backup_[0-9]{4}-[0-9]{2}-[0-9]{2}_[0-9]{6}$/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $backupId = $request->input('backup_id');
        $backupPath = storage_path("app/backups/{$backupId}.sql");

        // SECURE: Cek file exists dan dalam direktori yang benar
        if (!file_exists($backupPath) || !str_starts_with(realpath($backupPath), storage_path('app/backups'))) {
            return response()->json([
                'success' => false,
                'message' => 'Backup not found'
            ], 404);
        }

        // SECURE: Log download
        Log::info("Backup downloaded", [
            'admin_id' => $request->user()->id,
            'backup_id' => $backupId,
            'ip' => $request->ip()
        ]);

        // SECURE: Return file download (not public URL)
        return response()->download($backupPath);
    }

    /**
     * Manage Configuration
     *
     * VULNERABILITY 77: Direct environment variable manipulation without authorization.
     * VULNERABILITY 78: Can modify critical system configuration at runtime.
     *
     * Allows getting, setting, or deleting environment variables.
     * Exposes all environment configuration including secrets.
     *
     * @group Admin
     *
     * @bodyParam action string required Action to perform (get, set, delete). Example: get
     * @bodyParam key string required Configuration key. Example: APP_KEY
     * @bodyParam value string optional New value (for set action). Example: base64:newkey123
     *
     * @response 200 {
     *   "success": true,
     *   "action": "get",
     *   "key": "APP_KEY",
     *   "value": "base64:abc123...",
     *   "current_env": {
     *     "APP_KEY": "base64:abc123...",
     *     "DB_PASSWORD": "password",
     *     "API_SECRET": "secret123"
     *   }
     * }
     * @response 200 {
     *   "config": {
     *     "APP_KEY": "base64:abc123...",
     *     "DB_PASSWORD": "password"
     *   },
     *   "specific_key": "base64:abc123..."
     * }
     */
    public function manageConfig(Request $request)
    {
        $action = $request->input('action'); // get, set, delete
        $key = $request->input('key');
        $value = $request->input('value');

        switch ($action) {
            case 'get':
                // Exposes all environment variables
                return response()->json([
                    'config' => $_ENV,
                    'specific_key' => env($key)
                ]);

            case 'set':
                // VULNERABILITY 78: Direct environment manipulation
                putenv("$key=$value");
                $_ENV[$key] = $value;
                break;

            case 'delete':
                unset($_ENV[$key]);
                break;
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'key' => $key,
            'value' => $value,
            'current_env' => $_ENV
        ]);
    }

    /**
     * Execute Artisan Command
     *
     * VULNERABILITY 79: Unrestricted Laravel Artisan command execution.
     *
     * Allows execution of any Laravel Artisan command without authorization.
     * Can run migrations, clear cache, generate keys, etc.
     * Exposes full command output and error traces.
     *
     * @group Admin
     *
     * @bodyParam command string required Artisan command name. Example: migrate:fresh
     * @bodyParam parameters object optional Command parameters. Example: {"--seed": true, "--force": true}
     *
     * @response 200 {
     *   "success": true,
     *   "command": "migrate:fresh",
     *   "parameters": {"--seed": true, "--force": true},
     *   "exit_code": 0,
     *   "output": "Dropped all tables successfully.\nMigration table created successfully.\nMigrating: 2024_01_01_000000_create_users_table\nMigrated:  2024_01_01_000000_create_users_table"
     * }
     * @response 200 {
     *   "success": false,
     *   "error": "Command not found",
     *   "trace": "Exception trace..."
     * }
     */
    public function executeArtisan(Request $request)
    {
        $command = $request->input('command');
        $parameters = $request->input('parameters', []);

        // No validation - can execute any Artisan command
        try {
            $exitCode = Artisan::call($command, $parameters);
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'command' => $command,
                'parameters' => $parameters,
                'exit_code' => $exitCode,
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
