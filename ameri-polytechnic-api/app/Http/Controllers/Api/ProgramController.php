<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display the specified program.
     */
    public function show(int $id): JsonResponse
    {
        try {
            DB::statement('SET search_path TO academics');
            
            $program = DB::table('programs')
                ->where('id', $id)
                ->first();
            
            DB::statement('SET search_path TO public');
            
            if (!$program) {
                return response()->json([
                    'error' => 'Program not found'
                ], 404);
            }
        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
            return response()->json([
                'error' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
        
        // Parse JSONB fields if they exist
        $programData = (array) $program;
        
        if (isset($programData['skills']) && $programData['skills']) {
            $programData['skills'] = is_string($programData['skills']) 
                ? json_decode($programData['skills'], true) 
                : $programData['skills'];
        }
        
        if (isset($programData['career_paths']) && $programData['career_paths']) {
            $programData['career_paths'] = is_string($programData['career_paths']) 
                ? json_decode($programData['career_paths'], true) 
                : $programData['career_paths'];
        }
        
        if (isset($programData['certifications']) && $programData['certifications']) {
            $programData['certifications'] = is_string($programData['certifications']) 
                ? json_decode($programData['certifications'], true) 
                : $programData['certifications'];
        }
        
        return response()->json($programData);
    }

    /**
     * Search programs by name (for finding program IDs)
     */
    public function search(Request $request): JsonResponse
    {
        $name = $request->query('name');
        
        if (!$name) {
            return response()->json([
                'error' => 'Name parameter required'
            ], 400);
        }

        try {
            DB::statement('SET search_path TO academics');
            
            $programs = DB::table('programs')
                ->where('name', 'ILIKE', '%' . $name . '%')
                ->orWhere('name', 'ILIKE', '%' . str_replace('&', 'and', $name) . '%')
                ->select('id', 'name', 'program_code')
                ->limit(10)
                ->get();
            
            DB::statement('SET search_path TO public');
            
            return response()->json($programs);
            
        } catch (\Exception $e) {
            DB::statement('SET search_path TO public');
            return response()->json([
                'error' => 'Search failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

