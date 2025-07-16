<?php
include 'actions/db.php';
$id = $_GET['invoice_number'];
date_default_timezone_set("Asia/Calcutta");   
$invoice = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM invoices WHERE invoice_number = '$id'"));
$items = mysqli_query($conn, "SELECT * FROM invoice_items WHERE invoice_id = '$id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GPM PROPERTIES - Invoice <?= "GPM" . str_pad($id, 8, '0', STR_PAD_LEFT) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
    }
    .invoice-box {
      background: #fff;
      max-width: 1100px;
      margin: auto;
      /* padding: 30px; */
    }
    .invoice-top-bar {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      margin-bottom: 10px;
      color: #333;
    }
    .invoice-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 30px;
    }
    .logo-left {
      display: flex;
      align-items: flex-start;
      gap: 20px;
    }
    .logo-left img {
      max-width: 150px;
    }
    .company-info {
      font-size: 14px;
      line-height: 1.5;
    }
    .company-info strong {
      font-size: 18px;
    }
    .invoice-details {
      font-size: 14px;
      text-align: right;
      line-height: 1.8;
    }
    .invoice-details b {
      display: block;
      margin-bottom: 4px;
    }
    .bill-to {
      margin-bottom: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 10px;
      text-align: left;
    }
    th {
      background: #f3f3f3;
    }
    .total-summary {
      margin-top: 20px;
      text-align: right;
      font-size: 16px;
      font-weight: bold;
    }
    @media print {
    body * {
      visibility: hidden;
    }
    .invoice-box, .invoice-box * {
      visibility: visible;
    }
    .invoice-box {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
    }
  }
  </style>
  <script>
  window.onload = function() {
    window.print();
  };
</script>

</head>
<body>
  <div class="invoice-box">
    <div class="invoice-top-bar">
      <div><?= date("d/m/y, h:i A", strtotime($invoice['realtime'])); ?></div>
      <div>GPM PROPERTIES - Invoice <?= "GPM" . str_pad($id, 8, '0', STR_PAD_LEFT) ?></div>
    </div>

    <div class="invoice-header">
      <div class="logo-left">
        <img src="gpmlogo.png" alt="Company Logo">
        <div class="company-info">
          <strong>GPM PROPERTIES</strong><br>
          <b>Business Number</b><br>
          9176552727<br>
          GPM silver spring<br>
          apt,Nanmangalam<br>
          chennai<br>
          600129<br>
          9176522727<br>
          info@gpmproperties.in
        </div>
      </div>

      <div class="invoice-details">
        <b>Invoice No</b>
        <?= "GPM" . str_pad($id, 8, '0', STR_PAD_LEFT) ?>
        <b>DATE</b>
        <?= date("M d, Y", strtotime($invoice['invoice_date'])) ?>
        <b>DUE</b>
        On Receipt
        <b>BALANCE DUE</b>
        INR ₹<?= number_format($invoice['balance_amount'], 2) ?>
      </div>
    </div>

    <div class="bill-to">
      <strong>BILL TO</strong><br>
      <?= $invoice['customer_name'] ?><br>
      <?= nl2br($invoice['customer_address']) ?><br>
      <?= $invoice['customer_phone'] ?>
    </div>

    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th>Rate</th>
          <th>Qty</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($items)): ?>
          <tr>
            <td><?= nl2br($row['title']) ?></td>
            <td><?= nl2br($row['description']) ?></td>
            <td>₹<?= number_format($row['rate'], 2) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td>₹<?= number_format($row['amount'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="total-summary">
      TOTAL: ₹<?= number_format($invoice['total_amount'], 2) ?><br>
      Payment: -₹<?= number_format($invoice['paid_amount'], 2) ?><br>
      Balance Due: ₹<?= number_format($invoice['balance_amount'], 2) ?>
    </div>
  </div>
  <div class="text-center">
  <a href="index.php" class="btn btn-primary">Home</a>
  </div>
</body>
</html>
