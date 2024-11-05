<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Http\Response;

class TicketController extends Controller
{
       /**
     * @OA\Get(
     *     path="/api/ticket",
     *     summary="Listar todos os tickets",
     *     tags={"Tickets"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tickets",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="app/OpenApi/Schemas/Ticket.php")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $tickets = Ticket::all();
        return response()->json($tickets, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/tickets",
     *     summary="Criar um novo ticket",
     *     tags={"Tickets"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "status"},
     *             @OA\Property(property="title", type="string", example="Erro no sistema"),
     *             @OA\Property(property="description", type="string", example="Descrição do erro detalhada"),
     *             @OA\Property(property="status", type="string", enum={"open", "closed", "pending"}, example="open")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ticket criado com sucesso",
     *             @OA\Items(ref="app/OpenApi/Schemas/Ticket.php")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:open,closed,pending',
        ]);

        $ticket = Ticket::create($validatedData);

        return response()->json($ticket, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/tickets/{id}",
     *     summary="Exibir um ticket específico",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do ticket",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do ticket retornados com sucesso",
     *         @OA\JsonContent(ref="app/OpenApi/Schemas/Ticket.php")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket não encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($ticket, Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/tickets/{id}",
     *     summary="Atualizar um ticket existente",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do ticket",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Erro no sistema atualizado"),
     *             @OA\Property(property="description", type="string", example="Descrição detalhada do erro atualizado"),
     *             @OA\Property(property="status", type="string", enum={"open", "closed", "pending"}, example="closed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket atualizado com sucesso",
     *         @OA\JsonContent(ref="app/OpenApi/Schemas/Ticket.php")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket não encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|string|in:open,closed,pending',
        ]);

        $ticket->update($validatedData);

        return response()->json($ticket, Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/tickets/{id}",
     *     summary="Excluir um ticket",
     *     tags={"Tickets"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do ticket",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Ticket excluído com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $ticket->delete();

        return response()->json(['message' => 'Ticket excluído com sucesso'], Response::HTTP_NO_CONTENT);
    }
}
