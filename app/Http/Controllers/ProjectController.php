<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project; 

class ProjectController extends Controller
{
    public function project()
    {
         
        $projects = Project::all();

       
        return view('user.dashboard', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project' => 'required',
            'lokasi' => 'required',
            'date_start' => 'required|date',
        ]);

        Project::create($validated);

        return redirect()->route('user.dashboard')->with('success', 'Project success added');
    }
}
