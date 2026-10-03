<?php
require_once 'config.php';
requireLogin();

$stmt = $pdo->query("SELECT * FROM donors ORDER BY created_at DESC");
$donors = $stmt->fetchAll();

$total = count($donors);
$male = $pdo->query("SELECT COUNT(*) FROM donors WHERE gender = 'Male'")->fetchColumn();
$female = $pdo->query("SELECT COUNT(*) FROM donors WHERE gender = 'Female'")->fetchColumn();
$groups = $pdo->query("SELECT COUNT(DISTINCT blood_group) FROM donors")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Donor Details - Blood Bank System</title>
  <link rel="stylesheet" href="home.css">
  <style>
    .donors-container { max-width: 1600px; margin: 1rem auto; padding: 0 10px; }
    .donors-card { background: rgba(255,255,255,0.98); backdrop-filter: blur(10px); border-radius: 20px; padding: 3rem; box-shadow: 0 20px 60px rgba(0,0,0,0.15); margin-bottom: 2rem; }
    .donors-table { width: 100%; border-collapse: collapse; margin-top: 2rem; font-size: 0.95rem; }
    .donors-table th, .donors-table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e0e0e0; }
    .donors-table th { background: linear-gradient(135deg, #d32f2f, #b71c1c); color: white; font-weight: bold; position: sticky; top: 0; z-index: 10; }
    .donors-table tr:hover { background: rgba(211,47,47,0.08); }
    .blood-group-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: bold; color: white; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem; margin-bottom: 2.5rem; }
    .stat-card { background: linear-gradient(135deg, rgba(211,47,47,0.15), rgba(183,28,28,0.15)); padding: 2rem; border-radius: 15px; text-align: center; border-left: 6px solid #d32f2f; transition: transform 0.3s ease; }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-number { font-size: 3rem; font-weight: bold; color: #d32f2f; margin-bottom: 0.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.1); }
    .stat-label { color: #555; font-size: 1.1rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
    .table-container { overflow-x: auto; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.header-section { text-align: center; margin-bottom: 3rem; }
    .header-section h2 { font-size: 2.5rem; color: #d32f2f; margin-bottom: 1rem; text-align: center; }
    @media (max-width: 1400px) { .donors-container { max-width: 1200px; } }
    @media (max-width: 1200px) { .donors-table { font-size: 0.9rem; } }
    @media (max-width: 768px) { .donors-table th, .donors-table td { padding: 10px 8px; font-size: 0.85rem; } .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; } .donors-card { padding: 2rem 1.5rem; } }
    @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } .donors-table th, .donors-table td { padding: 8px 4px; font-size: 0.8rem; } }
    .header-flex {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.download-btn {
  background: linear-gradient(135deg, #d32f2f, #b71c1c);
  color: white;
  padding: 10px 18px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: 0.3s;
}

.download-btn:hover {
  background: linear-gradient(135deg, #b71c1c, #7f0000);
  transform: translateY(-2px);
}
h2{
   margin-left: 500px;
}
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
      <h1>All Donor Details</h1>
      <p>Live registry from database - <?php echo $total; ?> total donors</p>
    </div>
  </section>

  <main>
    <div class="donors-container">
      <div class="donors-card">
       <div class="header-section">
  <div class="header-flex">
    <h2>Complete Donor Directory</h2>
      <button onclick="CreatePDF() " class="download-btn">Download</button>
  </div>
</div>
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-number"><?php echo $total; ?></div>
            <div class="stat-label">Total Donors</div>
          </div>
          <div class="stat-card">
            <div class="stat-number"><?php echo $male; ?></div>
            <div class="stat-label">Male Donors</div>
          </div>
          <div class="stat-card">
            <div class="stat-number"><?php echo $female; ?></div>
            <div class="stat-label">Female Donors</div>
          </div>
          <div class="stat-card">
            <div class="stat-number"><?php echo $groups; ?></div>
            <div class="stat-label">Blood Groups</div>
          </div>
        </div>
        <div class="table-container">
          <table class="donors-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>DOB</th>
                <th>Age</th>
                <th>Blood Group</th>
                <th>Gender</th>
                <th>City</th>
                <th>Mobile</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($donors as $donor): ?>
              <tr>
                <td><?php echo htmlspecialchars($donor['id']); ?></td>
                <td><?php echo htmlspecialchars($donor['name']); ?></td>
                <td><?php echo date('Y-m-d', strtotime($donor['dob'])); ?></td>
                <td><?php echo $donor['age']; ?></td>
                <td><?php echo htmlspecialchars($donor['blood_group']); ?></td>
                <td><?php echo htmlspecialchars($donor['gender']); ?></td>
                <td><?php echo htmlspecialchars($donor['city']); ?></td>
                <td><?php echo htmlspecialchars($donor['mobile']); ?></td>
                <td><?php echo htmlspecialchars($donor['email']); ?></td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($donors)): ?>
              <tr><td colspan="9" style="text-align:center; padding: 4rem; color: #666;">No donors registered yet. <a href="Donor.php">Add first donor</a></td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>&copy; 2024 Blood Bank System | <?php echo $total; ?> donors | Logged as <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    </div>
  </footer>
   <!-- Add here -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

  <script>
    const hamburger = document.getElementById('hamburger'), navMenu = document.getElementById('nav-menu');
    function toggleMenu() { navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex'; }
    hamburger.onclick = toggleMenu;
  window.onresize = () => { if (window.innerWidth > 768) navMenu.style.display = 'flex'; };
    


function CreatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4');

    doc.setFontSize(20);
    doc.setTextColor(211, 47, 47);
    doc.text('COMPLETE DONOR DIRECTORY', 14, 20);

    doc.setFontSize(12);
    doc.setTextColor(0, 0, 0);
    doc.text(`Total Donors: <?php echo $total; ?> | Male: <?php echo $male; ?> | Female: <?php echo $female; ?> | Blood Groups: <?php echo $groups; ?>`, 14, 30);
    doc.text(`Report Generated: ${new Date().toLocaleDateString()}`, 14, 38);

    const table = document.querySelector('.donors-table');

    doc.autoTable({
        html: table,
        startY: 45
    });

    doc.save('donors.pdf');
}
</script>

</body>
</html>
