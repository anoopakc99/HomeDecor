<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function download($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->with('items.product')
            ->firstOrFail();

        $data = [
            'order' => $order,
            'title' => 'Invoice - ' . $order->order_number,
            'date' => date('m/d/Y'),
        ];

        $pdf = Pdf::loadView('front.user.invoice', $data);
        $pdf->setOption(['isRemoteEnabled' => true]);

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
