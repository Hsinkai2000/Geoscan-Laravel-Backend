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
    private function format_projects($projects)
    {
        $grouped_data = [];
        foreach ($projects as $project) {
            $client_name = $project['client_name'];
            if (!isset($grouped_data[$client_name])) {
                $grouped_data[$client_name] = [
                    'name' => $client_name,
                    'jobsite_location' => '',
                    'project_description' => '',
                    'bca_reference_number' => '',
                    'created_at' => '',
                    '_children' => [],
                ];
            }

            $end_user_info = [
                'name' => $project['end_user_name'],
                'jobsite_location' => $project['jobsite_location'],
                'project_description' => $project['project_description'],
                'bca_reference_number' => $project['bca_reference_number'],
                'created_at' => $project['created_at'],
            ];
            $grouped_data[$client_name]['_children'][] = $end_user_info;
        }

        // Convert associative array to indexed array
        return array_values($grouped_data);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $project_type = $request->get('project_type');
        if (Gate::authorize('view-project', $user)) {
            try {
                $projects = Project::where([['user_id', $user->id], ['project_type', $project_type]])->get();
                if ($project_type == 'sales') {
                    $projects = $this->format_projects($projects);
                }
                debug_log('project', [$projects]);
                return response()->json(["projects" => $projects]);
            } catch (Exception $e) {
                debug_log('ss', [$e->getMessage()]);
                return render_error($e->getMessage());
            }
        };
        debug_log("unauthorised");
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
