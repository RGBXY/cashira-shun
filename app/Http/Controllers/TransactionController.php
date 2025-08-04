<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class TransactionController extends Controller
{
    public function store(Request $request) {

        $validated = $request->validate([
            'cash' => 'required|integer',
            'total' => 'required|integer',
            'change' => 'required|integer',
            'cart' => 'required|array'
        ]);
    
        $transaction = Transaction::create([
            'cash' => $validated['cash'],
            'total' => $validated['total'],
            'change' => $validated['change'],
        ]);

        foreach($request->cart as $cart) {
            $transaction->details()->create([
                'transaction_id'    => $transaction->id,
                'product_id'     => $cart['id'],
                'qty'            => $cart['qty'],
                'unit'           => $cart['unit'],
                'price'          => $cart['price'],
            ]);
        }
    }


    public function searchByBarcode(Request $request) {
        $product = Product::where('barcode', $request->barcode)
                    ->first(); 

        return response()->json([
            'message' => 'Done',
            'product' => $product
        ]);
    }

    public function searchProductName(Request $request)
    {
        $products = Product::where('name', 'like', '%' . $request->name . '%')
                    ->limit(12)->get();  

       
        return response()->json([
            'message' => 'Done',
            'products' => $products
        ]);   
    }

    
    /**
     * Helper untuk menyusun teks kiri-kanan.
     */
    

    public function test(Request $request)
    {
        $validated = $request->validate([
            'cash' => 'required|integer',
            'total' => 'required|integer',
            'change' => 'required|integer',
            'cart' => 'required|array'
        ]);
    
        $transaction = Transaction::create([
            'cash' => $validated['cash'],
            'total' => $validated['total'],
            'change' => $validated['change'],
        ]);

        foreach($request->cart as $cart) {
            $transaction->details()->create([
                'transaction_id'    => $transaction->id,
                'product_id'     => $cart['id'],
                'name'           => $cart['name'],
                'qty'            => $cart['qty'],
                'unit'           => $cart['unit'],
                'price'          => $cart['price'],
            ]);
        }

        function justifyText($left, $right, $width = 32)
        {
            $left = (string) $left;
            $right = (string) $right;
            $space = $width - strlen($left) - strlen($right);

            return $left . str_repeat(" ", max(1, $space)) . $right;
        }

        try {
            // Ganti "POS-58" sesuai nama printer kamu
            $connector = new WindowsPrintConnector("POS-58");
            $printer = new Printer($connector);

            // === HEADER TOKO ===
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_EMPHASIZED);
            $printer->text("TOKO SETIA\n");

            $printer->selectPrintMode();
            $printer->text(wordwrap("JL. Bangsri Guyangan", 32, "\n", true) . "\n");
            $printer->text(date("d/m/Y") . "\n");
            $printer->text("--------------------------------\n");

            // === DAFTAR PRODUK ===
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text(justifyText("Nama Produk", "Total") . "\n");
            $printer->text("--------------------------------\n");

            $subtotal = 0;

            foreach ($request->cart as $product) {
                $name = $product['name'] ?? '-';
                $qty = $product['qty'] ?? 0;
                $price = $product['price'] ?? 0;
                $total = $qty * $price;
                $subtotal += $total;

                // Nama produk
                $printer->text(wordwrap($name, 32, "\n", true) . "\n");
                // Qty x Harga + total per item
                $printer->text(justifyText("{$qty} PCS x " . number_format($price), number_format($total)) . "\n");
            }

            // === TOTAL ===
            $printer->text("--------------------------------\n");
            $printer->text(justifyText("Subtotal", number_format($subtotal)) . "\n");
            $printer->text(justifyText("Cash", number_format($request->cash)) . "\n");
            $printer->text(justifyText("Change", number_format($request->change)) . "\n");
            $printer->text("--------------------------------\n");

            // Waktu
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text(date("d/m/Y") . "\n");

            $printer->feed(2);
            $printer->cut();
            $printer->close();

        } catch (\Exception $e) {
            return response()->json([
                "message" => "Gagal print: " . $e->getMessage()
            ], 500);
        }
    }

}
