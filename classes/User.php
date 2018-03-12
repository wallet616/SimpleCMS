<?php

require_once "../configuration.php";
require_once "Response.php";
require_once "ErrorElement.php";

class User {

    public $is_logged = false;

    public $user_id;
    public $login;
    public $password;



    public function __construct() 
    {
        
    }


    public function logout() : Response 
    {
        $response = new Response();

        if ($this->is_logged) 
        {
            $this->is_logged = false;
            $response->set_status("OK");
        } 
        else 
        {
            $response->set_status("NO_OK");
            $response->data_add(new ErrorElement("user wasn't logged in", "user"));
        }

        return $response;
    }


    public function login($db, $login, $password) : Response
    {
        $response = new Response();


        // User already logged in
        if ($this->is_logged === true) 
        {
            $response->set_status("NO_OK");
            $response->data_add(new ErrorElement("user already logged in", "user"));
            
            return $response;
        } 


        $was_error = false;


        // login validation
        if ($login !== null)
        {
            // Remove spaces from begining and ending
            $login = trim($login); 
        } 
        else 
        {
            $response->data_add(new ErrorElement("is null", "login"));
            $was_error = true;
        }



        // Password validation
        if ($password !== null)
        {
            // Encode
            $password = hash($GLOBALS["db_password_encoding"], $password);
        } 
        else 
        {
            $response->data_add(new ErrorElement("is null", "password"));
            $was_error = false;
        }


        if ($was_error) 
        {
            $response->set_status("NO_OK");
            return $response;
        }


        // search in database for user
        $db_table_users = $GLOBALS["db_table_accounts"];
        $query = "SELECT * FROM $db_table_users WHERE login=:login AND password=:password";
        $statement = $db->prepare($query);
        $statement->bindParam(":login", $login, PDO::PARAM_STR);
        $statement->bindParam(":password", $password, PDO::PARAM_STR);
        $statement->execute();

        if ($statement->rowCount() > 0) 
        {
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            $result = $result[0];

            $this->merge_data($result);

            $this->is_logged = true;
            $response->set_status("OK");

            return $response;
        }
        else
        {
            $response->data_add(new ErrorElement("no matching user in database found", "user"));
            $response->set_status("NO_OK");

            return $response;
        } 
    }


    public function merge_data($data) : bool
    {
        $this->user_id = intval($data->user_id);

        $this->password = $data->password;
        $this->login = $data->login;

        return true;
    }
}

?>