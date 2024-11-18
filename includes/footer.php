<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Other head content -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .whatsapp-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25D366;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            z-index: 1000;
        }
        
        .whatsapp-btn img {
            width: 24px;
            height: 24px;
        }
    </style>
</head>
<body>
    <!-- WhatsApp Floating Button -->
    <button id="whatsapp-btn" class="whatsapp-btn" onclick="openWhatsAppChat()">
        <img src="https://img.icons8.com/color/48/000000/whatsapp.png" alt="WhatsApp">
    </button>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">© 2024 Harapan Terang Sejahtera. All rights reserved.</span>
        </div>
    </footer>

    <!-- Bootstrap JS and SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openWhatsAppChat() {
            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Ingin menghubungi Sensei?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If user clicks "Yes", show loading animation
                    Swal.fire({
                        title: 'Connecting to WhatsApp...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Simulate a short delay and open WhatsApp chat in a new tab
                    setTimeout(() => {
                        Swal.close(); // Close SweetAlert loading
                        
                        // Create an anchor element to open WhatsApp chat in a new tab
                        const whatsappLink = document.createElement('a');
                        whatsappLink.href = 'https://wa.me/6285864139786?text=Hello%20Sensei!';
                        whatsappLink.target = '_blank';
                        whatsappLink.rel = 'noopener noreferrer'; // Add for security
                        whatsappLink.click();
                    }, 1000); // Delay in milliseconds
                }
                // If "No" is clicked, do nothing
            });
        }

    </script>
</body>
</html>
