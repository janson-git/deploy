@extends('./layout.blade.php')

@section('content')
    <div class="pure-g">
        <div class="pure-u-1">
            <section class="top-page-nav">
                <a href="/user" class="pure-button btn-secondary-outline btn-s">
                    <i class="fa-solid fa-arrow-left"></i> {{ __('back_to_profile') }}
                </a>
            </section>
        </div>
    </div>

    <div class="pure-g">
        <div class="pure-u-1">
            <p>{{ $msg }}</p>

            @if ($user->getAccessToken())
                <p class="text-warning">Access token is uploaded. It will be replaced on this form submitting</p>
            @endif
            <form class="pure-form pure-form-aligned" method="post" autocomplete="off">
                <fieldset class="pure-group" >
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                    <input
                            type="text"
                            class="pure-input-1-2"
                            placeholder="Pivate Access Token"
                            name="token"
                            spellcheck="false"
                            style="font-size: small; width: 100%; font-family: monospace;">
                </fieldset>

                <button type="submit" class="pure-button pure-input-1-2 pure-button-primary">{{ __('save') }}</button>
            </form>
        </div>
    </div>
@endsection
