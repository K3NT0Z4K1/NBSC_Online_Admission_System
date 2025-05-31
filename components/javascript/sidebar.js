// components/javascript/sidebar.js

function navigate(pageId) {
  // Hide all pages
  const pages = document.querySelectorAll(".page");
  pages.forEach(page => page.classList.remove("active"));

  // Show selected page
  const targetPage = document.getElementById(pageId);
  if (targetPage) {
    targetPage.classList.add("active");
  }

  // Highlight the active sidebar item
  const navItems = document.querySelectorAll(".nav-item");
  navItems.forEach(item => item.classList.remove("active"));
  const clickedItem = Array.from(navItems).find(item => item.textContent.trim().toLowerCase() === pageId.toLowerCase());
  if (clickedItem) {
    clickedItem.classList.add("active");
  }
}

function selectTab(tabId) {
  // Hide all tab content
  const tabs = document.querySelectorAll(".tab-content");
  tabs.forEach(tab => tab.classList.remove("active"));

  // Show selected tab
  const selectedTab = document.getElementById(tabId);
  if (selectedTab) {
    selectedTab.classList.add("active");
  }

  // Update tab buttons
  const buttons = document.querySelectorAll(".tab-button");
  buttons.forEach(btn => btn.classList.remove("active"));
  const activeBtn = Array.from(buttons).find(btn => btn.textContent.trim().toLowerCase().includes(tabId.toLowerCase()));
  if (activeBtn) {
    activeBtn.classList.add("active");
  }
}
