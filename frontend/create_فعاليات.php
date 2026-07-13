**create_فعاليات.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة فاعلية جديدة</h2>
        <form id="create-فعاليات-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">الوصف</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600"></textarea>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-bold text-gray-700 mb-2">التاريخ</label>
                <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="time" class="block text-sm font-bold text-gray-700 mb-2">الوقت</label>
                <input type="time" id="time" name="time" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-فعاليات-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/فعاليات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_فعاليات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**Note:** This code assumes you have jQuery and Tailwind CSS installed. You may need to adjust the CSS classes to match your specific Tailwind configuration. Additionally, you will need to create a backend PHP file (`فعاليات.php`) to handle the form submission and database interactions.