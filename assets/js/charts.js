
document.addEventListener('DOMContentLoaded', function() {
    // Check if configuration is available
    if (typeof dashboardConfig !== 'undefined') {
        // Color palette for services
        const serviceColors = [
            '#1A56DB', // Blue
            '#7E3AF2', // Purple
            '#F05252', // Red
            '#F59E0B', // Yellow
            '#10B981', // Green
            '#3F83F8'  // Light Blue
        ];

        // Initialize grid chart if element exists
        if (document.getElementById("grid-chart") && typeof ApexCharts !== 'undefined') {
            const chartOptions = {
                grid: {
                    show: true,
                    strokeDashArray: 4,
                    padding: {
                        left: 10,
                        right: 2,
                        top: 0
                    },
                },
                series: dashboardConfig.serviceChartData.map((service, index) => ({
                    name: service.name,
                    data: service.data,
                    color: serviceColors[index % serviceColors.length]
                })),
                chart: {
                    height: "100%",
                    maxWidth: "100%",
                    type: "area",
                    fontFamily: "Inter, sans-serif",
                    dropShadow: {
                        enabled: false,
                    },
                    toolbar: {
                        show: false,
                    },
                },
                tooltip: {
                    enabled: true,
                    x: {
                        show: false,
                    },
                    y: {
                        formatter: function (value) {
                            return '₱' + value.toFixed(2);
                        }
                    }
                },
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    itemMargin: {
                        horizontal: 8,
                        vertical: 8
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    width: 4,
                    curve: 'smooth'
                },
                xaxis: {
                    categories: dashboardConfig.chartLabels,
                    labels: {
                        show: true,
                    },
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                },
                yaxis: {
                    show: true,
                    labels: {
                        formatter: function (value) {
                            return '₱' + value.toFixed(2);
                        }
                    }
                },
            };

            const chart = new ApexCharts(document.getElementById("grid-chart"), chartOptions);
            chart.render();
        }

        // Initialize column chart if element exists
        if (document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
            const columnOptions = {
                colors: ["#1A56DB", "#FDBA8C"],
                series: [{
                    name: "Product Sales",
                    color: "#1A56DB",
                    data: dashboardConfig.columnChartData
                }],
                chart: {
                    type: "bar",
                    height: "320px",
                    fontFamily: "Inter, sans-serif",
                    toolbar: {
                        show: false,
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "70%",
                        borderRadiusApplication: "end",
                        borderRadius: 8,
                    },
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    style: {
                        fontFamily: "Inter, sans-serif",
                    },
                    y: {
                        formatter: function (value) {
                            return '₱' + value.toFixed(2);
                        }
                    }
                },
                states: {
                    hover: {
                        filter: {
                            type: "darken",
                            value: 1,
                        },
                    },
                },
                stroke: {
                    show: true,
                    width: 0,
                    colors: ["transparent"],
                },
                grid: {
                    show: false,
                    strokeDashArray: 4,
                    padding: {
                        left: 2,
                        right: 2,
                        top: -14
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: false,
                },
                xaxis: {
                    floating: false,
                    labels: {
                        show: true,
                        style: {
                            fontFamily: "Inter, sans-serif",
                            cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                        }
                    },
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                },
                yaxis: {
                    show: false,
                },
                fill: {
                    opacity: 1,
                },
            };

            const columnChart = new ApexCharts(document.getElementById("column-chart"), columnOptions);
            columnChart.render();
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Inventory Chart if element exists
    if (document.getElementById("inventory-chart") && typeof ApexCharts !== 'undefined' && window.productChartData) {
        const { products, stocks, categories } = window.productChartData;
        
        // Generate color based on stock level
        function getStockColor(stock) {
            if (stock > 10) return "#10B981";  // Green for good stock
            if (stock > 5) return "#F59E0B";   // Yellow for low stock
            return "#EF4444";                  // Red for very low stock
        }
        
        // Prepare series data with dynamic colors
        const seriesData = products.map((product, index) => ({
            x: product,
            y: stocks[index],
            fillColor: getStockColor(stocks[index]),
            category: categories[index]
        }));
        
        const inventoryChartOptions = {
            series: [{
                name: "Stock Level",
                data: seriesData
            }],
            chart: {
                type: "bar",
                height: "320px",
                fontFamily: "Inter, sans-serif",
                toolbar: { show: false },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        const productName = config.w.config.series[0].data[config.dataPointIndex].x;
                        const stockLevel = config.w.config.series[0].data[config.dataPointIndex].y;
                        console.log(`Selected ${productName} with ${stockLevel} in stock`);
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "60%",
                    borderRadiusApplication: "end",
                    borderRadius: 6,
                    colors: {
                        ranges: [{
                            from: 0,
                            to: 5,
                            color: '#EF4444'
                        }, {
                            from: 6,
                            to: 10,
                            color: '#F59E0B'
                        }, {
                            from: 11,
                            to: 9999,
                            color: '#10B981'
                        }]
                    }
                }
            },
            tooltip: {
                enabled: true,
                custom: function({ series, seriesIndex, dataPointIndex, w }) {
                    const product = w.config.series[0].data[dataPointIndex].x;
                    const stock = w.config.series[0].data[dataPointIndex].y;
                    const category = w.config.series[0].data[dataPointIndex].category;
                    
                    return `
                    <div class="bg-white shadow-lg rounded-lg p-4 border border-gray-200">
                        <div class="font-bold text-gray-800">${product}</div>
                        <div class="text-sm text-gray-600">${category}</div>
                        <div class="mt-2 flex items-center">
                            <span class="inline-block w-3 h-3 rounded-full mr-2" 
                                   style="background-color: ${getStockColor(stock)}"></span>
                            <span class="font-semibold">Stock: ${stock}</span>
                        </div>
                    </div>
                    `;
                }
            },
            xaxis: {
                categories: products,
                labels: {
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-gray-500'
                    },
                    formatter: function(value) {
                        return value.length > 12 ? value.substring(0, 10) + '...' : value;
                    }
                },
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: {
                show: true,
                labels: {
                    style: {
                        fontFamily: "Inter, sans-serif",
                        cssClass: 'text-xs font-normal fill-gray-500'
                    }
                },
                title: {
                    text: "Quantity",
                    style: {
                        cssClass: 'text-xs font-normal fill-gray-500'
                    }
                }
            },
            responsive: [{
                breakpoint: 640,
                options: {
                    plotOptions: {
                        bar: {
                            columnWidth: "40%",
                        }
                    },
                    xaxis: {
                        labels: {
                            rotate: -45
                        }
                    }
                }
            }]
        };

        const inventoryChart = new ApexCharts(document.getElementById("inventory-chart"), inventoryChartOptions);
        inventoryChart.render();
    }

    // Your existing chart initialization code can remain below
    if (typeof dashboardConfig !== 'undefined') {
        // ... rest of your existing chart code ...
    }
});
