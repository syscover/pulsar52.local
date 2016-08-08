@extends('www.layouts.default')

@section('title', 'Shopping cart')

@section('head')
    @parent
@stop

@section('content')
    <h1>Checkout (Step 2 - payment)</h1>

    <!-- heads -->
    <div class="row">
        <div class="col-md-3">
            <h5>Product</h5>
        </div>
        <div class="col-md-1">
            <h5>Price</h5>
        </div>
        <div class="col-md-1">
            <h5>Qty</h5>
        </div>
        <div class="col-md-1">
            <h5>Subtotal</h5>
        </div>
        <div class="col-md-1">
            <h5>Descuento</h5>
        </div>
        <div class="col-md-1">
            <h5>Sub + descuentos</h5>
        </div>
        <div class="col-md-1">
            <h5>Tax %</h5>
        </div>
        <div class="col-md-1">
            <h5>Tax €</h5>
        </div>
        <div class="col-md-1">
            <h5>Total</h5>
        </div>
    </div>
    <!-- /heads -->

    <form id="shoppingCartForm" action="{{ route('updateShoppingCart-' . user_lang()) }}" method="post">
        @foreach($cartItems as $item)
            <div class="row">
                <div class="col-md-1">
                    <img src="https://c.tadst.com/gfx/750w/sunrise-sunset-sun-calculator.jpg?1" class="img-responsive">
                </div>
                <div class="col-md-2">
                    <h4>{{ $item->name }}</h4>
                </div>
                <div class="col-md-1">
                    <h5>{{ $item->getPrice() }} € / unit</h5>
                </div>
                <div class="col-md-1">
                    <h5>{{ $item->getQuantity() }}</h5>
                </div>
                <div class="col-md-1">
                    <h4>{{ $item->getSubtotal() }} €</h4>
                </div>
                <div class="col-md-1">
                    <h4>{{ $item->getDiscountAmount() }} €</h4>
                </div>
                <div class="col-md-1">
                    <h4>{{ $item->getSubtotalWithDiscounts() }} €</h4>
                </div>
                <div class="col-md-1">
                    @foreach($item->getTaxRates() as $taxRate)
                        <h6>{{ $taxRate }} %</h6>
                    @endforeach
                </div>
                <div class="col-md-1">
                    <h4>{{ $item->getTaxAmount() }} €</h4>
                </div>
                <div class="col-md-1">
                    <h4>{{ $item->getTotal() }} €</h4>
                </div>
            </div>
        @endforeach
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="applyCouponCode">
    </form>
    <br><br><br><br>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-7">
                    <h4>Subtotal:</h4>
                </div>
                <div class="col-md-5">
                    <h4>{{ CartProvider::instance()->getSubtotal() }} €</h4>
                </div>
            </div>
            @foreach(CartProvider::instance()->getTaxRules() as $taxRule)
                <div class="row">
                    <div class="col-md-7">
                        <h5>{{ $taxRule->name }} ({{ $taxRule->getTaxRate() }}%)</h5>
                    </div>
                    <div class="col-md-5">
                        <h5>{{ $taxRule->getTaxAmount() }} €</h5>
                    </div>
                </div>
            @endforeach

            @foreach(CartProvider::instance()->getPriceRules() as $priceRule)
                <div class="row">
                    @if($priceRule->discountType == \Syscover\ShoppingCart\PriceRule::DISCOUNT_SUBTOTAL_PERCENTAGE || $priceRule->discountType == \Syscover\ShoppingCart\PriceRule::DISCOUNT_TOTAL_PERCENTAGE)
                    <div class="col-md-7">
                        <h5>{{ $priceRule->name }} ({{ $priceRule->getDiscountPercentage() }}%)</h5>
                    </div>
                    @endif
                    @if($priceRule->discountType == \Syscover\ShoppingCart\PriceRule::DISCOUNT_SUBTOTAL_FIXED_AMOUNT || $priceRule->discountType == \Syscover\ShoppingCart\PriceRule::DISCOUNT_TOTAL_FIXED_AMOUNT)
                        <div class="col-md-7">
                            <h5>{{ $priceRule->name }} ({{ $priceRule->getDiscountFixed() }} € )</h5>
                        </div>
                    @endif
                    <div class="col-md-5">
                        <h5>{{ $priceRule->getDiscountAmount() }}€</h5>
                    </div>
                </div>
            @endforeach

            <div class="row">
                <div class="col-md-7">
                    <h4>Total Discount:</h4>
                </div>
                <div class="col-md-5">
                    <h4>{{ CartProvider::instance()->getDiscountAmount() }} €</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <h4>Total Tax:</h4>
                </div>
                <div class="col-md-5">
                    <h4>{{ CartProvider::instance()->getTaxAmount() }} €</h4>
                </div>
            </div>
            {{--<div class="row">--}}
                {{--<div class="col-md-7">--}}
                    {{--<h4>Coste de envío:</h4>--}}
                {{--</div>--}}
                {{--<div class="col-md-5">--}}
                    {{--<h3>{{ CartProvider::instance()->getShippingAmount() }} €</h3>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-7">--}}
                    {{--<h4>Coupon name</h4>--}}
                {{--</div>--}}
                {{--<div class="col-md-5">--}}
                    {{--<h3>-9999€</h3>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="row">
                <div class="col-md-7">
                    <h4>Total:</h4>
                </div>
                <div class="col-md-5">
                    <h4>{{ CartProvider::instance()->getTotal() }} €</h4>
                </div>
            </div>
            <h3>Payment</h3>
            <form action="{{ route('postCheckout02-' . user_lang()) }}" method="post">
                @foreach($paymentMethods as $paymentMethod)
                    <div class="radio">
                        <label>
                            <input type="radio" name="paymentMethod" value="{{ $paymentMethod->id_115 }}">
                            {{ $paymentMethod->name_115 }}
                        </label>
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Pay</button>
            </form>
        </div>
        <div class="col-md-6">
            <h3>Shipping</h3>
            <div class="form-group">
                <label>Name</label><br>
                {{ $shipping['name'] }}
            </div>
            <div class="form-group">
                <label>Surname</label><br>
                {{ $shipping['surname'] }}
            </div>

            <div class="form-group">
                <label>Country</label><br>
                {{ $shipping['country'] }}
            </div>
            <div class="form-group">
                <label>??</label><br>
                {{ $shipping['territorialArea1'] }}
            </div>
            <div class="form-group">
                <label>??</label><br>
                {{ $shipping['territorialArea2'] }}
            </div>
            <div class="form-group">
                <label>??</label><br>
                {{ $shipping['territorialArea3'] }}
            </div>

            <div class="form-group">
                <label for="cp">CP</label><br>
                {{ $shipping['cp'] }}
            </div>
            <div class="form-group">
                <label for="address">Address</label><br>
                {{ $shipping['address'] }}
            </div>
        </div>
    </div>
@stop