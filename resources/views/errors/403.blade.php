<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Unauthorized</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="h-full bg-gray-50 flex items-center justify-center">
    <div class="text-center">
        <div class="text-6xl font-bold text-red-500 mb-4">403</div>
        <h1 class="text-2xl font-semibold text-gray-900 mb-2">Unauthorized Access</h1>
        <p class="text-gray-500 mb-6">You don't have permission to access this page.</p>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>
</body>
</html>
