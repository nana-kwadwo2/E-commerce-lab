<?php
include_once 'db_cred.php';

/**
 * @version 1.1
 */
if (!class_exists('db_connection')) {
    class db_connection
    {
        public $db = null;
        public $results = null;

        function db_connect()
        {
            // Close existing connection
            if ($this->db) {
                mysqli_close($this->db);
            }
            
            // Create new connection with error reporting
            $this->db = @mysqli_connect(SERVER, USERNAME, PASSWD, DATABASE, PORT);
            
            if (mysqli_connect_errno()) {
                error_log("DB Connection Failed: " . mysqli_connect_error() . 
                         " - Server: " . SERVER . 
                         " - DB: " . DATABASE . 
                         " - Port: " . PORT);
                return false;
            }
            return true;
        }
        
        function db_conn()
        {
            if (!$this->db_connect()) {
                return false;
            }
            return $this->db;
        }

        function db_query($sqlQuery)
        {
            if (!$this->db_connect()) {
                error_log("DB Query Failed - No connection");
                return false;
            }

            $this->results = mysqli_query($this->db, $sqlQuery);

            if ($this->results === false) {
                error_log("Query Error: " . mysqli_error($this->db) . " - Query: " . $sqlQuery);
                return false;
            }
            return true;
        }

function db_write_query($sqlQuery)
        {
            if (!$this->db_connect()) {
                return false;
            } elseif ($this->db == null) {
                return false;
            }

            //run query 
            $result = mysqli_query($this->db, $sqlQuery);

            if ($result == false) {
                return false;
            } else {
                return true;
            }
        }

        //fetch a single record
        /**
         * Get a single record
         * @param string $sql
         * @return array|false
         **/
        function db_fetch_one($sql)
        {
            // if executing query returns false
            if (!$this->db_query($sql)) {
                return false;
            }
            //return a record
            return mysqli_fetch_assoc($this->results);
        }

        //fetch all records
        /**
         * Get all records
         * @param string $sql
         * @return array|false
         **/
        function db_fetch_all($sql)
        {
            // if executing query returns false
            if (!$this->db_query($sql)) {
                return false;
            }
            //return all records
            return mysqli_fetch_all($this->results, MYSQLI_ASSOC);
        }

        //count data
        /**
         * Get count of records
         * @return int|false
         **/
        function db_count()
        {
            //check if result was set
            if ($this->results == null) {
                return false;
            } elseif ($this->results == false) {
                return false;
            }

            //return count
            return mysqli_num_rows($this->results);
        }

        function last_insert_id()
        {
            return mysqli_insert_id($this->db);
        }
    }
}    
?>