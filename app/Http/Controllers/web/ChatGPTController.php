<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class ChatGPTController extends Controller
{
    private string $apiUrl = 'https://api.openai.com/v1/chat/completions';
    private string $model = 'gpt-3.5-turbo';
    private array $headers;

    public function __construct()
    {
        $this->headers = [
            "Content-Type"  => "application/json",
            "Authorization" => "Bearer " . env("OPENAI_API_KEY")
        ];
    }

    /**
     * Autocomplete plant names
     *
     * @param string $characters
     * @return JsonResponse
     */
    public function autocomplete(string $characters):JsonResponse
    {

        if(strlen($characters) < 4) {
            return response()->json('{\n  "message": "Not enough characters"\n}');
        }
        $response = Http::withHeaders($this->headers)->post($this->apiUrl, [
            "model" => $this->model,
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a gardener who only knows plant names"
                ],
                [
                    "role" => "user",
                    "content" => "return 5 plants in a json object starting with ".$characters
                ],
            ],
            "temperature" => 0,
            "max_tokens" => 2048
        ])->body();
        return response()->json(
            json_decode($response)->choices[0]->message->content
        );
    }

    /**
     * Get harvest, planting and prune dates from ChatGPT by plant name
     *
     * @param string $plantName
     * @return JsonResponse
     */
    public function getTasks(string $plantName):JsonResponse
    {

        $response = Http::withHeaders($this->headers)->post($this->apiUrl, [
            "model" => $this->model,
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a gardener who only knows plant harvest, planting and prune dates"
                ],
                [
                    "role" => "user",
                    "content" => "harvest, planting and prune months in json for ".$plantName
                ],
            ],
            "temperature" => 0,
            "max_tokens" => 2048
        ])->body();
        return response()->json(
            json_decode($response)->choices[0]->message->content
        );
    }
}
