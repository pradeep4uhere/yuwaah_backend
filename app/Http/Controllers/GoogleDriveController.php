<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;


class GoogleDriveController extends Controller
{
    protected function getDriveService()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-oauth.json'));
        $client->addScope(Drive::DRIVE);
        return new Drive($client);
    }




    protected function getClient()
    {
        $client = new \Google\Client();
        $client->setAuthConfig(storage_path('app/google-oauth.json'));
        $client->addScope(\Google_Service_Drive::DRIVE);
        $client->setRedirectUri(route('google.callback'));
        $client->setAccessType('online');
        $client->setPrompt('auto    ');
        $client->addScope('https://www.googleapis.com/auth/drive.file');

        return $client;
    }

    public function redirectToGoogle()
    {
        $client = new \Google_Client();
        $client->setAuthConfig(storage_path('app/google-oauth.json'));
        //$client->addScope(\Google_Service_Drive::DRIVE);
        $client->addScope('https://www.googleapis.com/auth/drive.file');
        $client->setRedirectUri(route('google.callback'));
    
        return redirect()->away($client->createAuthUrl());
    }


    public function handleGoogleCallback(Request $request)
    {
        if (!$request->has('code')) {
            return response()->json([
                'error' => 'Authorization code not found in request.'
            ], 400);
        }
    
        $client = new \Google_Client();
        $client->setAuthConfig(storage_path('app/google-oauth.json'));
        $client->addScope(\Google_Service_Drive::DRIVE);
        $client->setRedirectUri(route('google.callback'));
    
        $token = $client->fetchAccessTokenWithAuthCode($request->code);
        //dd($token);
    
        $client->setAccessToken($token);
    
        session(['google_token' => $token]);
        return $this->upload();
    
        //return redirect()->route('dashboard');
    }


    public function upload()
    {
        try {
            $client = $this->getClient();
    
            if (!session()->has('google_token')) {
                return redirect()->route('google.auth');
            }
    
            $client->setAccessToken(session('google_token'));
    
            // Refresh token if expired
            if ($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                session(['google_token' => $client->getAccessToken()]);
            }
    
            $driveService = new \Google\Service\Drive($client);
            $zipFilePath = $this->createZipFromBackups(storage_path('app/backups/'));

            if (!$zipFilePath || !file_exists($zipFilePath)) {
                return response()->json([
                    'status' => false,
                    'message' => 'ZIP file not created or not found.'
                ]);
            }
    
            $filePath = $zipFilePath; 
    
            $fileMetadata = new \Google\Service\Drive\DriveFile([
                'name' => basename($filePath),
                'parents' => ['1H8FW1j6URRbRAsTI1WaUKWth-oKZ506e'], // 👈 VERY IMPORTANT
            ]);
    
            $content = file_get_contents($filePath);
    
            $uploadedFile = $driveService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/gzip',
                'uploadType' => 'multipart',
            ]);

            //dd($uploadedFile);
            if($uploadedFile->id){
                $this->deleteOldFiles(storage_path('app/backups/'));
                return response()->json([
                    'status' => true,
                    'file_id' => $uploadedFile->id,
                    'message' => 'Uploaded to YOUR Google Drive (2TB storage).'
                ]);
            }
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    



    protected function deleteOldFiles($backupPath){
        $files = glob($backupPath . '/*.sql.gz');
        if (empty($files)) {
            \Log::warning('No backup files found to .sql.');
            return false;
        }
        foreach ($files as $file) {
            unlink($file);
            \Log::info('file deleted: ' . $file);
        }
        return true;

    }


    protected function createZipFromBackups($backupPath)
    {
        $files = glob($backupPath . '/*.sql.gz');

        if (empty($files)) {
            \Log::warning('No backup files found to zip.');
            return false;
        }

        $zipFileName = 'backup_' . date('Y-M-d_H-i-s') . '.zip';
        $zipFilePath = $backupPath . '/' . $zipFileName;

        $zip = new \ZipArchive();

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            foreach ($files as $file) {
                if (is_file($file)) {
                    $zip->addFile($file, basename($file));
                }
            }

            $zip->close();
            \Log::info('ZIP backup created: ' . $zipFileName);
            return $zipFilePath;
        }

        \Log::error('Failed to create ZIP backup.');
        return false;
    }




}
