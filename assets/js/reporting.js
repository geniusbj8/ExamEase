// Function to generate reports based on filters
function generateReport() {
  // Placeholder: Fetch data based on filters (e.g., from a server or API)
  console.log("Report generated for selected filters.");
}

// Chart.js setup for Average Score Chart
const avgScoreCtx = document.getElementById('averageScoreChart').getContext('2d');
const averageScoreChart = new Chart(avgScoreCtx, {
  type: 'line',
  data: {
    labels: ['Student A', 'Student B', 'Student C', 'Student D'],
    datasets: [{
      label: 'Average Score (%)',
      data: [75, 82, 68, 90],
      borderColor: '#00a2ff',
      fill: false,
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
        max: 100
      }
    }
  }
});

// Chart.js setup for Top Performers Chart
const topPerformersCtx = document.getElementById('topPerformersChart').getContext('2d');
const topPerformersChart = new Chart(topPerformersCtx, {
  type: 'bar',
  data: {
    labels: ['Student A', 'Student B', 'Student C'],
    datasets: [{
      label: 'Scores (%)',
      data: [95, 88, 92],
      backgroundColor: ['#4caf50', '#ffa726', '#42a5f5'],
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
        max: 100
      }
    }
  }
});
