<?php
session_start();
require 'config.php';

$users = [];
$stmt = $pdo->query("SELECT * FROM users");
while ($row = $stmt->fetch()) {
    $users[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id AND password = :password");
    $stmt->execute(['id' => $userId, 'password' => $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['group_id'] = $user['group_id'];
        header('Location: file_manager.php');
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-6 rounded shadow-md">
            <h1 class="text-2xl font-bold mb-4">Login</h1>
            <?php if (isset($error)): ?>
                <div class="text-red-500 mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-4">
                    <label for="user" class="block text-sm font-medium text-gray-700">User</label>
                    <select id="user" name="user" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['id']; ?>">
                                <?php echo "{$user['firstname']} {$user['lastname']} ID: {$user['username']}"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                </div>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
