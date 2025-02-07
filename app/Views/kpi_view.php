<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPI Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center fw-semibold text-primary-emphasis">Dashboard KPI</h2>
        <div class="row mt-4">
            <div class="col-md-6">
                <canvas id="kpiChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="tasklistChart"></canvas>
            </div>
        </div>
    </div>
    
    <script>
        const kpiData = <?php echo json_encode($kpi_data); ?>;
        const tasklistData = <?php echo json_encode($tasklist_data); ?>;

        const labels = kpiData.map(item => item.Nama);
        const pencapaianSales = kpiData.map(item => item.Pencapaian_Sales);
        const pencapaianReport = kpiData.map(item => item.Pencapaian_Report);
        const totalBobotSales = kpiData.map(item => item.Total_Bobot_Sales);
        const totalBobotReport = kpiData.map(item => item.Total_Bobot_Report);
        const skorKpi = kpiData.map(item => item.Skor_Kpi);

        const karyawan = tasklistData.map(k => k.Nama);
        const persentaseOntime = tasklistData.map(k => k.Persentase_Ontime);
        const persentaseLate = tasklistData.map(k => k.Persentase_Late);

        new Chart(document.getElementById('kpiChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pencapaian Sales (%)',
                        data: pencapaianSales,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pencapaian Report (%)',
                        data: pencapaianReport,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Bobot Sales (%)',
                        data: totalBobotSales,
                        backgroundColor: 'rgba(218, 181, 33, 0.2)',
                        borderColor: 'rgb(163, 189, 104)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Bobot Report (%)',
                        data: totalBobotReport,
                        backgroundColor: 'rgba(203, 161, 89, 0.2)',
                        borderColor: 'rgb(102, 127, 255)',
                        borderWidth: 1
                    },
                    {
                        label: 'Skor KPI',
                        data: skorKpi,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('tasklistChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: karyawan,
                datasets: [
                    {
                        label: 'Tasklist Ontime (%)',
                        data: persentaseOntime,
                        backgroundColor: 'rgba(46, 135, 114, 0.2)',
                        borderColor: 'rgb(85, 192, 106)',
                        borderWidth: 1
                    },
                    {
                        label: 'Tasklist Late (%)',
                        data: persentaseLate,
                        backgroundColor: 'rgba(78, 45, 161, 0.2)',
                        borderColor: 'rgb(102, 127, 255)',
                        borderWidth: 1
                    }
                ]
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
