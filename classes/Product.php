<?php

require_once "../configuration.php";
require_once "Response.php";
require_once "ErrorElement.php";
require_once "Helpers.php";

class Product {

    public static function parse_link($link) : int 
    {
        // id12345-test-product-something
        $link = explode("-", $link, 2); // ["id12345", "test-product-something"]
        $link = $link[0]; // "id12345"
        $link =  preg_replace("/[^0-9,.]/", "", $link); // "12345"
        $link =  intval($link); // 12345

        return $link;
    }

    public static function get($db, $product_id, $language) : Response 
    {
        $response = new Response();

        if ($product_id == null) {
            $response->set_status("NO_OK");
            $response->data_add(new ErrorElement("invalid id", "product"));
            return $response;
        }

        $db_table_products = $GLOBALS["db_table_products"];

        $lang_name = "name_" . $language;
        $lang_description = "description_" . $language;

        $query = "SELECT 
            product_id,
            category_id,
            product_code,
            $lang_name AS name,
            $lang_description AS description
            FROM $db_table_products WHERE product_id=:product_id LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindParam(":product_id", $product_id, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        if ($statement->rowCount() > 0) 
        {
            $result = $result[0];
            $response->set_status("OK");
            $response->data_set($result);
        } 
        else 
        {
            $response->set_status("NO_OK");
            $response->data_add(new ErrorElement("not found", "product"));
        }

        return $response;
    }

    public static function create($db, $category_id, $product_code, $name_pl, $name_en, $description_pl, $description_en) : Response 
    {
        $response = new Response();

        $category_id = intval($category_id);
        $product_code = Helpers::sanitize_string($product_code, false, 200);

        $name_pl = Helpers::sanitize_string($name_pl, false, 500);
        $name_en = Helpers::sanitize_string($name_en, false, 500);
        $description_pl = Helpers::sanitize_string($description_pl, true, 10000);
        $description_en = Helpers::sanitize_string($description_en, true, 10000);

        $db_table_products = $GLOBALS["db_table_products"];

        $query = "INSERT INTO $db_table_products (
            `category_id`,
            `product_code`,
            `name_pl`,
            `name_en`,
            `description_pl`,
            `description_en`
        ) VALUES (
            :category_id,
            :product_code,
            :name_pl,
            :name_en,
            :description_pl,
            :description_en
        )";

        // possible bug: if subcategory_of does not exist, user will now get the feedback
        
        $statement = $db->prepare($query);
        $statement->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        $statement->bindParam(":product_code", $product_code, PDO::PARAM_STR);
        $statement->bindParam(":name_pl", $name_pl, PDO::PARAM_STR);
        $statement->bindParam(":name_en", $name_en, PDO::PARAM_STR);
        $statement->bindParam(":description_pl", $description_pl, PDO::PARAM_STR);
        $statement->bindParam(":description_en", $description_en, PDO::PARAM_STR);
        $statement->execute();


        $last_id = $db->lastInsertId();
        $query = "SELECT * FROM $db_table_products WHERE product_id=$last_id LIMIT 1";
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        $response->set_status("OK");
        $response->data_set($result[0]);

        return $response;
    }

/*
    public static function delete($db, $id) : Response 
    {
        $response = new Response();

        $id = intval($id);

        if ($id === 1) {
            $response->set_status("NO_OK");
            $response->data_add(new ErrorElement("unable to delete root category", "category_id"));
            return $response;
        }

        $db_table_categories = $GLOBALS["db_table_categories"];

        $query = "DELETE FROM $db_table_categories WHERE `category_id`=:id";

        // possible bug: if subcategory_of does not exist, user will now get the feedback
        
        $statement = $db->prepare($query);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();

        $response->set_status("OK");

        return $response;
    }


    public static function update($db, $id, $subcategory_of, $name_pl, $name_en) : Response 
    {
        $response = new Response();

        $id = intval($id);
        $subcategory_of = intval($subcategory_of);
        $name_pl = Helpers::sanitize_string($name_pl, false, 100);
        $name_pl = preg_replace("/\s+/", "-", $name_pl); // spaces to -
        $name_en = Helpers::sanitize_string($name_en, false, 100);
        $name_en = preg_replace("/\s+/", "-", $name_en); // spaces to -

        $db_table_categories = $GLOBALS["db_table_categories"];

        $query = "UPDATE $db_table_categories SET
            `subcategory_of`=:subcategory_of,
            `name_pl`=:name_pl,
            `name_en`=:name_en
        WHERE
            `category_id`=:id    
        ";

        // possible bug: if subcategory_of does not exist, user will now get the feedback
        
        $statement = $db->prepare($query);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->bindParam(":subcategory_of", $subcategory_of, PDO::PARAM_INT);
        $statement->bindParam(":name_pl", $name_pl, PDO::PARAM_STR);
        $statement->bindParam(":name_en", $name_en, PDO::PARAM_STR);
        $statement->execute();

        $response->set_status("OK");

        return $response;
    }

    public static function get_tree($db, $language, $category_name = null) : Response 
    {
        $response = new Response();

        if ($category_name == null || strlen($category_name) === 0)
        {
            $category_name = "root";
        } 
        else 
        {
            $category_name = Helpers::sanitize_string($category_name, false, 100);
            $category_name = preg_replace("/\s+/", "-", $category_name); // spaces to -        
        }

        $db_table_categories = $GLOBALS["db_table_categories"];
        $name_with_lang = "name_" . $language;

        $query = "SELECT category_id, subcategory_of, $name_with_lang AS name FROM $db_table_categories 
            WHERE 
            name_pl=:category_name OR
            name_en=:category_name 
            LIMIT 1
        ";
        $statement = $db->prepare($query);
        $statement->bindParam(":category_name", $category_name, PDO::PARAM_STR);
        $statement->execute();
        $result_base = $statement->fetchAll(PDO::FETCH_OBJ);

        if ($statement->rowCount() > 0) 
        {
            $result_base = $result_base[0];
        } 
        else 
        {
            $response->set_status("NO_OK");
            $response->data_add(new ErrorElement("category not found", "category"));
            return $response;
        }

        $query = "SELECT category_id, subcategory_of, $name_with_lang AS name FROM $db_table_categories";
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        if ($statement->rowCount() > 0) 
        {
            $children = array();
    
            foreach($result as $item)
                $children[$item->subcategory_of][] = $item;
    
            foreach($result as $item) 
                if (isset($children[$item->category_id]))
                    $item->children = $children[$item->category_id];
    
            if (isset($children[$result_base->category_id]))
            {
                $result_base->children = $children[$result_base->category_id];
            }

            $response->data_set($result_base);
        } 
        else 
        {
            $response->set_status("NO_OK");
            $response->data_add(new ErrorElement("no categories created", "category"));
            return $response;
        }


        $response->set_status("OK");

        return $response;
    }
    */
}

?>