**create_الميزانية.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $budget = trim($_POST['budget']);

    if (empty($name) || empty($description) || empty($budget)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert new record into database
        $sql = "INSERT INTO الميزانية (name, description, budget) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('sss', $name, $description, $budget);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_الميزانية.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create New الميزانية</h2>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-bold text-gray-700">Description:</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="budget" class="block text-sm font-bold text-gray-700">Budget:</label>
            <input type="number" id="budget" name="budget" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_الميزانية.js**
javascript
$(document).ready(function() {
    $('#create-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/الميزانية.php',
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_الميزانية.php';
                } else {
                    alert('Error creating الميزانية');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
                alert('Error creating الميزانية');
            }
        });
    });
});


**Note:** Make sure to replace `../backend/الميزانية.php` with the actual PHP file that handles the form submission and inserts the new record into the database. Also, update the `list_الميزانية.php` URL to match the actual page that lists all الميزانية records.