@extends('www.layouts.default')

@section('title', 'Shopping cart')

@section('head')
@stop

@section('content')
    <h1>Checkout (Step 1)</h1>

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
                    <h3>{{ CartProvider::instance()->getSubtotal() }} €</h3>
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
                    <h3>{{ CartProvider::instance()->getDiscountAmount() }} €</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <h4>Total Tax:</h4>
                </div>
                <div class="col-md-5">
                    <h3>{{ CartProvider::instance()->getTaxAmount() }} €</h3>
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
                    <h3>{{ CartProvider::instance()->getTotal() }} €</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h4>Shipping:</h4>
        </div>
    </div>
    <div class="row">
        <br>
        <div class="col-md-12">
            <a class="btn btn-primary" href="{{ route('checkout-' . user_lang()) }}">Checkout</a>
        </div>
    </div>
@stop