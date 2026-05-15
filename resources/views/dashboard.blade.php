<x-app-layout>

    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-xl p-10">

                <h1 class="text-3xl font-bold mb-4">
                    Welcome {{ auth()->user()->name }}
                </h1>

                <p class="text-gray-600 mb-6">
                    Manage your subscriptions and premium features.
                </p>

                <a href="{{ route('subscription') }}"
                    class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl">
                    Manage Subscription
                </a>

            </div>

        </div>

    </div>

</x-app-layout>