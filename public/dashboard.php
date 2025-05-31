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
    background: #f5f5f5;
}

.sidebar {
    width: 250px;
    background-color: #0d1b4c;
    color: white;
    padding: 20px;
    min-height: 100vh;
}

.logo {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
}

.logo-img {
    width: 50px;       
    height: auto;     
    margin-right: 10px;
    border-radius: 8px; 
}

.nav li {
    list-style: none;
    padding: 10px;
    cursor: pointer;
}

.nav li:hover, .nav li.active {
    background-color: #3053a5;
    border-radius: 5px;
}

.main {
    flex: 1;
    padding: 20px;
}

.top-bar {
    display: flex;
    justify-content: flex-end;
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
    margin-bottom: 20px;
}

.tab-button {
    margin-right: 10px;
    padding: 10px;
    background-color: #eee;
    border: none;
    cursor: pointer;
    border-bottom: 2px solid transparent;
}

.tab-button.active {
    border-bottom: 2px solid #0d1b4c;
    background-color: white;
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
    padding: 12px;
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

.passed {
    background-color: #7dff97;
    padding: 5px 10px;
    border-radius: 5px;
}

.sending {
    background-color: #b0a8f5;
    padding: 5px 10px;
    border-radius: 5px;
}

    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <img src="..\components\img\nbsc logo.jpg" alt="Logo">
            <h2>NBSC Online Admission</h2>
        </div>
        <ul class="nav">
            <li class="active" onclick="showTab('dashboard')">Dashboard</li>
            <li onclick="showTab('settings')">Settings and Profile</li>
        </ul>
    </div>

    <div class="main">
        <div class="top-bar">
            <button class="logout-btn">Log out</button>
        </div>

        <div class="tabs">
            <button onclick="selectTab('pending')" class="tab-button active">Pending Applications</button>
            <button onclick="selectTab('scheduling')" class="tab-button">Exam Scheduling</button>
            <button onclick="selectTab('results')" class="tab-button">Result Management</button>
        </div>

        <div id="pending" class="tab-content active">
            <h3>Exam Scheduling</h3>
            <table>
                <tr>
                    <th>Applicant Name</th>
                    <th>Application Type</th>
                    <th>Exam Date</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td>Kent Ryan Pagongpong</td>
                    <td>Senior High Grad</td>
                    <td>May 28, 2025</td>
                    <td><span class="approved">Approved</span></td>
                </tr>
                <tr>
                    <td>Jacob Israel Ranin</td>
                    <td>Senior High Grad</td>
                    <td>May 30, 2025</td>
                    <td><span class="approved">Approved</span></td>
                </tr>
            </table>

            <h3>Result Management</h3>
            <table>
                <tr>
                    <th>Applicant Name</th>
                    <th>Score</th>
                    <th>Assessment</th>
                    <th>State</th>
                </tr>
                <tr>
                    <td>Dexter Dimatao</td>
                    <td>51</td>
                    <td><span class="failed">Failed</span></td>
                    <td><span class="sending">Sending</span></td>
                </tr>
                <tr>
                    <td>John Lloyd Donque</td>
                    <td>67</td>
                    <td><span class="passed">Passed</span></td>
                    <td><span class="sending">Sending</span></td>
                </tr>
            </table>
        </div>

        <div id="settings" class="tab-content">
            <h2>Settings and Profile</h2>
            <p>Coming soon...</p>
        </div>
    </div>

    <script src="js/sidebar.js"></script>
</body>
</html>
