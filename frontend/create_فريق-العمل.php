**create_فريق-العمل.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $leader = trim($_POST['leader']);

    // Check if fields are not empty
    if (!empty($name) && !empty($description) && !empty($leader)) {
        // Insert new record into database
        $query = "INSERT INTO فريق_العمل (name, description, leader) VALUES ('$name', '$description', '$leader')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_فريق-العمل.php');
            exit;
        } else {
            echo 'Error inserting record';
        }
    } else {
        echo 'Please fill in all fields';
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create Team Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create Team</h2>
    <form id="create-team-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Team Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Team Description</label>
            <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="leader" class="block text-sm font-medium text-gray-700">Team Leader</label>
            <input type="text" id="leader" name="leader" class="block w-full p-2 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Create Team</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>


**create_فريق-العمل.js**
javascript
// Get form element
const form = document.getElementById('create-team-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    // Prevent default form submission
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/فريق-العمل.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        // Redirect back to list_{mod_slug}.php
        window.location.href = 'list_فريق-العمل.php';
    })
    .catch((error) => console.error(error));
});


**فريق-العمل.php (backend)**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['leader'])) {
    // Insert new record into database
    $query = "INSERT INTO فريق_العمل (name, description, leader) VALUES ('".$_POST['name']."', '".$_POST['description']."', '".$_POST['leader']."')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Return success message
        echo json_encode(array('success' => true));
    } else {
        // Return error message
        echo json_encode(array('success' => false));
    }
}
?>