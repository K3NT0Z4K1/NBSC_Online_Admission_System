function navigate(pageId) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.getElementById(pageId).classList.add('active');

  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.querySelector(`.nav-item[onclick="navigate('${pageId}')"]`).classList.add('active');
}

function selectTab(tabId) {
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  document.querySelector(`#${tabId}`).classList.add('active');

  document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
  document.querySelector(`.tab-button[onclick="selectTab('${tabId}')"]`).classList.add('active');
}
