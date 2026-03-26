<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Paddle -->
    <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>

    <script>
        Paddle.Environment.set('sandbox');

        Paddle.Initialize({
            token: "{{ env('PADDLE_CLIENT_SIDE_TOKEN') }}"
        });

        function openCheckout() {
            Paddle.Checkout.open({
                items: [
                    {
                        priceId: "pri_xxxxxxxxxxxxxxxxxxxx",
                        quantity: 1
                    }
                ]
            });
        }
    </script>
</head>

<body class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100 min-h-screen flex items-center justify-center">

    <!-- Card -->
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-md w-full text-center">

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            🚀 Go Premium
        </h1>

        <p class="text-gray-500 mb-6">
            Unlock all features and enjoy a seamless experience
        </p>

        <!-- Pricing -->
        <div class="mb-6">
            <span class="text-4xl font-extrabold text-indigo-600">₹200</span>
            <span class="text-gray-500">/month</span>
        </div>

        <!-- Features -->
        <ul class="text-left space-y-3 mb-6">
            <li class="flex items-center gap-2 text-gray-700">
                ✅ Unlimited access
            </li>
            <li class="flex items-center gap-2 text-gray-700">
                ✅ Priority support
            </li>
            <li class="flex items-center gap-2 text-gray-700">
                ✅ Premium features
            </li>
        </ul>

        <!-- Button -->
        <button onclick="openCheckout()"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition duration-300 shadow-lg">
            Subscribe Now
        </button>

        <!-- Footer -->
        <p class="text-xs text-gray-400 mt-4">
            Secure payment powered by Paddle
        </p>

    </div>

</body>
</html>