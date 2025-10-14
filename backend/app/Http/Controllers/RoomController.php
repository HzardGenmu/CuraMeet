<?php

namespace App\Http\Controllers;

use App\Services\RoomService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    protected $roomService;

    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function listRooms()
    {
        $result = $this->roomService->listRooms();
        return response()->json($result);
    }
}
