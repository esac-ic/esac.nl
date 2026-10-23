<footer class="bg-gray text-light footer-long py-5">
    
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-3">
                <x-logo />
                <p class="text-muted mt-4">
                    © {{ now()->year }} ESAC
                    <br>Eindhovense Studenten
                    <br>Alpen Club
                    <br><br>T: {{ $footerPhoneNumber }}
                    <br>M: bestuur@esac.nl
                    <br>B: NL75 RABO 0117 0524 85
                    <br>KvK: 40236076
                </p>
                <p class="font-size-180">
                    <span class="p-1"><a href="https://www.facebook.com/esac040" class="ion-social-facebook" aria-label="Facebook"></a></span>
                    <span class="p-1"><a href="https://www.instagram.com/esac.nl/" class="ion-social-instagram" aria-label="Instagram"></a></span>
                    <span class="p-1"><a href="https://www.youtube.com/channel/UCn6jb9BA10f-rbvinYCYlxA/" class="ion-social-youtube" aria-label="YouTube"></a></span>
                </p>
            </div>
            <div class="col-12 col-md-9">
                <span class="h5">The student climbing and mountaineering association of Eindhoven.</span>
                <h6>Supported by</h6>
                <div class="row">
                    <div class="col-6 col-lg-3">
                        <a class="sponsor-logo" href="https://venusp.tue.nl/sci-cgi/index.opl"><img src="{{asset('img/ssc.png')}}" alt="Student Sports Centre Eindhoven"></a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a class="sponsor-logo" href="https://boulderneoliet.nl/"><img src="{{asset('img/boulder.png')}}" alt="Boulder Neoliet"></a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a class="sponsor-logo" href="https://www.neoliet.nl"><img src="{{asset('img/neoliet.webp')}}" alt="Neoliet"></a>
                    </div>
                    <div class="col-6 col-lg-3">
                        <a class="sponsor-logo" href="https://www.klimwinkel.nl"><img src="{{asset('img/klimwinkel.png')}}" alt="Klimwinkel"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
