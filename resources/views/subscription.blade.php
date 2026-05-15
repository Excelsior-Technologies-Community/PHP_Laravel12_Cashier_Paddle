<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

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
                        priceId: "pri_01kmj83vr4kwsjq00x61ya46kn",
                        quantity: 1
                    }
                ],

                customer: {
                    email: "{{ auth()->user()->email }}"
                },

                customData: {
                    user_id: "{{ auth()->id() }}"
                }
            });
        }
    </script>
</head>

<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-screen flex items-center justify-center p-6">

<div class="max-w-lg w-full bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-2xl p-8 text-white">

    <h1 class="text-4xl font-bold text-center mb-3">
        🚀 Premium Plan
    </h1>

    <p class="text-center text-gray-300 mb-8">
        Unlock powerful premium features
    </p>

    @if(session('success'))

        <div class="bg-green-500/20 border border-green-500 text-green-200 p-3 rounded-lg mb-5">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-500/20 border border-red-500 text-red-200 p-3 rounded-lg mb-5">
            {{ session('error') }}
        </div>

    @endif

    <div class="text-center mb-8">

        <span class="text-5xl font-extrabold">
            ₹200
        </span>

        <span class="text-gray-300">
            /month
        </span>

    </div>

    <ul class="space-y-4 mb-8">

        <li>✅ Unlimited access</li>
        <li>✅ Premium dashboard</li>
        <li>✅ Priority support</li>
        <li>✅ Advanced analytics</li>

    </ul>

    @if(!$subscribed)

        <button onclick="openCheckout()"
            class="w-full bg-indigo-600 hover:bg-indigo-700 transition duration-300 py-4 rounded-xl font-semibold text-lg shadow-lg">
            Subscribe Now
        </button>

    @else

        <div class="space-y-4">

            <div class="bg-green-500/20 border border-green-500 p-4 rounded-xl text-center">
                ✅ Active Subscription
            </div>

            <a href="{{ route('premium') }}"
                class="block text-center bg-emerald-600 hover:bg-emerald-700 py-3 rounded-xl font-semibold">
                Access Premium Page
            </a>

            <form method="POST" action="{{ route('subscription.cancel') }}">
                @csrf

                <button
                    class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-xl font-semibold">
                    Cancel Subscription
                </button>
            </form>

        </div>

    @endif

</div>

</body>
</html>