/**
 * Real-time functionality for Fluffy Planet
 * Provides live clock, data updates, and notifications
 */

// Add CSS for real-time updates
const style = document.createElement("style");
style.textContent = `
    .stat-card.updated {
        animation: updatePulse 2s ease-in-out;
        border-left: 4px solid #28a745 !important;
    }
    
    @keyframes updatePulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    
    .notification-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        animation: notificationBounce 0.5s ease-in-out;
    }
    
    @keyframes notificationBounce {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .connection-status {
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .realtime-clock {
        text-align: center;
        min-width: 120px;
    }
    
`;
document.head.appendChild(style);

class RealtimeManager {
  constructor() {
    this.updateInterval = null;
    this.notificationInterval = null;
    this.isActive = true;
    this.lastUpdateTime = null;
    this.init();
  }

  init() {
    this.startRealTimeClock();
    this.startDataUpdates();
    this.startNotificationCheck();
    this.setupVisibilityHandling();
  }

  /**
   * Start real-time clock
   */
  startRealTimeClock() {
    const clockElements = document.querySelectorAll(".realtime-clock");

    if (clockElements.length === 0) return;

    const updateClock = () => {
      const now = new Date();
      const timeString = now.toLocaleTimeString("en-US", {
        hour12: true,
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
      });
      const dateString = now.toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
      });

      clockElements.forEach((element) => {
        const timeEl = element.querySelector(".clock-time");
        const dateEl = element.querySelector(".clock-date");

        if (timeEl) timeEl.textContent = timeString;
        if (dateEl) dateEl.textContent = dateString;
      });
    };

    // Update immediately
    updateClock();

    // Update every second
    setInterval(updateClock, 1000);
  }

  /**
   * Start data updates for dashboard statistics
   */
  startDataUpdates() {
    // Update every 30 seconds
    this.updateInterval = setInterval(() => {
      if (this.isActive && document.visibilityState === "visible") {
        this.updateDashboardData();
      }
    }, 30000);
  }

  /**
   * Update dashboard data via AJAX
   */
  async updateDashboardData() {
    try {
      const response = await fetch("/api/dashboard-stats", {
        method: "GET",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "Content-Type": "application/json",
        },
      });

      if (response.ok) {
        const data = await response.json();
        this.updateStatistics(data);
        this.lastUpdateTime = new Date();
      }
    } catch (error) {
      console.log("Dashboard update failed:", error);
    }
  }

  /**
   * Update statistics on the page
   */
  updateStatistics(data) {
    // Update order counts
    if (data.pending_orders !== undefined) {
      const pendingOrdersEl = document.querySelector(".pending-orders-count");
      if (pendingOrdersEl) {
        pendingOrdersEl.textContent = data.pending_orders;
      }
    }

    if (data.today_orders !== undefined) {
      const todayOrdersEl = document.querySelector(".today-orders-count");
      if (todayOrdersEl) {
        todayOrdersEl.textContent = data.today_orders;
      }
    }

    // Update available animals
    if (data.available_animals !== undefined) {
      const availableAnimalsEl = document.querySelector(".available-animals-count");
      if (availableAnimalsEl) {
        availableAnimalsEl.textContent = data.available_animals;
      }
    }

    // Update pending reservations
    if (data.pending_reservations !== undefined) {
      const pendingReservationsEl = document.querySelector(".pending-reservations-count");
      if (pendingReservationsEl) {
        pendingReservationsEl.textContent = data.pending_reservations;
      }
    }

    // Update total users
    if (data.total_users !== undefined) {
      const totalUsersEl = document.querySelector(".total-users-count");
      if (totalUsersEl) {
        totalUsersEl.textContent = data.total_users;
      }
    }

    // Update monthly revenue
    if (data.monthly_revenue !== undefined) {
      const monthlyRevenueEl = document.querySelector(".monthly-revenue-count");
      if (monthlyRevenueEl) {
        monthlyRevenueEl.textContent = "₱" + data.monthly_revenue.toLocaleString();
      }
    }

    // Update pending deliveries
    if (data.pending_deliveries !== undefined) {
      const pendingDeliveriesEl = document.querySelector(".pending-deliveries-count");
      if (pendingDeliveriesEl) {
        pendingDeliveriesEl.textContent = data.pending_deliveries;
      }
    }

    // Update notification badges
    if (data.unread_notifications !== undefined) {
      this.updateNotificationBadge(data.unread_notifications);
    }

    // Add visual feedback for updates
    this.showUpdateIndicator();
  }

  /**
   * Show visual indicator that data was updated
   */
  showUpdateIndicator() {
    const indicators = document.querySelectorAll(".stat-card");
    indicators.forEach((card) => {
      card.classList.add("updated");
      setTimeout(() => {
        card.classList.remove("updated");
      }, 2000);
    });
  }

  /**
   * Update last update indicator
   */
  updateLastUpdateIndicator() {
    // Last update indicator removed as requested
    // This method is kept for compatibility but does nothing
  }

  /**
   * Update notification badge
   */
  updateNotificationBadge(count) {
    const badge = document.getElementById("notificationBadge");
    if (badge) {
      if (count > 0) {
        badge.textContent = count;
        badge.style.display = "inline";
        badge.classList.add("notification-badge");
      } else {
        badge.style.display = "none";
      }
    }
  }

  /**
   * Start notification checking
   */
  startNotificationCheck() {
    // Check for notifications every 15 seconds
    this.notificationInterval = setInterval(() => {
      if (this.isActive && document.visibilityState === "visible") {
        this.checkNotifications();
      }
    }, 15000);
  }

  /**
   * Check for new notifications
   */
  async checkNotifications() {
    try {
      const response = await fetch("/api/notifications", {
        method: "GET",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      });

      if (response.ok) {
        const data = await response.json();
        if (data.new_notifications > 0) {
          this.showNotificationBadge(data.new_notifications);
          this.playNotificationSound();
        }
      }
    } catch (error) {
      console.log("Notification check failed:", error);
    }
  }

  /**
   * Show notification badge
   */
  showNotificationBadge(count) {
    const notificationIcon = document.querySelector(".notification-icon");
    if (notificationIcon) {
      let badge = notificationIcon.querySelector(".notification-badge");
      if (!badge) {
        badge = document.createElement("span");
        badge.className = "notification-badge";
        notificationIcon.appendChild(badge);
      }
      badge.textContent = count;
      badge.style.display = "block";
    }
  }

  /**
   * Play notification sound
   */
  playNotificationSound() {
    // Create and play a subtle notification sound
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);

    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
    oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);

    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);

    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.2);
  }

  /**
   * Setup page visibility handling
   */
  setupVisibilityHandling() {
    document.addEventListener("visibilitychange", () => {
      if (document.visibilityState === "visible") {
        this.isActive = true;
        // Refresh data when page becomes visible
        this.updateDashboardData();
      } else {
        this.isActive = false;
      }
    });
  }

  /**
   * Setup connection status monitoring
   */
  setupConnectionStatus() {
    // Connection status monitoring removed as requested
    // This method is kept for compatibility but does nothing
  }

  /**
   * Cleanup intervals
   */
  destroy() {
    if (this.updateInterval) {
      clearInterval(this.updateInterval);
    }
    if (this.notificationInterval) {
      clearInterval(this.notificationInterval);
    }
  }
}

// Initialize real-time functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  window.realtimeManager = new RealtimeManager();
});

// Cleanup on page unload
window.addEventListener("beforeunload", () => {
  if (window.realtimeManager) {
    window.realtimeManager.destroy();
  }
});

// Add CSS for notification badges
const style = document.createElement("style");
style.textContent = `
  .notification-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: #ff6b35;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.1);
    }
    100% {
      transform: scale(1);
    }
  }
`;
document.head.appendChild(style);
