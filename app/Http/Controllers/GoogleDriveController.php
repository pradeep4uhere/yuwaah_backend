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
        $client->setAuthConfig(storage_path('app/google-drive-key.json'));
        $client->addScope(Drive::DRIVE);

        return new Drive($client);
    }

    public function upload()
{
    try {
        ini_set('memory_limit', '1024M');
        $driveService = $this->getDriveService();

        // Full server path
        $filePath = storage_path('app/backups/yuwaahsakhi_2026-02-11_15-05-20.sql.gz');

        if (!file_exists($filePath)) {
            return response()->json([
                'status' => false,
                'message' => 'File not found on server.'
            ]);
        }

        $fileName = basename($filePath);

        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $fileName,
            'parents' => [env('GOOGLE_DRIVE_FOLDER_ID')]
        ]);

        $content = file_get_contents($filePath);

        $uploadedFile = $driveService->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => 'application/sql',
            'uploadType' => 'multipart',
        ]);

        return response()->json([
            'status' => true,
            'file_id' => $uploadedFile->id,
            'message' => 'File uploaded successfully to Google Drive.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
