<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-3">
                <x-logo />
                <p class="mt-4">
                    © {{ now()->year }} ESAC
                    <br>Eindhovense Studenten
                    <br>Alpen Club
                    @if ($contact->phone_number)
                        <br><br>T: {{ $contact->phone_number }}
                    @endif
                    <br>M: {{ $contact->email }}
                    <br>B: {{ $contact->iban }}
                    <br>KvK: {{ $contact->kvk_number }}
                </p>
                <p class="social-links">
                    @if ($contact->facebook_url)
                        <span class="p-1"><a href="{{ $contact->facebook_url }}" aria-label="Facebook"><x-brand-icon name="facebook" /></a></span>
                    @endif
                    @if ($contact->instagram_url)
                        <span class="p-1"><a href="{{ $contact->instagram_url }}" aria-label="Instagram"><x-brand-icon name="instagram" /></a></span>
                    @endif
                    @if ($contact->youtube_url)
                        <span class="p-1"><a href="{{ $contact->youtube_url }}" aria-label="YouTube"><x-brand-icon name="youtube" /></a></span>
                    @endif
                </p>
            </div>
            <div class="col-12 col-md-9">
                <p class="h5">The student climbing and mountaineering association of Eindhoven.</p>
                <h6>Supported by</h6>
                <div class="row">
                    @foreach ($sponsors as $sponsor)
                        <div class="col-6 col-lg-3">
                            <a class="sponsor-logo" href="{{ $sponsor->url }}"><img src="{{ $sponsor->getFirstMediaUrl('logo', 'footer') }}" alt="{{ $sponsor->name }}"></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>
