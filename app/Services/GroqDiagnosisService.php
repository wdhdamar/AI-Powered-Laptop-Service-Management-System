<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GroqDiagnosisService
{
    private const SYSTEM_PROMPT = 'Anda adalah AI bot ahli servis laptop dan komputer. Tugas Anda wajib mengekstrak teks keluhan pelanggan menjadi format JSON bersih dengan key wajib berikut: 1. "perangkat" (String: Merk atau tipe laptop/PC), 2. "kendala" (String: Penjelasan singkat kerusakan), 3. "estimasi_sparepart" (String: Nama komponen fisik yang rusak), 4. "estimasi_biaya" (Integer/Angka Saja: Perkiraan harga servis & sparepart dalam Rupiah). Jangan memberikan teks penjelasan apapun di luar JSON!';

    public function diagnose(string $keluhan): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type' => 'application/json',
            ])
                ->timeout(15)
                ->retry(1, 300)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => [
                        ['role' => 'system', 'content' => self::SYSTEM_PROMPT],
                        ['role' => 'user', 'content' => $keluhan],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.0,
                ]);
        } catch (ConnectionException $e) {
            Log::error('Groq diagnosis connection failed', ['message' => $e->getMessage()]);
            throw new RuntimeException('Layanan AI sedang tidak dapat dihubungi, silakan coba lagi.');
        }

        $responseData = $response->json();

        if (!isset($responseData['choices'])) {
            Log::error('Groq diagnosis failed', ['status' => $response->status(), 'response' => $responseData]);

            if ($response->status() === 429) {
                throw new RuntimeException('Layanan AI sedang sibuk, silakan coba beberapa saat lagi.');
            }

            throw new RuntimeException('Gagal menganalisa keluhan, silakan coba lagi.');
        }

        $hasilAi = json_decode($responseData['choices'][0]['message']['content'], true);

        if (!is_array($hasilAi)) {
            Log::warning('Groq diagnosis returned malformed JSON content', [
                'content' => $responseData['choices'][0]['message']['content'] ?? null,
            ]);
            $hasilAi = [];
        }

        return [
            'perangkat' => $hasilAi['perangkat'] ?? 'Tidak Terdeteksi',
            'kendala' => $hasilAi['kendala'] ?? 'Tidak Terdeteksi',
            'estimasi_sparepart' => $hasilAi['estimasi_sparepart'] ?? 'Dicek Manual',
            'estimasi_biaya' => $hasilAi['estimasi_biaya'] ?? 0,
        ];
    }
}
