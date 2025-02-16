<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\TherapySession;
use App\Models\Treatment;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;

class Statistics extends Controller
{
    public function statistics()
{
    $patientCount = TherapySession::countPatients();
    $mostActiveDoctor = TherapySession::mostActiveDoctor();
    $mostUsedTreatment = TherapySession::mostUsedTreatment();

    return response()->json([
        'patient_count' => $patientCount,
        'most_active_doctor' => $mostActiveDoctor,
        'most_used_treatment' => $mostUsedTreatment,
    ]);
}


public function statisticsDoctorWithSession(){

    $doctorsData = Doctor::getAllDoctorsWithSessionCount();
return response()->json($doctorsData, 200);

}
}
