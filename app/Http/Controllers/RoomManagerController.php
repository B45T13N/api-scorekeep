<?php

namespace App\Http\Controllers;

use App\Models\RoomManager;
use Illuminate\Http\Request;

class RoomManagerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function insert(string $name, string $email)
    {
        try
        {
            $roomManager = new RoomManager();
            $roomManager->name = $name;
            $roomManager->email = $email;

            $roomManager->save();
        }
        catch (\Throwable $e)
        {
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        try
        {
            $roomManager = new RoomManager();

            $roomManager->name = $validatedData['name'];
            $roomManager->email = $validatedData['email'];

            $roomManager->save();
        }
        catch (\Throwable $e)
        {
            throw $e;
        }
    }
}
