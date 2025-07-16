<!-- sidebar.php -->
<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 220px; min-height: 100vh;">
  <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <span class="fs-4">🧾 My Invoice</span>
  </a>
  <hr>
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="index.php" class="nav-link text-white">
        📄 View Invoices
      </a>
    </li>
    <li>
      <a href="new_bill.php" class="nav-link text-white">
        ➕ Add Invoice
      </a>
    </li>
    <li>
      <a href="actions/logout.php" class="nav-link text-white">
        ⏳ Logout
      </a>
    </li>
  </ul>
  <hr>
  <div class="text-white-50">
    <small>&copy; <?= date("Y") ?> GPM Properties</small>
  </div>
</div>
