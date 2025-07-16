<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Invoice List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
          echo "<td>
  <a href='print_invoice.php?invoice_number=" . $row['invoice_number'] . "' class='btn btn-sm btn-primary'>View</a> 
  <a href='edit_invoice.php?invoice_number=" . $row['invoice_number'] . "' class='btn btn-sm btn-secondary'>Edit</a> 
  <button class='btn btn-sm btn-danger' onclick='confirmDelete(\"" . $row['invoice_number'] . "\")'>Delete</button>
</td>";

          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
  <script>
function confirmDelete(invoiceNumber) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this invoice deletion!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'delete.php?invoice_number=' + invoiceNumber;
    }
  });
}
</script>

</body>

</html>