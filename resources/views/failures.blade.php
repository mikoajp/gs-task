@extends('layouts.app')

@section('content')
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-gray-900">Failure Reports</h1>
            <p class="mt-2 text-sm text-gray-700">Critical and routine failure reports requiring service attention.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('dashboard') }}" class="block rounded-md bg-white px-3 py-2 text-center text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Priority</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Service Date</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Contact</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($failures as $item)
                            <tr class="table-row-hover transition duration-150">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                    @if($item['priorytet'] === 'krytyczny')
                                        <span class="badge inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                            <i class="fa-solid fa-fire mr-1"></i> Critical
                                        </span>
                                    @elseif($item['priorytet'] === 'wysoki')
                                        <span class="badge inline-flex items-center rounded-md bg-orange-50 px-2 py-1 text-xs font-medium text-orange-800 ring-1 ring-inset ring-orange-600/20">
                                            <i class="fa-solid fa-arrow-up mr-1"></i> High
                                        </span>
                                    @else
                                        <span class="badge inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                            <i class="fa-solid fa-minus mr-1"></i> Normal
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span class="capitalize">{{ $item['status'] }}</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $item['termin wizyty serwisu'] ? date('M d, Y', strtotime($item['termin wizyty serwisu'])) : '-' }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-900 max-w-md break-words">
                                    {{ $item['opis'] }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    @if($item['numer telefonu osoby do kontaktu po stronie klienta'])
                                        <div class="flex items-center">
                                            <i class="fa-solid fa-phone text-gray-400 mr-2"></i>
                                            {{ $item['numer telefonu osoby do kontaktu po stronie klienta'] }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">No contact</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-10 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-regular fa-folder-open text-3xl mb-3 text-gray-300"></i>
                                        <p>No failure reports found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection