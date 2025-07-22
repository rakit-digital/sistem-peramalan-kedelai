@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between mt-6">
        {{-- Bagian Kiri: Informasi Jumlah Data (Opsional, tapi informatif) --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-start">
            <p class="text-sm text-bodytext leading-5">
                Menampilkan
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-medium">{{ $paginator->total() }}</span>
                hasil
            </p>
        </div>

        {{-- Bagian Kanan: Tombol-tombol Paginasi --}}
        <div class="flex-1 flex justify-center sm:justify-end">
            <div class="flex items-center gap-1">
                {{-- Tombol "Previous" --}}
                @if ($paginator->onFirstPage())
                    <span class="flex items-center justify-center h-9 w-9 rounded-md bg-lightgray text-gray-400 cursor-not-allowed" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <i class="ti ti-chevron-left text-lg"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center justify-center h-9 w-9 rounded-md bg-white border border-border text-bodytext hover:bg-lightprimary hover:text-primary transition-all duration-300" aria-label="@lang('pagination.previous')">
                        <i class="ti ti-chevron-left text-lg"></i>
                    </a>
                @endif

                {{-- Elemen Paginasi (Angka) --}}
                @foreach ($elements as $element)
                    {{-- Tanda "..." (Ellipsis) --}}
                    @if (is_string($element))
                        <span class="flex items-center justify-center h-9 w-9 rounded-md bg-lightgray text-gray-500 cursor-default" aria-disabled="true">{{ $element }}</span>
                    @endif

                    {{-- Array Angka Halaman --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="flex items-center justify-center h-9 w-9 rounded-md bg-primary text-white border border-primary font-semibold z-10" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="flex items-center justify-center h-9 w-9 rounded-md bg-white border border-border text-bodytext hover:bg-lightprimary hover:text-primary transition-all duration-300" aria-label="@lang('pagination.goto_page', ['page' => $page])">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Tombol "Next" --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex items-center justify-center h-9 w-9 rounded-md bg-white border border-border text-bodytext hover:bg-lightprimary hover:text-primary transition-all duration-300" aria-label="@lang('pagination.next')">
                        <i class="ti ti-chevron-right text-lg"></i>
                    </a>
                @else
                    <span class="flex items-center justify-center h-9 w-9 rounded-md bg-lightgray text-gray-400 cursor-not-allowed" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <i class="ti ti-chevron-right text-lg"></i>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif