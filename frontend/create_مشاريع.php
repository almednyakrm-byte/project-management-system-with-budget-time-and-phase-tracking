**create_مشاريع.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_مشاريع_form.php';

// Include footer
include 'footer.php';
?>


**create_مشاريع_form.php**

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة مشروع جديد</h2>
    <form id="create-project-form" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="project_name" class="block text-sm font-medium text-gray-700">اسم المشروع</label>
                <input type="text" id="project_name" name="project_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
            </div>
            <div>
                <label for="project_description" class="block text-sm font-medium text-gray-700">وصف المشروع</label>
                <textarea id="project_description" name="project_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"></textarea>
            </div>
        </div>
        <div>
            <label for="project_start_date" class="block text-sm font-medium text-gray-700">تاريخ بداية المشروع</label>
            <input type="date" id="project_start_date" name="project_start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
        </div>
        <div>
            <label for="project_end_date" class="block text-sm font-medium text-gray-700">تاريخ نهاية المشروع</label>
            <input type="date" id="project_end_date" name="project_end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">إضافة المشروع</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-project-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مشاريع.php',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_مشاريع.php';
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشاريع</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <!-- header content -->
</body>
</html>


**footer.php**

<!-- footer content -->
</body>
</html>


**navigation.php**

<!-- navigation content -->


Note: You need to replace `../backend/مشاريع.php` with the actual URL of your backend script that handles the form submission. Also, make sure to include the necessary JavaScript libraries (e.g. jQuery) and CSS files in your project.