@if (request()->routeIs('home'))
<section class="site-header site-header-home bg-dark text-white border-bottom">
    @if ($headerSlide)
        <img alt="" src="{{ $headerSlide->getFirstMediaUrl('image', 'wide') }}" class="site-header-image">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-9 col-lg-5">
                    <h1 class="display-3">{{ $headerSlide->heading }}</h1>
                    <p class="lead">{{ $headerSlide->message }}</p>
                    <a href="{{ $headerSlide->link }}" class="btn btn-accent btn-lg">More information</a>
                </div>
            </div>
        </div>
    @endif
</section>
@else
<section class="site-header bg-dark text-white">
    @if ($headerSlide)
        <img alt="" src="{{ $headerSlide->getFirstMediaUrl('image', 'wide') }}" class="site-header-image">
    @endif
</section>
@endif
