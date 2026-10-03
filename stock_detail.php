<?php
require_once 'config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stock Details - Blood Bank System</title>
<link rel="stylesheet" href="home.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
  <style>
    .stock-container { max-width: 800px; margin: 2rem auto; padding: 2rem; }
    .stock-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-radius: 15px; padding: 3rem; box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
    .stock-table { width: 100%; border-collapse: collapse; margin-top: 2rem; font-size: 1.1rem; }
    .stock-table th, .stock-table td { padding: 15px; text-align: left; border: 1px solid #ddd; }
    .stock-table th { background-color: #4caf50; color: white; font-weight: bold; }
    .stock-table tr:nth-child(even) { background-color: #f9f9f9; }
    .stock-table tr:hover { background-color: #e8f5e8; }
.total-units { text-align: center; font-size: 1.5rem; font-weight: bold; color: #2e7d32; margin-top: 2rem; }
    .pdf-download-btn:hover { background-color: #1976D2; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(33,150,243,0.4); }
    @media (max-width: 768px) { .stock-table { font-size: 1rem; } .stock-card { padding: 2rem 1.5rem; } }
  </style>
</head>
<body>
  <!-- Navigation Bar (same as other pages) -->
  <nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="container">
      <h1>Blood Bank System</h1>
      <ul id="nav-menu" role="menubar">
        <li><a href="home_full.php">Home</a></li>
        <li class="dropdown">
          <a>Donors</a>
          <div class="dropdown-content">
            <a href="Donor.php">Add Donors</a>
            <a href="Update_ donor.php">Update Donor</a>
            <a href="all_donor_details.php">All Donor Details</a>
          </div>
        </li>
        <li class="dropdown">
          <a>Search blood donor</a>
          <div class="dropdown-content">
            <a href="search_donor_location.php">Location</a>
            <a href="search_donor_bloodgroup.php">Blood group</a>
          </div>
        </li>
        <li class="dropdown">
          <a>Stock</a>
          <div class="dropdown-content">
            <a href="stock_increase.php">Stock (Increase)</a>
            <a href="stock_decrease.php">Stock (Decrease)</a>
            <a href="stock_detail.php">Detail</a>
          </div>
        </li>
        <li><a href="delete_donor.php">Delete donor</a></li>
        <li><a href="login.php">Log out</a></li>
      </ul>
      <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
      </div>
    </div>
  </nav>

  <section class="hero">
    <div class="hero-content">
      <h1>Blood Stock Overview</h1>
      <p>Current inventory levels across all blood groups</p>
    </div>
  </section>

  <main>
    <div class="stock-container">
      <div class="stock-card">
        <h2>Current Stock Details</h2>
        <?php
        $stmt = $pdo->query("SELECT blood_group, quantity FROM stock ORDER BY 
          CASE blood_group 
            WHEN 'O+' THEN 1 WHEN 'O-' THEN 2 
            WHEN 'A+' THEN 3 WHEN 'A-' THEN 4 
            WHEN 'B+' THEN 5 WHEN 'B-' THEN 6 
            WHEN 'AB+' THEN 7 WHEN 'AB-' THEN 8 
          END");
        $stocks = $stmt->fetchAll();
        $total = array_sum(array_column($stocks, 'quantity'));
        ?>

<button class="pdf-download-btn" onclick="CreatePDF()" style="background-color: #2196F3; color: white; border: none; padding: 15px 30px; border-radius: 10px; font-size: 1.1rem; font-weight: bold; cursor: pointer; float: right; margin-bottom: 1rem; transition: all 0.3s ease; box-shadow: 0 4px 8px rgba(33,150,243,0.3);">📄 Download Stock PDF</button>

        <table class="stock-table">
          <thead><tr><th>Blood Group</th><th>Total Number of Quantity</th></tr></thead>
          <tbody>
            <?php foreach ($stocks as $stock): ?>
            <tr><td><?php echo htmlspecialchars($stock['blood_group']); ?></td><td><?php echo $stock['quantity']; ?></td></tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="total-units">Total Units: <?php echo $total; ?></div>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <p>© 2024 Blood Bank System | <?php echo $_SESSION['username']; ?></p>
    </div>
  </footer>

  <script>
    // Same mobile menu JS as other pages
    const hamburger = document.getElementById('hamburger'), navMenu = document.getElementById('nav-menu');
    function toggleMenu() { navMenu.style.display = navMenu.style.display === 'flex' ? 'none' : 'flex'; }
    hamburger.onclick = toggleMenu; hamburger.onkeydown = e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleMenu(); } };
    window.onresize = () => { if (window.innerWidth > 768) navMenu.style.display = 'flex'; };
    
   function CreatePDF() {
    if (!window.jspdf || !window.jspdf.jsPDF) {
        alert("PDF library not loaded!");
        return;
    }

    const { jsPDF } = window.jspdf;

    // ✅ A4 Portrait
    const doc = new jsPDF('p', 'mm', 'a4');

    // Title
    doc.setFontSize(18);
    doc.setTextColor(76, 175, 80);
    doc.text('Blood Bank Stock Report', 14, 15);

    doc.setFontSize(10);
    doc.setTextColor(0, 0, 0);
    doc.text(`Generated: ${new Date().toLocaleDateString()}`, 14, 22);

    // Table
    const table = document.querySelector('.stock-table');

    doc.autoTable({
        html: table,
        startY: 30,

        styles: {
            fontSize: 11,
            cellPadding: 4,
        },

        headStyles: {
            fillColor: [76, 175, 80], // green
            textColor: 255,
            halign: 'center'
        },

        alternateRowStyles: {
            fillColor: [240, 248, 240]
        },

        theme: 'grid'
    });

    // Total Units
    const totalUnits = document.querySelector('.total-units').textContent;
    doc.setFontSize(12);
    doc.setFont(undefined, 'bold');
    doc.text(totalUnits, 14, doc.lastAutoTable.finalY + 10);

    // Footer
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(8);
        doc.text(`Page ${i} of ${pageCount}`, 180, 290);
    }

    doc.save('blood_stock_report.pdf');
}
  </script>
</body>
</html>
