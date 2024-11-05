/**
 * @OA\Schema(
 *     schema="Ticket",
 *     type="object",
 *     title="Ticket",
 *     description="Esquema para o modelo de Ticket",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         example="Erro no sistema"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         example="Descrição detalhada do erro"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="open"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-11-04T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-11-04T12:00:00Z"
 *     )
 * )
 */
