<?php
class DataBaseMssql{
    public function getConnection(){
            $db = new PDO("sqlsrv:server=".getenv("SQL_SERVER").";"
                ."Database=".getenv("SQL_DATABASE")
                .";Encrypt=yes;TrustServerCertificate=true", getenv("SQL_USER"), 
                getenv("SQL_PASSWORD"),
                [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION
                ]);
        return $db;
    }
}