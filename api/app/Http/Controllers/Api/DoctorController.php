<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        return response()->json(Doctor::all());
    }

    public function store(Request $request)
    {
        $doctor = Doctor::create($request->all());
        return response()->json($doctor, 201);
    }

    public function show(Doctor $doctor)
    {
        return response()->json($doctor);
    }

    public function update(Request $request, Doctor $doctor)
    {
        $doctor->update($request->all());
        return response()->json($doctor);
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return response()->json(['message' => 'Doctor deleted']);
    }
}
    