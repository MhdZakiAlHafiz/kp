// File: public/js/dashboard-charts.js

// Fungsi untuk Chart Status Proyek
function initStatusProyekChart(statusData) {
    // Set font default untuk Chart.js
    (Chart.defaults.global.defaultFontFamily = "Nunito"),
        '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = "#858796";

    var ctx = document.getElementById("statusProyekChart");
    if (!ctx) return;

    var labels = statusData.labels || [];
    var data = statusData.data || [];
    var backgroundColors = [
        "#4e73df",
        "#1cc88a",
        "#36b9cc",
        "#f6c23e",
        "#e74a3b",
        "#858796",
        "#5a5c69",
    ];
    var hoverBackgroundColors = [
        "#2e59d9",
        "#17a673",
        "#2c9faf",
        "#dda20a",
        "#c73021",
        "#60626b",
        "#37383e",
    ];

    var myPieChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: labels,
            datasets: [
                {
                    data: data,
                    backgroundColor: backgroundColors.slice(0, data.length),
                    hoverBackgroundColor: hoverBackgroundColors.slice(
                        0,
                        data.length
                    ),
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false,
            },
            cutoutPercentage: 80,
        },
    });

    // Generate label manual
    var labelContainer = document.getElementById("status-labels");
    if (labelContainer) {
        labelContainer.innerHTML = "";
        labels.forEach((label, i) => {
            labelContainer.innerHTML += `
        <span class="mr-2">
          <i class="fas fa-circle" style="color:${backgroundColors[i]}"></i> ${label}
        </span>
      `;
        });
    }
}

// Fungsi untuk Chart Owner Proyek
function initOwnerProyekChart(ownerData) {
    var ctx = document.getElementById("ownerProyekChart");
    if (!ctx) return;

    var labels = ownerData.labels || [];
    var data = ownerData.data || [];
    var backgroundColors = [
        "#e74a3b",
        "#858796",
        "#f6c23e",
        "#1cc88a",
        "#36b9cc",
        "#4e73df",
        "#5a5c69",
    ];
    var hoverBackgroundColors = [
        "#c73021",
        "#60626b",
        "#dda20a",
        "#17a673",
        "#2c9faf",
        "#2e59d9",
        "#37383e",
    ];

    var myPieChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: labels,
            datasets: [
                {
                    data: data,
                    backgroundColor: backgroundColors.slice(0, data.length),
                    hoverBackgroundColor: hoverBackgroundColors.slice(
                        0,
                        data.length
                    ),
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false,
            },
            cutoutPercentage: 80,
        },
    });

    // Generate label manual
    var labelContainer = document.getElementById("owner-labels");
    if (labelContainer) {
        labelContainer.innerHTML = "";
        labels.forEach((label, i) => {
            labelContainer.innerHTML += `
        <span class="mr-2">
        <i class="fas fa-circle" style="color:${backgroundColors[i]}"></i> ${label}
        </span>
    `;
        });
    }
}
