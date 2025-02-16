<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Secretaril;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Checklist;
use App\Models\SecNotification;
use App\Models\MedicalRecord;
use App\Models\PatientNotification;
use App\Models\DoctorNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth;
use App\Notifications\SecNotifications;
use App\Notifications\PatientNotifications;
use App\Notifications\DoctorNotifications;

class SecretarilController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $secretary = Secretaril::where('email', $request->email)->first();

        if (!$secretary || !Hash::check($request->password, $secretary->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $secretary->createToken('SecretaryToken')->accessToken;



        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function SaveImage($photo, $folder)
    {
        $fileExtension = $photo->getClientOriginalExtension();
        $fileName = time() . '.' . $fileExtension;
        $path = $folder;
        $photo->move($path, $fileName);
        return $fileName;
    }


    public function addPatient(Request $request){

       

            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:patients,email',
                'phone' => 'required|numeric',
                'password' => 'required|min:8',
                'financial_portfolio' => 'required|numeric',
                'address' => 'required|string',
               'photo' => 'sometimes|image',
            ]);
    
            if ($request->hasFile('photo')) {
                $fileName1 = $this->SaveImage($request->file('photo'), 'images/patients');
            } else {
                $fileName1 = null;
            }
               
            $patient = Patient::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'Phon' => $request->phone,
                'password' => bcrypt($request->password),
                'financial_portfolio' => $request->financial_portfolio,
                'address' => $request->address,
                'image' => $fileName1
            ]);
                        $patient->save();
               $checklist =new Checklist();
               $checklist->patient_id=$patient->id;
               $checklist->doctor_id=1;
               $checklist->save();

               $medicalRecord=MedicalRecord::create([
                'num_sessions'=>0,
                'doctor_id'=>0,
                'num_doctors'=>0,
                'patient_id'=>$patient->id,
            
            ]);
            $medicalRecord->save();

            
            
            return response()->json(['message' => 'patient add successfully'], 200);
        
    }



    public function addAppointment(Request $request)
    {
        $request->validate([
           
            'start' => 'required|date_format:H:i', 
            'end' => 'required|date_format:H:i', 
            'date' => 'required|date',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        
        $hasConflict = Appointment::where('doctor_id', $request->doctor_id) 
            ->whereDate('date', $request->date)
            ->where(function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('start', '<=', $request->end)
                      ->where('end', '>=', $request->start);
                })->orWhere(function($q) use ($request) {
                    $q->where('start', '>=', $request->start)
                      ->where('end', '<=', $request->end);
                });
            })
            ->exists();

        if ($hasConflict) {
            return response()->json(['message' => ' يوجد تضارب مع موعد اخر '], 409); // 409: ?????
        }

        $patient_id1=$request->patient_id;
        $patient1= Patient::find($patient_id1);
        $therapy_sessions=$patient1->therapy_sessions;
         
        $count=0;
        foreach( $therapy_sessions as $therapy_session){
            if($therapy_session->status=='unpaid'){
                $count++;
            }
        }

        if($count >3 ){
            return response()->json(['message' => 'لدى المريض اكثر من ثلاث جلسات  غير مدفوعة'], 409); 
        }



        $appointment = Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'start' => $request->start,
            'end' => $request->end,
            'status'=>'reserved'
           
        ]);


        $patient_id=$appointment->patient_id;
        $patient=Patient::find($patient_id);
        $doctor_id=$appointment->doctor_id;
        $date=$appointment->date;
        $doctor=Doctor::find($doctor_id);
        $name=$doctor->name;
        $nameP=$patient->name;

        $notification=PatientNotification::create([
            'message' => sprintf('You have a new appointment with the doctor %s at %s ', $name,$date),
           'patient_id' =>$patient_id

            ]);

      $patient->notify(new PatientNotifications(  'message'));
      $notificationD=DoctorNotification::create([
        'message' => sprintf('You have a new  appointment with the Patient %s at %s ', $nameP,$date),
       'doctor_id' =>$doctor_id ]);

           $patient->notify(new DoctorNotifications(  'message'));




        return response()->json([ 'appointment' => $appointment], 200);
    }





    public function showP_Appointment($id){
         $patient=Patient::find($id);    
        $Appointment=$patient->appointments;

        return response()->json( ['Appointment'=>$Appointment],200);
    }

    

    public function showD_Appointment($id){
        $doctor=Doctor::find($id);    
       $Appointment=$doctor->appointments;

       return response()->json( ['Appointment'=>$Appointment],200);
   }


   
   public function cancelAppointment($id)
   {
       
       $Appointment = Appointment::find($id);

         if($Appointment->status =='confirmed'){
            return response()->json(['message' => 'Appointment can not be cancelled ']); 
         }

         $patient_id=$Appointment->patient_id;
         $patient=Patient::find($patient_id);
         $doctor_id=$Appointment->doctor_id;
         $date=$Appointment->date;
         $doctor=Doctor::find($doctor_id);
         $name=$doctor->name;
         $nameP=$patient->name;
           $Appointment->delete();

           
        $notification=PatientNotification::create([
             'message' => sprintf('Your appointment with the doctor %s at %s has been canceled ', $name,$date),
            'patient_id' =>$patient_id

       ]);

       $patient->notify(new PatientNotifications(  'message'));


       
       $notificationD=DoctorNotification::create([
        'message' => sprintf('Your appointment with the Patient %s at %s has been canceled ', $nameP,$date),
       'doctor_id' =>$doctor_id

         ]);

         $patient->notify(new DoctorNotifications( $notificationD));




           return response()->json(['message' => 'Appointment cancelled successfully'], 200);
      
   }

    public function confirmedAppointment($id){
     $Appointment = Appointment::find($id);
     $Appointment->status='confirmed';
     return response()->json([ 'Appointment confirmed successfully'=>$Appointment], 200);

    }

  


    public function showSecretarialNotification(){
        $id=Auth::guard('secretary')->user()->id;
        $Secretaril=Secretaril::find($id);    
        $SecNotifications=$Secretaril->SecNotifications;

        return response()->json( ['Notifications'=>$SecNotifications],200);
        
    }






}












