<?php

class UserGateway
{
    private PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function getByApiKey(string $key): array
    {
        $sql = "SELECT *
            FROM user
            WHERE api_key = :api_key";

        $statement = $this->connection->prepare($sql);

        $statement->bindValue(":api_key", $key, PDO::PARAM_STR);

        $statement->execute();

        $query = $statement->fetch(PDO::FETCH_ASSOC);

        if ($query === false) {
            return [];
        }

        return $query;
    }
}