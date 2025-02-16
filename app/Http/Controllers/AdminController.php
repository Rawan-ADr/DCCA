<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Secretaril;
use App\Models\Archives;
use App\Models\Article;
use App\Models\Treatment;
use App\Models\Section;
use App\Models\PatientNotification;
use App\Models\TreatmentDepartment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth;
use App\Notifications\PatientNotifications;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('AdminToken')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }



    public function showAllDoctor()
    {
        $doctors = Doctor::all();
       return  response()->json(['doctors'=>$doctors], 200);
    }

    public function showAllSecretarial()
    {
        $secretarials = Secretaril::all();
       return  response()->json(['secretarials'=>$secretarials], 200);
    }

    public function showSecretarial($id)
    {
        $secretarial = Secretaril::find($id);
       return  response()->json(['secretarial'=>$secretarial], 200);
    }

    public function showDoctor($id)
    {
        $doctor = Doctor::find($id);
       return  response()->json(['doctor'=>$doctor], 200);
    }

    public function addDoctor(Request $request){

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:patients,email',
            'phone' => 'required|numeric',
            'password' => 'required|min:8',
            'financial_portfolio' => 'required|numeric',
            'specialization'=>'required|string',
            'percentage'=>'required|numeric',
            'section_id'=>'required',
            'address' => 'required|string',
           'photo' => 'sometimes|image',
        ]);

        if ($request->hasFile('photo')) {
            $fileNamee = $this->SaveImage($request->file('photo'), 'images/doctors');
        } else {
            $fileNamee = null;
        }

        $doctor = Doctor::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'Phon' => $request->phone,
            'password' => bcrypt($request->password),
            'specialization'=>$request->specialization,
            'financial_portfolio' => $request->financial_portfolio,
            'address' => $request->address,
            'percentage'=>$request->percentage,
            'section_id'=>$request->section_id,
            'image' => $fileNamee
        ]);
        
        return response()->json(['message' => 'doctor add successfully'], 200);
    }


    public function SaveImage($photo, $folder)
    {
        $fileExtension = $photo->getClientOriginalExtension();
        $fileName = time() . '.' . $fileExtension;
        $path = $folder;
        $photo->move($path, $fileName);
        return $fileName;
    }





    public function addSecretaril(Request $request){

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
            $fileName1 = $this->SaveImage($request->file('photo'), 'images/secretarial');
        } else {
            $fileName1 = null;
        }

        $secretarial = Secretaril::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'Phon' => $request->phone,
            'password' => bcrypt($request->password),
            'financial_portfolio' => $request->financial_portfolio,
            'address' => $request->address,
            'image' => $fileName1
        ]);
        
        return response()->json(['message' => 'secrtarial add successfully'], 200);
    }


     public function updateDoctor(Request $request,$id){

         
        $validator = Validator::make($request->all(),[
            
                'name' => 'required|string',
                'email' => 'email|unique:patients,email',
                'phone' => 'required|numeric',
                'password' => 'required|min:8',
                'financial_portfolio' => 'required|numeric',
                'specialization'=>'required|string',
                'percentage'=>'required|numeric',
                'section_id'=>'required',
                'address' => 'required|string',
               'photo' => 'required|image', 
            ]);

        $doctor = Doctor::findOrFail($id);
        
        if ($request->hasFile('photo')) {
            $fileName2 = $this->SaveImage($request->file('photo'), 'images/doctors');
        } else {
            $fileName2 = null;
        }   
        $doctor->name = $request->input('name');
       // $doctor->email = $request->input('email');
       $doctor->specialization = $request->input('specialization');
       $doctor->password = $request->input('password');
       $doctor->Phon = $request->input('phone');
       $doctor->image = $fileName2;
       $doctor->financial_portfolio = $request->input('financial_portfolio');
       $doctor->percentage = $request->input('percentage');
       $doctor->section_id = $request->input('section_id');
       $doctor->address = $request->input('address');    
        
    
     
        $doctor->save();

        


        return response()->json([
            'message' => 'Doctor updated successfully!',
            'doctor' => $doctor
        ], 200);


     }  

        

     public function deleteSecretarial($id)
{
    
    $secretarial = Secretaril::where('id',$id)->first();

    if($secretarial){
        
        $secretarial->delete();
        return response()->json(['message' => 'secretarial deleted successfully'], 200);
    } else { 
        return response()->json(['message' => 'secretarial not found'], 404);
    }
}
    

    public function AddToArchives($id){
        
        $doctor = Doctor::find($id);

        if(!$doctor){
            return response()->json(['message'=>'doctor not found'],404);

        }

        $archive= Archives::create([
          'name' =>$doctor->name,
          'email'=>$doctor->email,
          'Phon' =>$doctor->Phon,
          'address' =>$doctor->address,
          'specialization'=>$doctor->specialization
        ]);
             
        $doctor->delete();

        return response()->json (['message'=>'doctor deleted successfully and add to archive'],200);


    }



    public function addArticle(Request $request){
        $request->validate([
          'text'=>'required|string',
          'title'=>'required|string'   
        ]);
         $adminId=Auth::guard('admin')->user()->id;
        $article = new Article;
        $article->text=$request->input('text');
        $article->title=$request->input('title');
        $article->admin_id= $adminId ;
        $article->save();

        
        $message = 'A new article has been published, and we are pleased for you to read it'; 

        $patients = Patient::all(); 
        
        foreach ($patients as $patient) {
            $notification = PatientNotification::create([
                'message' => $message, 
                'patient_id' => $patient->id 
            ]);
        
            $patient->notify(new PatientNotifications( 'message'));
        }


        return response()->json(['articla add successfully'=>$article],200);

    }


    public function addTreatment(Request $request){
        $request->validate([
            'name'=>'required|string' ,
            'price'=>'required|numeric' ,
            'treatment_department_id'=>'required' 
          ]);
          $treatment= Treatment::create([
            'name'=>$request->name,
            'price'=>$request->price,
            'treatment_department_id'=>$request->treatment_department_id
          ]);
          $treatment->save();

          return response()->json(['Treatment add successfully'=>$treatment]);

    }
    
    public function updatePrice(Request $request,$id){
        $request->validate([
          
            'price'=>'required|numeric' 
          ]);

          $treatment=Treatment::findOrFail($id);

             $treatment->price=$request->input('price');

             $treatment->update();
             return response()->json([
                'Price updated successfully!'=> $treatment], 200);

               
    }


    public function showTreatment(){

         $treatmentDepartment=TreatmentDepartment::with('treatments')->get();

        return response()->json(['treatmentDepartment'=>$treatmentDepartment],200);

    }
         
    public function showD_Section($id){

        $Section=Section::find($id);
         $Doctors=$Section->doctors;
         return response()->json( ['Doctors'=>$Doctors],200);


    }
         
    public function showSection(){

        $Section=Section::all();

         return response()->json( ['Section'=>$Section],200);

    }


    
    public function addadmin(Request $request){

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:patients,email',
            'phone' => 'required|numeric',
            'password' => 'required|min:8',
            'financial_portfolio' => 'required|numeric',
            'address' => 'required|string',
           'photo' => 'image', 
        ]);

        if ($request->hasFile('photo')) {
            $fileName1 = $this->SaveImage($request->file('photo'), 'images/admin');
        } else {
            $fileName1 = null;
        }

        $admin = Admin::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'Phon' => $request->phone,
            'password' => bcrypt($request->password),
            'financial_portfolio' => $request->financial_portfolio,
            'address' => $request->address,
            'image' => $fileName1
        ]);
        
        return response()->json(['message' => 'admin add successfully'], 200);
    }

    public function addSection(Request $request){
        $request->validate([
            'name'=>'required|string'  
          ]);
          $section= Section::create([
            'name'=>$request->name
          ]);
          $section->save();

          return response()->json(['Treatment add successfully'=>$section]);

    }

    public function showArchives(){

        $Archives=Archives::all();

         return response()->json( ['Archives'=>$Archives],200);

    }





}
