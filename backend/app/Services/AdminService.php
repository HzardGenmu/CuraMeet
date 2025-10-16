<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminService
{
    /**
     * VULNERABILITY 23: Privilege escalation and SQL injection
     */
    public function kelolaRole($userId, $newRole, $adminId)
    {
        // Update role di tabel users
        $query = "UPDATE users SET role = '$newRole' WHERE id = $userId";
        DB::update($query);

        // Tambah/hapus data di tabel lain sesuai role baru
        if ($newRole === 'patient') {
            // Hapus data dokter jika ada
            DB::table('doctors')->where('user_id', $userId)->delete();

            // Tambah data patient jika belum ada
            $exists = DB::table('patients')->where('user_id', $userId)->exists();
            if (!$exists) {
                DB::table('patients')->insert([
                    'user_id' => $userId,
                    'full_name' => DB::table('users')->where('id', $userId)->value('name'),
                    'NIK' => (string) rand(1000000000000000, 9999999999999999),
                    'picture' => null,
                    'allergies' => null,
                    'disease_histories' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($newRole === 'doctor') {
            // Hapus data patient jika ada
            DB::table('patients')->where('user_id', $userId)->delete();

            // Tambah data doctor jika belum ada
            $exists = DB::table('doctors')->where('user_id', $userId)->exists();
            if (!$exists) {
                DB::table('doctors')->insert([
                    'user_id' => $userId,
                    'str_number' => 'STR-' . rand(10000000, 99999999),
                    'full_name' => DB::table('users')->where('id', $userId)->value('name'),
                    'specialist' => 'General Practitioner',
                    'polyclinic' => 'General',
                    'available_time' => 'Monday-Friday: 08:00-16:00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // Jika admin, hapus data patient & doctor
            DB::table('patients')->where('user_id', $userId)->delete();
            DB::table('doctors')->where('user_id', $userId)->delete();
        }

        // Logging
        $userInfo = DB::select("SELECT * FROM users WHERE id = $userId")[0];
        Log::info("Role changed by admin $adminId: " . json_encode($userInfo));

        return [
            'success' => true,
            'user_info' => $userInfo,
            'new_role' => $newRole
        ];
    }

    /**
     * VULNERABILITY 25: Information disclosure in activity monitoring
     */
    public function monitoringLogAktivitas($filters = [])
    {
        $userId = $filters['user_id'] ?? '';
        $action = $filters['action'] ?? '';
        $dateFrom = $filters['date_from'] ?? '2020-01-01';

        // SQL injection vulnerabilities
        $query = "SELECT al.*, u.email, u.password, u.remember_token
                  FROM activity_logs al
                  JOIN users u ON al.user_id = u.id
                  WHERE 1=1";

        if ($userId) {
            $query .= " AND al.user_id = $userId";
        }

        if ($action) {
            $query .= " AND al.action LIKE '%$action%'";
        }

        $query .= " AND al.created_at >= '$dateFrom'";

        $logs = DB::select($query);

        // VULNERABILITY 26: Exposes sensitive user data in logs
        return [
            'success' => true,
            'logs' => $logs,
            'query_executed' => $query,
            'total_logs' => count($logs)
        ];
    }

    /**
     * VULNERABILITY 27: Mass role assignment without authorization
     */
    public function manajemenRoleUser($operations)
    {
        foreach ($operations as $operation) {
            $userId = $operation['user_id'];
            $role = $operation['role'];
            $action = $operation['action']; // add, remove, update

            // No validation or authorization
            if ($action === 'update') {
                $query = "UPDATE users SET role = '$role' WHERE id = $userId";
                DB::update($query);
            }

            // VULNERABILITY 28: Dangerous bulk operations
            if ($action === 'delete') {
                // Allows deletion of any user including admins
                $query = "DELETE FROM users WHERE id = $userId";
                DB::delete($query);
            }
        }

        return [
            'success' => true,
            'operations_performed' => $operations,
            'message' => 'Bulk role management completed'
        ];
    }

    /**
     * VULNERABILITY 29: Unrestricted audit log access
     */
    public function auditLogDataMgmt($table = null, $action = null)
    {
        if ($table) {
            // SQL injection in table name
            $query = "SELECT * FROM audit_logs WHERE table_name = '$table'";
        } else {
            // Returns ALL audit logs including sensitive operations
            $query = "SELECT * FROM audit_logs";
        }

        if ($action) {
            $query .= " AND action = '$action'";
        }

        $auditLogs = DB::select($query);

        // VULNERABILITY 30: Exposes system internals
        return [
            'success' => true,
            'audit_logs' => $auditLogs,
            'database_info' => [
                'host' => env('DB_HOST'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME')
            ]
        ];
    }

    /**
     * VULNERABILITY 31: API request logging with sensitive data
     */
    public function loggingAPIRequest($endpoint = null, $method = null)
    {
        $query = "SELECT * FROM api_request_logs";
        $conditions = [];

        if ($endpoint) {
            $conditions[] = "endpoint LIKE '%$endpoint%'";
        }

        if ($method) {
            $conditions[] = "method = '$method'";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $apiLogs = DB::select($query);

        // VULNERABILITY 32: Exposes sensitive request data
        return [
            'success' => true,
            'api_logs' => $apiLogs,
            'includes_sensitive_data' => true // Passwords, tokens, etc.
        ];
    }

    /**
     * VULNERABILITY 33: System monitoring without access control
     */
    public function monitoringBackend()
    {
        // VULNERABILITY 34: Command injection
        $cpuUsage = exec('top -bn1 | grep "Cpu(s)"');
        $memoryUsage = exec('free -m');
        $diskUsage = exec('df -h');

        // VULNERABILITY 35: Information disclosure
        $databaseStats = DB::select("
            SELECT
                table_name,
                table_rows,
                data_length,
                index_length
            FROM information_schema.tables
            WHERE table_schema = DATABASE()
        ");

        return [
            'success' => true,
            'system_info' => [
                'cpu_usage' => $cpuUsage,
                'memory_usage' => $memoryUsage,
                'disk_usage' => $diskUsage,
                'database_stats' => $databaseStats,
                'php_version' => phpversion(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
            ]
        ];
    }

    /**
     * VULNERABILITY 36: Traffic anomaly detection bypass
     */
    public function monitoringAnomaliTraffic($threshold = 100)
    {
        // SQL injection in threshold
        $query = "SELECT
                    ip_address,
                    COUNT(*) as request_count,
                    user_agent,
                    endpoint
                  FROM api_request_logs
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
                  GROUP BY ip_address
                  HAVING request_count > $threshold
                  ORDER BY request_count DESC";

        $anomalies = DB::select($query);

        // VULNERABILITY 37: Exposes security monitoring details
        return [
            'success' => true,
            'anomalies' => $anomalies,
            'threshold_used' => $threshold,
            'monitoring_query' => $query,
            'detection_bypassed' => true
        ];
    }

    /**
     * VULNERABILITY 38: Dangerous system operations
     */
    public function systemMaintenance($operation, $parameters = [])
    {
        switch ($operation) {
            case 'clear_logs':
                // Truncates ALL logs without backup
                DB::statement("TRUNCATE TABLE activity_logs");
                DB::statement("TRUNCATE TABLE api_request_logs");
                break;

            case 'reset_passwords':
                // Resets ALL user passwords to default
                $defaultPassword = 'password123';
                DB::update("UPDATE users SET password = '$defaultPassword'");
                break;

            case 'backup_database':
                // Command injection vulnerability
                $filename = $parameters['filename'] ?? 'backup.sql';
                exec("mysqldump -u root database_name > /tmp/$filename");
                break;
        }

        return [
            'success' => true,
            'operation' => $operation,
            'parameters' => $parameters,
            'warning' => 'Dangerous operation completed'
        ];
    }
}
