document.addEventListener("DOMContentLoaded", function () {
    // Diet Program Distribution Pie Chart
    if (document.getElementById("diet-program-chart")) {
        const weightGainCount = parseInt(document.getElementById("diet-program-chart").getAttribute("data-weight-gain")) || 0;
        const weightLossCount = parseInt(document.getElementById("diet-program-chart").getAttribute("data-weight-loss")) || 0;
        const fatLossCount = parseInt(document.getElementById("diet-program-chart").getAttribute("data-fat-loss")) || 0;
        const totalEnrollments = weightGainCount + weightLossCount + fatLossCount;
        
        const dietProgramOptions = {
            series: [weightGainCount, weightLossCount, fatLossCount],
            chart: {
                type: "donut",
                fontFamily: "inherit",
                foreColor: "#adb0bb",
                height: 350, 
            },
            colors: ["#4F46E5", "#10B981", "#F59E0B"],
            labels: [
                "Program Naik BB",
                "Program Turun BB",
                "Program Turun Lemak"
            ],
            plotOptions: {
                pie: {
                    donut: {
                        size: "65%", 
                        background: "transparent",
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: "16px", 
                                color: undefined,
                                offsetY: -10,
                            },
                            value: {
                                show: true,
                                fontSize: "20px", 
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
                                fontSize: "16px", 
                                fontWeight: 600,
                                color: "#7C8FAC", 
                                formatter: function (w) {
                                    return totalEnrollments;
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
                    horizontal: 10,
                    vertical: 8
                }
            },
            tooltip: {
                theme: "dark",
                fillSeriesColor: false,
            },
        };

        const dietProgramChart = new ApexCharts(document.querySelector("#diet-program-chart"), dietProgramOptions);
        dietProgramChart.render();
    }

    // Weight Trend Chart
    if (document.getElementById("weight-trend-chart")) {
        const chartElement = document.getElementById("weight-trend-chart");
        const weightTrendDates = JSON.parse(chartElement.getAttribute("data-dates") || '[]');
        const weightGainData = JSON.parse(chartElement.getAttribute("data-weight-gain") || '[]');
        const weightLossData = JSON.parse(chartElement.getAttribute("data-weight-loss") || '[]');
        const fatLossData = JSON.parse(chartElement.getAttribute("data-fat-loss") || '[]');
        const customerData = JSON.parse(chartElement.getAttribute("data-customer-data") || '[]');
        
        function generateDistinctColors(count) {
            const goldenRatioConjugate = 0.618033988749895;
            const colors = [];
            let h = Math.random(); 
            
            for (let i = 0; i < count; i++) {
                h += goldenRatioConjugate;
                h %= 1; 
                
                const hslH = Math.floor(h * 360);
                const hslS = 65 + Math.floor(Math.random() * 15); 
                const hslL = 45 + Math.floor(Math.random() * 10); 
                
                colors.push(`hsl(${hslH}, ${hslS}%, ${hslL}%)`);
            }
            
            return colors;
        }
        
        const customerColors = {};
        
        const programColors = {
            'weightGain': "var(--color-info)",
            'weightLoss': "var(--color-success)",
            'fatLoss': "var(--color-warning)"
        };

        const customerCount = customerData.length;
        const distinctColors = generateDistinctColors(Math.max(20, customerCount)); 
        
        if (customerData && customerData.length) {
            customerData.forEach((customer, index) => {
                customerColors[customer.id] = distinctColors[index % distinctColors.length];
            });
        }
        
        let initialSeries = [];
        
        if (customerData && customerData.length) {
            customerData.forEach(customer => {
                const customerWeights = weightTrendDates.map(date => customer.weights[date] || null);
                
                if (customerWeights.every(weight => weight === null)) {
                    return;
                }
                
                initialSeries.push({
                    name: customer.name,
                    data: customerWeights,
                    color: customerColors[customer.id]
                });
            });
        }
        
        if (initialSeries.length === 0) {
            initialSeries = [
                {
                    name: 'Program Naik BB',
                    data: weightGainData,
                    color: programColors['weightGain'],
                },
                {
                    name: 'Program Turun BB',
                    data: weightLossData,
                    color: programColors['weightLoss'],
                },
                {
                    name: 'Program Turun Lemak',
                    data: fatLossData,
                    color: programColors['fatLoss'],
                }
            ];
        }
        
        populateCustomerDropdown('all');
        
        // Weight trend chart options
        const weightTrendOptions = {
            series: initialSeries,
            chart: {
                height: 350,
                type: "line",
                fontFamily: "inherit",
                toolbar: {
                    show: false
                }
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
                width: 4,
                curve: "smooth",
                colors: ['transparent']
            },
            xaxis: {
                categories: weightTrendDates,
                labels: {
                    style: {
                        colors: Array(weightTrendDates.length).fill('var(--color-bodytext)'),
                        fontSize: '12px'
                    },
                    rotate: -45,
                    rotateAlways: false,
                    hideOverlappingLabels: true
                }
            },
            yaxis: {
                title: {
                    text: 'Berat (kg)',
                    style: {
                        fontWeight: 500
                    }
                },
                min: function(min) {
                    return min - 5 > 0 ? min - 5 : 0;
                }
            },
            fill: {
                opacity: 0.8
            },
            markers: {
                size: 6,
                hover: {
                    size: 8
                }
            },
            grid: {
                borderColor: "rgba(0,0,0,0.1)",
                strokeDashArray: 3,
                row: {
                    colors: ["transparent", "transparent"],
                    opacity: 0.5
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: false,
                offsetY: -2,
                offsetX: -5
            },
            tooltip: {
                theme: "dark",
                y: {
                    formatter: function (val) {
                        return val ? val + " kg" : "No data";
                    }
                }
            }
        };

        const weightTrendChart = new ApexCharts(document.querySelector("#weight-trend-chart"), weightTrendOptions);
        weightTrendChart.render();
        
        function populateCustomerDropdown(programFilter) {
            const customerFilter = document.getElementById('customerFilter');
            if (!customerFilter) return;
            
            const currentSelection = customerFilter.value;
            
            while (customerFilter.options.length > 1) {
                customerFilter.remove(1);
            }
            
            const customersByProgram = {
                'weightGain': [],
                'weightLoss': [],
                'fatLoss': []
            };
            
            customerData.forEach(customer => {
                if (programFilter === 'all' || customer.programType === programFilter) {
                    customersByProgram[customer.programType].push(customer);
                }
            });
            
            Object.keys(customersByProgram).forEach(programType => {
                customersByProgram[programType].sort((a, b) => a.name.localeCompare(b.name));
            });
            
            const programLabels = {
                'weightGain': 'Program Naik BB',
                'weightLoss': 'Program Turun BB',
                'fatLoss': 'Program Turun Lemak'
            };
            
            const programTypesToDisplay = programFilter === 'all' ? 
                Object.keys(customersByProgram) : 
                [programFilter];
            
            programTypesToDisplay.forEach(programType => {
                const customers = customersByProgram[programType];
                
                if (customers.length > 0) {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = programLabels[programType];
                    customerFilter.appendChild(optgroup);
                    
                    customers.forEach(customer => {
                        const option = document.createElement('option');
                        option.value = customer.id;
                        option.textContent = customer.name;
                        option.dataset.programType = programType;
                        customerFilter.appendChild(option);
                    });
                }
            });
            
            if (currentSelection !== 'all') {
                const exists = Array.from(customerFilter.options).some(option => option.value === currentSelection);
                if (exists) {
                    customerFilter.value = currentSelection;
                } else {
                    customerFilter.value = 'all';
                }
            }
        }
        
        function filterChartSeries(programFilter, customerId) {
            if (programFilter === 'all' && customerId === 'all') {
                return initialSeries;
            }
            
            if (customerId !== 'all') {
                const customer = customerData.find(c => c.id.toString() === customerId.toString());
                if (!customer) return [];
                
                const customerWeights = weightTrendDates.map(date => customer.weights[date] || null);
                return [{
                    name: customer.name,
                    data: customerWeights,
                    color: customerColors[customer.id]
                }];
            }
            
            if (programFilter !== 'all') {
                return initialSeries.filter(series => {
                    const customer = customerData.find(c => c.name === series.name);
                    return customer && customer.programType === programFilter;
                });
            }
            
            return initialSeries;
        }
        
        document.getElementById('programFilter').addEventListener('change', function() {
            const programFilter = this.value;
            
            populateCustomerDropdown(programFilter);
            
            const customerId = document.getElementById('customerFilter').value;
            const filteredSeries = filterChartSeries(programFilter, customerId);
            weightTrendChart.updateOptions({
                series: filteredSeries
            });
        });
        
        document.getElementById('customerFilter').addEventListener('change', function() {
            const customerId = this.value;
            const programFilter = document.getElementById('programFilter').value;
            const filteredSeries = filterChartSeries(programFilter, customerId);
            weightTrendChart.updateOptions({
                series: filteredSeries
            });
        });
    }
});