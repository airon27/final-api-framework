<?php

    class MySQLDatabase{
        private $HOST = "localhost";
        private $USERNAME = "root";
        private $PASSWORD =  "myadmin123";
        private $DBNAME = "sample-dct";

        public function startConnection(){
            $connect = mysqli_connect($this->HOST, $this->USERNAME, $this->PASSWORD, $this->DBNAME);

            // Check Conncetion First
            if(!$connect){
                die("Connection Failed: " . mysqli_connect_error());
            }
            return $connect;
        }

        //Close Connection
        public function stopConnection($connect){
           mysqli_close($connect);
        }
    }
