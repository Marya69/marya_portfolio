<?php
session_start();
// Display success messages
if (isset($_SESSION['success'])) {
    echo "<div style='color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;'>"
        . htmlspecialchars($_SESSION['success']) . "</div>";
    unset($_SESSION['success']);
}

// Display error messages
if (isset($_SESSION['error'])) {
    if (is_array($_SESSION['error'])) {
        echo "<div style='color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;'>";
        foreach ($_SESSION['error'] as $error) {
            echo htmlspecialchars($error) . "<br>";
        }
        echo "</div>";
    } else {
        echo "<div style='color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;'>"
            . htmlspecialchars($_SESSION['error']) . "</div>";
    }
    unset($_SESSION['error']);
}
include '../config.php';

$query = "SELECT phone_n, gmail FROM contacts WHERE id = 1"; // Use actual ID or condition
$result = mysqli_query($conn, $query);
$contact = mysqli_fetch_assoc($result);

// Fallback values if not set
$phone = $contact['phone_n'] ?? '';
$gmail = $contact['gmail'] ?? '';
?>
<section id="contact" class="p-3">
    <div class="container">
        <h1 style="color: #3498DB;">
            <i class="fa fa-address-book p-1"></i>
            Contact

        </h1>
        <!-- Include Bootstrap CSS (if not already included) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <div class="container mt-5">
            <form class="p-4 rounded  bg-light" method="POST" action="./contact/update.php">
                <h4 class="mb-4 " style="color: #3498DB;">Update Your Contact Details</h4>

                <!-- Phone Number Input -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" value="<?php echo htmlspecialchars($phone); ?>"
                        name="phone" pattern="^\+?\d{10,15}$" required>
                </div>

                <!-- Gmail Input -->
                <div class="mb-3">
                    <label for="gmail" class="form-label">Gmail Address</label>
                    <input type="email" class="form-control" id="gmail" name="gmail"
                        value="<?php echo htmlspecialchars($gmail); ?>" pattern="^[a-zA-Z0-9._%+-]+@gmail\.com$"
                        required>
                </div>

                <button type="submit" class="btn  w-100" style="background-color: #3498DB;color:white">Update</button>
            </form>
        </div>


    </div>
</section>