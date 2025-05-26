<?php
session_start();
include("connect.php");

// Check if the form data is set
if (isset($_POST['mobile']) && isset($_POST['password']) && isset($_POST['role'])) {
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Debugging: Print input values
    error_log("Mobile: $mobile, Password: $password, Role: $role");

    // Use prepared statements to prevent SQL injection
    $stmt = $connect->prepare("SELECT * FROM user WHERE mobile = ? AND password = ? AND role = ?");
    if (!$stmt) {
        error_log("Prepare failed: (" . $connect->errno . ") " . $connect->error);
        exit("Database error");
    }
    $stmt->bind_param("sss", $mobile, $password, $role);

    if (!$stmt->execute()) {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        exit("Database error");
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userdata = $result->fetch_array(MYSQLI_ASSOC);
        error_log("User data fetched successfully");

        // Fetch groups data
        $groups_stmt = $connect->prepare("SELECT * FROM user WHERE role = ?");
        $role_value = 2; // or whatever the role value is for groups
        $groups_stmt->bind_param("i", $role_value);

        if (!$groups_stmt->execute()) {
            error_log("Groups execute failed: (" . $groups_stmt->errno . ") " . $groups_stmt->error);
            exit("Database error");
        }

        $groups_result = $groups_stmt->get_result();
        $groupsdata = $groups_result->fetch_all(MYSQLI_ASSOC);

        // Debugging: Print fetched groups data
        error_log("Groups data: " . print_r($groupsdata, true));

        // Set session data
        $_SESSION['userdata'] = $userdata;
        $_SESSION['groupsdata'] = $groupsdata;

        // Redirect to the dashboard
        echo '
        <script>
        window.location="../routes/dashboard.php";
        </script>
        ';
    } else {
        // Debugging: Log invalid login attempt
        error_log("Invalid login attempt: Mobile: $mobile, Password: $password, Role: $role");

        // Invalid credentials
        echo '
        <script>
        alert("Invalid Credentials or User Not Found");
        window.location="../";
        </script>
        ';
    }

    // Close statements and connection
    $stmt->close();
    $groups_stmt->close();
    $connect->close();
} else {
    error_log("Form data is not set");
    echo '
    <script>
    alert("Form data is not set. Please try again.");
    window.location="../";
    </script>
    ';
}
?>
