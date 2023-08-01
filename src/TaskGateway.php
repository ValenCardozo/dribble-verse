<?php

declare(strict_types=1);

class TaskGateway
{
    private PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function getAllForUser(int $userId): array
    {
        $sql = "SELECT *
                FROM task
                WHERE user_id = :userId
                ORDER BY name";

        $statement = $this->connection->prepare($sql);

        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);

        $statement->execute();

        $data = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $row['is_completed'] = (bool) $row['is_completed'];

            $data[] = $row;
        }

        return $data;
    }

    public function getForUser(int $userId, string $id): array
    {
        $sql = "SELECT *
                FROM task
                WHERE id = :id
                AND user_id = :userId";

        $statement = $this->connection->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);

        $statement->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if ($data !== false) {
            $data['is_completed'] = (bool) $data['is_completed'];
        }

        return $data ? $data : [];
    }

    public function createForUser(int $userId, array $data): string
    {
        $sql = "INSERT INTO task (name, priority, is_completed, user_id)
                VALUES (:name, :priority, :is_completed, :userId)";

        $statement = $this->connection->prepare($sql);

        $statement->bindValue(":name", $data['name'], PDO::PARAM_STR);

        if ( empty($data["priority"])) {
            $statement->bindValue(":priority", null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(":priority", $data['priority'], PDO::PARAM_INT);
        }

        $statement->bindValue(":is_completed", $data['is_completed'] ?? false, PDO::PARAM_BOOL);
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);

        $statement->execute();

        return $this->connection->lastInsertId();
    }

    public function updateForUser(int $userId, string $id, array $data): int
    {
        $fields = [];

        if (! empty($data["name"])) {
            $fields["name"] = [
                $data["name"],
                PDO::PARAM_STR
            ];
        }

        if (array_key_exists("priority", $data)) {
            $fields["priority"] = [
                $data["priority"],
                $data["priority"] === null ? PDO::PARAM_NULL : PDO::PARAM_INT
            ];
        }

        if (array_key_exists("is_completed", $data)) {
            $fields["is_completed"] = [
                $data["is_completed"],
                PDO::PARAM_BOOL
            ];
        }

        if (empty($fields)) {

            return 0;
        } else {
            $sets = array_map(function($value) {
                return "$value = :$value";

            }, array_keys($fields));

            $sql = "UPDATE task"
                    . " SET " . implode(", ", $sets)
                    . " WHERE id = :id"
                    . " AND user_id = :userId";

            $statement = $this->connection->prepare($sql);

            $statement->bindValue(":id", $id, PDO::PARAM_INT);
            $statement->bindValue(":userId", $userId, PDO::PARAM_INT);

            foreach ($fields as $name => $values) {
                $statement->bindValue(":$name", $values[0], $values[1]);
            }

            $statement->execute();

            return $statement->rowCount();
        }
    }

    public function deleteForUser(int $userId, string $id): int
    {
        $sql = "DELETE FROM task
                WHERE id = :id
                AND user_id = :userId";


        $statement = $this->connection->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(":userId", $userId, PDO::PARAM_INT);

        $statement->execute();

        return $statement->rowCount();
    }
}