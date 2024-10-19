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


class AuthController extends Controller

{
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Autentica um usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário autenticado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Authorized"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado, credenciais inválidas"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
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


    /**
     * @OA\Post(
     *     path="/api/auth/employer-login",
     *     summary="Autentica um empregador",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="employer@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empregador autenticado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Authorized"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado, credenciais inválidas"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Registra um novo usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "documentNumber", "phone_number", "idade"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="documentNumber", type="string", example="12345678901"),
     *             @OA\Property(property="phone_number", type="string", example="11987654321"),
     *             @OA\Property(property="idade", type="integer", example=30),
     *             @OA\Property(property="photo", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao criar usuário"
     *     )
     * )
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:4',
            'documentNumber' => 'required|string|min:4',
            'phone_number' => 'required|string|min:4',
            'idade' => 'required|integer',
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

    /**
     * @OA\Post(
     *     path="/api/auth/register-employer",
     *     summary="Registra um novo empregador",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "documentNumber", "phone_number"},
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="employer@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="documentNumber", type="string", example="12345678901"),
     *             @OA\Property(property="phone_number", type="string", example="11987654321"),
     *             @OA\Property(property="photo", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Empregador registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Employee created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao criar empregador"
     *     )
     * )
     */
    public function createEmployer(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:employees,email',
            'password' => 'required|string|min:4',
            'documentNumber' => 'required|string|min:4',
            'phone_number' => 'required|string|min:4',
            'photo' => 'nullable|string|min:4',
        ]);

        try {
            $validatedData['password'] = Hash::make($validatedData['password']);
            Employee::create($validatedData);

            return response()->json(['message' => 'Employee created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create employee', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
