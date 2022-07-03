<?php declare(strict_types=1);
namespace Tonod\LogInformation\Controllers;

use Illuminate\Http\JsonResponse;

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
