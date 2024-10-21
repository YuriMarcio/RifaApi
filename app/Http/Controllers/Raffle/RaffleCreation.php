<?php

namespace App\Http\Controllers\Raffle;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\Raffle;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;


class RaffleCreation extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/raffle",
     *     summary="Obtém todas as rifas",
     *     tags={"Raffle"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de rifas obtida com sucesso",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */
    public function index()
    {
        $raffles = Raffle::all();

        return response()->json($raffles);
    }

    /**
     * @OA\Post(
     *     path="/api/raffle/create",
     *     summary="Cria uma rifa",
     *     tags={"Raffle"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "raffleType", "startDate", "endDate", "ticketPrice", "maxTicketsPerUser", "maxParticipants", "paymentMethod", "awards"},
     *             @OA\Property(property="title", type="string", example="Rifa de Natal"),
     *             @OA\Property(property="raffleType", type="string", example="sorteio"),
     *             @OA\Property(property="startDate", type="string", format="date-time", example="2024-12-01T00:00:00Z"),
     *             @OA\Property(property="endDate", type="string", format="date-time", example="2024-12-31T23:59:59Z"),
     *             @OA\Property(property="ticketPrice", type="number", example=10.00),
     *             @OA\Property(property="maxTicketsPerUser", type="integer", example=5),
     *             @OA\Property(property="maxParticipants", type="integer", example=100),
     *             @OA\Property(property="paymentMethod", type="string", example="Cartão de Crédito"),
     *             @OA\Property(property="awards", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="Id", type="string", example="award1"),
     *                     @OA\Property(property="Title", type="string", example="Prêmio 1"),
     *                     @OA\Property(property="Description", type="string", example="Descrição do prêmio"),
     *                     @OA\Property(property="tagType", type="string", example="Product"),
     *                     @OA\Property(property="images", type="array",
     *                         @OA\Items(type="file")
     *                     ),
     *                     @OA\Property(property="Value", type="number", example=100.00),
     *                     @OA\Property(property="image", type="file")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rifa criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rifa criada com sucesso"),
     *             @OA\Property(property="raffle", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro de validação"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor"
     *     )
     * )
     */

    public function RaffleCreate(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'raffleType' => 'required|string|max:50',
                'startDate' => 'date',
                'endDate' => 'date',
                'description' => 'nullable|string|max:1000',
                'terms' => 'nullable|string|max:1000',
                'ticketPrice' => 'required|numeric|min:0',
                'maxTicketsPerUser' => 'required|integer|min:1',
                'maxParticipants' => 'required|integer|min:1',
                'paymentMethod' => 'required|string|in:pix,Credito,Boleto',
                'awards' => 'required|array|min:1',
                'awards.*.Title' => 'required|string|max:255',
                'awards.*.Description' => 'nullable|string|max:1000',
                'awards.*.images' => 'nullable|array|max:4',
                'awards.*.images.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'awards.*.tagType' => 'required|string|in:Product,Money',
                'awards.*.Value' => 'nullable|required_if:awards.*.tagType,Money|numeric|min:0',
                'awards.*.image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
            ]);

            DB::beginTransaction();

            // Gerando uma URL única com 5 caracteres finais
            $Url = substr(uniqid($request->title, true), -5);
            $DateNow = new DateTime();

            Log::info('Dados validados:', $validated);

            // Salvando a rifa com os campos adicionais
            $raffle = Raffle::create([
                'title' => $validated['title'],
                'raffleType' => $validated['raffleType'],
                'startDate' => $validated['startDate'] ?? null, // Permite null
                'endDate' => $validated['endDate'] ?? null, // Permite null
                'description' => $validated['description'] ?? null, // Permite null
                'terms' => $validated['terms'] ?? null, // Permite null
                'ticketPrice' => $validated['ticketPrice'],
                'maxTicketsPerUser' => $validated['maxTicketsPerUser'],
                'maxParticipants' => $validated['maxParticipants'],
                'paymentMethod' => $validated['paymentMethod'],
                'url' => $Url // Definindo manualmente
            ]);


            $awardIds = []; // Array para armazenar os IDs dos prêmios

            // Processando os prêmios (awards)
            foreach ($validated['awards'] as $awardData) {
                $award = new Award();
                $award->title = $awardData['Title'];
                $award->description = $awardData['Description'] ?? null;
                $award->tagType = $awardData['tagType'];

                if ($awardData['tagType'] === 'Product') {
                    // Verificando as imagens e salvando se existir
                    if (isset($awardData['images'])) {
                        $images = [];
                        foreach ($awardData['images'] as $image) {
                            if ($image) {
                                $imagePath = $image->store('awards_images');
                                $images[] = $imagePath;
                            }
                        }
                        $award->images = json_encode($images);
                    }
                } elseif ($awardData['tagType'] === 'Money') {
                    // Salvando imagem se existir
                    if (isset($awardData['image']) && $awardData['image']) {
                        $imagePath = $awardData['image']->store('awards_images');
                        $award->image = $imagePath;
                    }
                    $award->value = $awardData['Value'];
                }

                $award->save();
                $awardIds[] = $award->id; // Adiciona o ID do prêmio ao array
            }

            // Associando prêmios à rifa
            $raffle->awards()->attach($awardIds);

            DB::commit();
            // Retorno em caso de sucesso
            return response()->json([
                'message' => 'Rifa criada com sucesso',
                'raffle' => $raffle
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retorno em caso de erro de validação
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 400);
        } catch (Exception $e) {
            DB::rollBack();
            // Retorno em caso de erro interno do servidor
            return response()->json([
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/raffle/{id}",
     *     operationId="getRaffleById",
     *     tags={"Raffle"},
     *     summary="Exibe uma rifa específica",
     *     description="Retorna os detalhes de uma rifa com base no ID fornecido.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da rifa a ser exibida",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da rifa encontrados",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rifa não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rifa não encontrada.")
     *         )
     *     ),
     * )
     */
    public function show($id)
    {
        // Tenta encontrar a rifa pelo ID
        $raffle = Raffle::where('id', $id)->first();

        // Se a rifa não for encontrada, retorna uma resposta 404
        if (!$raffle) {
            return response()->json([
                'message' => 'Rifa não encontrada.'
            ], 404);
        }

        // Se a rifa for encontrada, retorna a rifa em formato JSON
        return response()->json($raffle);
    }

    /**
     * @OA\Put(
     *     path="/api/raffle/{id}",
     *     operationId="updateRaffle",
     *     tags={"Raffle"},
     *     summary="Atualiza uma rifa específica",
     *     description="Atualiza os dados de uma rifa com base no ID fornecido.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da rifa a ser atualizada",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "raffleType", "startDate", "endDate", "ticketPrice", "maxTicketsPerUser", "maxParticipants", "paymentMethod"},
     *             @OA\Property(property="title", type="string", example="Rifa de Natal"),
     *             @OA\Property(property="raffleType", type="string", example="sorteio"),
     *             @OA\Property(property="startDate", type="string", format="date-time", example="2024-12-01T00:00:00Z"),
     *             @OA\Property(property="endDate", type="string", format="date-time", example="2024-12-31T23:59:59Z"),
     *             @OA\Property(property="description", type="string", example="Descrição da rifa"),
     *             @OA\Property(property="terms", type="string", example="Termos e condições"),
     *             @OA\Property(property="ticketPrice", type="number", example=10.00),
     *             @OA\Property(property="maxTicketsPerUser", type="integer", example=5),
     *             @OA\Property(property="maxParticipants", type="integer", example=100),
     *             @OA\Property(property="paymentMethod", type="string", example="Cartão de Crédito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rifa atualizada com sucesso.",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rifa não encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rifa não encontrada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação dos dados.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Os dados fornecidos são inválidos.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Validação dos dados recebidos, permitindo que os campos sejam opcionais
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'raffleType' => 'sometimes|required|string',
            'startDate' => 'sometimes|required|date',
            'endDate' => 'sometimes|required|date',
            'ticketPrice' => 'sometimes|required|numeric',
            'maxTicketsPerUser' => 'sometimes|required|integer',
            'maxParticipants' => 'sometimes|required|integer',
            'paymentMethod' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        // Encontra a rifa pelo ID
        $raffle = Raffle::find($id);

        if (!$raffle) {
            return response()->json([
                'message' => 'Rifa não encontrada.'
            ], 404);
        }

        $teste= "teste";

        // Preenche o modelo com os dados validados
        $raffle->fill($validatedData);

        // Salva as alterações no banco de dados
        $raffle->save();

        return response()->json($raffle, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/raffle/{id}",
     *     operationId="destroyRaffle",
     *     tags={"Raffle"},
     *     summary="Remove uma rifa específica",
     *     description="Deleta uma rifa com base no ID fornecido.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da rifa a ser excluída",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rifa excluída com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rifa excluída com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rifa não encontrada.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rifa não encontrada.")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        // Encontra a rifa pelo ID
        $raffle = Raffle::find($id);

        // Verifica se a rifa foi encontrada
        if (!$raffle) {
            return response()->json([
                'message' => 'Rifa não encontrada.'
            ], 404);
        }

        // Exclui a rifa
        $raffle->delete();

        // Retorna uma resposta de sucesso
        return response()->json([
            'message' => 'Rifa excluída com sucesso.'
        ], 200);
    }
}
