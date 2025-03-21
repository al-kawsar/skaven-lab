<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;

class UserController extends Controller
{

    public function index(Request $request)
    {

        $data['totalData'] = User::count();
        return view('pages.user.index', compact('data'));
    }

    public function getData()
    {

        $data = User::where('id', '!=', auth()->id())->orderBy('updated_at', 'desc')->get();

        $transformedData = $data->map(function ($user) use (&$counter) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->name,
                'number' => ++$counter, // Increment counter for each iteration
            ];
        });

        return response()->json([
            'data' => $transformedData,
        ], 200);
    }

    public function create()
    {
        $roles = Role::all();
        return view('pages.user.create', compact('roles'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $payload = $request->validated();

            User::create($payload);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Data User berhasil ditambahkan'
        ], 200);
    }

    public function show(string $id)
    {
        return 'show jancuk';
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('pages.user.edit', compact('user', 'roles'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        try {
            $payload = $request->validated();

            $user->update($payload);

        } catch (\Exception $e) {
            return to_route('user.edit', $user->id)->with([
                'status' => 'error',
                'type' => 'toast',
                'message' => $e->getMessage()
            ], 500);
        }

        return to_route('user.index')->with([
            'status' => 'success',
            'type' => 'toast',
            'message' => 'User berhasil diubah'
        ], 200);
    }

    public function destroy(User $id)
    {
        try {
            $id->delete();
            return response()->json([
                'message' => 'Data User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyAll()
    {
        try {
            User::truncate();
            return response()->json(['message' => 'Data User berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
