<?php

namespace App\Http\Controllers\Admin\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectFlat;

class ProjectFlatsPrintController extends Controller
{
    /**
     * Show project flats print template
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showProjectFlats(Request $request)
    {
        // Get project ID and status filter from request
        $projectId = $request->get('project_id');
        $statusFilter = $request->get('status'); // 'available', 'sold', 'reserved', 'land_owner', or null for all
        
        if (!$projectId) {
            abort(404, 'Project ID is required');
        }

        try {
            $project = Project::with([
                'flats.paymentSchedules',
                'flats.flatSales.customer'
            ])->findOrFail($projectId);

            // Get flats with optional status filter
            $flatsQuery = $project->flats();
            
            // Apply status filter if provided
            if ($statusFilter) {
                $flatsQuery->where('status', $statusFilter);
            }
            
            $flats = $flatsQuery->orderByRaw("CASE 
                WHEN status = 'available' THEN 1 
                WHEN status = 'sold' THEN 2 
                WHEN status = 'reserved' THEN 3 
                WHEN status = 'land_owner' THEN 4 
                ELSE 5 
            END")
            ->orderBy('flat_number', 'asc')
            ->get();

            return view('admin.projects.print-flats', compact('project', 'flats', 'statusFilter'));

        } catch (\Exception $e) {
            \Log::error('Error in showProjectFlats: ' . $e->getMessage());
            abort(500, 'Error loading project flats template');
        }
    }
}

