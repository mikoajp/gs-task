@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">Overview of service requests and inspections.</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Inspections Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg card-hover border border-gray-100">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-blue-50 p-3">
                                <i class="fa-solid fa-clipboard-check text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500">Active Inspections</dt>
                                <dd>
                                    <div class="text-3xl font-bold text-gray-900">{{ $stats['inspections_count'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('inspections') }}" class="font-medium text-blue-700 hover:text-blue-900">
                            View all inspections <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Failures Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg card-hover border border-gray-100">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-red-50 p-3">
                                <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500">Reported Failures</dt>
                                <dd>
                                    <div class="text-3xl font-bold text-gray-900">{{ $stats['failures_count'] }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('failures') }}" class="font-medium text-red-700 hover:text-red-900">
                            View all failures <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- System Status Card -->
             <div class="bg-white overflow-hidden shadow rounded-lg card-hover border border-gray-100">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-md bg-green-50 p-3">
                                <i class="fa-solid fa-server text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="truncate text-sm font-medium text-gray-500">System Status</dt>
                                <dd>
                                    <div class="flex items-center mt-1">
                                         <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Operational</span>
                                    </div>
                                    @if($stats['last_updated'])
                                    <p class="mt-2 text-xs text-gray-400">
                                        Updated: {{ date('M d, H:i', $stats['last_updated']) }}
                                    </p>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Section -->
        <div class="bg-white shadow sm:rounded-lg border border-gray-100">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Import Data</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>Upload a JSON source file to process new messages and update the system.</p>
                </div>
                <form action="{{ route('process') }}" method="POST" enctype="multipart/form-data" class="mt-5">
                    @csrf
                    <div class="flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10 hover:bg-gray-50 transition-colors">
                        <div class="text-center">
                            <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-300"></i>
                            <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                    <span>Upload a file</span>
                                    <input id="file-upload" name="source_file" type="file" accept=".json" class="sr-only" onchange="document.getElementById('file-name').innerText = this.files[0].name">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs leading-5 text-gray-600">JSON files up to 2MB</p>
                            <p id="file-name" class="mt-2 text-sm font-semibold text-gray-900"></p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                         <button type="submit" class="inline-flex items-center gap-x-2 rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                             <i class="fa-solid fa-gears"></i> Process File
                         </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection