<?php
require_once 'config.php';
requireLogin();

$message = '';
if ($_POST) {
    $blood_group = $_POST['blood_group'];
    $quantity = (int)$_POST['quantity'];
    if ($blood_group && $quantity > 0) {
        $stmt = $pdo->prepare("INSERT INTO stock (blood_group, quantity) VALUES (?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + ?");
        $stmt->execute([$blood_group, $quantity, $quantity]);
        $message = "Added $quantity units of $blood_group to stock successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Increase Stock - Blood Bank System</title>
  <link rel="stylesheet" href="home.css">
  <style>
    .stock-container { max-width: 500px; margin: 2rem auto; }
    .stock-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 15px; padding: 3rem; box-shadow: 0 15px 35px rgba(0,0,0,0.2); text-align: center; }
    .stock-row { display: flex; gap: 15px; align-items: end; margin-bottom: 1.5rem; }
    .stock-row .form-group { flex: 1; margin-bottom: 0; }
    .add-stock-btn { background-color: #4caf50; color: white; border: none; padding: 15px 30px; border-radius: 10px; cursor: pointer; font-size: 1.1rem; font-weight: bold; height: fit-content; white-space: nowrap; transition: all 0.3s ease; }
    .add-stock-btn:hover { background-color: #388e3c; transform: translateY(-2px); }
    .success-message { margin-top: 1.5rem; padding: 1rem; background: rgba(76,175,80,0.2); border: 2px solid #4caf50; border-radius: 10px; color: #2e7d32; font-weight: bold; animation: fadeIn 0.5s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    select.form-control, input.form-control[type="number"] { padding: 15px; font-size: 1.1rem; }
    @media (max-width: 768px) { .stock-row { flex-direction: column; } .add-stock-btn { align-self: stretch; } .stock-card { padding: 2rem 1.5rem; } }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <h1>Blood Bank System</h1>
      <ul id="nav-menu">
        <li><a href="home_full.php">Home</a></li>
        <li class="dropdown"><a>Donors</a><div class="dropdown-content"><a href="Donor.php">Add Donors</a><a href="Update_ donor.php">Update Donor</a><a href="all_donor_details.php">All Donor Details</a></div></li>
        <li class="dropdown"><a>Search blood donor</a><div class="dropdown-content"><a href="search_donor_location.php">Location</a><a href="search_donor_bloodgroup.php">Blood group</a></div></li>
        <li class="dropdown"><a>Stock</a><div class="dropdown-content"><a href="stock_increase.php">Stock (Increase)</a><a href="stock_decrease.php">Stock (Decrease)</a><a href="stock_detail.php">Detail</a></div></li>
        <li><a href="delete_donor.php">Delete donor</a></li>
        <li><a href="login.php?logout=1">Log out</a></li>
      </ul>
      <div class="hamburger" id="hamburger"><span></span><span></span><span></span></div>
    </div>
  </nav>

  <section class="hero">
    <div class="hero-content">
      <h1>Increase Blood Stock</h1>
      <p>Add new blood units to inventory</p>
    </div>
  </section>

  <main>
    <div class="stock-container">
      <div class="stock-card">
        <h2>Add Stock</h2>
        <?php if ($message): ?>
          <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" id="stockForm">
          <div class="stock-row">
            <div class="form-group">
              <label for="blood_group">Blood Group *</label>
              <select id="blood_group" name="blood_group" class="form-control" required>
                <option value="" disabled selected>Select Blood Group</option>
                <option>O+</option><option>O-</option><option>A+</option><option>A-</option><option>B+</option><option>B-</option><option>AB+</option><option>AB-</option>
              </select>
            </div>
            <div class="form-group">
              <label for="quantity">Quantity (units) *</label>
              <input type="number" id="quantity" name="quantity" class="form-control" min="1" max="1000" placeholder="0" required>
            </div>
          </div>
          <button type="submit" class="add-stock-btn">Add to Stock</button>
        </form>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; 2024 Blood Bank System | Logged as <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    </div>
  </footer>

  <script>
    const hamburger = document.getElementById('hamburger'), navMenu = document.getElementById('nav-menu');
    function toggleMenu() { navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex'; }
    hamburger.onclick = toggleMenu;
    window.onresize = () => { if (window.innerWidth > 768) navMenu.style.display = 'flex'; };
  </script>
</body>
</html>
