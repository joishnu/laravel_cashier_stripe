@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
    <div class="alert alert-success">
        {{ session('error') }}
    </div>
@endif
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ $product->description }}</p>
                    <p class="card-text">${{ $product->price }}</p>
                    
                    <form action="{{ route('products.purchase', ['product' => $product->id]) }}" method="POST" id="payment-form">
                        @csrf
                        <div class="form-group">
                            <label for="card-element">Credit or debit card</label>
                            <div id="card-element">
                                <!-- Stripe Element will be inserted here -->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ env('STRIPE_KEY') }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const { paymentMethod, error } = await stripe.createPaymentMethod('card', cardElement);

        if (error) {
            // Display error in the payment form
        } else {
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method');
            hiddenInput.setAttribute('value', paymentMethod.id);
            form.appendChild(hiddenInput);

            form.submit();
        }
    });
</script>
@endsection