@extends('admin.layouts.app')

@section('title', 'Sliders')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Homepage Sliders</h1>
        <a href="{{ route('admin.sliders.create') }}"
            class="bg-slate-800 text-white px-4 py-2 rounded-md hover:bg-slate-700 transition-colors">
            + Add New Slider
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                        Order</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                        Image</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                        Title / Subtitle</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                        Status</th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($sliders as $slider)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $slider->order }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ asset('storage/' . $slider->image) }}" alt="Slider"
                                class="h-16 w-32 object-cover rounded border border-slate-200 slider-thumbnail cursor-pointer"
                                data-full-image="{{ asset('storage/' . $slider->image) }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-900">{{ $slider->title ?? 'No Title' }}</div>
                            <div class="text-sm text-slate-500">{{ $slider->subtitle }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($slider->status)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.sliders.edit', $slider->id) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Delete this slider?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-slate-500">
                            No sliders found. Add one to get started!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Image Preview Popover -->
    <div id="slider-image-popover"
        class="fixed z-50 hidden bg-white p-2 rounded-lg shadow-xl border border-slate-200 pointer-events-none"
        style="max-width: 600px;">
        <img src="" alt="Preview" class="w-full h-auto rounded">
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Image Preview Logic
            $(document).on('mouseenter', '.slider-thumbnail', function (e) {
                const fullImageSrc = $(this).data('full-image');
                if (fullImageSrc) {
                    const $popover = $('#slider-image-popover');
                    $popover.find('img').attr('src', fullImageSrc);
                    $popover.removeClass('hidden');
                }
            });

            $(document).on('mousemove', '.slider-thumbnail', function (e) {
                const $popover = $('#slider-image-popover');
                // Position to the right of the cursor
                let top = e.clientY + 10;
                let left = e.clientX + 20;

                // Check if it goes off screen (bottom)
                if (top + $popover.outerHeight() > $(window).height()) {
                    top = e.clientY - $popover.outerHeight() - 10;
                }

                // Check if it goes off screen (right)
                if (left + $popover.outerWidth() > $(window).width()) {
                    left = e.clientX - $popover.outerWidth() - 20;
                }

                $popover.css({
                    top: top + 'px',
                    left: left + 'px'
                });
            });

            $(document).on('mouseleave', '.slider-thumbnail', function () {
                $('#slider-image-popover').addClass('hidden');
            });
        });
    </script>
@endpush