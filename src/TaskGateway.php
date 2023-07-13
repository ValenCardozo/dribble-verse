<?php

declare(strict_types=1);

class TaskGateway
{
    private PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT *
                FROM task
                ORDER BY name";

        $statement = $this->connection->query($sql);

        $data = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $row['is_completed'] = (bool) $row['is_completed'];

            $data[] = $row;
        }

        return $data;
    }

    public function get(string $id): array
    {
        $sql = "SELECT *
                FROM task
                WHERE id = :id";

        $statement = $this->connection->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if ($data !== false) {
            $data['is_completed'] = (bool) $data['is_completed'];
        }

        return $data ? $data : [];
    }
}