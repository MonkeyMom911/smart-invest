<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini; 
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function handle(Request $request)
    {
        $question = $request->input('question', '');

        if (!$question) {
            return response()->json(['answer' => 'Mohon ajukan pertanyaan.']);
        }

        // Pastikan API Key ada di file .env
        if (!env('GEMINI_API_KEY')) {
             Log::error('GEMINI_API_KEY not set in .env file.');
             return response()->json(['answer' => 'Konfigurasi API Key belum diatur oleh admin.']);
        }

        try {
            // Gabungkan konteks dan pertanyaan pengguna
            $prompt = "Kamu adalah asisten AI untuk platform investasi 'Smart-Invest'. Jawablah pertanyaan berikut dengan ramah dan informatif dalam Bahasa Indonesia.\n\nPertanyaan: {$question}";

            // Panggil API menggunakan library
            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')->generateContent($prompt);

            $answer = $result->text();

        } catch (\Exception $e) {
            // Jika terjadi error saat menghubungi API
            Log::error('Gemini API Exception: ' . $e->getMessage());
            $answer = 'Maaf, sedang ada gangguan teknis dengan asisten AI. Silakan coba lagi nanti.';
        }
        
        return response()->json(['answer' => $answer]);
    }
}