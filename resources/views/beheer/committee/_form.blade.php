<div class="form-row">
    <div class="form-group col-md-5">
        <label for="name">Name</label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $committee->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-5">
        <label for="email">Email</label>
        <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email', $committee->email ?? '') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="committee@esac.nl"
                required
        >
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-2">
        <label for="abbreviation">Abbreviation</label>
        <input
                type="text"
                name="abbreviation"
                id="abbreviation"
                value="{{ old('abbreviation', $committee->abbreviation ?? '') }}"
                class="form-control @error('abbreviation') is-invalid @enderror"
                required
        >
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-12">
        <label for="description">Description</label>
        <textarea
            name="description"
            id="description"
            rows="4"
            class="form-control @error('description') is-invalid @enderror"
        >
            {{ old('description', $committee->description ?? '') }}
        </textarea>
        <small class="form-text text-muted">This is the description that will show on the public committees page.</small>
    </div>
</div>
<button class="btn btn-primary" type="submit">Save</button>
{{--TODO the behaviour in the other controllers is to redirect to the show view, but this seems somewhat unintuitive to me, what do you think is better?--}}
<a href="{{route("beheer.committees.index")}}" class="btn btn-danger">Cancel</a>