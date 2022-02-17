<?php

namespace App\Controllers\Ven;

use App\Controllers\BaseController;
use Datatables_server_side;
class Ven023 extends BaseController
{

    public function __construct(){}
    public function index()
    {
        $data = [
            "arquivo_dataTable" => true,
        ];
        $this->load_template($data);
    }
}