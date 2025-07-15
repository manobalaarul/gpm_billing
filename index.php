<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container my-4">
    <div class="d-flex justify-content-between mb-4 items-center">
       <h2>Invoices</h2>
        <a href="new_bill.php" class="btn btn-primary">New Bill</a>
    </div>
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Invoice No</th>
          <th>Customer Name</th>
          <th>Date</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM invoices ORDER BY id DESC");
        $count = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $count++ . "</td>";
          echo "<td>" . $row['invoice_number'] . "</td>";
          echo "<td>" . $row['customer_name'] . "</td>";
          echo "<td>" . date("d/m/y, h:i A", strtotime($row['realtime'])) . "</td>";
          echo "<td>₹" . number_format($row['total_amount'], 2) . "</td>";
          echo "<td><a href='print_invoice.php?id=" . $row['id'] . "' target='_blank' class='btn btn-sm btn-primary'>View</a></td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
