@php
    // Simple, printer-friendly receipt inspired by provided design
    // Uses $receipt_details supplied by TransactionUtil::getReceiptDetails()
@endphp
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $receipt_details->invoice_no ?? 'Receipt' }}</title>
    <style>
        body{ font-family: 'Courier New', Courier, monospace; color:#000; font-size:12px; }
        .center { text-align:center; }
        .logo { max-height:100px; display:block; margin:0 auto 6px auto; }
        .sep { border-top:1px dashed #000; margin:8px 0; }
        .items { width:100%; }
        .item-row { margin-bottom:4px; }
        .item-code { display:block; font-weight:600; }
        .item-desc { display:block; }
        .price { float:right; }
        .totals { width:100%; margin-top:8px; }
        .totals .row { display:block; clear:both; padding:4px 0; }
        .totals .label { float:left; }
        .totals .value { float:right; }
        .payments { margin-top:8px; }
        .small { font-size:11px; }
        .thank { margin-top:8px; font-weight:700; }
        .barcode img{ display:block; margin:8px auto 0 auto; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="center">
        @if(!empty($receipt_details->logo) && empty($receipt_details->letter_head))
            <img src="{{ $receipt_details->logo }}" class="logo" alt="logo">
        @endif

        @if(!empty($receipt_details->display_name))
            <div style="font-size:14px; font-weight:700;">{{ $receipt_details->display_name }}</div>
        @endif

        @if(!empty($receipt_details->address))
            <div class="small">{!! nl2br($receipt_details->address) !!}</div>
        @endif

        @if(!empty($receipt_details->contact))
            <div class="small">{{ $receipt_details->contact }}</div>
        @endif
    </div>

    <div class="sep"></div>

    <div class="center small">{{ $receipt_details->invoice_date ?? '' }}</div>

    <div class="sep"></div>

    <div class="small clearfix">
        <div style="float:left;">Reg: {{ $receipt_details->register ?? '' }}</div>
        <div style="float:right;">{{ $receipt_details->customer_label ?? '' }}: {{ $receipt_details->customer_info ?? '' }}</div>
    </div>

    <div class="sep"></div>

    <div class="items">
        @foreach($receipt_details->lines as $line)
            <div class="item-row">
                <span class="item-code"># {{ $line['sku'] ?? $line['product_id'] ?? '' }}
                    <span class="price">{{ $line['line_total'] }}</span>
                </span>
                <span class="item-desc">{{ $line['name'] }} {{ $line['product_variation'] ?? '' }} {{ $line['variation'] ?? '' }}</span>
            </div>
        @endforeach
    </div>

    <div class="sep"></div>

    <div class="totals small">
        @if(!empty($receipt_details->subtotal))
            <div class="row clearfix"><span class="label">Subtotal</span><span class="value">{{ $receipt_details->subtotal }}</span></div>
        @endif
        @if(!empty($receipt_details->tax) )
            <div class="row clearfix"><span class="label">Tax</span><span class="value">{{ $receipt_details->tax }}</span></div>
        @endif
        <div class="row clearfix" style="margin-top:6px;"><span class="label" style="font-weight:700;">Total</span><span class="value" style="font-weight:700;">{{ $receipt_details->total }}</span></div>
    </div>

    <div class="sep"></div>

    <div class="payments small">
        @if(!empty($receipt_details->payments))
            @foreach($receipt_details->payments as $payment)
                <div class="clearfix">
                    <div style="float:left;">{{ $payment['method'] }}</div>
                    <div style="float:right;">{{ $payment['amount'] }}</div>
                </div>
                @if(!empty($payment['card_number']) || !empty($payment['card_type']) || !empty($payment['card_transaction_number']) )
                    <div class="small" style="margin-top:4px;">
                        @if(!empty($payment['card_number']))
                            <div>Card number	 <span style="float:right">{{ $payment['card_number'] }}</span></div>
                        @endif
                        @if(!empty($payment['card_type']))
                            <div>Card type	 <span style="float:right">{{ ucfirst($payment['card_type']) }}</span></div>
                        @endif
                        @if(!empty($payment['card_transaction_number']))
                            <div>Reference #	 <span style="float:right">{{ $payment['card_transaction_number'] }}</span></div>
                        @endif
                        @if(!empty($payment['date']))
                            <div>Date/time	 <span style="float:right">{{ $payment['date'] }}</span></div>
                        @endif
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    <div class="sep"></div>

    <div class="center thank">THANK YOU<br/>HAVE A NICE DAY</div>

    @if(!empty($receipt_details->show_barcode) && !empty($receipt_details->invoice_no))
        <div class="barcode">
            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2,40,array(0,0,0), true)}}" alt="barcode">
        </div>
    @endif

</body>
</html>
