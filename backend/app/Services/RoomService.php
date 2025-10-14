<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class RoomService
{
    public function listRooms()
    {
        $rooms = DB::select('SELECT id, name, available FROM rooms');
        return [
            'success' => true,
            'rooms' => $rooms,
            'count' => count($rooms)
        ];
    }
}
