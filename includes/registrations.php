<?php

class Registrations {
    private $conn;

    // Constructor untuk inisialisasi koneksi database
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Fungsi untuk mendapatkan data registrasi
    public function getAllRegistrations() {
        $query = "SELECT users.name as user_name, events.event_name 
                  FROM registrations 
                  INNER JOIN users ON registrations.user_id = users.id 
                  INNER JOIN events ON registrations.event_id = events.id";
        
        // Menggunakan prepared statement untuk keamanan
        $result = prepare_query($this->conn, $query, []);
        return $result;
    }
}
?>
