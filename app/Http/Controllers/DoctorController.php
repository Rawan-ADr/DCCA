<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Checklist;
use App\Models\Problem;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use Carbon\Carbon;
use App\Models\TherapySession;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth;
use App\Models\DoctorNotification;
use App\Notifications\DoctorNotifications;

class DoctorController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $doctor = Doctor::where('email', $request->email)->first();

        if (!$doctor || !Hash::check($request->password, $doctor->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $doctor->createToken('DoctorToken')->accessToken;

       

        return response()->json(['token' => $token], 200);
    }


    
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function search(Request $request){

        $name=$request->input('name');
        $patients=Patient::where('name','like','%'. $name.'%')->get();

                    if($patients->isEmpty()){
                      return response()->json(['message'=>'Not Found'],404);
                       }
        return response()->json(['patients'=>$patients],200);

    }


    public function show_DAppointment(){
        $id=Auth::guard('doctor')->user()->id; 
        $doctor=Doctor::find($id);   
       $Appointment=$doctor->appointments;

       return response()->json( ['Appointment'=>$Appointment],200);
   }
        
   public function profile(){
    $id=Auth::guard('doctor')->user()->id;
    $doctor=Doctor::find($id);   
    return response()->json( ['profile'=>$doctor],200);

   }


   public function addCheklist(Request $request){
           $id=$request->input('patient_id');
          $checklist= Checklist::Where('patient_id',$id)->first();;
         
          $checklist->doctor_id=Auth::guard('doctor')->user()->id;
          $checklist->totalTime=$checklist->problems()->sum('Expected_number_of_sessions')*2;
          $checklist->totalPrice=$checklist->problems()->sum('expected_cost');
          $checklist->save();

          return response()->json(['message'=>'Add successfully'],200);

   }

   public function getCheklist($id){
    
         $patient=Patient::find($id); 
         $checklist=$patient->checklist;
         return response()->json( ['Cheklist'=>$checklist],200);
    
   }


   public function addProblem(Request $request){
            
          $probleme=new Problem();
          $probleme->name=$request->input('name');
          $probleme->tooth_name=$request->input('tooth_name');
          $probleme->expected_cost=$request->input('expected_cost');
          $probleme->Expected_number_of_sessions=$request->input('Expected_number_of_sessions');
          $probleme->damege_status=$request->input('damege_status');
          $probleme->status=$request->input('status');
          $probleme->checklist_id=$request->input('checklist_id');

                 $probleme->save();

                 return response()->json(['message'=>'Add successfully'],200);


   }

   public function getProblem($id){

        $checklist=Checklist::find($id); 
         $probleme=$checklist->problems;
         return response()->json( ['problemes'=>$probleme],200);
   }


   public function addTherapySession(Request $request){
         $TherapySession= new TherapySession();
            
         $TherapySession->patient_id=$request->input('patient_id');
         $TherapySession->treatment_id=$request->input('treatment_id');
         $TherapySession->problem_id=$request->input('problem_id');
         $TherapySession->doctor_id=Auth::guard('doctor')->user()->id;
         $TherapySession->date=Carbon::now();
         $TherapySession->status='unpaid';

         $TherapySession->save();

         $medicalRecord = MedicalRecord::where('patient_id', $TherapySession->patient_id)->first();

      
         $medicalRecord->num_sessions++;
         if (!$medicalRecord->first_sessions) {
             $medicalRecord->first_sessions = $TherapySession->date;
             $medicalRecord->last_sessions = $TherapySession->date;
     
             $medicalRecord->num_doctors++; 
         } else {
            
             $medicalRecord->last_sessions = $TherapySession->date;
     
             if ($medicalRecord->num_doctors == 0 || $medicalRecord->doctor_id != $TherapySession->doctor_id) {
                 $medicalRecord->num_doctors++;
                 $medicalRecord->doctor_id = $TherapySession->doctor_id;
             }
         }
     
         $medicalRecord->save();
         return response()->json(['message'=>'Add successfully'],200);


   }

   public function showDoctorNotification(){
    $id=Auth::guard('doctor')->user()->id; 
    $doctor=Doctor::find($id);    
    $DoctorNotifications=$doctor->DoctorNotifications;

    return response()->json( ['Notifications'=>$DoctorNotifications],200);
    
}

   
public function showAllAppointmentByDay(){
    $id=Auth::guard('doctor')->user()->id; 
    $doctor=Doctor::find($id);
    $doctorId=$doctor->id;


    $today=Carbon::today();
    $Appointment = Appointment::where('doctor_id', $id)->whereDate('date',$today)->get();

    return response()->json( ['Appointment'=>$Appointment],200);
}



}
