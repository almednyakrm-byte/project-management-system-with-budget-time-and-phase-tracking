**edit_موارد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/موارد.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if record exists
if (empty($data)) {
    echo 'Record not found';
    exit;
}

// Set form data
$form_data = $data;

// Set form action URL
$form_action = '../backend/موارد.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مورد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 mt-4 bg-slate-900 rounded-md">
        <h1 class="text-3xl text-indigo-500 font-bold mb-4">Edit مورد</h1>
        <form id="edit-form" class="w-full max-w-md mx-auto">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
                <input id="name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" type="text" value="<?= $form_data['name'] ?>">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="description">Description</label>
                <textarea id="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $form_data['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '<?= $form_action ?>',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/موارد.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = array(
    'id' => $id,
    'name' => 'مورد 1',
    'description' => 'This is a مورد'
);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);