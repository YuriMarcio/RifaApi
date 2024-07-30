<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function loginUser(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($credentials)) {
                throw new \Exception('Unauthorized');
            }

            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Authorized',
                'token' => $token,
                'user' => $user
            ], Response::HTTP_OK);

        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to create token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function loginEmployer(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $employee = Employee::where('email', $credentials['email'])->first();

            if ($employee && Hash::check($credentials['password'], $employee->password)) {
                $token = JWTAuth::fromUser($employee);
                return response()->json(['message' => 'Authorized', 'token' => $token], Response::HTTP_OK);
            } else {
                throw new \Exception('Unauthorized');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized', 'error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:4',
            'documentNumber' => 'required|string|min:4',
            'phone_number' => 'required|string|min:4',
            'idade' => 'required|string',
            'photo' => 'nullable|string|min:4',
        ]);

        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            return response()->json(['message' => 'User created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createEmployer(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:employees,email',
                'password' => 'required|string|min:6',
                'documentNumber' => 'required|string|min:11|max:14',
                'phone_number' => 'required|string|min:10|max:15',
                'photo' => 'nullable|string|min:4',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();

        try {
            $employee = new Employee();
            $employee->name = $validatedData['name'];
            $employee->email = $validatedData['email'];
            $employee->password = Hash::make($validatedData['password']);
            $employee->documentNumber = $validatedData['documentNumber'];
            $employee->phone_number = $validatedData['phone_number'];
            $employee->photo = $validatedData['photo'] ?? null;
            $employee->access_level = 1;
            $employee->save();

            DB::commit();

            return response()->json(['message' => 'Employee created successfully'], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'An unexpected error occurred', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function teste()
    {
        return response()->json(['message' => 'Test successful'], Response::HTTP_OK);
    }

    public function showPerId(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['User' => $user], Response::HTTP_OK);
        } catch (\Exception $e) {
            try {
                $employee = Employee::findOrFail($id);
                return response()->json(['User' => $employee], Response::HTTP_OK);
            } catch (\Exception $e) {
                return response()->json(['message' => 'User not found', 'error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function edit(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['User' => $user], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found', 'error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone_number' => 'required|string',
                'documentNumber' => 'required|string',
                'idade' => 'required|integer',
                'photo' => 'nullable|string',
            ]);

            $user = User::findOrFail($id);
            $user->fill($request->only([
                'name',
                'email',
                'phone_number',
                'documentNumber',
                'idade',
                'photo',
            ]));

            $user->save();

            return response()->json(['message' => 'User updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], Response::HTTP_OK);
    }
}
