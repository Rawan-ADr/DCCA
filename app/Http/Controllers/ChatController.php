<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Chat;
use App\Models\Message;
use App\Models\DoctorMeaaage;
use App\Models\PatientMessage;
use Pusher\Pusher;
class ChatController extends Controller
{
          

    public function createChat(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        $patientId = $request->input('patient_id');

        $chat = Chat::create([
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
        ]);

        return response()->json([
            'chat_id' => $chat->id,
        ]);
    } 


     public function sendPatientMessage(Request $request)
    {
        $chatId = $request->input('chat_id');
        $patient_id = $request->input('patient_id');
        $message = $request->input('message');

        $message = PatientMessage::create([
            'chat_id' => $chatId,
            'patient_id' => $patient_id,
            'message' => $message,
        ]);

        // بث الرسالة باستخدام Pusher
       
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true,
                ]
                );

        $pusher->trigger('chat-' . $chatId, 'new-message', [
            'message' => $message->message,
            'user_id' => $message->patient_id,
            'created_at' => $message->created_at->toDateTimeString(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }


    public function getPatientMessage(Request $request, $chatId)
    {
        $messages = PatientMessage::where('chat_id', $chatId)->get();
        return response()->json($messages);
    }



    public function sendDoctorMessage(Request $request)
    {
        $chatId = $request->input('chat_id');
        $doctor_id = $request->input('doctor_id');
        $message = $request->input('message');

        $message = DoctorMeaaage::create([
            'chat_id' => $chatId,
            'doctor_id' => $doctor_id,
            'message' => $message,
        ]);

        // بث الرسالة باستخدام Pusher
       
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true,
                ]
                );

        $pusher->trigger('chat-' . $chatId, 'new-message', [
            'message' => $message->message,
            'user_id' => $message->doctor_id,
            'created_at' => $message->created_at->toDateTimeString(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }


    public function getDoctorMessage(Request $request, $chatId)
    {
        $messages = DoctorMeaaage::where('chat_id', $chatId)->get();
        return response()->json($messages);
    }


    
}
