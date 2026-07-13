<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection
include '../backend/config.php';

// Set the page title
$page_title = 'Create اشتراكات_الاعضاء';

// Set the module slug
$mod_slug = 'اشتراكات-الاعضاء';

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8">
        <h1 class="text-3xl font-bold mb-4"><?php echo $page_title; ?></h1>
        <form id="create-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم العضو</label>
                <input type="text" id="name" name="name" class="block w-full mt-1 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full mt-1 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="block w-full mt-1 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="subscription_type" class="block text-sm font-medium text-gray-700">نوع الاشتراك</label>
                <select id="subscription_type" name="subscription_type" class="block w-full mt-1 rounded-md shadow-sm">
                    <option value="">اختر نوع الاشتراك</option>
                    <option value="monthly">شهرية</option>
                    <option value="yearly">سنوية</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="subscription_date" class="block text-sm font-medium text-gray-700">تاريخ الاشتراك</label>
                <input type="date" id="subscription_date" name="subscription_date" class="block w-full mt-1 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="expiration_date" class="block text-sm font-medium text-gray-700">تاريخ انتهاء الاشتراك</label>
                <input type="date" id="expiration_date" name="expiration_date" class="block w-full mt-1 rounded-md shadow-sm">
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/اشتراكات-الاعضاء.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>