<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{

    public function show()
    {
        return view('web.project');
    }

    public function create(Request $request)
    {
        try {
            $project_params = $request->only((new Project)->getFillable());
            $project_id = Project::insertGetId($project_params);
            $project = Project::find($project_id);
            return render_ok(["project" => $project]);

        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function index(Request $request)
    {
        debug_log("hihi");
        $user = Auth::user();
        if (Gate::authorize('view-project', $user)) {

            try {
                return response()->json(["projects" => Project::all()]);
            } catch (Exception $e) {
                debug_log('ss', [$e->getMessage()]);
                return render_error($e->getMessage());
            }
        };
        return render_error("Unauthorised");
    }

    public function get(Request $request)
    {
        try {
            $id = $request->route('id');
            $project = Project::find($id);
            if (!$project) {
                return render_unprocessable_entity("Unable to find project with id " . $id);
            }
            return render_ok(["project" => $project]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $id = $request->route('id');
            $project_params = $request->only((new Project)->getFillable());
            $project = Project::find($id);
            if (!$project) {
                return render_unprocessable_entity("Unable to find project with id " . $id);
            }

            if (!$project->update($project_params)) {
                throw new Exception("Unable to update project");
            }

            return render_ok(["project" => $project]);

        } catch (Exception $e) {
            render_error($e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->route('id');
            $project = Project::find($id);
            if (!$project) {
                return render_unprocessable_entity("Unable to find project with id " . $id);
            }
            if (!$project->delete()) {
                throw new Exception("Unable to delete project");
            }
            return render_ok(["project" => $project]);

        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }
}
