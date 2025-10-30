<?php

namespace App\Services;

use Exception;

class FontteService
{
	protected $apiKey;
	protected $baseUrl;

	public function __construct()
	{
		$this->apiKey = env('FONTTE_API_KEY');
		$this->baseUrl = 'https://api.fonnte.com/send';
	}

	/**
	 * Kirim pesan WhatsApp via Fontte (cURL native agar support Laravel 5)
	 * @param string $phone Nomor tujuan (format internasional, tanpa +)
	 * @param string $message Isi pesan
	 * @return array|bool
	 */
	public function sendMessage($phone, $message)
	{
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
				'target' => $phone,
				'message' => $message,
			]));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Authorization: ' . $this->apiKey
			]);

			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$err = curl_error($ch);
			curl_close($ch);

			if ($err) {
				throw new Exception('cURL error: ' . $err);
			}
			if ($httpCode >= 200 && $httpCode < 300) {
				return json_decode($response, true);
			} else {
				throw new Exception('Gagal mengirim WA: ' . $response);
			}
		} catch (Exception $e) {
			// Log error jika perlu
			return false;
		}
	}
}
