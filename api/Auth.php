<?php

class Auth
{
    private UserGateway $userGateway;
    private int $userId;

    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    public function authenticateAPIKey(): bool
    {
        if (empty($_SERVER["HTTP_X_API_KEY"])) {
            http_response_code(400);
            echo json_encode(["message" => "missing API key"]);
            return false;
        }

        $api_key = $_SERVER["HTTP_X_API_KEY"];
        $user = $this->userGateway->getByApiKey($api_key);

        if ($user === []) {
            http_response_code(401);
            echo json_encode(["message" => "invalid API key"]);
            return false;
        }

        $this->userId = $user['id'];
        return true;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}