namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Raffle",
 *     type="object",
 *     required={"title", "raffleType", "startDate", "endDate", "ticketPrice", "maxTicketsPerUser", "maxParticipants", "paymentMethod"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Rifa de Natal"),
 *     @OA\Property(property="raffleType", type="string", example="sorteio"),
 *     @OA\Property(property="startDate", type="string", format="date-time", example="2024-12-01T00:00:00Z"),
 *     @OA\Property(property="endDate", type="string", format="date-time", example="2024-12-31T23:59:59Z"),
 *     @OA\Property(property="description", type="string", example="Descrição da rifa"),
 *     @OA\Property(property="terms", type="string", example="Termos e condições"),
 *     @OA\Property(property="ticketPrice", type="number", example=10.00),
 *     @OA\Property(property="maxTicketsPerUser", type="integer", example=5),
 *     @OA\Property(property="maxParticipants", type="integer", example=100),
 *     @OA\Property(property="paymentMethod", type="string", example="Cartão de Crédito"),
 *     @OA\Property(property="awards", type="array",
 *         @OA\Items(ref="#/components/schemas/Award")
 *     ),
 * )
 */
