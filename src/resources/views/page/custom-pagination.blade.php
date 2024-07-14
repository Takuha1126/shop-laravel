<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}" />
</head>
<body>
<div class="pagination">
    <ul class="Pagination">
        @if ($paginator->onFirstPage())
            <li class="Pagination-Item disabled"><span>&laquo;</span></li>
        @else
            <li class="Pagination-Item"><a href="{{ $paginator->previousPageUrl() }}" class="Pagination-Item-Link" rel="prev">&laquo;</a></li>
        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="Pagination-Item disabled"><span>{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="Pagination-Item active"><span>{{ $page }}</span></li>
                    @else
                        <li class="Pagination-Item"><a href="{{ $url }}" class="Pagination-Item-Link">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <li class="Pagination-Item"><a href="{{ $paginator->nextPageUrl() }}" class="Pagination-Item-Link" rel="next">&raquo;</a></li>
        @else
            <li class="Pagination-Item disabled"><span>&raquo;</span></li>
        @endif
    </ul>
</div>
</body>
</html>

