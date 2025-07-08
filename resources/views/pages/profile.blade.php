@extends('layout.main')

@push('script')
    <script>
        $('input#accountActivation').on('change', function () {
           $('button.deactivate-account').attr('disabled', !$(this).is(':checked'));
        });

        document.addEventListener('DOMContentLoaded', function (e) {
            (function () {
                // Update/reset user image of account page
                let accountUserImage = document.getElementById('uploadedAvatar');
                const fileInput = document.querySelector('.account-file-input'),
                    resetFileInput = document.querySelector('.account-image-reset');

                if (accountUserImage) {
                    const resetImage = accountUserImage.src;
                    fileInput.onchange = () => {
                        if (fileInput.files[0]) {
                            accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                        }
                    };
                    resetFileInput.onclick = () => {
                        fileInput.value = '';
                        accountUserImage.src = resetImage;
                    };
                }
            })();
        });
    </script>
@endpush

@section('content')
    <x-breadcrumb
        :values="[__('navbar.profile.profile')]">
    </x-breadcrumb>

    <div class="row">
        <div class="col">
            {{-- Tab --}}
            @if(auth()->user()->role == 'admin')
            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);">{{ __('navbar.profile.profile') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('settings.show') }}">{{ __('navbar.profile.settings') }}</a>
                </li>
            </ul>
            @endif

            <div class="card mb-4">
                <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Account -->
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ $data->profile_picture }}" alt="user-avatar"
                                 class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">{{ __('menu.general.upload') }}</span>
                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                    <input type="file" name="profile_picture" id="upload" class="account-file-input" hidden=""
                                           accept="image/png, image/jpeg">
                                </label>
                                <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">{{ __('menu.general.cancel') }}</span>
                                </button>

                                <p class="text-muted mb-0">< 800K (JPG, GIF, PNG)</p>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="col-md-6 col-lg-12">
                                <x-input-form name="name" :label="__('model.user.name')" :value="$data->name" />
                            </div>
                            <div class="col-md-6">
                                <x-input-form name="email" :label="__('model.user.email')" :value="$data->email" />
                            </div>
                            <div class="col-md-6">
                                <x-input-form name="phone" :label="__('model.user.phone')" :value="$data->phone ?? ''" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">{{ __('menu.general.update') }}</button>
                            <button type="reset" class="btn btn-outline-secondary">{{ __('menu.general.cancel') }}</button>
                        </div>
                    </div>
                    <!-- /Account -->
                </form>
            </div>

            {{-- Change Password Card --}}
            <div class="card mb-4">
                <h5 class="card-header">{{ __('navbar.profile.change_password') }}</h5>
                <div class="card-body">
                    <form method="POST" action="{{ url('/user/password') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-3 col-md-12 form-password-toggle">
                                <label class="form-label" for="current_password">{{ __('model.user.current_password') }}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="current_password" id="current_password"
                                           class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                           required autocomplete="current-password"/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('current_password', 'updatePassword')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password">{{ __('model.user.new_password') }}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                           required autocomplete="new-password"/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password', 'updatePassword')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password_confirmation">{{ __('model.user.confirm_password') }}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                           required autocomplete="new-password"/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password_confirmation', 'updatePassword')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <p class="text-muted mb-0">Minimum 8 characters</p> {{-- Assuming default Fortify rules, can be made dynamic if PasswordValidationRules are more complex --}}
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">{{ __('menu.general.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(auth()->user()->role == 'staff')
            <div class="card">
                <h5 class="card-header">{{ __('navbar.profile.deactivate_account') }}</h5>
                <div class="card-body">
                    <div class="mb-3 col-12 mb-0">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading fw-bold mb-1">{{ __('navbar.profile.deactivate_confirm_message') }}</h6>
                        </div>
                    </div>
                    <form id="formAccountDeactivation" action="{{ route('profile.deactivate') }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation">
                            <label class="form-check-label" for="accountActivation">{{ __('navbar.profile.deactivate_confirm') }}</label>
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account" disabled>{{ __('navbar.profile.deactivate_account') }}</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
