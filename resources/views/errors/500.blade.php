@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center space-y-8">
        <div>
            <h1 class="text-9xl font-extrabold text-primary-600 dark:text-primary-500">500</h1>
            <h2 class="mt-6 text-3xl font-bold text-gray-900 dark:text-white">Server Error</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Oops, something went wrong on our end. We are working to fix this issue as quickly as possible.
            </p>
        </div>
        <div class="mt-8 space-x-4">
            <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                Try Again
            </button>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                Return Home
            </a>
        </div>
    </div>
</div>
@endsection
