<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Illuminate\Support\Facades\Auth;

class CheckJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o cabeçalho Authorization está presente
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader || !preg_match('/^Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            return Response::json(['message' => 'Token JWT não fornecido ou inválido.'], 401);
        }

        // Extrai o token JWT do cabeçalho
        $jwt = $matches[1];

        try {
            // Decodifica o token usando a chave secreta definida no .env
            $decoded = JWT::decode($jwt, new \Firebase\JWT\Key(env('JWT_SECRET'), 'HS256'));

            // Verifica se a decodificação retornou um objeto stdClass
            if (!is_object($decoded)) {
                return Response::json(['message' => 'Token JWT inválido.'], 401);
            }

        } catch (ExpiredException $e) {
            return Response::json(['message' => 'Token JWT expirado.'], 401);
        } catch (SignatureInvalidException $e) {
            return Response::json(['message' => 'Token JWT com assinatura inválida.'], 401);
        } catch (BeforeValidException $e) {
            return Response::json(['message' => 'Token JWT não está válido ainda.'], 401);
        } catch (\Exception $e) {
            return Response::json(['message' => 'Token JWT inválido.'], 401);
        }

        return $next($request);
    }
}
