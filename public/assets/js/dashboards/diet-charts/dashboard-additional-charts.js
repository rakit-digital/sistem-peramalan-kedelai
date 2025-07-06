document.addEventListener("DOMContentLoaded", function () {
    // Initialize all new visualization charts
    initBMIDistributionChart();
    initProgramStatusChart();
    initAgeDistributionChart();
});

// BMI Distribution Chart
function initBMIDistributionChart() {
    const chartElement = document.getElementById('bmi-distribution-chart');
    if (!chartElement) return;
    
    const underweight = parseInt(chartElement.getAttribute('data-underweight')) || 0;
    const normal = parseInt(chartElement.getAttribute('data-normal')) || 0;
    const overweight = parseInt(chartElement.getAttribute('data-overweight')) || 0;
    const obese = parseInt(chartElement.getAttribute('data-obese')) || 0;
    
    const totalBMIs = underweight + normal + overweight + obese;
    
    const options = {
        series: [underweight, normal, overweight, obese],
        chart: {
            type: 'donut',
            height: 350,
            fontFamily: "inherit",
            foreColor: "#adb0bb",
        },
        labels: ['Kekurangan BB', 'Normal', 'Kelebihan BB', 'Obesitas'],
        colors: ["#4F46E5", "#10B981", "#F59E0B", "#EF4444"],
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    background: 'transparent',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '16px',
                            color: undefined,
                            offsetY: -10,
                        },
                        value: {
                            show: true,
                            fontSize: '20px',
                            fontWeight: 600,
                            color: undefined,
                            offsetY: 16,
                            formatter: function (val) {
                                return val;
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '16px',
                            fontWeight: 600,
                            color: "#7C8FAC",
                            formatter: function (w) {
                                return totalBMIs;
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: false,
        },
        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '14px',
            markers: {
                width: 12,
                height: 12,
                radius: 6,
            },
            itemMargin: {
                horizontal: 8,
                vertical: 8
            },
        },
        tooltip: {
            theme: "dark",
            fillSeriesColor: false,
            y: {
                formatter: function(value, { seriesIndex, dataPointIndex, w }) {
                    const percent = Math.round((value / totalBMIs) * 100);
                    return `${value} (${percent}%)`;
                }
            }
        }
    };
    
    new ApexCharts(chartElement, options).render();
}

// Program Status Distribution Chart
function initProgramStatusChart() {
    const chartElement = document.getElementById('program-status-chart');
    if (!chartElement) return;
    
    const statusLabels = JSON.parse(chartElement.getAttribute('data-labels') || '[]');
    const statusValues = JSON.parse(chartElement.getAttribute('data-values') || '[]');
    
    const options = {
        series: statusValues,
        chart: {
            type: 'pie',
            height: 350,
            fontFamily: "inherit",
        },
        labels: statusLabels,
        colors: ['var(--color-info)', 'var(--color-success)', 'var(--color-error)', 'var(--color-warning)'],
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        tooltip: {
            theme: "dark",
            y: {
                formatter: function(value) {
                    return value;
                }
            }
        }
    };
    
    new ApexCharts(chartElement, options).render();
}

// Age Distribution Chart
function initAgeDistributionChart() {
    const chartElement = document.getElementById('age-distribution-chart');
    if (!chartElement) return;
    
    const ageLabels = JSON.parse(chartElement.getAttribute('data-labels') || '[]');
    const ageValues = JSON.parse(chartElement.getAttribute('data-values') || '[]');
    const detailedBreakdown = JSON.parse(chartElement.getAttribute('data-detailed-breakdown') || '{}');
    
    const totalCustomers = ageValues.reduce((acc, curr) => acc + curr, 0);
    
    const options = {
        series: [{
            name: 'Customers',
            data: ageValues
        }],
        chart: {
            type: 'bar',
            height: 350,
            fontFamily: "inherit",
            foreColor: "#adb0bb",
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 4,
                distributed: true,
            },
        },
        colors: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#6366F1', '#EC4899'],
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: false
        },
        grid: {
            borderColor: "rgba(0,0,0,0.1)",
            strokeDashArray: 3,
            xaxis: {
                lines: {
                    show: false,
                },
            },
        },
        xaxis: {
            categories: ageLabels,
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            }
        },
        yaxis: {
            title: {
                text: 'Jumlah Pelanggan',
            },
            tickAmount: 5,
            min: 0,
            forceNiceScale: true,
            labels: {
                formatter: function(val) {
                    return Math.floor(val);
                }
            },
            // Ensure only integers are shown on y-axis
            decimalsInFloat: 0,
        },
        tooltip: {
            theme: "dark",
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                // Get the age group data and its detailed breakdown
                const ageGroup = w.globals.categoryLabels[dataPointIndex];
                const value = series[0][dataPointIndex];
                const percent = totalCustomers > 0 ? Math.round((value / totalCustomers) * 100) : 0;
                
                // Get the detailed breakdown for this age group
                const breakdown = detailedBreakdown[dataPointIndex] || {};
                
                let detailedHTML = '';
                
                // Sort ages numerically 
                const sortedAges = Object.keys(breakdown).map(Number).sort((a, b) => a - b);
                
                // Create detailed breakdown HTML
                if (sortedAges.length > 0) {
                    detailedHTML = '<div class="mt-2 pt-2 border-t border-gray-700">';
                    detailedHTML += '<div class="font-medium mb-1">Detail:</div>';
                    
                    sortedAges.forEach(age => {
                        const count = breakdown[age];
                        detailedHTML += `<div class="grid grid-cols-2 gap-2">
                            <span class="text-left">${age} tahun:</span> 
                            <span class="text-right">${count} pelanggan</span>
                        </div>`;
                    });
                    
                    detailedHTML += '</div>';
                }
                
                // Return tooltip HTML
                return `<div class="bg-gray-800 p-3 rounded shadow-lg">
                    <div class="font-medium mb-2">${ageGroup}</div>
                    <div class="text-lg font-semibold">${Math.round(value)} pelanggan (${percent}%)</div>
                    ${detailedHTML}
                </div>`;
            }
        }
    };
    
    new ApexCharts(chartElement, options).render();
}
