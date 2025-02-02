<?php
namespace App\Middleware;

use App\Services\AuthService;

class ApiMiddleware {
    private $authService;
    private $rateLimiter;

    public function __construct() {
        $this->authService = new AuthService();
        $this->rateLimiter = new RateLimiter();
    }

    public function handle($request, $next) {
        // Check if the request has a valid JWT token
        $token = $request->getHeader('Authorization');

        if (!$token || !$this->authService->validateToken($token)) {
            return $this->respondWithUnauthorized();
        }

        // Extract user ID from the JWT token
        $userId = $this->authService->getUserIdFromToken($token);

        // Check for rate limiting
        if (!$this->rateLimiter->checkRateLimit($userId)) {
            return $this->respondWithRateLimitExceeded();
        }

        // Log the API request
        $this->rateLimiter->logRequest($userId);

        return $next($request);
    }

    private function respondWithUnauthorized() {
        return json_encode([
            'error' => 'Unauthorized',
            'message' => 'Invalid or missing token'
        ]);
    }

    private function respondWithRateLimitExceeded() {
        return json_encode([
            'error' => 'Rate Limit Exceeded',
            'message' => 'You have exceeded the rate limit. Please try again later.'
        ]);
    }
}
