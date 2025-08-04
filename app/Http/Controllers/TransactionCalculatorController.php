<?php

namespace App\Http\Controllers;

use App\Models\TransactionCalculator;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class TransactionCalculatorController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'expressions' => 'required|array',
            'total' => 'required|integer',
        ]);
    
       $data = TransactionCalculator::create([
            'expressions' => $validated['expressions'],
            'total' => $validated['total'],
        ]);
    }

    public function test(Request $request)
    {
        $request->validate([
            'expressions' => 'required|array',
            'totalDisplay' => 'required|string',
            'total' => 'required|integer'
        ]);

        try {
            // Ganti "POS58" dengan nama printer kamu
            $connector = new WindowsPrintConnector("POS-58");
            $printer = new Printer($connector);

            // Header
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_EMPHASIZED);
            $printer->text("TOKO SETIA\n");
            
            $printer->selectPrintMode();           
            $printer->text(wordwrap("JL. Bangsri Guyangan", 32, "\n", true) . "\n");
            $printer->text(date("d/m/Y H:i") . "\n");
            $printer->text("--------------------------------\n");
            
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            foreach ($request->expressions as $detail) {
                $printer->text( $detail . "\n");
            }

            $printer->feed();

            $printer->text('Total: ' . $request->totalDisplay . "\n");

            $printer->cut();
            $printer->close();

            $this->store($request);

        } catch (\Exception $e) {
            return "Gagal print: " . $e->getMessage();
        }
    }
}
