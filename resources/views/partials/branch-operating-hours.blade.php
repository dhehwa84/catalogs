@php
    $dayNames = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'];
    $hoursByDay = $branch->operatingHours->keyBy('day_of_week');
    $today = now()->dayOfWeekIso;
    $todayHours = $hoursByDay->get($today);

    $formatTime = function ($time) {
        if (! $time) {
            return null;
        }

        return \Illuminate\Support\Carbon::parse($time)->format('H:i');
    };
@endphp

<div class="mt-4 rounded-lg bg-slate-50 p-3">
    <div class="flex items-center justify-between gap-3">
        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Operating hours</p>
        @if($todayHours)
            <p class="text-xs font-semibold {{ $todayHours->is_closed ? 'text-rose-700' : 'text-emerald-700' }}">
                Today:
                {{ $todayHours->is_closed ? 'Closed' : $formatTime($todayHours->opens_at).' - '.$formatTime($todayHours->closes_at) }}
            </p>
        @endif
    </div>

    @if($hoursByDay->isNotEmpty())
        <dl class="mt-3 grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-slate-600">
            @foreach($dayNames as $day => $label)
                @php $hours = $hoursByDay->get($day); @endphp
                <div class="flex justify-between gap-2">
                    <dt class="font-semibold text-slate-700">{{ $label }}</dt>
                    <dd class="text-right">
                        @if(! $hours)
                            Not set
                        @elseif($hours->is_closed)
                            Closed
                        @else
                            {{ $formatTime($hours->opens_at) }} - {{ $formatTime($hours->closes_at) }}
                        @endif
                    </dd>
                </div>
            @endforeach
        </dl>
    @else
        <p class="mt-2 text-xs text-slate-500">Operating hours have not been added for this branch.</p>
    @endif
</div>
