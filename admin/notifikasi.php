<?php
function tampilkan_notifikasi() {
    if (isset($_GET['status']) && isset($_GET['message'])) {
        $status = $_GET['status'];
        $message = $_GET['message'];
        $alert_class = ($status == 'success') ? 'alert-success' : 'alert-danger';
        
        echo "<div class='alert {$alert_class} alert-dismissible fade show' role='alert'>
                {$message}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}
?>