@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Update Settings</h3>
                    </div>

                    <div class="card-body">
                        <form class="ajax-form" method="POST" id="create-form">
                            @csrf

                            <div class="row">
                                @if (is_null($settings) || (!is_null($settings) && ($settings->hide_cron_message == 0 || now()->diffInHours($settings->last_cron_run) > 48)))
                                    <div class="col-md-12">
                                        <div class="alert alert-primary">
                                            <h6>Set following cron command on your server (Ignore if already done)</h6>
                                            @php
                                                try {
                                                    $phpPath = PHP_BINDIR . '/php';
                                                } catch (\Throwable $th) {
                                                    $phpPath = 'php';
                                                }
                                                echo '<code>* * * * * ' . $phpPath . ' ' . base_path() . '/artisan schedule:run >> /dev/null 2>&1</code>';
                                            @endphp
                                            <div class="mt-3"><strong>Note:</strong>
                                                <ins>{{ $phpPath }}</ins> in
                                                above command is the path of PHP on your server.
                                                Please enter the correct PHP path to make it work</div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name">Envato Username</label>
                                        <input type="text" class="form-control" id="envato_username"
                                            name="envato_username" value="{{ $settings->envato_username ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="product_link">Envato Personal Token</label>
                                        <input name="envato_api_key" id="envato_api_key" class="form-control"
                                            value="{{ $settings->envato_api_key ?? '' }}">
                                        <a href="https://build.envato.com/create-token" target="_blank">Get Access Token</a>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_link">Your Timezone</label>
                                        <select name="timezone" id="timezone" class="form-control">
                                            @foreach ($timezones as $tz)
                                                <option @if ($settings && $settings->timezone == $tz) selected @endif>
                                                    {{ $tz }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="product_link">Logo</label>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="logo" name="logo"
                                                    aria-describedby="inputGroupFileAddon01">
                                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" name="daily_email" type="checkbox"
                                                id="daily_email"
                                                {{ $settings && $settings->daily_email ? 'checked' : '' }}>
                                            <label class="form-check-label" for="daily_email">
                                                Receive Daily Sales Summary Email
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="save-form" class="btn btn-success">Submit</button>
                            <a href="{{ url()->previous() }}" class="btn btn-light ml-1">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script>
        $('body').on('click', '#save-form', function() {
            $.easyAjax({
                url: "{{ route('settings.store') }}",
                container: '#create-form',
                type: "POST",
                redirect: true,
                file: true,
                data: $('#create-form').serialize()
            });
        })
    </script>
@endpush
