<?php
require_once 'config.php';
requireLogin();

// Dynamic stats from DB
$stmt = $pdo->query("SELECT COUNT(*) as total_donors FROM donors");
$total_donors = $stmt->fetch()['total_donors'];

$stmt = $pdo->query("SELECT SUM(quantity) as total_stock FROM stock");
$total_stock = $stmt->fetch()['total_stock'] ?: 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank System - Home</title>
    <link rel="stylesheet" href="home.css">
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="container">
      <h1>Blood Bank System</h1>
      <ul id="nav-menu" role="menubar">
        <li role="none"><a href="home_full.php" role="menuitem">Home</a></li>
        <li role="none" class="dropdown">
          <a role="menuitem">Donors</a>
          <div class="dropdown-content">
            <a href="Donor.php"> Add Donors</a>
            <a href="Update_ donor.php">Update Donor</a>
            <a href="all_donor_details.php">All Donor Details</a>
          </div>
        </li>
        <li role="none" class="dropdown">
          <a role="menuitem">Search blood donor </a>
          <div class="dropdown-content">
            <a href="search_donor_location.php">Location</a>
            <a href="search_donor_bloodgroup.php">Blood group</a>
          </div>
        </li>
        <li role="none" class="dropdown">
          <a role="menuitem">Stock </a>
          <div class="dropdown-content">
            <a href="stock_increase.php">Stock (Increase)</a>
            <a href="stock_decrease.php">Stock (Decrease)</a>
            <a href="stock_detail.php">Detail</a>
          </div>
        </li>
        <li role="none"><a href="delete_donor.php" role="menuitem">Delete donor</a></li>
        <li role="none"><a href="login.php?logout=1" role="menuitem">Log out</a></li>
      </ul>
      <div class="hamburger" id="hamburger" aria-label="Toggle menu" role="button" tabindex="0">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content container">
      <h1>Welcome to Blood Bank</h1>
      <p>Saving Lives, One Donation at a Time | Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
      <button class="btn" onclick="handleBecomeDonor()">Become a Donor</button>
    </div>
  </section>

  <!-- Main Content -->
  <main class="container">
    <!-- Quick Stats (dynamic) -->
    <section class="stats-section">
      <h2>Quick Stats</h2>
      <div class="stats-grid">
        <div class="card stats-card">
          <h3><?php echo number_format($total_donors); ?></h3>
          <p>Active Donors</p>
        </div>
        <div class="card stats-card">
          <h3><?php echo number_format($total_stock); ?></h3>
          <p>Total Stock Units</p>
        </div>
        <div class="card stats-card">
          <h3><?php echo $_SESSION['role'] === 'admin' ? 'Full Access' : '100%'; ?></h3>
          <p><?php echo $_SESSION['role']; ?> Access</p>
        </div>
      </div>
    </section>

    <!-- Why Donate -->
    <section class="why-donate">
      <h2>Why Donate Blood?</h2>
      <p>You could save up to 3 lives with one donation. Blood is needed every 2 seconds. Be a hero today!</p>
      <button class="btn" onclick="scrollToBloodTypes()">Find Out More</button>
    </section>

    <!-- Blood Types Needed -->
    <section id="blood-types" class="blood-types-section">
      <h2>Blood Types Needed</h2>
      <div class="blood-types-grid">
        <div class="card blood-type-card o-negative">
          <h3>O-</h3>
          <p>Universal Donor</p>
        </div>
        <div class="card blood-type-card ab-positive">
          <h3>AB+</h3>
          <p>Universal Receiver</p>
        </div>
        <div class="card blood-type-card a-positive">
          <h3>A+</h3>
          <p>High Demand</p>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p>&copy; 2024 Blood Bank System. All rights reserved. | Welcome <?php echo htmlspecialchars($_SESSION['username']); ?> | Emergency: 1-800-BLOOD</p>
    </div>
  </footer>

  <script>
    // Logout handler
    <?php if (isset($_GET['logout'])): ?>
    <?php session_destroy(); header('Location: login.php'); exit; ?>
    <?php endif; ?>

    // Mobile menu toggle
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('nav-menu');
    hamburger.addEventListener('click', toggleMenu);
    hamburger.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleMenu(); }
    });
    function toggleMenu() { navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex'; }
    window.addEventListener('resize', () => { if (window.innerWidth > 768) navMenu.style.display = 'flex'; });

    function handleBecomeDonor() { window.location.href = 'Donor.php'; }
    function scrollToBloodTypes() { document.getElementById('blood-types').scrollIntoView({ behavior: 'smooth' }); }
    window.addEventListener('resize', () => {
      document.body.style.backgroundSize = 'cover';
      document.body.style.backgroundPosition = 'center center';
    });
  </script>
</body>
</html>
