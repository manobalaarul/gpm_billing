<?php
include 'db.php';

if (isset($_GET['invoice_number'])) {
    $invoice_number = $_GET['invoice_number'];

    // 1. Get invoice ID by invoice_number
    $invoice = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM invoices WHERE invoice_number = '$invoice_number'"));

    if ($invoice) {
        // 2. Delete items
        mysqli_query($conn, "DELETE FROM invoice_items WHERE invoice_id = '$invoice_number'");

        // 3. Delete invoice
        mysqli_query($conn, "DELETE FROM invoices WHERE invoice_number = '$invoice_number'");

        header("Location: ../index.php");

    } else {
        echo "Invoice not found.";
    }
} else {
    echo "Invoice number not provided.";
}
?>
