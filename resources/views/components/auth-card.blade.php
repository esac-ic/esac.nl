@props(['heading', 'subheading'])
<x-menu />

<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="card card-lg text-center mt-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h1 class="h2 mb-2">{{ $heading }}</h1>
                            <span>{{ $subheading }}</span>
                        </div>
                        <div class="row no-gutters justify-content-center">
                            @session('status')
                                <div class="alert alert-success">
                                    {{ $value }}
                                </div>
                            @endsession
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
