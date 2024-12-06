document.addEventListener('DOMContentLoaded', fetchResults);

async function fetchResults() {
    try {
        const response = await fetch('../api/fetch_results.php');
        const data = await response.json();

        // Populate the table
        const resultsTableBody = document.getElementById('resultsTableBody');
        resultsTableBody.innerHTML = '';

        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.student_name}</td>
                <td>${row.score}</td>
                <td>${row.correct_answers}</td>
                <td>${row.total_questions}</td>
                <td>${row.percentage}%</td>
            `;
            resultsTableBody.appendChild(tr);
        });

        // Update the Chart
        const labels = data.map(row => row.student_name);
        const scores = data.map(row => row.percentage);
        updateChart(labels, scores);

    } catch (error) {
        console.error('Error fetching results:', error);
    }
}

// Define the Chart.js chart initialization here
function updateChart(labels, scores) {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Scores (%)',
                data: scores,
                backgroundColor: '#00a2ff',
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
}
