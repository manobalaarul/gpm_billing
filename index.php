<?php include 'actions/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Invoice List</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- DataTables + Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.min.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js"></script>
</head>

<body>
<div class="d-flex">
  <?php include 'includes/sidebar.php'; ?>

  <div class="container-fluid p-4">
    <div class="d-flex justify-content-between mb-4 align-items-center">
      <h2>Invoices</h2>
      <a href="new_bill.php" class="btn btn-primary">➕ New Bill</a>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered" id="inv-data">
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
              <a href='print_invoice.php?invoice_number=" . $row['invoice_number'] . "' class='btn btn-sm btn-primary'>🖨️ View</a> 
              <a href='edit_invoice.php?invoice_number=" . $row['invoice_number'] . "' class='btn btn-sm btn-secondary'>✏️ Edit</a> 
              <button class='btn btn-sm btn-danger' onclick='confirmDelete(\"" . $row['invoice_number'] . "\")'>🗑️ Delete</button>
              </td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function confirmDelete(invoiceNumber) {
  Swal.fire({
    title: 'Are you sure?',
    text: "This will permanently delete the invoice!",
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

$(document).ready(function () {
  new DataTable('#inv-data', {
    responsive: true,
    language: {
      search: "🔍",
      lengthMenu: "Show _MENU_",
      info: "Showing _START_ to _END_ of _TOTAL_ invoices"
    }
  });
});
</script>

</body>
</html>
