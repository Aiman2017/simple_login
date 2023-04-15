<?php

namespace Database;

use PDO;
use PDOException;
use validation\Validation;
defined('ROOTPATH') OR exit('Access Denied!');
class Database
{
    //using pdo and connect to the database
    protected static function connect()
    {
        try {
            return new PDO("mysql:hostname=localhost;dbname=registration", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            die("some error");
        }

    }

    // make a query method that will execute the info from database
    public static function query($query, $data = [])
    {
        $con = static::connect()->prepare($query);
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
    public static function insert($table, $key = []) {

        $data = implode(', ', array_keys($key));
        $data2 = ':' . implode(', :', array_keys($key));
        $query = 'INSERT INTO ' . $table. ' (' . $data . ') VALUES (' . $data2 . ')';
        $result = static::query($query, $key);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // read database
    public static function read($table, $data, $key) {
        $query = "SELECT COUNT(*) as count FROM $table WHERE $key = :$key ";
        return self::query($query, $data);
    }

    public static function loggedIn($table, $data) {
        $query = "SELECT COUNT(*) as count FROM $table WHERE (email = :login or phone = :login) && password = :password limit 1";
        return self::query($query, $data);

    }

}