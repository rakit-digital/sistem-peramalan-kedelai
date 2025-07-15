document.addEventListener("DOMContentLoaded", function () {
    // Initialize Weight Trend Chart
    initWeightTrendChart();
    
    // Initialize Body Composition Chart
    initBodyCompositionChart();
});

// Weight Trend Chart
function initWeightTrendChart() {
    const chartElement = document.getElementById('weight-trend-chart');
    if (!chartElement) return;
    
    const weightTrendDates = JSON.parse(chartElement.getAttribute("data-dates") || '[]');
    const weightTrendValues = JSON.parse(chartElement.getAttribute("data-weights") || '[]');
    
    const weightTrendOptions = {
        series: [{
            name: 'Berat Badan',
            data: weightTrendValues,
            color: "var(--color-primary)"
        }],
        chart: {
            fontFamily: "inherit",
            height: 350,
            type: "area",
            toolbar: {
                show: false
            },
            offsetX: -15
        },
        grid: {
            borderColor: "transparent",
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val ? val.toFixed(1) + " kg" : "";
            },
            textAnchor: 'middle',
            style: {
                fontSize: '12px',
                fontWeight: 600,
                colors: ['#333']
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                padding: 4,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.7
            },
            offsetY: -10
        },
        stroke: {
            width: 3,
            curve: "smooth",
        },
        xaxis: {
            categories: weightTrendDates,
            labels: {
                style: {
                    colors: Array(weightTrendDates.length).fill('var(--color-bodytext)'),
                    fontSize: '12px'
                },
                rotate: -45,
                rotateAlways: false
            },
            title: {
                text: "Tanggal",
                offsetY: -5,
                style: {
                    fontWeight: 500
                }
            }
        },
        yaxis: {
            title: {
                text: "Berat Badan (kg)",
                style: {
                    fontWeight: 500
                }
            },
            labels: {
                formatter: function(val) {
                    return val.toFixed(1);
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        },
        markers: {
            size: 4,
            strokeWidth: 2,
            hover: {
                size: 6
            }
        },
        tooltip: {
            theme: "dark",
            y: {
                formatter: function(val) {
                    return val ? val.toFixed(1) + " kg" : "No data";
                }
            }
        }
    };

    const weightTrendChart = new ApexCharts(document.querySelector("#weight-trend-chart"), weightTrendOptions);
    weightTrendChart.render();
}

// Body Composition Chart
function initBodyCompositionChart() {
    const chartElement = document.getElementById('body-composition-chart');
    if (!chartElement) return;
    
    const bodyCompositionDates = JSON.parse(chartElement.getAttribute("data-dates") || '[]');
    const bodyFatData = JSON.parse(chartElement.getAttribute("data-body-fat") || '[]');
    const bellyFatData = JSON.parse(chartElement.getAttribute("data-belly-fat") || '[]');
    const muscleMassData = JSON.parse(chartElement.getAttribute("data-muscle-mass") || '[]');
    
    const bodyCompositionOptions = {
        series: [
            {
                name: 'Lemak Tubuh (%)',
                data: bodyFatData,
                color: "#EC4899" // Pink color
            },
            {
                name: 'Lemak Perut (%)',
                data: bellyFatData,
                color: "#F97316" // Orange color
            },
            {
                name: 'Massa Otot (kg)',
                data: muscleMassData,
                color: "#10B981" // Green color
            }
        ],
        chart: {
            fontFamily: "inherit",
            type: 'line',
            height: 350,
            width: '100%',
            toolbar: {
                show: false
            },
            offsetX: -10,
            redrawOnWindowResize: true
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 3,
            curve: 'smooth',
        },
        grid: {
            borderColor: "transparent",
        },
        xaxis: {
            categories: bodyCompositionDates,
            labels: {
                style: {
                    colors: Array(bodyCompositionDates.length).fill('var(--color-bodytext)'),
                    fontSize: '12px'
                },
                rotate: -45,
                rotateAlways: false
            },
            title: {
                text: "Tanggal",
                offsetY: -5,
                style: {
                    fontWeight: 500
                }
            }
        },
        yaxis: {
            title: {
                text: "Nilai",
                style: {
                    fontWeight: 500
                }
            },
            labels: {
                formatter: function(val) {
                    return val.toFixed(1);
                }
            }
        },
        legend: {
            show: false, // Hide default legend as we have custom HTML legend
            position: 'top',
            horizontalAlign: 'center',
            fontSize: '14px',
            markers: {
                width: 12,
                height: 12,
                radius: 6
            },
            itemMargin: {
                horizontal: 15,
                vertical: 8
            }
        },
        tooltip: {
            theme: "dark",
            y: {
                formatter: function(val, { seriesIndex }) {
                    if (seriesIndex === 2) {
                        return val.toFixed(1) + " kg";
                    } else {
                        return val.toFixed(1) + "%";
                    }
                }
            },
            shared: true,
            intersect: false
        },
        markers: {
            size: 4,
            strokeWidth: 2,
            hover: {
                size: 6
            }
        }
    };

    const bodyCompositionChart = new ApexCharts(document.querySelector("#body-composition-chart"), bodyCompositionOptions);
    bodyCompositionChart.render();
}
