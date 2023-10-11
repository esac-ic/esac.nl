@extends('layouts.app')

@section('content')
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">{{$curPageName}}</h2>
                <p>{{'The website is currently unavailable. For more information, contact the board (bestuur@esac.nl) or the internet committee (ic@esac.nl).'}}</p>
            </div>
        </div>
    </div>
@endsection
