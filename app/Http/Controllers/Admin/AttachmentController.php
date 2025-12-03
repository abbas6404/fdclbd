<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AttachmentController extends Controller
{
    /**
     * Download or view an attachment securely
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attachment = Attachment::findOrFail($id);
        
        // Check if file exists
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File not found');
        }
        
        $filePath = Storage::disk('public')->path($attachment->file_path);
        $mimeType = Storage::disk('public')->mimeType($attachment->file_path);
        
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($attachment->file_path) . '"',
        ]);
    }
    
    /**
     * Download an attachment as download
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $attachment = Attachment::findOrFail($id);
        
        // Check if file exists
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File not found');
        }
        
        return Storage::disk('public')->download(
            $attachment->file_path,
            $attachment->document_name . '.' . pathinfo($attachment->file_path, PATHINFO_EXTENSION)
        );
    }
}

