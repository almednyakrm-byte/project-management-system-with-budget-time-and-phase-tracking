**edit_مشاريع.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get project ID from URL
$id = $_GET['id'];

// Fetch project details via AJAX
$project = json_decode(file_get_contents('../backend/مشاريع.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مشروع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل مشروع</h2>
        <form id="edit-project-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المشروع</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-md" value="<?= $project['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">وصف المشروع</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-md"><?= $project['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">حالة المشروع</label>
                <select id="status" name="status" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-md">
                    <option value="active" <?= $project['status'] == 'active' ? 'selected' : '' ?>>نشط</option>
                    <option value="inactive" <?= $project['status'] == 'inactive' ? 'selected' : '' ?>>غير نشط</option>
                </select>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-project-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مشاريع.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating project');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `<?= $_SESSION['mod_slug'] ?>` with the actual value of the `mod_slug` session variable.