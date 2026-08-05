<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GroqDiagnosisService
{
    private const SYSTEM_PROMPT = 'Anda adalah AI bot ahli servis laptop dan komputer. Tugas Anda wajib mengekstrak teks keluhan pelanggan menjadi format JSON bersih dengan key wajib berikut: 1. "perangkat" (String: Merk atau tipe laptop/PC), 2. "kendala" (String: Penjelasan singkat kerusakan), 3. "estimasi_sparepart" (String: Nama komponen fisik yang rusak), 4. "estimasi_biaya" (Integer/Angka Saja: Perkiraan harga servis & sparepart dalam Rupiah). Jangan memberikan teks penjelasan apapun di luar JSON!';

    public function diagnose(string $keluhan): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                ['role' => 'system', 'content' => self::SYSTEM_PROMPT],
                ['role' => 'user', 'content' => $keluhan],
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.0,
        ]);

        $responseData = $response->json();

        if (!isset($responseData['choices'])) {
            Log::error('Groq diagnosis failed', ['response' => $responseData]);
            throw new RuntimeException('API Limit atau Error');
        }

        $hasilAi = json_decode($responseData['choices'][0]['message']['content'], true);

        return [
            'perangkat' => $hasilAi['perangkat'] ?? 'Tidak Terdeteksi',
            'kendala' => $hasilAi['kendala'] ?? 'Tidak Terdeteksi',
            'estimasi_sparepart' => $hasilAi['estimasi_sparepart'] ?? 'Dicek Manual',
            'estimasi_biaya' => $hasilAi['estimasi_biaya'] ?? 0,
        ];
    }
}
