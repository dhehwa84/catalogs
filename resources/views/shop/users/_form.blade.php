@php $user = $user ?? null; @endphp
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="label">Name</label>
        <input name="name" value="{{ old('name', $user?->name) }}" class="field mt-1" required>
    </div>
    <div>
        <label class="label">Email</label>
        <input type="email" name="email" value="{{ old('email', $user?->email) }}" class="field mt-1" required>
    </div>
    <div>
        <label class="label">Password</label>
        <input type="password" name="password" class="field mt-1" {{ $user ? '' : 'required' }}>
    </div>
    <div>
        <label class="label">Position</label>
        <input name="position" value="{{ old('position', $user?->pivot?->position) }}" class="field mt-1">
    </div>
    <div>
        <label class="label">Role</label>
        <select name="role" class="field mt-1" required>
            @foreach(['shop_admin', 'catalogue_manager', 'branch_manager', 'viewer'] as $role)
                <option value="{{ $role }}" @selected(old('role', $user?->pivot?->role ?? 'viewer') === $role)>{{ str_replace('_', ' ', ucfirst($role)) }}</option>
            @endforeach
        </select>
    </div>
</div>
