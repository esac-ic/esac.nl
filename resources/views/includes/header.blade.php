@php($header = collect([
    ['background' => 'header-img1.jpeg', 'heading' => 'Stunts and more', 'message' => 'With the Stuntcommittee we organize stunts during events such as abseiling, zip lining and climbing.', 'url' => route('pages.show', 'stunt')],
    ['background' => 'header-img2.jpeg', 'heading' => 'Ski mountaineering introduction', 'message' => 'Regularly, an introductory course to the ski mountaineering is organized during the winter week.', 'url' => route('pages.show', 'alpenwinter')],
    ['background' => 'header-img3.jpeg', 'heading' => 'Climbing weekends in Belgium', 'message' => 'The ESAC annually organizes about 12 climbing weekends to Belgium, Germany or beyond.', 'url' => route('pages.show', 'buitenklimmen')],
    ['background' => 'header-img4.jpeg', 'heading' => 'Climbing competition', 'message' => 'The EPlaKKa is held every year to determine who is the best climber of the ESAC.', 'url' => route('pages.show', 'klimwedstrijden')],
    ['background' => 'header-img5.jpeg', 'heading' => 'Ski in the Alps', 'message' => 'During the carnival holiday the ESAC leaves travels a week to the Alps to enjoy the snow.', 'url' => route('pages.show', 'alpenwinter')],
    ['background' => 'header-img7.jpeg', 'heading' => 'Mountaineering courses', 'message' => 'The NSAC offers courses at various levels with the ultimate goal of making alpine trips independently.', 'url' => route('pages.show', 'alpenzomer')],
    ['background' => 'header-img8.jpeg', 'heading' => 'Mountaineering', 'message' => 'Mountaineering is the king discipline in mountain sports where all facets come together.', 'url' => route('pages.show', 'alpenzomer')],
    ['background' => 'header-img9.jpeg', 'heading' => 'Fun!', 'message' => 'During and after climbing there is of course also place for good food and social activities.', 'url' => route('events.index')],
    ['background' => 'header-img10.jpeg', 'heading' => 'Bouldering', 'message' => 'In addition to sports climbing, we also do a lot of bouldering, including in Fontainebleau.', 'url' => route('events.index')],
])->random())
@if (request()->routeIs('home'))
<section class="bg-dark text-white height-70 border-bottom">
    <img alt="Header image" src="/img/{{ $header['background'] }}" class="bg-image opacity-70">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-9 col-lg-5">
                <h1 class="display-3 word-wrap-break-word">{{ $header['heading'] }}</h1>
                <p class="lead">{{ $header['message'] }}</p>
                <a href="{{ $header['url'] }}" class="btn btn-info btn-lg">More Information</a>
            </div>
        </div>
    </div>
</section>
@else
<section class="bg-dark text-white height-30">
    <img alt="Header image" src="/img/{{ $header['background'] }}" class="bg-image opacity-70">
</section>
@endif
