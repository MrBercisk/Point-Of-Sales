<?php
namespace App\Http\Controllers;
 
use App\Models\Order;
use App\Services\ReceiptService;
use Illuminate\Http\Response;
 
class ReceiptController extends Controller
{
    public function download(Order $order, ReceiptService $receiptService): Response
    {
        $pdfContent = $receiptService->getPdfContent($order->load('items.product'));
 
        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$order->invoice_number}.pdf\"",
        ]);
    }
}
