<?php namespace App\Controllers;

use App\Models\KpiModel;

class KpiController extends BaseController
{
    public function index()
    {
        $model = new KpiModel();
        $data['kpi_data'] = $model->getKpiData();
        $data['tasklist_data'] = $model->getTasklistData();
        
        return view('kpi_view', $data);
    }
}
