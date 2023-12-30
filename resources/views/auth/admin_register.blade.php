@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input type="hidden" name="role" value="0">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('theme/js/jquery.min.js')}}"></script>
<script src="{{ URL::asset('theme/js/jquery.validate.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js" integrity="sha512-XZEy8UQ9rngkxQVugAdOuBRDmJ5N4vCuNXCh8KlniZgDKTvf7zl75QBtaVG1lEhMFe2a2DuA22nZYY+qsI2/xA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {
        $("#registerForm").validate({
            // ignore: [],
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true
                },
                password: {
                    required: true
                },
                password_confirmation: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter name"
                },
                email: {
                    required: "Please enter email"
                },
                password: {
                    required: "Please enter password"
                },
                password_confirmation: {
                    required: "Please enter confirm password"
                }
            },
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `invalid-feedback` class to the error element
                error.addClass("invalid-feedback");
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                }
                else {
                    error.insertAfter(element.after());
                }
            },
            submitHandler: function (form) {
                var formData = new FormData($("#registerForm")[0]);

            $.blockUI({ message: '<i class="fa fa-circle-o-notch fa-spin"></i>',css: {fontSize: '30px',backgroundColor: 'transperant', color: '#fff', border: 'none'} });
            $("button[type=submit]").attr("disabled",true);
            $.ajax({
                    url: $("#registerForm").attr("action"),
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        $.unblockUI();
                        if(typeof data.errors != 'undefined'){
                            toastr.error(data.message, 'Error');
                            $("button[type=submit]").attr("disabled",false);
                        }else{
                            toastr.success('Login Successfully', 'Success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                        return;

                    },
                    error: function(data, errorThrown)
                    {
                        $("button[type=submit]").attr("disabled",false);
                        $.unblockUI();
                        if(typeof data.responseText != 'undefined'){
                            try {
                                var error = JSON.parse(data.responseText);
                                if(typeof error.errors != 'undefined'){
                                    var message = Object.values(error.errors);
                                    message = message[0][0];
                                    toastr.error(message);
                                    return;
                                }
                                toastr.error(error.message);
                                if(error.message.toLowerCase().includes("csrf")){
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                    return;
                                }

                            }
                            catch(err) {
                                toastr.error("Something went wrong");
                            }

                        }else{
                            toastr.error("Something went wrong");
                        }

                    }
                });
            }
        });

    });

</script>
@endsection
