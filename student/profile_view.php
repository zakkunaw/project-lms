<?php
session_start();
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = fetchUserData($conn, $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Update profile information
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $update_query = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, password = ? WHERE id = ?");
        $update_query->bind_param("ssssi", $username, $email, $phone, $password_hash, $user_id);
    } else {
        $update_query = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ? WHERE id = ?");
        $update_query->bind_param("sssi", $username, $email, $phone, $user_id);
    }

    // Execute the query
    $profileUpdated = $update_query->execute();

    // Handle cropped image upload, if available
    if (isset($_POST['croppedImage']) && !empty($_POST['croppedImage'])) {
        $croppedImage = $_POST['croppedImage'];
        $image_parts = explode(";base64,", $croppedImage);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.' . $image_type;
        $uploadFileDir = 'student_image/';
        $dest_path = $uploadFileDir . $fileName;

        if (file_put_contents($dest_path, $image_base64)) {
            $update_image_query = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            $update_image_query->bind_param("si", $fileName, $user_id);
            $profileUpdated = $update_image_query->execute();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save cropped image.']);
            exit();
        }
    }

    // Return success or error status
    if ($profileUpdated) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile.']);
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <title>User Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        #cropperModal .modal-body {
            max-height: 70vh;
            overflow: auto;
        }
    </style>
</head>
<body>
    <?php include '../includes/student/navbar.php';?>
    <div class="container mt-5">
        <h2>User Profile</h2>
        <form id="profileForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
            </div>
            <div class="form-group">
                <label for="password">New Password (leave blank to keep current):</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" class="form-control-file" id="profile_picture" name="profile_picture" accept="image/*">
                <img id="imagePreview" src="<?= 'student_image/' . htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture Preview" class="mt-3" style="max-width: 200px;">
            </div>
            <input type="hidden" id="croppedImage" name="croppedImage">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
        <p class="mt-3">Account created on: <?= htmlspecialchars($user['created_at']) ?></p>
    </div>

    <!-- Cropper Modal -->
    <div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropperModalLabel">Crop Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="cropperImage" src="" alt="Image to crop" style="max-width: 100%;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="cropImage">Crop</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        let cropper;

        $('#profile_picture').change(function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#cropperImage').attr('src', e.target.result);
                    $('#cropperModal').modal('show');

                    $('#cropperModal').on('shown.bs.modal', function() {
                        cropper = new Cropper(document.getElementById('cropperImage'), {
                            aspectRatio: 1,
                            viewMode: 1,
                        });
                    });
                }
                reader.readAsDataURL(file);
            }
        });

        $('#cropImage').click(function() {
            const croppedImageData = cropper.getCroppedCanvas().toDataURL('image/jpeg');
            $('#imagePreview').attr('src', croppedImageData);
            $('#croppedImage').val(croppedImageData);
            $('#cropperModal').modal('hide');
        });

        $('#cropperModal').on('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        $('#profileForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // If there's a cropped image, add it to formData
            if ($('#croppedImage').val()) {
                formData.append('croppedImage', $('#croppedImage').val());
            }

            $.ajax({
                url: '',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembaruan Berhasil!',
                            text: 'Profil Anda telah diperbarui.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
    </script>
</body>
</html>