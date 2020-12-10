@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => 'https://marmelade-app.fr'])
            <img src="https://images.marmelade-app.fr/landing/md_1_1578679073.png" width="300" alt="Marmelade">
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} Marmelade SAS. {{ __('All rights reserved') }}
            <p><em><a href="https://marmelade-app.fr/contact">{{ __('Contact') }}</a>&#xA0;• <a href="https://marmelade-app.fr/faq">{{ __('FAQ') }}</a>&#xA0;• </em>&#xA0;<a href="https://marmelade-app.fr/CGU">{{ __('Terms of Use') }}</a>&#xA0;• <a href="https://marmelade-app.fr/legal-notice">{{ __('Legal') }}</a></p>
        @endcomponent
    @endslot
@endcomponent
