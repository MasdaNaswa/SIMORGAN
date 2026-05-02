@props(['questions', 'start', 'end', 'title'])

<div class="mb-8 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3">
        <h4 class="text-md font-semibold text-white">{{ $title }}</h4>
    </div>
    <div class="p-5 space-y-4">
        @php
            $options = ["Sangat Tidak Setuju", "Tidak Setuju", "Setuju", "Sangat Setuju"];
            $colorMap = [
                'Sangat Setuju' => 'border-green-200 hover:border-green-300 hover:bg-green-50',
                'Setuju' => 'border-blue-200 hover:border-blue-300 hover:bg-blue-50',
                'Tidak Setuju' => 'border-yellow-200 hover:border-yellow-300 hover:bg-yellow-50',
                'Sangat Tidak Setuju' => 'border-red-200 hover:border-red-300 hover:bg-red-50'
            ];
        @endphp

        @for($i = $start; $i <= $end; $i++)
            @php $displayNumber = $i - $start + 1; @endphp
            <div class="space-y-3 p-4 rounded-lg border border-gray-100 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-600 font-semibold text-xs flex-shrink-0">
                        {{ $displayNumber }}
                    </span>
                    <p class="text-gray-700 font-medium text-sm leading-relaxed">{{ $questions[$i-1] }}</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 ml-10">
                    @foreach($options as $opt)
                        @php $colorClass = $colorMap[$opt] ?? 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'; @endphp
                        <label class="flex items-center gap-2 p-2 rounded-lg border cursor-pointer transition-all {{ $colorClass }}">
                            <input type="radio" 
                                   name="proses_{{ $i }}" 
                                   value="{{ $opt }}" 
                                   required 
                                   class="w-4 h-4 text-blue-600">
                            <span class="text-xs font-medium">{{ $opt }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endfor
    </div>
</div>