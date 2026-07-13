**edit_فريق-العمل.php**

<?php
// Session validation
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/فريق-العمل.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title and content
$page_title = 'تعديل فريق العمل';
$page_content = '
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-lg font-medium leading-6 text-gray-900">' . $page_title . '</h2>
        <form id="edit-form" class="mt-6 space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم الفريق</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full pl-10 pr-12 py-2 text-base border-gray-300 rounded-md focus:border-emerald-600 focus:ring-emerald-600" value="' . $data['name'] . '">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">وصف الفريق</label>
                <textarea id="description" name="description" class="mt-1 block w-full h-48 pl-10 pr-12 py-2 text-base border-gray-300 rounded-md focus:border-emerald-600 focus:ring-emerald-600">' . $data['description'] . '</textarea>
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">حفظ التغييرات</button>
        </form>
    </div>
';

// Include HTML template
include 'template.php';
?>

<script>
    // Fetch existing record details via GET
    fetch("../backend/فريق-العمل.php?id=<?php echo $id; ?>")
        .then(response => response.json())
        .then(data => {
            document.getElementById("name").value = data.name;
            document.getElementById("description").value = data.description;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById("edit-form").addEventListener("submit", function(event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData(this);

        // Send AJAX PUT request
        fetch("../backend/فريق-العمل.php", {
            method: "PUT",
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to list page
                    window.location.href = "list_فريق-العمل.php";
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>


**template.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <header>
        <nav class="bg-teal-500 py-2">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <a href="#" class="text-lg font-bold text-white">فريق العمل</a>
                <ul class="flex items-center space-x-4">
                    <li><a href="#" class="text-gray-200 hover:text-white">الصفحة الرئيسية</a></li>
                    <li><a href="#" class="text-gray-200 hover:text-white">التسجيل</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <?php echo $page_content; ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
    <script src="edit_فريق-العمل.js"></script>
</body>
</html>


**edit_فريق-العمل.js**
javascript
// No code needed


**backend/فريق-العمل.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if ID is numeric
if (!is_numeric($id)) {
    echo json_encode(array('error' => 'ID is not numeric'));
    exit;
}

// Fetch existing record details via GET
$query = "SELECT * FROM فريق_العمل WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if data exists
if (empty($data)) {
    echo json_encode(array('error' => 'Record not found'));
    exit;
}

// Send data as JSON
echo json_encode($data);
?>


Note: This code assumes you have a MySQL database connection established in the backend file. You should replace the `mysqli_query` and `mysqli_fetch_assoc` functions with your actual database connection and query functions.