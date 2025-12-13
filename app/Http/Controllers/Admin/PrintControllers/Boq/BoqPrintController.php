<?php

namespace App\Http\Controllers\Admin\PrintControllers\Boq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\BoqRecord;

class BoqPrintController extends Controller
{
    /**
     * Show BOQ print template
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showBoq(Request $request)
    {
        // Get project ID from request
        $projectId = $request->get('project_id') ?? $request->get('project');
        
        if (!$projectId) {
            abort(404, 'Project ID is required');
        }

        try {
            $project = Project::findOrFail($projectId);
            $boqRecords = BoqRecord::with(['headOfAccount', 'createdBy', 'updatedBy'])
                ->where('project_id', $projectId)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.print-templates.boq.print', compact('project', 'boqRecords'));

        } catch (\Exception $e) {
            \Log::error('Error in showBoq: ' . $e->getMessage());
            abort(500, 'Error loading BOQ template');
        }
    }
}

