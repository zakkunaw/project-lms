<?php
// admin/manage_users.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

// Function to handle adding a user
function addUser($conn) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $role = $conn->real_escape_string($_POST['role']);
    
    $sql_add_user = "INSERT INTO users (username, password, email, phone, role) VALUES ('$username', '$password', '$email', '$phone', '$role')";
    $conn->query($sql_add_user);
    echo json_encode(['status' => 'success']);
    exit();
}

// Function to handle editing a user
function editUser($conn) {
    $user_id = intval($_POST['edit_user_id']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $role = $conn->real_escape_string($_POST['role']);

    $sql = "UPDATE users SET username='$username', email='$email', phone='$phone', role='$role' WHERE id=$user_id";
    $conn->query($sql);
    echo json_encode(['status' => 'success']);
    exit();
}

// Function to handle changing a user's password
function changePassword($conn) {
    $user_id = intval($_POST['user_id']);
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $sql = "UPDATE users SET password='$new_password' WHERE id=$user_id";
    $conn->query($sql);
    echo json_encode(['status' => 'success']);
    exit();
}

// Function to handle deleting a user
function deleteUser($conn) {
    $user_id = intval($_POST['id']);
    $sql = "DELETE FROM users WHERE id=$user_id";
    $conn->query($sql);
    echo json_encode(['status' => 'success']);
    exit();
}

// Function to handle blocking a user
function blockUser($conn) {
    $user_id = intval($_POST['id']);
    $sql = "UPDATE users SET is_blocked=1 WHERE id=$user_id";
    $conn->query($sql);
    echo json_encode(['status' => 'success']);
    exit();
}

// Function to handle unblocking a user
function unblockUser($conn) {
    $user_id = intval($_POST['id']);
    $sql = "UPDATE users SET is_blocked=0 WHERE id=$user_id";
    $conn->query($sql);
    echo json_encode(['status' => 'success']);
    exit();
}

// Function to fetch user data
function getUser($conn) {
    $user_id = intval($_GET['id']);
    $sql = "SELECT * FROM users WHERE id=$user_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
    exit();
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_user':
                addUser($conn);
                break;
            case 'edit_user':
                editUser($conn);
                break;
            case 'change_password':
                changePassword($conn);
                break;
            case 'delete_user':
                deleteUser($conn);
                break;
            case 'block_user':
                blockUser($conn);
                break;
            case 'unblock_user':
                unblockUser($conn);
                break;
        }
    }
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    getUser($conn);
}

// Fetch all users except admin
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
    <button class="btn" id="menu-toggle"><i class="fas fa-bars"></i></button>
    
    <div class="d-flex" id="wrapper">
    <?php include '../includes/navbar.php'; ?>
        <div class="container mt-5">
        <h2>Kelola Pengguna</h2>
        
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addUserModal">
            Tambah Pengguna
        </button>

        <!-- Add User Modal -->
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
                        <form id="addUserForm">
                            <input type="hidden" name="action" value="add_user">
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
                            <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm">
                            <input type="hidden" name="action" value="edit_user">
                            <input type="hidden" name="edit_user_id" id="edit_user_id">
                            <div class="form-group">
                                <label for="edit_username">Username</label>
                                <input type="text" name="username" id="edit_username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_email">Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_phone">Phone</label>
                                <input type="text" name="phone" id="edit_phone" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_role">Role</label>
                                <select name="role" id="edit_role" class="form-control" required>
                                    <option value="instructor">Instructor</option>
                                    <option value="student">Student</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Pengguna</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Ganti Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="changePasswordForm">
                            <input type="hidden" name="action" value="change_password">
                            <input type="hidden" name="user_id" id="change_password_user_id">
                            <div class="form-group">
                                <label for="new_password">Password Baru</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Ganti Password</button>
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
                            <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $user['id'] ?>" data-toggle="modal" data-target="#editUserModal">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $user['id'] ?>">Hapus</button>
                            <?php if(!$user['is_blocked']): ?>
                                <button class="btn btn-secondary btn-sm block-btn" data-id="<?= $user['id'] ?>">Blokir</button>
                            <?php else: ?>
                                <button class="btn btn-success btn-sm unblock-btn" data-id="<?= $user['id'] ?>">Unblokir</button>
                            <?php endif; ?>
                            <button class="btn btn-info btn-sm change-password-btn" data-id="<?= $user['id'] ?>" data-toggle="modal" data-target="#changePasswordModal">Ganti Password</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
$(document).ready(function() {
    // Add user form submit
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'manage_users.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'User Added!',
                    text: 'New user has been added successfully.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // Reload the page to see changes
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to add user. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Edit button click
    $('.edit-btn').on('click', function() {
        var userId = $(this).data('id');
        $.ajax({
            url: 'manage_users.php',
            type: 'GET',
            data: { id: userId },
            success: function(response) {
                var user = JSON.parse(response);
                $('#edit_user_id').val(user.id);
                $('#edit_username').val(user.username);
                $('#edit_email').val(user.email);
                $('#edit_phone').val(user.phone);
                $('#edit_role').val(user.role);
            }
        });
    });

    // Edit form submit
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'manage_users.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'User Updated!',
                    text: 'User details have been updated successfully.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // Reload the page to see changes
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update user. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Change password button click
    $('.change-password-btn').on('click', function() {
        var userId = $(this).data('id');
        $('#change_password_user_id').val(userId);
    });

    // Change password form submit
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'manage_users.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Password Changed!',
                    text: 'User password has been updated successfully.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // Reload the page to see changes
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to change password. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Delete button click
    $('.delete-btn').on('click', function() {
        var userId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'manage_users.php',
                    type: 'POST',
                    data: { action: 'delete_user', id: userId },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'User has been deleted successfully.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload the page to see changes
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete user. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // Block button click
    $('.block-btn').on('click', function() {
        var userId = $(this).data('id');
        $.ajax({
            url: 'manage_users.php',
            type: 'POST',
            data: { action: 'block_user', id: userId },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'User Blocked!',
                    text: 'User has been blocked successfully.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // Reload the page to see changes
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to block user. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Unblock button click
    $('.unblock-btn').on('click', function() {
        var userId = $(this).data('id');
        $.ajax({
            url: 'manage_users.php',
            type: 'POST',
            data: { action: 'unblock_user', id: userId },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'User Unblocked!',
                    text: 'User has been unblocked successfully.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // Reload the page to see changes
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to unblock user. Please try again.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});

    </script>
</body>
</html>