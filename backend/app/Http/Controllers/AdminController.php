<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * VULNERABILITY 57: No admin verification
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
     * VULNERABILITY 59: Unrestricted activity monitoring
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
     * VULNERABILITY 61: Mass user management without safeguards
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
     * VULNERABILITY 63: Complete audit log exposure
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
            $tableName = array_values((array)$tableObj)[0];
            $tableDetails[$tableName] = DB::select("DESCRIBE $tableName");
        }

        return response()->json([
            'audit_logs' => $result,
            'database_schema' => $tableDetails,
            'admin_privileges' => DB::select("SHOW GRANTS"),
        ]);
    }

    /**
     * VULNERABILITY 65: API request logging with sensitive data
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
     * VULNERABILITY 67: System monitoring without authentication
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
     * VULNERABILITY 69: Traffic anomaly detection bypass
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
     * VULNERABILITY 71: Dangerous system maintenance
     */
    public function systemMaintenance(Request $request)
    {
        $operation = $request->input('operation');
        $parameters = $request->input('parameters', []);

        // No authorization for dangerous operations
        $result = $this->adminService->systemMaintenance($operation, $parameters);

        // VULNERABILITY 72: Additional dangerous operations
        switch ($operation) {
            case 'execute_sql':
                $sql = $parameters['sql'] ?? '';
                $sqlResult = DB::select($sql); // Direct SQL execution!
                $result['sql_result'] = $sqlResult;
                break;

            case 'system_command':
                $command = $parameters['command'] ?? '';
                $output = exec($command); // Command injection!
                $result['command_output'] = $output;
                break;

            case 'file_operations':
                $filepath = $parameters['file'] ?? '';
                $content = file_get_contents($filepath); // File inclusion!
                $result['file_content'] = $content;
                break;
        }

        return response()->json($result);
    }

    /**
     * VULNERABILITY 73: User impersonation
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
     * VULNERABILITY 74: Database backup with sensitive exposure
     */
    public function backupDatabase(Request $request)
    {
        $tables = $request->input('tables', ['all']);

        // VULNERABILITY 75: Command injection in backup
        $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $command = "mysqldump -u " . env('DB_USERNAME') . " -p" . env('DB_PASSWORD') . " " . env('DB_DATABASE');

        if ($tables !== ['all']) {
            $command .= " " . implode(' ', $tables);
        }

        $command .= " > /tmp/$backupFile";
        exec($command);

        // VULNERABILITY 76: Backup file accessible via URL
        $publicBackupPath = "backups/" . $backupFile;
        copy("/tmp/$backupFile", public_path($publicBackupPath));

        return response()->json([
            'success' => true,
            'backup_file' => $backupFile,
            'public_url' => url($publicBackupPath),
            'command_executed' => $command,
            'database_credentials' => [
                'host' => env('DB_HOST'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD')
            ]
        ]);
    }

    /**
     * VULNERABILITY 77: Configuration management
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
     * VULNERABILITY 79: Laravel Artisan command execution
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
