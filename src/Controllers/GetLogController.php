<?php
namespace Tonod\LogInformation\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class GetLogController extends Controller
{
    public function index(): JsonResponse
    {
        $defaultLogSetting = config('logging.default');
        $channeles = config('logging.channels');
        $path = $channeles[$defaultLogSetting];

        return response()->json([
            'filename' => 'test'
        ]);
    }
}
