<?php

namespace App\Http\Controllers;

use App\Models\Timekeeper;
use Illuminate\Http\Request;

class TimekeeperController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function insert(string $name, string $email)
    {
        try
        {
            $timekeeper = new Timekeeper();
            $timekeeper->name = $name;
            $timekeeper->email = $email;

            $timekeeper->save();
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
            $timekeeper = new Timekeeper();

            $timekeeper->name = $validatedData['name'];
            $timekeeper->email = $validatedData['email'];

            $timekeeper->save();
        }
        catch (\Throwable $e)
        {
            throw $e;
        }
    }
}
