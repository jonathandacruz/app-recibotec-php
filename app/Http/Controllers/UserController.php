<?php

namespace App\Http\Controllers;

use App\Services\BillingAddressService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;
    protected $billingAddressService;

    // Injetando o UserService no construtor
    public function __construct(UserService $userService, BillingAddressService $billingAddressService)
    {
        $this->userService = $userService;
        $this->billingAddressService = $billingAddressService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $this->userService->createUser($request->all());
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function login(Request $request)
    {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Basic ')) {
            return response()->json(['message' => 'Invalid or missing Authorization header'], 401);
        }

        $encoded = substr($header, 6);
        $decoded = base64_decode($encoded);

        if (!$decoded || !str_contains($decoded, ':')) {
            return response()->json(['message' => 'Malformed credentials'], 401);
        }

        [$email, $password] = explode(':', $decoded, 2);

        $credentials = ['email' => $email, 'password' => $password];

        $token = $this->userService->login($credentials);

        if ($token) {
            return response()->json(['token' => $token]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function userBillingAddress(Request $request): JsonResponse
    {
        // Valida os dados do request
        $validated = $request->validate([
            'cep' => 'required|string|max:9',
            'endereco' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
            'pais' => 'required|string|max:255',
        ]);

        // Obtém o usuário autenticado
        $user = Auth::user();

        try {
            // Chama o serviço para salvar o endereço
            $billingAddress = $this->billingAddressService->saveBillingAddress($validated, $user->id);

            return response()->json([
                'message' => 'Endereço de cobrança salvo com sucesso!',
                'data' => $billingAddress,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao salvar o endereço.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
