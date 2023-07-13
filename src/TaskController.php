<?php

class TaskController
{
    private TaskGateway $gateway;
    public function __construct(TaskGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function processRequest(string $method, ?string $id): void
    {
        if ($id === null) {
            if ($method === 'GET') {
                echo json_encode($this->gateway->getAll());
            } elseif ($method === 'POST') {
                echo 'create';
            } else {
                $this->respondMethodNotAllowed('GET, POST');
            }
        } else {
            $task = $this->gateway->get($id);

            if ($task === []) {
                $this->respondNotFound($id);
                return;
            }

            switch ($method) {
                case 'GET':
                    echo json_encode($task);
                    break;
                case 'PATCH':
                    echo "update $id";
                    break;
                case 'DELETE':
                    echo "delete $id";
                    break;
                default:
                    $this->respondMethodNotAllowed('GET, PATCH, DELETE');
            }
        }
    }

    private function respondMethodNotAllowed(string $allowedMethods): void
    {
        http_response_code(405);
        header("Allow: {$allowedMethods}");
    }

    private function respondNotFound(int $id): void
    {
        http_response_code(404);
        echo json_encode(["message" => "Task with ID {$id} not found"]);
    }
}