@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center space-y-8">
        <div>
            <h1 class="text-9xl font-extrabold text-primary-600 dark:text-primary-500">403</h1>
            <h2 class="mt-6 text-3xl font-bold text-gray-900 dark:text-white">Access Denied</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Sorry, you do not have permission to access this page. It seems this area is restricted.
            </p>
        </div>
        <div class="mt-8">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Homepage
            </a>
        </div>
    </div>
</div>
@endsection
