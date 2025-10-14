<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * VULNERABILITY: Broken Access Control - No admin verification
     * INTENTIONALLY KEPT - Anyone can change roles
     */
    public function kelolaRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'new_role' => 'required|string|in:patient,doctor,admin'
        ]);

        $userId = $request->input('user_id');
        $newRole = $request->input('new_role');
        $adminId = $request->user()->id ?? 'anonymous';

        // Broken Access Control - no permission check
        $result = $this->adminService->kelolaRole($userId, $newRole, $adminId);

        // FIXED: Don't log sensitive data
        Log::info("Role change operation", [
            'admin_id' => $adminId,
            'user_id' => $userId,
            'new_role' => $newRole
        ]);

        return response()->json($result);
    }

    /**
     * FIXED: Activity monitoring with validation
     */
    public function monitoringLogAktivitas(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'action' => 'nullable|string|max:255',
            'date_from' => 'nullable|date'
        ]);

        $filters = $request->all();
        $result = $this->adminService->monitoringLogAktivitas($filters);

        // FIXED: Don't expose system information
        return response()->json([
            'activity_logs' => $result
        ]);
    }

    /**
     * VULNERABILITY: Broken Access Control - Mass user management
     * INTENTIONALLY KEPT - No authorization check
     */
    public function manajemenRoleUser(Request $request)
    {
        $request->validate([
            'operations' => 'required|array',
            'operations.*.user_id' => 'required|integer|exists:users,id',
            'operations.*.role' => 'required|string|in:patient,doctor,admin',
            'operations.*.action' => 'required|string|in:update'
        ]);

        $operations = $request->input('operations');

        // Broken Access Control - no validation or limits on bulk operations
        $result = $this->adminService->manajemenRoleUser($operations);

        // FIXED: Log without sensitive data
        Log::info("Bulk user operations", [
            'count' => count($operations),
            'admin_ip' => $request->ip()
        ]);

        return response()->json($result);
    }

    /**
     * FIXED: Audit log management with validation
     */
    public function auditLogDataMgmt(Request $request)
    {
        $request->validate([
            'table' => 'nullable|string|max:255',
            'action' => 'nullable|string|max:255'
        ]);

        $table = $request->input('table');
        $action = $request->input('action');

        $result = $this->adminService->auditLogDataMgmt($table, $action);

        // FIXED: Don't expose database schema
        return response()->json($result);
    }

    /**
     * FIXED: API request logging with validation
     */
    public function loggingAPIRequest(Request $request)
    {
        $request->validate([
            'endpoint' => 'nullable|string|max:255',
            'method' => 'nullable|string|in:GET,POST,PUT,PATCH,DELETE'
        ]);

        $endpoint = $request->input('endpoint');
        $method = $request->input('method');

        $result = $this->adminService->loggingAPIRequest($endpoint, $method);

        // FIXED: Don't log current request sensitive data
        return response()->json($result);
    }

    /**
     * FIXED: System monitoring with proper access control needed
     */
    public function monitoringBackend(Request $request)
    {
        $result = $this->adminService->monitoringBackend();

        // FIXED: Don't execute dangerous system commands
        return response()->json($result);
    }

    /**
     * FIXED: Traffic anomaly detection with validation
     */
    public function monitoringAnomaliTraffic(Request $request)
    {
        $request->validate([
            'threshold' => 'nullable|integer|min:1|max:10000'
        ]);

        $threshold = $request->input('threshold', 100);
        $result = $this->adminService->monitoringAnomaliTraffic($threshold);

        return response()->json($result);
    }

    /**
     * FIXED: System maintenance with limited operations
     */
    public function systemMaintenance(Request $request)
    {
        $request->validate([
            'operation' => 'required|string|in:clear_logs',
            'parameters' => 'nullable|array',
            'parameters.days_to_keep' => 'nullable|integer|min:1|max:365'
        ]);

        $operation = $request->input('operation');
        $parameters = $request->input('parameters', []);

        // FIXED: Only allow safe operations
        $result = $this->adminService->systemMaintenance($operation, $parameters);

        return response()->json($result);
    }

    /**
     * REMOVED: Dangerous operations removed
     * - impersonateUser
     * - backupDatabase
     * - manageConfig
     * - executeArtisan
     */
}
