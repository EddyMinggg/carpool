@extends('admin.layout')

@section('title', 'Edit Trip')

@section('content')
    <h1 class="mb-4">Edit Trip #{{ $trip->trip_id }}</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.trips.update', ['trip' => $trip->trip_id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="creator_id" class="form-label">Creator</label>
                    <select name="creator_id" id="creator_id" class="form-select @error('creator_id') is-invalid @enderror">
                        @foreach ($users as $user)
                            <option value="{{ $user->user_id }}" {{ $trip->creator_id == $user->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('creator_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="start_place" class="form-label">Start Place</label>
                    <input type="text" name="start_place" id="start_place" class="form-control @error('start_place') is-invalid @enderror" value="{{ old('start_place', $trip->start_place) }}">
                    @error('start_place')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="end_place" class="form-label">End Place</label>
                    <input type="text" name="end_place" id="end_place" class="form-control @error('end_place') is-invalid @enderror" value="{{ old('end_place', $trip->end_place) }}">
                    @error('end_place')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="plan_departure_time" class="form-label">Plan Departure Time (HH:MM)</label>
                    <input type="time" name="plan_departure_time" id="plan_departure_time" class="form-control @error('plan_departure_time') is-invalid @enderror" value="{{ old('plan_departure_time', $trip->plan_departure_time->format('H:i')) }}">
                    @error('plan_departure_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="max_people" class="form-label">Max People</label>
                    <input type="number" name="max_people" id="max_people" min="1" max="4" class="form-control @error('max_people') is-invalid @enderror" value="{{ old('max_people', $trip->max_people) }}">
                    @error('max_people')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="is_private" class="form-label">Is Private?</label>
                    <input type="checkbox" name="is_private" id="is_private" class="form-check-input" {{ $trip->is_private || old('is_private') ? 'checked' : '' }}>
                </div>

                <div class="mb-3">
                    <label for="trip_status" class="form-label">Trip Status</label>
                    <select name="trip_status" id="trip_status" class="form-select @error('trip_status') is-invalid @enderror">
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}" {{ (old('trip_status', $trip->trip_status) === $key) ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('trip_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="base_price" class="form-label">Base Price</label>
                    <input type="number" step="0.01" name="base_price" id="base_price" class="form-control @error('base_price') is-invalid @enderror" value="{{ old('base_price', $trip->base_price) }}">
                    @error('base_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Trip</button>
                <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection