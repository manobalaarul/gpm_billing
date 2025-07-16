<?php
include 'db.php';

$customer_name = $_POST['customer_name'];
$customer_phone = $_POST['customer_phone'];
$customer_address = $_POST['customer_address'];
$invoice_date = $_POST['invoice_date'];
$total_amount = $_POST['total_amount'];
$paid_amount = $_POST['paid_amount'];
$balance_amount = $_POST['balance_amount'];

// Step 1: Insert without invoice_number
$insert_sql = "INSERT INTO invoices (customer_name, customer_phone, customer_address, invoice_date, total_amount, paid_amount, balance_amount,realtime)
VALUES ('$customer_name', '$customer_phone', '$customer_address', '$invoice_date', '$total_amount', '$paid_amount', '$balance_amount',CURRENT_TIMESTAMP())";
mysqli_query($conn, $insert_sql);

// Step 2: Get inserted ID
$invoice_id = mysqli_insert_id($conn);

// Step 3: Generate invoice number like GPM00000001
$invoice_number = "GPM" . str_pad($invoice_id, 8, "0", STR_PAD_LEFT);

// Step 4: Update the invoice with invoice number
$update_sql = "UPDATE invoices SET invoice_number = '$invoice_number' WHERE id = $invoice_id";
mysqli_query($conn, $update_sql);


// Insert product items
$titles = $_POST['title'];
$descriptions = $_POST['description'];
$rates = $_POST['rate'];
$qtys = $_POST['qty'];
$amounts = $_POST['amount'];

for ($i = 0; $i < count($descriptions); $i++) {
    $ti = $titles[$i];
    $desc = $descriptions[$i];
    $rate = $rates[$i];
    $qty = $qtys[$i];
    $amount = $amounts[$i];

    $sql_item = "INSERT INTO invoice_items (invoice_id, title, description, rate, quantity, amount,realtime)
    VALUES ('$invoice_number', '$ti', '$desc', '$rate', '$qty', '$amount',CURRENT_TIMESTAMP())";
    mysqli_query($conn, $sql_item);
}

// ✅ Redirect to print view
header("Location: print_invoice.php?invoice_number=$invoice_number");
exit;
?>
