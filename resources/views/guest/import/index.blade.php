<x-guest-layout>
    @push('styles')
        <style>
            .pagination {
                font-size: 12px;
            }
            .badge {
                font-size: 12px;
            }
            .card-header {
                font-size: 16px;
            }
        </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import / Export') }}
        </h2>
    </x-slot>


    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        CSV Import
                    </div>

                    <div class="card-body">
                        <form class="flex items-center space-x-6" action="{{ route('importNew') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <div class="form-group">
                                        <input class="form-control"
                                               name="file"
                                               type="file"
                                               id="formFile"
                                        >
                                    </div>
                                </div>

                                <div class="col-2">
                                    <button type="submit" class="btn text-white btn-primary">Submit</button>
                                    <a href="{{ route('export') }}" class="btn btn-outline-primary">Export</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-3">
                                Last Import Error Log
                            </div>

{{--                            <div class="col text-end">--}}
{{--                                <span class="badge rounded-pill bg-primary">Records: {{ $totalRecords }}</span>--}}
{{--                                <span class="badge rounded-pill bg-success">Success: {{ $totalPassed }}</span>--}}
{{--                                <span class="badge rounded-pill bg-danger">Errors: {{ $totalFailure }}</span>--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col">--}}
{{--                                        <span class="badge rounded-pill bg-info">By: {{ $importedBy }}</span>--}}
{{--                                        <span class="badge rounded-pill bg-info">To: {{ $importedTo }}</span>--}}
{{--                                        <span class="badge rounded-pill bg-info">{{ $importedAt }}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                        </div>
                    </div>
                    <div class="card-body">

{{--                        <table class="table">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th scope="col">#</th>--}}
{{--                                <th scope="col">Row</th>--}}
{{--                                <th scope="col">Column</th>--}}
{{--                                <th scope="col">Message</th>--}}
{{--                                <th scope="col">Value</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @forelse($errors as $error)--}}
{{--                                <tr>--}}
{{--                                    <th scope="row">{{ $error['id'] }}</th>--}}
{{--                                    <td>{{ $error['row'] }}</td>--}}
{{--                                    <td>{{ $error['column'] }}</td>--}}
{{--                                    <td>{{ $error['message'] }}</td>--}}
{{--                                    <td>{{ $error['value'] }}</td>--}}
{{--                                </tr>--}}
{{--                            @empty--}}
{{--                                <tr>--}}
{{--                                    <th scope="row">{{ __('No Errors Found') }}</th>--}}
{{--                                </tr>--}}
{{--                            @endforelse--}}
{{--                            </tbody>--}}
{{--                        </table>--}}

{{--                        {{ $errors->links() }}--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>



