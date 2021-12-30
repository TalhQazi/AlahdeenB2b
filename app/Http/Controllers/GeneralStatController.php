<?php

namespace App\Http\Controllers;

use App\Models\GeneralStat;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class GeneralStatController extends Controller
{
  use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $generalStats = GeneralStat::first();

      return $this->success([
        'no_of_rfqs' => !empty($generalStats->no_of_rfqs) ? $generalStats->no_of_rfqs : 0,
        'no_of_categories' => !empty($generalStats->no_of_categories) ? $generalStats->no_of_categories : 0,
        'no_of_active_suppliers' => !empty($generalStats->no_of_active_suppliers) ? $generalStats->no_of_active_suppliers : 0,
      ]);

    }

}
