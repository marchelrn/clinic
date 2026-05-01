<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpParser\Comment\Doc;
use App\Http\Resources\DoctorResource;

class DoctorController extends Controller
{
    public function index()
    {
        $doctor = Doctor::all();

        return ApiResponse::success(
            'Doctors retrieved successfully', 
            DoctorResource::collection($doctor),
            200
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|numeric|unique:doctors',
            'email' => 'required|email|unique:doctors',
            'password' => 'required|min:6' 
        ]);

        if ($validator->fails()) {
            return ApiResponse::error(
                $validator->errors(),
                400
            );
        }

        $specialization = $request->input('specialization') ?: 'Dokter Umum';

        $doctor = Doctor::create(
            [
                'name' => $request->name,
                'specialization' => $specialization,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'active'
            ]
        );
        return ApiResponse::success(
            'Doctor created successfully',
            new DoctorResource($doctor),
            200
        );
    }

    public function show(Doctor $doctor)
    {   
        return ApiResponse::success(
            'Doctor retrieved successfully',
            new DoctorResource($doctor),
            200
        );
    }

    public function update(Request $request, Doctor $doctor)
    {
        $doctor->update($request->all());
        return ApiResponse::success(
            'Doctor updated successfully',
            new DoctorResource($doctor),
            200
        );
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return ApiResponse::success(
            'Doctor deleted successfully',
        );
    }
}
