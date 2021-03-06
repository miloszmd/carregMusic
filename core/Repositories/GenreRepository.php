<?php

namespace CarregMusic\Repositories;

use CarregMusic\Database;
use CarregMusic\Mappers\GenreMapper;

class GenreRepository
{
    function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAll()
    {
        $response = [];

        $data = $this->database->getAll('genres');

        while($row = mysqli_fetch_array($data)){
            array_push($response, GenreMapper::map($row));
        }

        return $response;
    }

    public function getFavouriteGenreFor($username)
    {
        $data = mysqli_query($this->database->connection, "SELECT genres.genreName FROM genres INNER JOIN users USING(genreID) WHERE username = '$username' LIMIT 1");
        $row = mysqli_fetch_array($data);

        return $row['genreName'];
    }
}