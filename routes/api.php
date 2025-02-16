<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SecretarilController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Statistics;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('patient')->group(function () {
    Route::post('register', [PatientController::class, 'register']);
    Route::post('login', [PatientController::class, 'login']);
    Route::post('logout', [PatientController::class, 'logout'])->middleware('auth:patient');
    Route::post('add/form', [PatientController::class, 'addForm'])->middleware(['auth:patient', 'auth.patient']);
    Route::post('edit/form', [PatientController::class, 'editForm'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('show/medical/record', [PatientController::class, 'showMedicalRecord'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('get/form', [PatientController::class, 'getForm'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('get/sessions', [PatientController::class, 'getSessions'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('get/unpaid/sessions', [PatientController::class, 'getUnPaidSessions'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('get/appointments', [PatientController::class, 'getPatientAppointment'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('get/financialPortfolio', [PatientController::class, 'getfinancialPortfolio'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('get/total/price/sessions', [PatientController::class, 'getTotalPriceSessions'])->middleware(['auth:patient', 'auth.patient']);
    Route::post('concultation/appointment/doctor/name', [PatientController::class, 'concultationAppointment'])->middleware(['auth:patient', 'auth.patient']);
    Route::post('concultation/appointment/time', [PatientController::class, 'concultationAppointmentTime'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('cancel/appointment/{appointment_id}', [PatientController::class, 'cancelAppointment'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('paidFor/oneSession/{session_id}', [PatientController::class, 'paidForSession'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('paidFor/multiSession', [PatientController::class, 'paidForMultiSession'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('get/doctors', [PatientController::class, 'showDoctors']);
    Route::get('get/name/email', [PatientController::class, 'patientNameAndEmail'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('showPatientNotification', [PatientController::class, 'showPatientNotification'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('/confirmedMyAppointment/{id}', [PatientController::class, 'confirmedMyAppointment'])->middleware(['auth:patient', 'auth.patient']);
    Route::get('showAddressesForArticles', [PatientController::class, 'showAddressesForArticles'])->middleware(['auth:patient', 'auth.patient']);


});

// Secretary routes
Route::prefix('secretary')->group(function () {
    Route::post('login', [SecretarilController::class, 'login']);
    Route::post('logout', [SecretarilController::class, 'logout'])->middleware('auth:secretary');
    Route::post('addPatient', [SecretarilController::class, 'addPatient'])->middleware('auth.secretary');
    Route::post('addAppointment', [SecretarilController::class, 'addAppointment'])->middleware('auth:secretary');
    Route::get('/showD_Appointment/{id}', [SecretarilController::class, 'showD_Appointment']);
    Route::get('/showP_Appointment/{id}', [SecretarilController::class, 'showP_Appointment']);
    Route::delete('/cancelAppointment/{id}', [SecretarilController::class, 'cancelAppointment'])->middleware('auth:secretary');
    Route::get('/confirmedAppointment/{id}', [SecretarilController::class, 'confirmedAppointment']);
    Route::get('showAllAppointmentByDay', [SecretarilController::class, 'showAllAppointmentByDay']);
    Route::get('showSecretarialNotification', [SecretarilController::class, 'showSecretarialNotification']);

});

// Doctor routes
Route::prefix('doctor')->group(function () {
    Route::post('login', [DoctorController::class, 'login']);
    Route::post('logout', [DoctorController::class, 'logout'])->middleware('auth:doctor');
    Route::post('search', [DoctorController::class, 'search']);
    Route::get('show_DAppointment', [DoctorController::class, 'show_DAppointment'])->middleware('auth.doctor');
    Route::get('profile', [DoctorController::class, 'profile'])->middleware('auth.doctor');
    Route::post('addProblem', [DoctorController::class, 'addProblem'])->middleware('auth.doctor');
    Route::post('addCheklist', [DoctorController::class, 'addCheklist'])->middleware('auth.doctor');
    Route::post('addTherapySession', [DoctorController::class, 'addTherapySession'])->middleware('auth.doctor');
    Route::get('/getCheklist/{id}', [DoctorController::class, 'getCheklist']);
    Route::get('/getProblem/{id}', [DoctorController::class, 'getProblem']);
    Route::get('showDoctorNotification', [DoctorController::class, 'showDoctorNotification'])->middleware('auth.doctor');
    Route::get('showAllAppointmentByDay', [DoctorController::class, 'showAllAppointmentByDay'])->middleware('auth.doctor');


});
 
// Admin routes
Route::prefix('admin')->group(function () {
    Route::post('login', [AdminController::class, 'login']);
    Route::post('logout', [AdminController::class, 'logout'])->middleware('auth:admin');
    Route::post('addDoctor',[AdminController::class,'addDoctor'])->middleware('auth.admin');
    Route::post('addSecretaril',[AdminController::class,'addSecretaril'])->middleware('auth.admin');
    Route::post('/updateDoctor/{id}',[AdminController::class,'updateDoctor']);
    Route::delete('/deleteSecretarial/{id}', [AdminController::class, 'deleteSecretarial'])->middleware('auth.admin');
    Route::delete('/AddToArchives/{id}', [AdminController::class, 'AddToArchives'])->middleware('auth.admin');
    Route::post('addArticle',[AdminController::class,'addArticle'])->middleware('auth.admin');
    Route::post('addTreatment',[AdminController::class,'addTreatment'])->middleware('auth.admin');
    Route::post('/updatePrice/{id}',[AdminController::class,'updatePrice'])->middleware('auth.admin');
    Route::post('addadmin',[AdminController::class,'addadmin'])->middleware('auth.admin');
    Route::post('addSection',[AdminController::class,'addSection'])->middleware('auth.admin');
    Route::get('showArchives',[AdminController::class,'showArchives'])->middleware('auth.admin');
    Route::get('/updatePrice/{id}',[AdminController::class,'updatePrice'])->middleware('auth.admin');
    Route::get('/showD_Section/{id}',[AdminController::class,'showD_Section'])->middleware('auth.admin');
    Route::get('showSection',[AdminController::class,'showSection'])->middleware('auth.admin');


});


Route::prefix('chat')->group(function () {
    Route::post('createChat', [ChatController::class, 'createChat']);
    Route::post('sendPatientMessage', [ChatController::class, 'sendPatientMessage']);
    Route::post('sendDoctorMessage', [ChatController::class, 'sendDoctorMessage']);
    Route::get('/getDoctorMessage/{id}', [ChatController::class, 'getDoctorMessage']);
    Route::get('/getPatientMessage/{id}', [ChatController::class, 'getPatientMessage']);


});

//statistics
Route::get('/statistics', [Statistics::class, 'statistics']);
Route::get('/statistics/doctor/with/session', [Statistics::class, 'statisticsDoctorWithSession']);

//articlesAddresses
Route::get('addresses/articles', [PatientController::class, 'showAddressesForArticles']);
Route::get('show/articals', [PatientController::class, 'showArticals']);
Route::get('show/doctors/nameAndSpecialization', [PatientController::class, 'nameSpecializationForDoctors']);
Route::get('showAllDoctor', [AdminController::class, 'showAllDoctor']);
Route::get('showAllSecretarial', [AdminController::class, 'showAllSecretarial']);
Route::get('/showSecretarial/{id}', [AdminController::class, 'showSecretarial']);
Route::get('/showDoctor/{id}', [AdminController::class, 'showDoctor']);
Route::get('showTreatment', [AdminController::class, 'showTreatment']);
Route::get('showSection', [AdminController::class, 'showSection']);
Route::get('/showD_Section/{id}', [AdminController::class, 'showD_Section']);





