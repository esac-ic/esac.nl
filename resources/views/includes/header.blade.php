@if ($curPageName == "Home")
<section class="bg-dark text-white height-70 border-bottom">
    <img id="background" alt="Header image" src="" class="bg-image opacity-70">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-9 col-lg-5">
                <h1 id="headerTitle" class="display-3 word-wrap-break-word"></h1>
                <p id="headerText" class="lead"></p>
                <a id="headerButton" href="#" class="btn btn-info btn-lg"></a>
            </div>
        </div>
    </div>
</section>
@else
<section class="bg-dark text-white height-30">
    <img id="background" alt="Header image" src="" class="bg-image opacity-70">
</section>
@endif

@push('scripts')
<script>
var headerHome = [{
    background: 'header-img1.jpeg',
    heading: '{{'Stunts and more'}}',
    message: '{{'With the Stuntcommittee we organize stunts during events such as abseiling, zip lining and climbing.'}}',
    buttonUrl: '{{url('stunt')}}',
    buttonText: '{{'More Information'}}'
}, {
    background: 'header-img2.jpeg',
    heading: '{{'Ski mountaineering introduction'}}',
    message: '{{'Regularly, an introductory course to the ski mountaineering is organized during the winter week.'}}',
    buttonUrl: '{{url('courses')}}',
    buttonText: '{{'More Information'}}'
}, {
    background: 'header-img3.jpeg',
    heading: '{{'Climbing weekends in Belgium'}}',
    message: '{{'The ESAC annually organizes about 12 climbing weekends to Belgium, Germany or beyond.'}}',
    buttonUrl: '{{url('klimweekenden')}}',
    buttonText: '{{'More Information'}}'
}, {
    background: 'header-img4.jpeg',
    heading: '{{'Climbing competition'}}',
    message: '{{'The EPlaKKa is held every year to determine who is the best climber of the ESAC.'}}',
    buttonUrl: '{{url('eplakka')}}',
    buttonText: '{{'More Information'}}'
}, {
    background: 'header-img5.jpeg',
    heading: '{{'Ski in the Alps'}}',
    message: '{{'During the carnival holiday the ESAC leaves travels a week to the Alps to enjoy the snow.'}}',
    buttonUrl: '{{url('courses')}}',
    buttonText: '{{'More Information'}}'
},  {
    background: 'header-img7.jpeg',
    heading: '{{'Mountaineering courses'}}',
    message: '{{'The NSAC offers courses at various levels with the ultimate goal of making alpine trips independently.'}}',
    buttonUrl: '{{url('courses')}}',
    buttonText: '{{'More Information'}}'
}, {
    background: 'header-img8.jpeg',
    heading: '{{'Mountaineering'}}',
    message: '{{'Mountaineering is the king discipline in mountain sports where all facets come together.'}}',
    buttonUrl: '{{url('courses')}}',
    buttonText: '{{'More Information'}}'
}, {
    background: 'header-img9.jpeg',
    heading: '{{'Fun!'}}',
    message: '{{'During and after climbing there is of course also place for good food and social activities.'}}',
    buttonUrl: '{{url('agenda')}}',
    buttonText: '{{'More Information'}}'
}, {
    background: 'header-img10.jpeg',
    heading: '{{'Bouldering'}}',
    message: '{{'In addition to sports climbing, we also do a lot of bouldering, including in Fontainebleau.'}}',
    buttonUrl: '{{url('agenda')}}',
    buttonText: '{{'More Information'}}'
    }
];

var i = Math.floor(Math.random() * headerHome.length);
$('#background').attr('src', '/img/' + headerHome[i].background);
$('#headerTitle').html(headerHome[i].heading);
$('#headerText').html(headerHome[i].message);
$('#headerButton')
    .html(headerHome[i].buttonText)
    .attr('href',headerHome[i].buttonUrl);
</script>
@endpush