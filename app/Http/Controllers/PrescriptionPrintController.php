<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\View\View;

class PrescriptionPrintController extends Controller
{
    public function __invoke(Prescription $prescription): View
    {
        $prescription->load(['patient', 'doctor']);

        return view('prescriptions.print', [
            'prescription' => $prescription,
        ]);
    }
}

