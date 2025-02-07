<?php namespace App\Models;

use CodeIgniter\Model;

class KpiModel extends Model
{
    protected $table = 'kpi_marketing';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id','nama_tasklist','id_karyawan', 'id_kpi', 'deadline', 'aktual'];

    public function getKpiData()
    {
        $builder = $this->db->query("
            WITH target_kpi AS (
                SELECT id_rules, target, bobot, late FROM rules_kpi
            )
            SELECT
                ka.nama_karyawan AS Nama,

                -- Ambil Target Sales dari tabel aturan KPI
                kr_sales.target AS Target_Sales,
                SUM(CASE WHEN kp.id_kpi = 1 THEN 1 ELSE 0 END) AS Actual_Sales,

                -- Pencapaian Sales dengan perhitungan dinamis
                ROUND(
                (SUM(CASE WHEN kp.id_kpi = 1 THEN 1 ELSE 0 END) / kr_sales.target) * 100, 0
                ) AS Pencapaian_Sales,
                
                -- Ambil Target Sales dari tabel aturan KPI
                kr_sales.bobot AS Bobot_Sales,
                
                -- Jika ada keterlambatan, tampilkan pengurangan bobot
                CASE
                    WHEN SUM(CASE WHEN kp.id_kpi = 1 AND km.aktual > km.deadline THEN 1 ELSE 0 END) > 0 THEN kr_sales.late
                    ELSE 0
                END AS Late_Sales,
                
                -- Hitung Total Bobot Sales
                ROUND(
                (SUM(CASE WHEN kp.id_kpi = 1 THEN 1 ELSE 0 END) / MAX(kr_sales.target)) * MAX(kr_sales.bobot), 0
                ) AS Total_Bobot_Sales,

                -- Ambil Target Report dari tabel aturan KPI
                kr_report.target AS Target_Report,
                SUM(CASE WHEN kp.id_kpi = 2 THEN 1 ELSE 0 END) AS Actual_Report,

                -- Pencapaian Report dengan perhitungan dinamis
                ROUND(
                (SUM(CASE WHEN kp.id_kpi = 2 THEN 1 ELSE 0 END) / kr_report.target) * 100, 0
                ) AS Pencapaian_Report,
                
                -- Ambil Target Report dari tabel aturan KPI
                kr_report.bobot AS Bobot_Report,
                
                -- Jika ada keterlambatan, tampilkan pengurangan bobot
                CASE
                    WHEN SUM(CASE WHEN kp.id_kpi = 2 AND km.aktual > km.deadline THEN 1 ELSE 0 END) > 0 THEN kr_report.late
                    ELSE 0
                END AS Late_Report,
                
                -- Hitung Total Bobot Report
                ROUND(
                (SUM(CASE WHEN kp.id_kpi = 2 THEN 1 ELSE 0 END) / MAX(kr_report.target)) * MAX(kr_report.bobot), 0
                ) AS Total_Bobot_Report,
                
                -- Hitung Skor KPI Sales (Total Bobot + Late)
                ROUND(
                ((SUM(CASE WHEN kp.id_kpi = 1 THEN 1 ELSE 0 END) / MAX(kr_sales.target)) * MAX(kr_sales.bobot)
                + (SUM(CASE WHEN kp.id_kpi = 2 THEN 1 ELSE 0 END) / MAX(kr_sales.target)) * MAX(kr_report.bobot)
                + CASE
                    WHEN SUM(CASE WHEN kp.id_kpi = 1 AND km.aktual > km.deadline THEN 1 ELSE 0 END) > 0 THEN kr_sales.late
                    WHEN SUM(CASE WHEN kp.id_kpi = 2 AND km.aktual > km.deadline THEN 1 ELSE 0 END) > 0 THEN kr_report.late
                    ELSE 0
                    END), 0
                ) AS Skor_Kpi

            FROM kpi_marketing km
            JOIN karyawan ka ON km.id_karyawan = ka.id_karyawan
            JOIN kpi kp ON km.id_kpi = kp.id_kpi

            -- Menggunakan JOIN ke target_kpi untuk mengambil target sales dan report
            LEFT JOIN target_kpi kr_sales ON kr_sales.id_rules = 1
            LEFT JOIN target_kpi kr_report ON kr_report.id_rules = 2

            GROUP BY ka.id_karyawan;
        ");
        return $builder->getResultArray(); // Mengembalikan hasil query sebagai array
    }

    public function getTasklistData()
    {
        $builder = $this->db->query("
            SELECT
                ka.nama_karyawan AS Nama,
                
                -- Hitung jumlah tasklist ontime
                COUNT(CASE WHEN km.aktual <= km.deadline THEN 1 ELSE NULL END) AS Tasklist_Ontime,
                
                -- Hitung jumlah tasklist late
                COUNT(CASE WHEN km.aktual > km.deadline THEN 1 ELSE NULL END) AS Tasklist_Late,
                
                -- Hitung total tasklist
                COUNT(*) AS Total_Tasklist,

                -- Hitung persentase tasklist ontime
                ROUND(
                (COUNT(CASE WHEN km.aktual <= km.deadline THEN 1 ELSE NULL END) / COUNT(*)) * 100, 2
                ) AS Persentase_Ontime,

                -- Hitung persentase tasklist late
                ROUND(
                (COUNT(CASE WHEN km.aktual > km.deadline THEN 1 ELSE NULL END) / COUNT(*)) * 100, 2
                ) AS Persentase_Late
                
            FROM kpi_marketing km
            JOIN karyawan ka ON km.id_karyawan = ka.id_karyawan

            GROUP BY ka.id_karyawan;
        ");
        return $builder->getResultArray(); // Mengembalikan hasil query sebagai array
    }
}
