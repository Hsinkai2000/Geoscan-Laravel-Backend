<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Exception;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
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

    public function index()
    {
        try {
            return render_ok(["projects" => Project::all()]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
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
