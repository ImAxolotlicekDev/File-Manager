<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

require 'config.php';

$users = [];
$stmt = $pdo->query("SELECT * FROM users");
while ($row = $stmt->fetch()) {
    $users[] = $row;
}

$group_id = $_SESSION['group_id'];
$groupFolder = "uploads/group_$group_id";

if (!is_dir($groupFolder)) {
    mkdir($groupFolder, 0777, true);
}

$selectedUser = isset($_GET['user']) ? $_GET['user'] : $_SESSION['username'];
$userFolder = $groupFolder . '/' . $selectedUser;

if (!is_dir($userFolder)) {
    mkdir($userFolder, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    move_uploaded_file($file['tmp_name'], $userFolder . '/' . basename($file['name']));
}

$files = array_diff(scandir($userFolder), array('..', '.'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">File manager</h1>

        <form method="GET" class="mb-4">
            <label for="user" class="block text-sm font-medium text-gray-700">Folder of user</label>
            <select id="user" name="user" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" onchange="this.form.submit()">
                <?php foreach ($users as $user): ?>
                    <?php if ($user['group_id'] == $group_id): ?>
                        <option value="<?php echo $user['username']; ?>" <?php echo $user['username'] === $selectedUser ? 'selected' : ''; ?>>
                            <?php echo "{$user['firstname']} {$user['lastname']}"; ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </form>

        <form method="POST" enctype="multipart/form-data" class="mb-4">
            <input type="file" name="file" class="block mb-2">
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Upload</button>
        </form>

        <h2 class="text-xl font-semibold mb-2">Files in <?php echo "{$users[array_search($selectedUser, array_column($users, 'username'))]['firstname']} {$users[array_search($selectedUser, array_column($users, 'username'))]['lastname']}"; ?>'s Folder:</h2>
        <ul class="list-disc pl-5">
            <?php foreach ($files as $file): ?>
                <li>
                    <a href="<?php echo $userFolder . '/' . $file; ?>" class="text-blue-500" download><?php echo $file; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <form method="POST" action="logout.php" class="mt-4">
            <button type="submit" class="bg-red-500 text-white p-2 rounded">Log out</button>
        </form>
    </div>
</body>
</html>
