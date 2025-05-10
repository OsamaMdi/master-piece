
// Chart.js Script for Admin Dashboard Charts

document.addEventListener('DOMContentLoaded', function () {
    // ========== Accounts Growth Chart ==========
    const accountsGrowthCtx = document.getElementById('accountsGrowthChart')?.getContext('2d');
    if (accountsGrowthCtx && typeof lastWeekAccounts !== 'undefined' && typeof thisWeekAccounts !== 'undefined') {
        new Chart(accountsGrowthCtx, {
            type: 'bar',
            data: {
                labels: ['Last Week', 'This Week'],
                datasets: [{
                    label: 'New Accounts',
                    data: [lastWeekAccounts, thisWeekAccounts],
                    backgroundColor: ['#e2e8f0', '#4a6cf7']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // ========== Products Growth Chart ==========
    const productsGrowthCtx = document.getElementById('productsGrowthChart')?.getContext('2d');
    if (productsGrowthCtx && typeof lastWeekProducts !== 'undefined' && typeof thisWeekProducts !== 'undefined') {
        new Chart(productsGrowthCtx, {
            type: 'bar',
            data: {
                labels: ['Last Week', 'This Week'],
                datasets: [{
                    label: 'New Products',
                    data: [lastWeekProducts, thisWeekProducts],
                    backgroundColor: ['#cbd5e0', '#38a169']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // ========== Reservations by Category Chart ==========
    const reservationsByCategoryCtx = document.getElementById('reservationsByCategoryChart')?.getContext('2d');
    if (reservationsByCategoryCtx && typeof categoryNames !== 'undefined' && typeof categoryReservationCounts !== 'undefined') {
        new Chart(reservationsByCategoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryNames,
                datasets: [{
                    label: 'Reservations',
                    data: categoryReservationCounts,
                    backgroundColor: [
                        '#4a6cf7', '#38a169', '#ed8936', '#e53e3e', '#805ad5', '#2b6cb0'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // ========== Reservations by Product (Grouped by Category) ==========
    const reservationsByProductCtx = document.getElementById('reservationsByProductChart')?.getContext('2d');
    if (reservationsByProductCtx && typeof productReservationLabels !== 'undefined' && typeof productReservationData !== 'undefined') {
        new Chart(reservationsByProductCtx, {
            type: 'bar',
            data: {
                labels: productReservationLabels,
                datasets: productReservationData // array of { label: categoryName, data: [reservationsCount], backgroundColor: '#color' }
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y}`;
                            }
                        }
                    }
                }
            }
        });
    }
});
