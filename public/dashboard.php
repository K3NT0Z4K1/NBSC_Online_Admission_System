<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NBSC Online Admission</title>
  <!-- <link rel="stylesheet" href="css/style.css"> -->
   <style>
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
  gap: 10px;
  margin-bottom: 30px;
}

.logo-img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid white;
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

.nav-item:hover,
.nav-item.active {
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
}

.tab-content {
  display: none;
}

.tab-content.active {
  display: block;
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

.failed {
  background-color: #f5a623;
  padding: 5px 10px;
  border-radius: 5px;
}

.sending {
  background-color: #b0a8f5;
  padding: 5px 10px;
  border-radius: 5px;
}

.page {
  display: none;
}

.page.active {
  display: block;
}

   </style>
</head>
<body>
  <div class="sidebar">
    <div class="logo">
      <img src="images/nbsc-logo.png" alt="Logo" class="logo-img" />
      <h2>NBSC Online Admission</h2>
    </div>
    <ul class="nav">
      <li class="nav-item active" onclick="navigate('dashboard')">Dashboard</li>
      <!-- <li class="nav-item" onclick="navigate('settings')">Settings and Profile</li> -->
    </ul>
  </div>

  <div class="main">
    <div class="top-bar">
      <button class="logout-btn">Log out</button>
    </div>

    <!-- DASHBOARD CONTENT -->
    <div id="dashboard" class="page active">
      <div class="tabs">
        <button onclick="selectTab('pending')" class="tab-button active">Pending Applications</button>
        <button onclick="selectTab('scheduling')" class="tab-button">Exam Scheduling</button>
        <button onclick="selectTab('results')" class="tab-button">Result Management</button>
      </div>

      <div id="pending" class="tab-content active">
        <h3>Pending Applications</h3>
        <p>...data here...</p>
      </div>

      <div id="scheduling" class="tab-content">
        <h3>Exam Scheduling</h3>
        <table>
          <tr>
            <th>Applicant</th><th>Type</th><th>Date</th><th>Status</th>
          </tr>
          <tr>
            <td>Kent Ryan</td><td>SH Grad</td><td>May 28</td><td><span class="approved">Approved</span></td>
          </tr>
        </table>
      </div>

      <div id="results" class="tab-content">
        <h3>Result Management</h3>
        <table>
          <tr>
            <th>Applicant</th><th>Score</th><th>Result</th><th>State</th>
          </tr>
          <tr>
            <td>Dexter</td><td>51</td><td><span class="failed">Failed</span></td><td><span class="sending">Sending</span></td>
          </tr>
        </table>
      </div>
    </div>

    <!-- SETTINGS CONTENT -->
    <div id="settings" class="page">
      <h2>Settings and Profile</h2>
      <p>This is where you manage your account settings.</p>
    </div>
  </div>

  <script src=".\components\javascript\sidebar.js"></script>
</body>
</html>
