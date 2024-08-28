<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Product;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function show(int $id)
    {
        return view('products.show', ['product' => Product::findOrFail($id)]);
    }

    public function purchase(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $product->price * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $request->input('payment_method'),
                'confirmation_method' => 'manual',
                'confirm' => true, // Attempt to confirm the payment immediately
                'return_url' => route('products.index')
            ]);

            // Check the status of the PaymentIntent
            if ($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_source_action') {
                // Additional authentication required, send client_secret to the frontend
                return redirect()->route('products.show', $product->id)
                                 ->with('requires_action', true)
                                 ->with('payment_intent_client_secret', $paymentIntent->client_secret);
            } elseif ($paymentIntent->status === 'succeeded') {
                // Payment was successful
                return redirect()->route('products.index')->with('success', 'Purchase successful! Thank you for your order.');
            }
        } catch (\Exception $e) {
            return redirect()->route('products.show', $product->id)
                             ->with(['error' => 'Payment failed: ' . $e->getMessage()]);
        }
    }
}
