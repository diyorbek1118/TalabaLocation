<?php
namespace App\Http\Controllers\Api\Admin;

use App\Exports\Students;
use App\Exports\Rents;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XmlDownloadController extends Controller
{
    public function export(Request $request, $model)
    {
        try {
            Log::info('Export request', [
                'model' => $model,
                'search' => $request->query('search')
            ]);
            
            switch ($model) {
                case 'students':
                    $filters = [
                        'search' => $request->query('search'),
                        'sort_by' => $request->query('sort_by'),
                        'sort_order' => $request->query('sort_order'),
                    ];
                    
                    $filename = 'students_' . time() . '.xlsx';
                    return Excel::download(new Students($filters), $filename);
                
                case 'rents':
                    $filters = [
                        'search' => $request->query('search'),
                    ];
                    
                    Log::info('Rents export', ['filters' => $filters]);
                    
                    $filename = 'rents_' . time() . '.xlsx';
                    return Excel::download(new Rents($filters), $filename);
                
                default:
                    return response()->json(['message' => 'Model not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Export failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Export failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}