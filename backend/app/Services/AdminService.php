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
        DB::table('users')->where('id', $userId)->update(['role' => $newRole]);


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
        $userInfo = DB::table('users')->where('id', $userId)->first();
        Log::info("Role changed by admin $adminId: " . json_encode($userInfo));
        return [
            'success' => true,
            'user_info' => $userInfo,
            'new_role' => $newRole
        ];
    }

    public function manajemenRoleUser($operations)
    {
        foreach ($operations as $operation) {
            $userId = $operation['user_id'];
            $role = $operation['role'];
            $action = $operation['action']; // add, remove, update

            // No validation or authorization
            if ($action === 'update') {
                // FIX: Use parameter binding
                DB::table('users')->where('id', $userId)->update(['role' => $role]);
            }

            // VULNERABILITY 28: Dangerous bulk operations
            if ($action === 'delete') {
                // FIX: Use parameter binding
                DB::table('users')->where('id', $userId)->delete();
            }
        }

        return [
            'success' => true,
            'operations_performed' => $operations,
            'message' => 'Bulk role management completed'
        ];
    }

}
