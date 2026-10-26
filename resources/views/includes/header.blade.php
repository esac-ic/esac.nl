@php($header = collect([
    ['background' => 'header-img1.jpeg', 'heading' => 'Stunts and more', 'message' => 'Our stunt committee organizes stunts such as abseiling, zip lining and climbing during events.', 'url' => route('pages.show', 'stunt')],
    ['background' => 'header-img2.jpeg', 'heading' => 'Ski mountaineering introduction', 'message' => 'An introductory ski mountaineering course is regularly organized during the winter week.', 'url' => route('pages.show', 'alpenwinter')],
    ['background' => 'header-img3.jpeg', 'heading' => 'Climbing weekends in Belgium', 'message' => 'Every year the ESAC organizes over 20 climbing weekends in Belgium, Germany or further afield.', 'url' => route('pages.show', 'buitenklimmen')],
    ['background' => 'header-img4.jpeg', 'heading' => 'Climbing competition', 'message' => 'The EPlaKKa is held every year to find out who is the best climber of the ESAC.', 'url' => route('pages.show', 'klimwedstrijden')],
    ['background' => 'header-img5.jpeg', 'heading' => 'Ski in the Alps', 'message' => 'During the carnival holiday the ESAC travels to the Alps for a week to enjoy the snow.', 'url' => route('pages.show', 'alpenwinter')],
    ['background' => 'header-img7.jpeg', 'heading' => 'Mountaineering courses', 'message' => 'The NSAC offers courses at various levels, with the goal of going on alpine trips on your own.', 'url' => route('pages.show', 'alpenzomer')],
    ['background' => 'header-img8.jpeg', 'heading' => 'Mountaineering', 'message' => 'Mountaineering is the king of mountain sports, where all its facets come together.', 'url' => route('pages.show', 'alpenzomer')],
    ['background' => 'header-img9.jpeg', 'heading' => 'Fun!', 'message' => 'During and after climbing there is of course also room for good food and social activities.', 'url' => route('events.index')],
    ['background' => 'header-img10.jpeg', 'heading' => 'Bouldering', 'message' => 'Besides sport climbing, we also do a lot of bouldering, including in Fontainebleau.', 'url' => route('events.index')],
])->random())
@if (request()->routeIs('home'))
<section class="site-header site-header-home bg-dark text-white border-bottom">
    <img alt="" src="{{ asset('img/'.$header['background']) }}" class="site-header-image">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-9 col-lg-5">
                <h1 class="display-3">{{ $header['heading'] }}</h1>
                <p class="lead">{{ $header['message'] }}</p>
                <a href="{{ $header['url'] }}" class="btn btn-info btn-lg">More information</a>
            </div>
        </div>
    </div>
</section>
@else
<section class="site-header bg-dark text-white">
    <img alt="" src="{{ asset('img/'.$header['background']) }}" class="site-header-image">
</section>
@endif
