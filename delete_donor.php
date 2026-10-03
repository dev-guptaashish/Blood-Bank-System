<?php
require_once 'config.php';
requireLogin();

$message = '';
$donor_info = null;

if ($_POST) {
    $identifier = trim($_POST['donor_id']);
    if ($_POST['donor_id'] === $_POST['confirm_id']) {
        $stmt = $pdo->prepare("SELECT * FROM donors WHERE id = ? OR mobile = ?");
        $stmt->execute([$identifier, $identifier]);
        $donor_info = $stmt->fetch();
        
        if ($donor_info) {
            $delete_stmt = $pdo->prepare("DELETE FROM donors WHERE id = ? OR mobile = ?");
            if ($delete_stmt->execute([$identifier, $identifier])) {
                $message = "Donor '{$donor_info['name']}' ({$donor_info['blood_group']}) deleted successfully!";
            }
        } else {
            $message = "Donor ID/Mobile not found!";
        }
    } else {
        $message = "IDs do not match. Please confirm correctly.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Donor - Blood Bank System</title>
  <link rel="stylesheet" href="home.css">
  <style>
    .form-container { max-width: 500px; margin: 2rem auto; }
    .form-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 15px; padding: 3rem; box-shadow: 0 15px 35px rgba(0,0,0,0.2); text-align: center; }
    .message { margin-top: 1.5rem; padding: 1rem; border-radius: 10px; font-weight: bold; }
    .success { background: rgba(76,175,80,0.2); border: 2px solid #4caf50; color: #2e7d32; }
    .error { background: rgba(244,67,54,0.2); border: 2px solid #f44336; color: #c62828; }
    .warning { background: rgba(255,152,0,0.2); border: 2px solid #ff9800; color: #e65100; }
    .donor-preview { background: rgba(255,235,59,0.2); border: 2px solid #fdd835; margin-top: 1rem; padding: 1rem; border-radius: 8px; }
    @media (max-width: 768px) { .form-card { padding: 2rem 1.5rem; } }
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
      <h1>Delete Donor Record</h1>
      <p>⚠️ Permanent deletion - cannot be undone</p>
    </div>
  </section>

  <main>
    <div class="form-container">
      <div class="form-card">
        <h2>Delete Donor</h2>
        <?php if ($message): ?>
          <div class="message <?php echo strpos($message, 'deleted') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
          </div>
          <?php if (isset($donor_info) && strpos($message, 'deleted') !== false): ?>
            <div class="message success" style="margin-top: 1rem;">
              Deleted: <?php echo htmlspecialchars($donor_info['name']); ?> (<?php echo htmlspecialchars($donor_info['blood_group']); ?>) - <?php echo htmlspecialchars($donor_info['mobile']); ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>
        <form method="POST" id="deleteForm">
          <div class="form-group">
            <label for="donor_id">Donor ID or Mobile Number *</label>
            <input type="text" id="donor_id" name="donor_id" class="form-control" placeholder="Enter Donor ID or Mobile" required>
          </div>
          
          <div class="form-group">
            <label for="confirm_id">Confirm Donor ID or Mobile Number *</label>
            <input type="text" id="confirm_id" name="confirm_id" class="form-control" placeholder="Re-enter exact ID/Mobile" required>
          </div>
          
          <div class="warning" style="margin-top: 1rem;">
            <strong>⚠️ Warning:</strong> This action is permanent. Donor data will be deleted forever.
          </div>
          
          <button type="submit" class="btn" style="background: #f44336; color: white;" onclick="return confirm('PERMANENT DELETION: Are you absolutely sure?\n\nThis cannot be undone.')">
            Delete Donor Permanently
          </button>
        </form>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; 2024 Blood Bank System | Admin: <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="all_donor_details.php">View All Donors</a></p>
    </div>
  </footer>

  <script>
    const hamburger = document.getElementById('hamburger'), navMenu = document.getElementById('nav-menu');
    function toggleMenu() { navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex'; }
    hamburger.onclick = toggleMenu;
    window.onresize = () => { if (window.innerWidth > 768) navMenu.style.display = 'flex'; };
    
    document.getElementById('donor_id').oninput = function() {
      document.getElementById('confirm_id').value = this.value;
    };
  </script>
</body>
</html>

<?php
function getBloodColor($group) {
    $colors = ['O+'=>'#d32f2f','O-'=>'#f44336','A+'=>'#388e3c','A-'=>'#4caf50','B+'=>'#f57c00','B-'=>'#ff9800','AB+'=>'#1976d2','AB-'=>'#2196f3'];
    return $colors[$group] ?? '#9e9e9e';
}
?>

