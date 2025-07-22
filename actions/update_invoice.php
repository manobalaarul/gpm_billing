<?php
include 'db.php';

// Fetch posted data
$invoice_id = $_POST['invoice_id'];
$customer_name = $_POST['customer_name'];
$customer_phone = $_POST['customer_phone'];
$customer_address = $_POST['customer_address'];
$invoice_date = $_POST['invoice_date'];
$total_amount = $_POST['total_amount'];
$paid_amount = $_POST['paid_amount'];
$balance_amount = $_POST['balance_amount'];
$additional_notes = $_POST['additional_notes'];

// Step 1: Update invoice
$update_sql = "UPDATE invoices SET 
    customer_name = '$customer_name',
    customer_phone = '$customer_phone',
    customer_address = '$customer_address',
    invoice_date = '$invoice_date',
    total_amount = '$total_amount',
    paid_amount = '$paid_amount',
    balance_amount = '$balance_amount',
    additional_notes = '$additional_notes'
    WHERE invoice_number = '$invoice_id'";

if (!mysqli_query($conn, $update_sql)) {
    die("Invoice update failed: " . mysqli_error($conn));
}

// Step 2: Delete old items
mysqli_query($conn, "DELETE FROM invoice_items WHERE invoice_id = '$invoice_id'");

// Step 3: Insert updated items
$titles = $_POST['title'];
$descriptions = $_POST['description'];
$rates = $_POST['rate'];
$qtys = $_POST['qty'];
$amounts = $_POST['amount'];

for ($i = 0; $i < count($descriptions); $i++) {
    $ti = mysqli_real_escape_string($conn, $titles[$i]);
    $desc = mysqli_real_escape_string($conn, $descriptions[$i]);
    $rate = floatval($rates[$i]);
    $qty = floatval($qtys[$i]);
    $amount = floatval($amounts[$i]);

    $sql_item = "INSERT INTO invoice_items (invoice_id, title, description, rate, quantity, amount)
                 VALUES ('$invoice_id', '$ti', '$desc', $rate, $qty, $amount)";
    mysqli_query($conn, $sql_item);
}

// ✅ Redirect back to invoice list or view page
header("Location: ../index.php?updated=1");
exit;
?>
