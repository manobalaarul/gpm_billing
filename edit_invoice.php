<?php 
include 'actions/auth.php'; // 🔒 Restrict access
include 'actions/db.php';

$id = $_GET['invoice_number'];
$invoice = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM invoices WHERE invoice_number = '$id'"));
$items = mysqli_query($conn, "SELECT * FROM invoice_items WHERE invoice_id = '$id'");
?>

<!DOCTYPE html>
<html>
<head>
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
  function addRow(title = "", description = "", rate = "", qty = "", amount = "") {
    const table = document.getElementById("products");
    const row = table.insertRow();
    row.innerHTML = `
      <td><input type="text" name="title[]" class="form-control" value="${title}" required></td>
      <td><input type="text" name="description[]" class="form-control" value="${description}" required></td>
      <td><input type="number" name="rate[]" class="form-control" step="0.01" value="${rate}" oninput="calcAmount(this)" required></td>
      <td><input type="number" name="qty[]" class="form-control" value="${qty}" oninput="calcAmount(this)" required></td>
      <td><input type="number" name="amount[]" class="form-control" value="${amount}" readonly></td>
      <td><button type="button" onclick="removeRow(this)" class="btn btn-danger btn-sm">❌</button></td>
    `;
    calcTotal();
  }

  function removeRow(btn) {
    btn.closest("tr").remove();
    calcTotal();
  }

  function calcAmount(input) {
    const row = input.closest("tr");
    const rate = parseFloat(row.querySelector('input[name="rate[]"]').value) || 0;
    const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
    row.querySelector('input[name="amount[]"]').value = (rate * qty).toFixed(2);
    calcTotal();
  }

  function calcTotal() {
    let total = 0;
    document.querySelectorAll('input[name="amount[]"]').forEach(item => {
      total += parseFloat(item.value) || 0;
    });
    document.getElementById("total_amount").value = total.toFixed(2);

    const paid = parseFloat(document.getElementById("paid_amount").value) || 0;
    document.getElementById("balance_amount").value = (total - paid).toFixed(2);
  }

  // Recalculate balance when paid amount changes
  function attachPaidEvent() {
    document.getElementById("paid_amount").addEventListener("input", calcTotal);
  }

  window.addEventListener("DOMContentLoaded", () => {
    attachPaidEvent();
    calcTotal();
  });
</script>

</head>
<body>
      <div class="d-flex">
  <?php include 'includes/sidebar.php'; ?>

    <div class="container my-4">
  <h2>Edit Invoice</h2>
  <form method="POST" action="actions/update_invoice.php">
    <input type="hidden" name="invoice_id" value="<?= $id ?>">

    <div class="mb-3">
      <label>Customer Name</label>
      <input type="text" name="customer_name" class="form-control" value="<?= $invoice['customer_name'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Phone</label>
      <input type="text" name="customer_phone" class="form-control" value="<?= $invoice['customer_phone'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Address</label>
      <textarea name="customer_address" class="form-control" required><?= $invoice['customer_address'] ?></textarea>
    </div>
    <div class="mb-3">
      <label>Date</label>
      <input type="date" name="invoice_date" class="form-control" value="<?= $invoice['invoice_date'] ?>" required>
    </div>

    <h4>Products</h4>
    <table class="table" id="products">
      <thead>
        <tr>
          <th>Description</th>
          <th>Rate</th>
          <th>Qty</th>
          <th>Amount</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($items)) { ?>
          <script>
            window.addEventListener('DOMContentLoaded', () => {
              addRow(`<?= $row['title'] ?>`,`<?= addslashes($row['description']) ?>`, `<?= $row['rate'] ?>`, `<?= $row['quantity'] ?>`, `<?= $row['amount'] ?>`);
            });
          </script>
        <?php } ?>
      </tbody>
    </table>
    <button type="button" onclick="addRow()" class="btn btn-secondary mb-3">➕ Add Product</button>

    <div class="row">
      <div class="col-md-4">
        <label>Total Amount</label>
        <input type="number" name="total_amount" id="total_amount" class="form-control" value="<?= $invoice['total_amount'] ?>" step="0.01" required>
      </div>
      <div class="col-md-4">
        <label>Paid Amount</label>
        <input type="number" name="paid_amount" id="paid_amount" class="form-control" value="<?= $invoice['paid_amount'] ?>" step="0.01" required>
      </div>
      <div class="col-md-4">
        <label>Balance Amount</label>
        <input type="number" name="balance_amount" id="balance_amount" class="form-control" value="<?= $invoice['balance_amount'] ?>" step="0.01" required>
      </div>
    </div>

    <button type="submit" class="btn btn-primary mt-4">Update Invoice</button>
  </form>
    </div>
      </div>
</body>
</html>
