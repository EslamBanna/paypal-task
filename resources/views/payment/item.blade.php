<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buy Now :)</title>

    <!-- Vendor Script -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    @include('payment.style')
    @notifyCss
</head>

<body>
    <div class="container">
        @include('notify::components.notify')
        <div class="imgBx">
            <img src=" {{ $item['item_image'] }} ">
        </div>
        <div class="details">
            <div class="content">
                <h2> {{ $item['item_title'] }} <br>
                </h2>
                <p> {{ $item['item_description'] }} </p>
                <p class="product-colors">Available Colors:
                    <span class="black active" data-color-primary="#000" data-color-sec="#212121"></span>
                    <span class="red" data-color-primary="#7E021C" data-color-sec="#bd072d"></span>
                    <span class="orange" data-color-primary="#CE5B39" data-color-sec="#F18557"></span>
                </p>
                <h3>Price: {{ $item['item_price'] . $item['item_currency'] }} </h3>
                <form method="POST" action="{{ route('payment.pay') }}">
                    @csrf
                    <input type="hidden" value="{{ $item['item_price'] }}" name="item_price" />
                    <input type="hidden" value="{{ $item['item_name'] }}" name="item_name" />
                    <input type="hidden" value="{{ $item['item_description'] }}" name="item_description" />
                    <input type="hidden" value="{{ $item['item_qty'] }}" name="item_qty" />
                    <button type="submit" id="buyBtn">Buy Now Using PayPal</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Change The Picture and Associated Element Color when Color Options Are Clicked.
        $(".product-colors span").click(function() {
            $(".product-colors span").removeClass("active");
            $(this).addClass("active");
            $(".active").css("border-color", $(this).attr("data-color-sec"))
            $("body").css("background", $(this).attr("data-color-primary"));
            $(".content h2").css("color", $(this).attr("data-color-sec"));
            $(".content h3").css("color", $(this).attr("data-color-sec"));
            $(".container .imgBx").css("background", $(this).attr("data-color-sec"));
            $(".container .details button").css("background", $(this).attr("data-color-sec"));
        });
    </script>
    @notifyJs
</body>

</html>
