<?php

namespace Database;

use PDO;
use PDOException;
use validation\Validation;

defined('ROOTPATH') or exit('Access Denied!');

class Database
{
    //using pdo and connect to the database
    public static function connect()
    {
        try {
            return new PDO("mysql:hostname=localhost;dbname=registration", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            die("no connection...");
        }

    }

    // make a query method that will execute the info from database
    public static function query($query, $data = [])
    {
        $con = self::connect()->prepare($query);
        $statement = $con->execute($data);
        if ($statement) {
            $result = $con->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result) > 0) {
                return $result;
            }
        }
        return false;
    }

    // insert into database
    public static function insert($table, $key = [])
    {

        $data = implode(', ', array_keys($key));
        $data2 = ':' . implode(', :', array_keys($key));
        $query = 'INSERT INTO ' . $table . ' (' . $data . ') VALUES (' . $data2 . ')';
        return self::query($query, $key);

    }

    // update into database
    public static function update($table, $key = [])
    {
        $query = 'update  ' . $table . ' SET name = :name, email= :email, phone =:phone, password = :password  WHERE id =:id';
        return self::query($query, $key);

    }

    // read database
    public static function read($table, $data, $key)
    {
        $query = "SELECT COUNT(*) as count FROM $table WHERE $key = :$key ";
        return self::query($query, $data);
    }

    public static function loggedIn($table, $data)
    {
        $query = "SELECT COUNT(*) as count FROM $table WHERE (email = :login or phone = :login) && password = :password limit 1";
        return self::query($query, $data);
    }

    public static function readAll($table, $data = [])
    {
        $query = "SELECT * FROM $table";
        return self::query($query);
    }
}