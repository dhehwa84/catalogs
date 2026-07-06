@php
    $branch = $branch ?? null;
    $hours = $branch?->operatingHours?->keyBy('day_of_week') ?? collect();
    $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="label">Branch name</label>
        <input name="name" value="{{ old('name', $branch?->name) }}" class="field mt-1" required>
    </div>
    <div>
        <label class="label">Province</label>
        <select name="province_id" class="field mt-1">
            <option value="">Select province</option>
            @foreach($provinces as $province)
                <option value="{{ $province->id }}" @selected(old('province_id', $branch?->province_id) == $province->id)>{{ $province->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">City</label>
        <select name="city_id" class="field mt-1">
            <option value="">Select city</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" @selected(old('city_id', $branch?->city_id) == $city->id)>{{ $city->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="label">Area / suburb</label>
        <select name="suburb_id" class="field mt-1">
            <option value="">Select area</option>
            @foreach($suburbs as $suburb)
                <option value="{{ $suburb->id }}" @selected(old('suburb_id', $branch?->suburb_id) == $suburb->id)>{{ $suburb->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="label">Address</label>
        <textarea name="address" rows="3" class="field mt-1">{{ old('address', $branch?->address) }}</textarea>
    </div>
    <div><label class="label">Phone</label><input name="phone" value="{{ old('phone', $branch?->phone) }}" class="field mt-1"></div>
    <div><label class="label">WhatsApp</label><input name="whatsapp" value="{{ old('whatsapp', $branch?->whatsapp) }}" class="field mt-1"></div>
    <div><label class="label">Email</label><input type="email" name="email" value="{{ old('email', $branch?->email) }}" class="field mt-1"></div>
    <div class="grid grid-cols-2 gap-3">
        <div><label class="label">Latitude</label><input name="latitude" value="{{ old('latitude', $branch?->latitude) }}" class="field mt-1"></div>
        <div><label class="label">Longitude</label><input name="longitude" value="{{ old('longitude', $branch?->longitude) }}" class="field mt-1"></div>
    </div>
    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
        <input type="checkbox" name="is_main" value="1" class="rounded border-slate-300 text-emerald-700" @checked(old('is_main', $branch?->is_main))>
        Main branch
    </label>
    <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
        <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-emerald-700" @checked(old('is_active', $branch?->is_active ?? true))>
        Active
    </label>
</div>

<div class="mt-6">
    <h2 class="text-lg font-bold text-slate-950">Operating hours</h2>
    <div class="mt-3 grid gap-3">
        @foreach($days as $day => $label)
            @php $dayHours = $hours->get($day); @endphp
            <div class="grid items-center gap-3 rounded-lg border border-slate-200 p-3 sm:grid-cols-[120px_1fr_1fr_120px]">
                <p class="text-sm font-semibold">{{ $label }}</p>
                <input type="time" name="hours[{{ $day }}][opens_at]" value="{{ old("hours.$day.opens_at", $dayHours?->opens_at ? substr($dayHours->opens_at, 0, 5) : '08:00') }}" class="field">
                <input type="time" name="hours[{{ $day }}][closes_at]" value="{{ old("hours.$day.closes_at", $dayHours?->closes_at ? substr($dayHours->closes_at, 0, 5) : '17:00') }}" class="field">
                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-600">
                    <input type="checkbox" name="hours[{{ $day }}][is_closed]" value="1" class="rounded border-slate-300 text-emerald-700" @checked(old("hours.$day.is_closed", $dayHours?->is_closed))>
                    Closed
                </label>
            </div>
        @endforeach
    </div>
</div>
