<?php

function clean_input($data) {
    $data = trim($data); 
    $data = stripslashes($data); 
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); 
    return $data;
}

function prepare_query($conn, $query, $params = null) {
    $stmt = $conn->prepare($query);
    if ($stmt === false) {   
        die('Error preparing statement: ' . $conn->error);
    }

    if ($params) {
        $stmt->bind_param(...$params);
    }

    $stmt->execute();

    if ($stmt->errno) {
        die('Query execution failed: ' . $stmt->error);
    }

    return $stmt->get_result(); 
}

function get_user_by_id($conn, $user_id) {
   
    $user_id = clean_input($user_id);
    $query = "SELECT * FROM users WHERE id = ?";
    $params = ['i', $user_id];  
    $result = prepare_query($conn, $query, $params);

   
    if ($result->num_rows > 0) {
        return $result->fetch_assoc(); 
    } else {
        return null; 
    }
}
?>