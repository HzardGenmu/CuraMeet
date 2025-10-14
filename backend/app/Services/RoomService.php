<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RoomService
{
    public function listRooms()
    {
        // FIXED: Use query builder
        $rooms = DB::table('rooms')
            ->select('id', 'name', 'available')
            ->get();
            
        return [
            'success' => true,
            'rooms' => $rooms,
            'count' => count($rooms)
        ];
    }
}
