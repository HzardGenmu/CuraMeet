<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminService
{
    /**
     * VULNERABILITY: Broken Access Control - Role management without authorization
     * INTENTIONALLY KEPT - No verification if admin has permission
     */
    public function kelolaRole($userId, $newRole, $adminId)
    {
        // Broken Access Control vulnerability kept - no permission check
        // But FIXED: Use parameterized query
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'role' => $newRole,
                'updated_at' => now()
            ]);

        // FIXED: Don't log sensitive data or expose passwords
        $userInfo = DB::table('users')
            ->where('id', $userId)
            ->select('id', 'name', 'email', 'role')
            ->first();
            
        Log::info("Role changed by admin", [
            'admin_id' => $adminId,
            'user_id' => $userId,
            'new_role' => $newRole
        ]);

        // FIXED: Don't expose password hash
        return [
            'success' => true,
            'user_info' => $userInfo,
            'new_role' => $newRole
        ];
    }

    /**
     * FIXED: Activity monitoring without SQL injection
     */
    public function monitoringLogAktivitas($filters = [])
    {
        $userId = $filters['user_id'] ?? null;
        $action = $filters['action'] ?? null;
        $dateFrom = $filters['date_from'] ?? '2020-01-01';

        // FIXED: Use query builder to prevent SQL injection
        $query = DB::table('activity_logs as al')
            ->join('users as u', 'al.user_id', '=', 'u.id')
            ->select('al.*', 'u.email', 'u.name');

        if ($userId) {
            $query->where('al.user_id', $userId);
        }

        if ($action) {
            $query->where('al.action', 'LIKE', '%' . $action . '%');
        }

        $query->where('al.created_at', '>=', $dateFrom);

        $logs = $query->get();

        // FIXED: Don't expose sensitive data
        return [
            'success' => true,
            'logs' => $logs,
            'total_logs' => count($logs)
        ];
    }

    /**
     * VULNERABILITY: Broken Access Control - Mass role assignment without authorization
     * INTENTIONALLY KEPT - No authorization check
     */
    public function manajemenRoleUser($operations)
    {
        // Broken Access Control kept - no authorization check
        // But FIXED: Use parameterized queries and limit dangerous operations
        
        foreach ($operations as $operation) {
            $userId = $operation['user_id'];
            $role = $operation['role'];
            $action = $operation['action']; // add, remove, update

            // FIXED: Use proper query builder
            if ($action === 'update') {
                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'role' => $role,
                        'updated_at' => now()
                    ]);
            }

            // FIXED: Remove dangerous delete operation
            // Deletion should be a separate, more protected operation
        }

        return [
            'success' => true,
            'operations_count' => count($operations),
            'message' => 'Bulk role management completed'
        ];
    }

    /**
     * FIXED: Audit log access without SQL injection
     */
    public function auditLogDataMgmt($table = null, $action = null)
    {
        // FIXED: Use query builder to prevent SQL injection
        $query = DB::table('audit_logs');

        if ($table) {
            $query->where('table_name', $table);
        }

        if ($action) {
            $query->where('action', $action);
        }

        $auditLogs = $query->get();

        // FIXED: Don't expose database credentials
        return [
            'success' => true,
            'audit_logs' => $auditLogs
        ];
    }

    /**
     * FIXED: API request logging without SQL injection
     */
    public function loggingAPIRequest($endpoint = null, $method = null)
    {
        // FIXED: Use query builder to prevent SQL injection
        $query = DB::table('api_request_logs');

        if ($endpoint) {
            $query->where('endpoint', 'LIKE', '%' . $endpoint . '%');
        }

        if ($method) {
            $query->where('method', $method);
        }

        $apiLogs = $query->get();

        return [
            'success' => true,
            'api_logs' => $apiLogs
        ];
    }

    /**
     * FIXED: System monitoring without command injection
     */
    public function monitoringBackend()
    {
        // FIXED: Don't execute system commands, use PHP functions
        $databaseStats = DB::table('information_schema.tables')
            ->where('table_schema', DB::getDatabaseName())
            ->select('table_name', 'table_rows', 'data_length', 'index_length')
            ->get();

        return [
            'success' => true,
            'system_info' => [
                'database_stats' => $databaseStats,
                'php_version' => phpversion()
            ]
        ];
    }

    /**
     * FIXED: Traffic anomaly detection without SQL injection
     */
    public function monitoringAnomaliTraffic($threshold = 100)
    {
        // FIXED: Use query builder with parameter binding
        $anomalies = DB::table('api_request_logs')
            ->select('ip_address', DB::raw('COUNT(*) as request_count'), 'user_agent', 'endpoint')
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 HOUR)'))
            ->groupBy('ip_address', 'user_agent', 'endpoint')
            ->having('request_count', '>', $threshold)
            ->orderBy('request_count', 'DESC')
            ->get();

        return [
            'success' => true,
            'anomalies' => $anomalies,
            'threshold_used' => $threshold
        ];
    }

    /**
     * FIXED: System maintenance without dangerous operations
     */
    public function systemMaintenance($operation, $parameters = [])
    {
        // FIXED: Remove dangerous operations
        switch ($operation) {
            case 'clear_logs':
                // Only allow clearing old logs, not all
                $daysToKeep = $parameters['days_to_keep'] ?? 30;
                DB::table('activity_logs')
                    ->where('created_at', '<', now()->subDays($daysToKeep))
                    ->delete();
                DB::table('api_request_logs')
                    ->where('created_at', '<', now()->subDays($daysToKeep))
                    ->delete();
                break;

            default:
                return [
                    'success' => false,
                    'message' => 'Unknown operation'
                ];
        }

        return [
            'success' => true,
            'operation' => $operation,
            'message' => 'Operation completed successfully'
        ];
    }
}
