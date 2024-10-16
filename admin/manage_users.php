<?php
// admin/manage_users.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

// Handle penghapusan akun
if(isset($_GET['delete'])){
    $delete_id = intval($_GET['delete']);
    $sql_delete = "DELETE FROM users WHERE id=$delete_id";
    $conn->query($sql_delete);
    header("Location: manage_users.php");
    exit();
}

// Handle pemblokiran akun
if(isset($_GET['block'])){
    $block_id = intval($_GET['block']);
    $sql_block = "UPDATE users SET is_blocked=1 WHERE id=$block_id";
    $conn->query($sql_block);
    header("Location: manage_users.php");
    exit();
}

// Handle unblokir akun
if(isset($_GET['unblock'])){
    $unblock_id = intval($_GET['unblock']);
    $sql_unblock = "UPDATE users SET is_blocked=0 WHERE id=$unblock_id";
    $conn->query($sql_unblock);
    header("Location: manage_users.php");
    exit();
}

// Handle penambahan akun baru
if(isset($_POST['add_user'])){
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $role = $conn->real_escape_string($_POST['role']);
    
    $sql_add_user = "INSERT INTO users (username, password, email, phone, role) VALUES ('$username', '$password', '$email', '$phone', '$role')";
    $conn->query($sql_add_user);
    header("Location: manage_users.php");
    exit();
}

// Fetch semua pengguna kecuali admin
$sql = "SELECT * FROM users WHERE role!='admin'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Kelola Pengguna</h2>
        
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addUserModal">
            Tambah Pengguna
        </button>

        <!-- Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="manage_users.php">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="instructor">Instructor</option>
                                    <option value="student">Student</option>
                                </select>
                            </div>
                            <button type="submit" name="add_user" class="btn btn-primary">Tambah Pengguna</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= ucfirst($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['phone']) ?></td>
                        <td><?= $user['is_blocked'] ? 'Diblokir' : 'Aktif' ?></td>
                        <td>
                            <?php if(!$user['is_blocked']): ?>
                                <a href="?block=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Blokir</a>
                            <?php else: ?>
                                <a href="?unblock=<?= $user['id'] ?>" class="btn btn-success btn-sm">Unblokir</a>
                            <?php endif; ?>
                            <a href="?delete=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
