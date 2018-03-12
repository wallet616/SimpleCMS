<?php

class ErrorElement {
    public $description = "undefined error";
    public $caused_by = "server";

    public function __construct($description = null, $caused_by = null) 
    {
        if ($description !== null)
            $this->description = $description;
        
        if ($caused_by !== null)
            $this->caused_by = $caused_by;
    }
}

?>