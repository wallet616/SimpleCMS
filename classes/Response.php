<?php

class Response {

    public $status = "OK";
    public $data = array();


    /**
     * Constructor.
     *
     * @param [string] $status - Status: "OK" | "NO_OK"
     * @param [object | array] $data - Data object that will be send back.
     */
    public function __construct($status = null, $data = null) 
    {
        if ($status !== null)
            $this->status = $status;

        if ($data !== null)
            $this->data = $data;
    }


    /**
     * Overwrite status.
     *
     * @param [string] $status - Status: "OK" | "NO_OK"
     * @return bool - true when success, false when failed
     */
    public function set_status($status) : bool 
    {
        $this->status = $status;
        return true;
    }


    /**
     * Add data to response. Works only when $this->data is an array.
     *
     * @param [object] $data - Object that will be added to array.
     * @return bool - true when success, false when failed ($this->data is not an array)
     */
    public function data_add($data) : bool 
    {
        if (is_array($this->data)) {
            array_push($this->data, $data);
            return true;
        }
        return false;
    }


    /**
     * Set response data.
     *
     * @param [object] $data - Object that will be set as response data.
     * @return bool - true when success, false when failed ($this->data is not an array)
     */
    public function data_set($data) : bool 
    {
        $this->data = $data;
        return true;
    }


    /**
     * Merge response data.
     *
     * @param [object] $data - Object that will be set as response data.
     * @return bool - true when success, false when failed ($this->response is not an Response object)
     */
    public function merge_with($response) : bool 
    {
        if ($response instanceof Response)
        {
            if ($response->status === "NO_OK")
            {
                $this->status = $response->status;
            } // no else

            if (is_array($response->data)) {
                foreach ($response->data as $el) 
                {
                    array_push($this->data, $el);
                }
            } 
            else 
            {
                $this->data = $response->data;
            }
            return true;
        }
        else 
        {
            // cannot merge
            return false;
        }
    }
}

?>