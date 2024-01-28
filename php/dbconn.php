    <?php

    class DataBaseConnection {
        private $server = "localhost";
        private $username = "root";
        private $password = "";
        private $database = "moviesite";
        private $conn;

        public function startConnection() {
            $this->conn = mysqli_connect($this->server, $this->username, $this->password, $this->database);

            if(!$this->conn) {
                die("Error: " . mysqli_connect_error());
            } else {
                return $this->conn;
            }
        }  

        public function getConnection() {
            return $this->conn;
        }
        
        public function add_Message($first, $last, $email, $message) {
            $query = "INSERT INTO messages (name, surname, email, message) VALUES ('$first', '$last','$email', '$message')";
            $execute = mysqli_query($this->conn, $query);
        
            return $execute;
        }

        public function get_Messages() {
            $query = "SELECT * FROM messages";
            $result = mysqli_query($this->conn, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                while($row = $result->fetch_assoc()) {
                    $messages[] = $row;
                }
            }
            return $messages;
        }

        public function load_Landing($dataid, $tableName) {
            $query = "SELECT * FROM $tableName WHERE dataid = ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $dataid); 
            $stmt->execute();
            

            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $data = $row['data'];
            } else {

                $data = "Default landing page data";
            }
            $stmt->close();
            
            return $data;
        }
        
        public function closeConnection() {
            mysqli_close($this->conn);
            
        }
    }

    ?>


