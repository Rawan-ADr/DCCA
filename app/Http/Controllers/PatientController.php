<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Checklist;
use App\Models\TherapySession;
use App\Models\Article;
use App\Models\Bill;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Doctor_Day;
use App\Models\Day;
use App\Models\Treatment;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function register(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:patients,email',
            'phone' => 'required|numeric',
            'password' => 'required|min:8',
            'financial_portfolio' => 'required|numeric',
            'address' => 'required|string',
            'photo' => 'required|image', 
        ]);
    
        if ($request->hasFile('photo')) {
            $fileName = $this->SaveImage($request->file('photo'), 'images/patients');
        } else {
            $fileName = null;
        }
    
        $patient = Patient::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'Phon' => $request->phone,
            'password' => bcrypt($request->password),
            'financial_portfolio' => $request->financial_portfolio,
            'address' => $request->address,
            'image' => $fileName
        ]);
    
      
        $token = $patient->createToken('PatientToken')->accessToken;
    
        return response()->json(['Data' => ['token' => $token, 'name' => $patient->name, 'patient_id' => $patient->id]], 200);
    }
    public function SaveImage($photo, $folder)
    {
        $fileExtension = $photo->getClientOriginalExtension();
        $fileName = time() . '.' . $fileExtension;
        $path = $folder;
        $photo->move($path, $fileName);
        return $fileName;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $patient = Patient::where('email', $request->email)->first();

        if (!$patient || !Hash::check($request->password, $patient->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $patient->createToken('PatientToken')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function addForm(Request $request){
        $request->validate([
            'age' => 'required|string',
            'job' => 'required|string',
            'gender' => 'required|in:male,female',
            'diabetes' => 'required|in:yes,no',
            'kidney_problems' => 'required|in:yes,no',
            'pressure' => 'required|in:yes,no',
            'heart' => 'required|in:yes,no',
            'allergic' => 'required|in:yes,no',
            'blood_thinning' => 'required|in:yes,no',
            'epidemic_liver' => 'required|in:yes,no',
            'thyroid' => 'required|in:yes,no',
            'cancer' => 'required|in:yes,no',
            'rheumatic' => 'required|in:yes,no',
            'another_illnesses' => 'nullable|string',
            'smoked' => 'required|in:yes,no',
            'pregnant' => 'required|in:yes,no',
            'pharmaceutical' => 'nullable|string',
            'first_visit_to_doctor' => 'required|in:yes,no',
        ]);

        $form = new Form();
        $form->age = $request->age;
        $form->job = $request->job;
        $form->gender = $request->gender;
        $form->diabetes = $request->diabetes;
        $form->kidney_problems = $request->kidney_problems;
        $form->pressure = $request->pressure;
        $form->heart = $request->heart;
        $form->allergic = $request->allergic;
        $form->blood_thinning = $request->blood_thinning;
        $form->epidemic_liver = $request->epidemic_liver;
        $form->thyroid = $request->thyroid;
        $form->cancer = $request->cancer;
        $form->rheumatic = $request->rheumatic;
        $form->another_illnesses = $request->another_illnesses;
        $form->smoked = $request->smoked;
        $form->pregnant = $request->pregnant;
        $form->pharmaceutical = $request->pharmaceutical;
        $form->first_visit_to_doctor = $request->first_visit_to_doctor;
        $form->patient_id = Auth::guard('patient')->id();

        $form->save();

        return response()->json(['message' => 'Form created successfully', 'form' => $form], 201);
    }

    public function editForm(Request $request){
        $patient_id = Auth::guard('patient')->id();
        $form = Form::where('patient_id', $patient_id)->firstOrFail();

        $request->validate([
            'age' => 'required|string',
            'job' => 'required|string',
            'gender' => 'required|in:male,female',
            'diabetes' => 'required|in:yes,no',
            'kidney_problems' => 'required|in:yes,no',
            'pressure' => 'required|in:yes,no',
            'heart' => 'required|in:yes,no',
            'allergic' => 'required|in:yes,no',
            'blood_thinning' => 'required|in:yes,no',
            'epidemic_liver' => 'required|in:yes,no',
            'thyroid' => 'required|in:yes,no',
            'cancer' => 'required|in:yes,no',
            'rheumatic' => 'required|in:yes,no',
            'another_illnesses' => 'nullable|string',
            'smoked' => 'required|in:yes,no',
            'pregnant' => 'required|in:yes,no',
            'pharmaceutical' => 'nullable|string',
            'first_visit_to_doctor' => 'required|in:yes,no',
        ]);

        $form->age = $request->age;
        $form->job = $request->job;
        $form->gender = $request->gender;
        $form->diabetes = $request->diabetes;
        $form->kidney_problems = $request->kidney_problems;
        $form->pressure = $request->pressure;
        $form->heart = $request->heart;
        $form->allergic = $request->allergic;
        $form->blood_thinning = $request->blood_thinning;
        $form->epidemic_liver = $request->epidemic_liver;
        $form->thyroid = $request->thyroid;
        $form->cancer = $request->cancer;
        $form->rheumatic = $request->rheumatic;
        $form->another_illnesses = $request->another_illnesses;
        $form->smoked = $request->smoked;
        $form->pregnant = $request->pregnant;
        $form->pharmaceutical = $request->pharmaceutical;
        $form->first_visit_to_doctor = $request->first_visit_to_doctor;

        $form->save();

        return response()->json(['message' => 'Form updated successfully', 'form' => $form], 200);
    }
    
    public function getForm(){
        $patient_id = Auth::guard('patient')->id();

       
        $form = Form::where('patient_id', $patient_id)->first();

        if (!$form) {
            return response()->json(['error' => 'Form not found'], 404);
        }

        return response()->json(['form' => $form], 200);
    }

    public function showMedicalRecord(){
        $patient_id = Auth::guard('patient')->id();
        $medicalRecord = MedicalRecord::where('patient_id', $patient_id)->first();
        $form = Form::where('patient_id', $patient_id)->first();
        $checklist = Checklist::where('patient_id', $patient_id)->with('problems')->first();
        $therapySession = TherapySession::where('patient_id', $patient_id)->first();

        if (!$medicalRecord) {
            return response()->json(['error' => 'Medical record not found'], 404);
        }

        return response()->json(['medical_record' => $medicalRecord,'form'=>$form,'checklist'=>$checklist,
      'therapySession'=>$therapySession], 200);
    }

    public function showArticals(){

        $articles = Article::select('title','text')->get();

        return response()->json(['articles' => $articles], 200);
    }


    public function getSessions(){
        $patient_id = Auth::guard('patient')->id();

        $sessions = TherapySession::where('patient_id', $patient_id)->get();

        if ($sessions->isEmpty()) {
            return response()->json(['message' => 'No sessions found'], 404);
        }

        return response()->json(['sessions' => $sessions], 200);
    }
    
    //function return unpaid session for auth patient
    public function getUnPaidSessions(){
      
            $patient_id = Auth::guard('patient')->id();
            $sessions = TherapySession::with('treatment:id,price') 
                                       ->where('patient_id', $patient_id)
                                       ->where('status', 'unpaid')
                                       ->get();
        
            if ($sessions->isEmpty()) {
                return response()->json(['message' => 'No sessions found'], 404);
            }
        
            $result = $sessions->map(function ($session) {
                return [
                    'session_id' => $session->id, 
                    'treatment_price' => $session->treatment->price ?? null, 
                     
                ];
            });
        
            return response()->json(['sessions' => $result], 200);
        }
        
        // function to get total price for unpaid sessions
        public function getTotalPriceSessions(){
            $patient_id = Auth::guard('patient')->id();

            $sessions = TherapySession::with('treatment:id,price') 
                                       ->where('patient_id', $patient_id)
                                       ->where('status', 'unpaid')
                                       ->get();
        
            if ($sessions->isEmpty()) {
                return response()->json(['message' => 'No sessions found'], 404);
            }
        
            $totalPrice = $sessions->sum(function ($session) {
                return $session->treatment->price ?? 0;
            });
        
            return response()->json([
                'total_price' => $totalPrice
            ], 200);
        }

    //show patient appointment
    public function getPatientAppointment(){
        $patient_id = Auth::guard('patient')->id();

        $appointment = Appointment::where('patient_id', $patient_id)->get();

        if ($appointment->isEmpty()) {
            return response()->json(['message' => 'No appointments found'], 404);
        }

        return response()->json(['appointments' => $appointment], 200);
    }

    // patient financial

    public function getfinancialPortfolio(){
        $patient_id = Auth::guard('patient')->id();
        $patient = Patient::where('id', $patient_id)-> select('financial_portfolio')->get();
        return response()->json(['patient_financial_portfolio' => $patient], 200);
    }

    //address for articles
    public function showAddressesForArticles(){
        $articles= Article::select('title')->get();
        return response()->json(['articles_addresses' => $articles], 200);
    }

    //doctors names and specialization
    public function nameSpecializationForDoctors(){
        $doctor = Doctor::select('name','specialization')->get();
        return response()->json(['doctors' => $doctor], 200);
    }
    //patient name and email
    public function patientNameAndEmail(){
        $patient_id = Auth::guard('patient')->id();
        $patient = Patient::where('id', $patient_id)-> select('name','email')->get();
        return response()->json(['patient' => $patient], 200);
    }

    public function concultationAppointment(Request $request){

        $validator = Validator::make($request->all(), [
            'doctor_name' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $doctorName = $request->input('doctor_name');
        $doctor = Doctor::where('name', $doctorName)->first();
    
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
    
        $date = $request->input('date');
        $dayOfWeek = Carbon::parse($date)->format('l'); 
    
        $day = Day::where('day_name', $dayOfWeek)->first();
    
        if (!$day) {
            return response()->json(['message' => 'Day not found'], 404);
        }
    
        $doctorDay = DB::table('doctor__days')
            ->where('doctor_id', $doctor->id)
            ->where('day_id', $day->id)
            ->first();
    
        if (!$doctorDay) {
            return response()->json(['message' => 'Doctor does not have consultation on this day'], 400);
        }
    
        $patient_id = Auth::guard('patient')->id();

        $therapySessionsCount = DB::table('therapy_sessions')
        ->where('patient_id', $patient_id)
        ->where('status', 'unpaid')
        ->count();

         if ($therapySessionsCount >= 3) {
        return response()->json(['message' => 'You cannot book a consultation appointment because you have more than 3 therapy sessions'], 400);
      } 


        $start = $doctorDay->begin_consultation_time;
        $end = $doctorDay->end_consultation_time;
    
        $existingConsultationAppointment = Appointment::where('patient_id', $patient_id)
            ->where('date', $date)
            ->where('type', 'consultation')
            ->exists();
    
        if ($existingConsultationAppointment) {
            return response()->json(['message' => 'You already have a consultation appointment on this date'], 400);
        }
    
        $conflictingPatientAppointment = Appointment::where('patient_id', $patient_id)
            ->where('date', $date)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start', '<', $end)->where('end', '>', $start);
                });
            })->exists();
    
        if ($conflictingPatientAppointment) {
            return response()->json(['message' => 'You already have another appointment that conflicts with the given time'], 400);
        }
    
        $conflictingAppointment = Appointment::where('doctor_id', $doctor->id)
            ->where('date', $date)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start', '<', $end)->where('end', '>', $start);
                });
            })
            ->exists();
    
        if ($conflictingAppointment) {
            return response()->json(['message' => 'Appointment time is already booked'], 400);
        }
    
        $appointment = new Appointment();
        $appointment->start = $start;
        $appointment->end = $end;
        $appointment->date = $date;
        $appointment->status = 'reserved';
        $appointment->type = 'consultation';
        $appointment->doctor_id = $doctor->id;
        $appointment->patient_id = $patient_id;
        $appointment->save();
           

        $pa=Patient::find($patient_id);
        $name=$pa->name;

        $notificationD=DoctorNotification::create([
            'message' => sprintf('An appointment for consulation  with the Patient %s has been scheduled  at %s ', $name,$date),
           'doctor_id' =>$doctor_id
    
             ]);
    
             $patient->notify(new DoctorNotifications( 'message'));




    
        return response()->json(['appointment' => $appointment], 201);
}
    

    public function concultationAppointmentTime(Request $request){

        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'start' => 'required|date_format:H:i:s',
            'end' => 'required|date_format:H:i:s|after:start',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $date = $request->input('date');
        $start = $request->input('start');
        $end = $request->input('end');
        $dayOfWeek = Carbon::parse($date)->format('l'); 

        $availableDoctors = Doctor::whereHas('days', function ($query) use ($dayOfWeek, $start, $end) {
            $query->where('day_name', $dayOfWeek)
                  ->where('begin_consultation_time', '<=', $start)
                  ->where('end_consultation_time', '>=', $end);
        })->get();

        if ($availableDoctors->isEmpty()) {
            return response()->json(['message' => 'No available doctors found for the given time'], 404);
        }

        $patient_id = Auth::guard('patient')->id();

        $therapySessionsCount = DB::table('therapy_sessions')
        ->where('patient_id', $patient_id)
        ->where('status', 'unpaid')
        ->count();

       if ($therapySessionsCount >= 3) {
        return response()->json(['message' => 'You cannot book a consultation appointment because you have more than 3 therapy sessions'], 400);
        }

       
        $existingConsultationAppointment = Appointment::where('patient_id', $patient_id)
            ->where('date', $date)
            ->where('type', 'consultation')
            ->exists();

        if ($existingConsultationAppointment) {
            return response()->json(['message' => 'You already have a consultation appointment on this date'], 400);
        }

      
        $conflictingPatientAppointment = Appointment::where('patient_id', $patient_id)
            ->where('date', $date)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start', '<', $end)->where('end', '>', $start);
                });
            })->exists();

        if ($conflictingPatientAppointment) {
            return response()->json(['message' => 'You already have another appointment that conflicts with the given time'], 400);
        }

       
        $doctor = $availableDoctors->first();

       
        $conflictingDoctorAppointment = Appointment::where('doctor_id', $doctor->id)
            ->where('date', $date)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start', '<', $end)->where('end', '>', $start);
                });
            })->exists();

        if ($conflictingDoctorAppointment) {
            return response()->json(['message' => 'Selected doctor is already booked for the given time'], 400);
        }

        
        $appointment = new Appointment();
        $appointment->start = $start;
        $appointment->end = $end;
        $appointment->date = $date;
        $appointment->status = 'reserved';
        $appointment->type = 'consultation';
        $appointment->doctor_id = $doctor->id;
        $appointment->patient_id = $patient_id;
        $appointment->save();

        $pa=Patient::find($patient_id);
        $name=$pa->name;

        $notificationD=DoctorNotification::create([
            'message' => sprintf('An appointment for consulation  with the Patient %s has been scheduled  at %s ', $name,$date),
           'doctor_id' =>$doctor_id
    
             ]);
    
             $patient->notify(new DoctorNotifications( 'message'));

        return response()->json(['appointment' => $appointment], 201);
  }
    



 public function paidForSession($session_id){

        $patient_id = Auth::guard('patient')->id();

    $session = TherapySession::where('id', $session_id)
                             ->where('patient_id', $patient_id)
                             ->first();

    if (!$session) {
        return response()->json(['message' => 'Therapy session not found or you do not have permission to pay for this session'], 404);
    }

    if ($session->status === 'paid') {
        return response()->json(['message' => 'This session is already paid'], 400);
    }

    $treatment = Treatment::find($session->treatment_id);
    $doctor = Doctor::find($session->doctor_id);
    $admin = Admin::first();

    $treatmentPrice = $treatment->price;
    $doctorPercentage = $doctor->percentage / 100;
    $doctorAmount = $treatmentPrice * $doctorPercentage;
    $adminAmount = $treatmentPrice - $doctorAmount;

  
    $patient = Patient::find($patient_id);
    if ($patient->financial_portfolio < $treatmentPrice) {
        return response()->json(['message' => 'Insufficient funds in patient\'s financial portfolio'], 400);
    }

    DB::transaction(function () use ($patient, $treatmentPrice, $doctor, $doctorAmount, $admin, $adminAmount, $session) {
     
        $patient->financial_portfolio -= $treatmentPrice;
        $patient->save();

        $doctor->financial_portfolio += $doctorAmount;
        $doctor->save();

        $admin->financial_portfolio += $adminAmount;
        $admin->save();

        $session->status = 'paid';
        $session->save();
    });

    return response()->json(['message' => 'Payment processed successfully'], 200);
 }

    public function paidForMultiSession(){

        $patient_id = Auth::guard('patient')->id();

        $sessions = TherapySession::where('patient_id', $patient_id)
                                  ->where('status', 'unpaid')
                                  ->get();
    
        if ($sessions->isEmpty()) {
            return response()->json(['message' => 'No unpaid sessions found'], 404);
        }
    
        $totalAmount = 0;
        $doctorAmounts = [];
        $adminAmount = 0;
    
        foreach ($sessions as $session) {
            $treatment = Treatment::find($session->treatment_id);
            $doctor = Doctor::find($session->doctor_id);
            $treatmentPrice = $treatment->price;
    
            $doctorPercentage = $doctor->percentage / 100;
            $doctorAmount = $treatmentPrice * $doctorPercentage;
            $remainingAmount = $treatmentPrice - $doctorAmount;
    
            $totalAmount += $treatmentPrice;
    
            if (!isset($doctorAmounts[$doctor->id])) {
                $doctorAmounts[$doctor->id] = 0;
            }
            $doctorAmounts[$doctor->id] += $doctorAmount;
    
            $adminAmount += $remainingAmount;
        }
    
        $patient = Patient::find($patient_id);
        if ($patient->financial_portfolio < $totalAmount) {
            return response()->json(['message' => 'Insufficient funds in patient\'s financial portfolio'], 400);
        }
    
        DB::transaction(function () use ($patient, $totalAmount, $doctorAmounts, $adminAmount, $sessions) {

            $patient->financial_portfolio -= $totalAmount;
            $patient->save();
    
            foreach ($doctorAmounts as $doctorId => $amount) {
                $doctor = Doctor::find($doctorId);
                $doctor->financial_portfolio += $amount;
                $doctor->save();
            }
    
            $admin = Admin::first();
            $admin->financial_portfolio += $adminAmount;
            $admin->save();
    
            foreach ($sessions as $session) {
                $session->status = 'paid';
                $session->save();
            }
        });
    
        return response()->json(['message' => 'Payments processed successfully'], 200);
    }
    
    
      

     
    




    public function confirmedMyAppointment($id){

        $Appointment = Appointment::find($id);
        $Appointment->status='confirmed';

        $patient_id=$Appointment->patient_id;
        $patient=Patient::find($patient_id);
        $nameP=$patient->name;
        $date=$Appointment->date;

        $Secretaril=Secretaril::first();
        $notificationD=SecNotification::create([
               'message' => sprintf('the appointment with the Patient %s at %s has been confirmed', $nameP,$date),
              'secretaril_id' =>$Secretaril->id
       
                ]);
       
                $patient->notify(new SecNotifications( 'message'));

        return response()->json([ 'Appointment confirmed successfully'=>$Appointment], 200);
      
   
  }



  public function cancelAppointment($appointment_id){

    $patient_id = Auth::guard('patient')->id();
  
    $Appointment = Appointment::where('id', $appointment_id)
                              ->where('patient_id', $patient_id)
                              ->first();

    if (!$appointment) {
        return response()->json(['message' => 'Appointment not found or you do not have permission to delete this appointment'], 404);
    }
    $patient_id=$Appointment->patient_id;
    $doctor_id=$Appointment->doctor_id;
    $date=$Appointment->date;
    $patient=Patient::find($patient_id);
    $nameP=$patient->name;

    $appointment->delete();

    

    $notificationD=DoctorNotification::create([
        'message' => sprintf('Your appointment with the Patient %s at %s has been canceled ', $nameP,$date),
       'doctor_id' =>$doctor_id

         ]);

         $patient->notify(new DoctorNotifications(  'message'));



       $Secretaril=Secretaril::first();
     $notificationD=SecNotification::create([
            'message' => sprintf('the appointment with the Patient %s at %s has been canceled ', $nameP,$date),
           'secretaril_id' =>$Secretaril
    
             ]);
    
             $patient->notify(new SecNotifications(  'message'));




    return response()->json(['message' => 'Appointment deleted successfully'], 200);
}





 public function showPatientNotification(){
    $id=Auth::guard('patient')->user()->id; 
        $patient=Patient::find($id);    
        $patientNotifications=$patient->patientNotifications;

        return response()->json( ['Notifications'=>$patientNotifications],200);
        
    }
    }
    
      

     
    

