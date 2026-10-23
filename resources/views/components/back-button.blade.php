@props(['href', 'block' => true])
<a href="{{ $href }}" @class(['btn', 'btn-block' => $block, 'btn-primary'])>
    <em class="ion-android-arrow-back"></em> Back
</a>
