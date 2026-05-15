<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Premium Content</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-10 rounded-2xl shadow-xl text-center">

        <h1 class="text-4xl font-bold text-green-600 mb-4">
            🎉 Premium Access Granted
        </h1>

        <p class="text-gray-600 text-lg">
            You are now a premium subscriber.
        </p>

        <a href="{{ route('dashboard') }}"
           class="inline-block mt-6 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg">
            Back Dashboard
        </a>

    </div>

</body>
</html>