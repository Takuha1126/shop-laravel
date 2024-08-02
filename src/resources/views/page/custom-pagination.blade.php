<!DOCTYPE html>
<html lang="ja">
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
            @php
                if ($currentPage == 1) {
                    $startPage = 1;
                    $endPage = min(3, $lastPage);
                } elseif ($currentPage == $lastPage) {
                    $startPage = max(1, $lastPage - 2);
                    $endPage = $lastPage;
                } else {
                    $startPage = max(1, $currentPage - 1);
                    $endPage = min($lastPage, $currentPage + 1);
                }
            @endphp
            @for ($page = $startPage; $page <= $endPage; $page++)
                @if ($page == $currentPage)
                    <li class="Pagination-Item active"><span>{{ $page }}</span></li>
                @else
                    <li class="Pagination-Item"><a href="{{ $paginator->url($page) }}" class="Pagination-Item-Link">{{ $page }}</a></li>
                @endif
            @endfor
            @if ($paginator->hasMorePages())
                <li class="Pagination-Item"><a href="{{ $paginator->nextPageUrl() }}" class="Pagination-Item-Link" rel="next">&raquo;</a></li>
            @else
                <li class="Pagination-Item disabled"><span>&raquo;</span></li>
            @endif
        </ul>
    </div>
</body>
</html>

