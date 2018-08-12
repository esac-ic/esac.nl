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
<script type="text/javascript">
var headerHome = [{
    background: 'header-1.jpg',
    heading: '{{trans('front-end/home.headerMessages.stunt_title')}}',
    message: '{{trans('front-end/home.headerMessages.stunt_desc')}}',
    buttonUrl: '{{url('stunt')}}',
    buttonText: '{{trans('front-end/home.moreInfo')}}'
}, {
    background: 'header-2.jpg',
    heading: '{{trans('front-end/home.headerMessages.tourskie_title')}}',
    message: '{{trans('front-end/home.headerMessages.tourskie_desc')}}',
    buttonUrl: '{{url('courses')}}',
    buttonText: '{{trans('front-end/home.moreInfo')}}'
}, {
    background: 'header-3.jpg',
    heading: '{{trans('front-end/home.headerMessages.klimweekend_title')}}',
    message: '{{trans('front-end/home.headerMessages.klimweekend_desc')}}',
    buttonUrl: '{{url('klimweekenden')}}',
    buttonText: '{{trans('front-end/home.moreInfo')}}'
}, {
    background: 'header-4.jpg',
    heading: '{{trans('front-end/home.headerMessages.eplakka_title')}}',
    message: '{{trans('front-end/home.headerMessages.eplakka_desc')}}',
    buttonUrl: '{{url('eplakka')}}',
    buttonText: '{{trans('front-end/home.moreInfo')}}'
}, {
    background: 'header-5.jpg',
    heading: '{{trans('front-end/home.headerMessages.tourskiecursus_title')}}',
    message: '{{trans('front-end/home.headerMessages.tourskiecursus_desc')}}',
    buttonUrl: '{{url('courses')}}',
    buttonText: '{{trans('front-end/home.moreInfo')}}'
},  {
    background: 'header-7.jpg',
    heading: '{{trans('front-end/home.headerMessages.alpinecursus_title')}}',
    message: '{{trans('front-end/home.headerMessages.alpinecursus_desc')}}',
    buttonUrl: '{{url('courses')}}',
    buttonText: '{{trans('front-end/home.moreInfo')}}'
}, {
    background: 'header-8.jpg',
    heading: '{{trans('front-end/home.headerMessages.alpine_title')}}',
    message: '{{trans('front-end/home.headerMessages.alpine_desc')}}',
    buttonUrl: '{{url('courses')}}',
    buttonText: '{{trans('front-end/home.moreInfo')}}'
}, {
    background: 'header-9.jpg',
    heading: '{{trans('front-end/home.headerMessages.sociability_title')}}',
    message: '{{trans('front-end/home.headerMessages.sociability_desc')}}',
    buttonUrl: '{{url('agenda')}}',
    buttonText: '{{trans('front-end/home.moreInfo')}}'
}, {
    background: 'header-10.jpg',
    heading: '{{trans('front-end/home.headerMessages.bouldering_title')}}',
    message: '{{trans('front-end/home.headerMessages.bouldering_desc')}}',
    buttonUrl: '{{url('agenda')}}',
    buttonText: '{{trans('front-end/home.moreInfo')}}'
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