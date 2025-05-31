<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>NBSC Online Admission - Exam Scheduling</title>
  <style>
    /* Same styles as dashboard for consistency */
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      display: flex;
    }
    .sidebar {
      width: 250px;
      background-color: #0d1b4c;
      color: white;
      min-height: 100vh;
      padding: 20px;
    }
    .logo {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 30px;
    }
    .logo-img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: contain;
      border: 2px solid white;
      padding: 10px;
      background-color: white;
    }
    .nav {
      list-style: none;
      padding: 0;
    }
    .nav-item {
      padding: 12px;
      cursor: pointer;
      border-radius: 5px;
    }
    .nav-item.active,
    .nav-item:hover {
      background-color: #3053a5;
    }
    .main {
      flex: 1;
      padding: 20px;
      background: #f5f5f5;
    }
    .top-bar {
      text-align: right;
      margin-bottom: 20px;
    }
    .logout-btn {
      padding: 10px 15px;
      background-color: #5aa6e5;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .tabs {
      margin-bottom: 15px;
    }
    .tab-button {
      padding: 10px;
      background-color: #eee;
      border: none;
      cursor: pointer;
      margin-right: 5px;
    }
    .tab-button.active {
      background-color: white;
      border-bottom: 2px solid #0d1b4c;
      font-weight: bold;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 10px;
      text-align: left;
    }
    .approved {
      background-color: #7dff97;
      padding: 5px 10px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="logo">
      <img src="..\components\img\nbsc logo.jpg" alt="Logo" class="logo-img" />
      <h2>NBSC Online Admission</h2>
    </div>
    <ul class="nav">
      <li class="nav-item"><a href="dashboard.html" style="color: white; text-decoration: none;">Dashboard</a></li>
      <li class="nav-item active">Exam Scheduling</li>
      <li class="nav-item"><a href="result-management.html" style="color: white; text-decoration: none;">Result Management</a></li>
    </ul>
  </div>

  <div class="main">
    <div class="top-bar">
      <button class="logout-btn">Log out</button>
    </div>

    <h3>Exam Scheduling</h3>
    <table>
      <tr>
        <th>Applicant</th><th>Type</th><th>Date</th><th>Status</th>
      </tr>
      <tr>
        <td>Kent Ryan</td><td>SH Grad</td><td>May 28</td><td><span class="approved">Approved</span></td>
      </tr>
      <tr>
        <td>Anna Rivera</td><td>ALS</td><td>May 29</td><td><span class="approved">Approved</span></td>
      </tr>
    </table>
  </div>
</body>
</html>
