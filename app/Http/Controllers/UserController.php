<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(Request $request)
    {
        try {
            $user_params = $request->only((new User)->getFillable());
            $user_params['password'] = Hash::make($user_params['password']);

            $user_id = User::insertGetId($user_params);
            $user = User::find($user_id);
            return render_ok(["user" => $user]);

        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function index()
    {
        try {
            return render_ok(["users" => User::all()]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function get(Request $request)
    {
        try {

            $id = $request->route('id');
            $user = User::find($id);
            if (!$user) {

                return render_unprocessable_entity("Unable to find user with id " . $id);
            }
            return render_ok(["user" => $user]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $id = $request->route('id');
            $user_params = $request->only((new User)->getFillable());
            $user = User::find($id);
            if (!$user) {
                return render_unprocessable_entity("Unable to find user with id " . $id);
            }

            if (!$user->update($user_params)) {
                throw new Exception("Unable to update user");
            }
            return render_ok(["user" => $user_params]);

        } catch (Exception $e) {
            render_error($e);
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->route('id');
            $user = User::find($id);
            if (!$user) {
                return render_unprocessable_entity("Unable to find user with id " . $id);
            }
            if (!$user->delete()) {
                throw new Exception("Unable to delete user");
            }
            return render_ok(["user" => $user]);

        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

}
