<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DeployController extends Controller
{
    public function deploy(Request $request)
    {
        $githubPayload = $request->getContent();
        $githubHash = $request->header('X-Hub-Signature');
        $localToken = config('app.deploy_secret');
        $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);
        Log::info($githubHash);
        Log::info($localHash);

        if (hash_equals($githubHash, $localHash)) {
            $root_path = base_path();
            Log::info('cd ' . $root_path . '/scripts; ./deploy.sh');
            $process = new Process('cd ' . $root_path . '/scripts; ./deploy.sh');
            $process->run(function ($type, $buffer) {
                echo $buffer;
            });
        }
    }
}
