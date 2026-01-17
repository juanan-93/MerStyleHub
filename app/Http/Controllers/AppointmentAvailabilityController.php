<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentAvailabilityController extends Controller
{
    public function index()
    {
        return view('admin_appointments.index');
    } 
    
    public function create()
    {
        return view('admin_appointments.create');
    } 

    public function edit()
    {
        return view('admin_appointments.edit');
    } 
    
}
