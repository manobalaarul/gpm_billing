<?php 
include 'actions/auth.php'; // 🔒 Restrict access
include 'actions/db.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PHP Billing System</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table td input {
            width: 100%;
        }
    </style>
    <script>
        function addRow() {
            const table = document.getElementById("products");
            const row = table.insertRow();
            row.innerHTML = `
      <td><input type="text" name="title[]" class="form-control" required></td>
      <td><input type="text" name="description[]" class="form-control" required></td>
      <td><input type="number" name="rate[]" step="0.01" class="form-control" oninput="calcAmount(this)" required></td>
      <td><input type="number" name="qty[]" class="form-control" oninput="calcAmount(this)" required></td>
      <td><input type="number" name="amount[]" class="form-control" readonly></td>
      <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">❌</button></td>
    `;
        }

        function removeRow(btn) {
            btn.closest("tr").remove();
            updateTotal();
        }

        function calcAmount(input) {
            const row = input.closest("tr");
            const rate = parseFloat(row.querySelector('input[name="rate[]"]').value) || 0;
            const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
            row.querySelector('input[name="amount[]"]').value = (rate * qty).toFixed(2);

            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('input[name="amount[]"]').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById("total_amount").value = total.toFixed(2);
            updateBalance(); // Recalculate balance after total updates
        }

        function updateBalance() {
            const total = parseFloat(document.getElementById("total_amount").value) || 0;
            const paid = parseFloat(document.getElementById("paid_amount").value) || 0;
            const balance = total - paid;
            document.getElementById("balance_amount").value = balance.toFixed(2);
        }
    </script>

</head>

<body>
    <div class="d-flex">
  <?php include 'includes/sidebar.php'; ?>

    <div class="container my-4">
        <h2 class="mb-4">Invoice Billing Page</h2>

        <form method="POST" action="actions/submit.php">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="customer_phone" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="customer_address" class="form-control" rows="2" required></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Invoice Date</label>
                <input type="date" name="invoice_date" class="form-control" required>
            </div>

            <h4>Products</h4>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="products">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Rate</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <button type="button" class="btn btn-secondary my-2" onclick="addRow()">➕ Add Product</button>

            <div class="row mb-3 mt-4">
                <div class="col-md-4">
                    <label class="form-label">Total Amount</label>
                    <input type="number" name="total_amount" id="total_amount" step="0.01" class="form-control" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Paid Amount</label>
                    <input type="number" name="paid_amount" id="paid_amount" step="0.01" class="form-control" required oninput="updateBalance()">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Balance Amount</label>
                    <input type="number" name="balance_amount" id="balance_amount" step="0.01" class="form-control" readonly>
                </div>
            </div>


            <button type="submit" class="btn btn-primary">Submit Invoice</button>
        </form>
    </div>
    </div>
    <!-- Bootstrap JS Bundle (optional for features like dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>