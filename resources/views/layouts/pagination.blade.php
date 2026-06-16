@if ($paginator->hasPages())

<nav style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">

    @if ($paginator->onFirstPage())
        <span style="
            padding:6px 10px;
            border-radius:7px;
            border:1px solid var(--border);
            color:#94a3b8;
            background:var(--white);
        ">
            ‹
        </span>
    @else

        <a href="{{ $paginator->previousPageUrl() }}"
           style="
                padding:6px 10px;
                border-radius:7px;
                border:1px solid var(--border);
                background:var(--white);
                text-decoration:none;
                color:var(--text);
           ">
            ‹
        </a>

    @endif

    @foreach ($elements as $element)
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())

                    <span style="
                        padding:6px 12px;
                        border-radius:7px;
                        font-size:13px;
                        font-weight:600;
                        background:var(--accent);
                        color:#fff;
                        border:1px solid var(--accent);
                    ">
                        {{ $page }}
                    </span>
                @else

                    <a href="{{ $url }}"
                       style="
                            padding:6px 12px;
                            border-radius:7px;
                            font-size:13px;
                            color:var(--text);
                            border:1px solid var(--border);
                            background:var(--white);
                            text-decoration:none;
                       ">
                        {{ $page }}
                    </a>

                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           style="
                padding:6px 10px;
                border-radius:7px;
                border:1px solid var(--border);
                background:var(--white);
                text-decoration:none;
                color:var(--text);
           ">
            ›
        </a>

    @else
        <span style="
            padding:6px 10px;
            border-radius:7px;
            border:1px solid var(--border);
            color:#94a3b8;
            background:var(--white);
        ">
            ›
        </span>

    @endif
</nav>
@endif