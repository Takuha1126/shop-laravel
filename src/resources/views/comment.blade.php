@extends('layouts.add')

@section('css')
<link rel="stylesheet" href="{{ asset('css/comment.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')

<div class="main">
    <div class="main__ttl">
        <div class="main__item">
            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->productName }}">
        </div>
        <div class="main__about">
            <div class="about__group">
                <div class="about__ttl">
                    <p class="about__title">{{ $product->productName }}</p>
                    <p class="about__brand">{{ $product->brand }}</p>
                </div>
                <div class="about__price">
                    <p class="price__ttl">¥{{ $product->price }}(値段)</p>
                </div>
                <div class="about__action">
                    <div class="action__favorite">
                        <i id="favoriteIcon" class="action__star {{ $isFavorite ? 'fas text-black' : 'far' }} fa-star" data-product-id="{{ $product->id }}"></i>
                        <p id="favoriteCount" class="star__number">{{ $product->favorites->count() }}</p>
                    </div>
                    <div class="action__comment">
                        <a class="comment__link" href="{{ route('comment.index', ['id' => $product->id]) }}">
                            <i class="far fa-comment"></i>
                            <p class="comment__number">{{ $product->comments->count() }}</p>
                        </a>
                    </div>
                </div>
                <div class="comment__group">
                    <div class="comments">
                        @foreach($comments as $comment)
                            <div class="comment__about" id="comment-{{ $comment->id }}">
                                @if($comment->user_id === $product->user_id)
                                    <div class="user__flex--seller">
                                @else
                                    <div class="user__flex--buyer">
                                @endif
                                        <div class="user__info">
                                            <div class="user__icon-wrapper">
                                                @if ($comment->user->profile->profile_image && $comment->user->profile->profile_image !== 'default.jpg')
                                                    @if (App::environment('local'))
                                                        <img src="{{ asset('storage/' . $comment->user->profile->profile_image) }}" alt="Profile Image">
                                                    @else
                                                        <img src="{{ Storage::disk('s3')->url($comment->user->profile->profile_image) }}" alt="Profile Image">
                                                    @endif
                                                @else
                                                    @if (App::environment('local'))
                                                        <img src="{{ asset('storage/profile_images/default.jpg') }}" alt="Default Profile Image">
                                                    @else
                                                        <img src="{{ Storage::disk('s3')->url('profile_images/default.jpg') }}" alt="Default Profile Image">
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="user__name">
                                                {{ $comment->user->profile->name }}
                                            </div>
                                        </div>
                                    </div>
                                <div class="user__comment" id="comment-{{ $comment->id }}">
                                    <div class="user__comment-post">{{ $comment->content }}</div>
                                    @if($comment->user_id == Auth::id())
                                        <button class="comment__delete-button" onclick="deleteComment({{ $comment->id }})">削除</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        @if ($comments->hasMorePages())
                            <div class="load__more">
                                <a href="{{ $comments->nextPageUrl() }}" class="load__more-button">続きを見る</a>
                            </div>
                        @endif
                        <div class="comment__text">
                            <label class="label">商品へのコメント</label>
                            <form action="{{ route('comment.store', ['id' => $product->id]) }}" method="POST">
                                @csrf
                                <textarea class="comment__textarea" name="content" placeholder="コメントを入力してください"></textarea>
                                <button type="submit" class="comment__button">コメントを送信する</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var productId = $('#favoriteIcon').data('product-id');
        var favoriteCount = parseInt($('#favoriteCount').text());
        var isFavorite = localStorage.getItem('favorite-' + productId) === 'true';

        if (isFavorite) {
            $('#favoriteIcon').removeClass('far').addClass('fas text-black');
        } else {
            $('#favoriteIcon').removeClass('fas text-black').addClass('far');
        }

        $('#favoriteIcon').on('click', function() {
            isFavorite = !isFavorite;

            if (isFavorite) {
                $(this).removeClass('far').addClass('fas text-black');
                favoriteCount++;
            } else {
                $(this).removeClass('fas text-black').addClass('far');
                favoriteCount--;
            }

            $('#favoriteCount').text(favoriteCount);


            $.ajax({
                url: '{{ route("favorite.toggle") }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                error: function(xhr, status, error) {
                    alert('お気に入りの更新に失敗しました');
                }
            });

            localStorage.setItem('favorite-' + productId, isFavorite.toString());
        });
    });

    function deleteComment(commentId) {
        $.ajax({
            url: '/comment/' + commentId + '/delete',
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#comment-' + commentId).hide();
            },
            error: function(xhr, status, error) {
                console.error('Ajax Error:', error);
                alert('コメントの削除に失敗しました。');
            }
        });
    }

    $(document).ready(function() {
        var nextPageUrl = "{{ $comments->nextPageUrl() }}";
        var loading = false;

        $('#load-more-comments').on('click', function(e) {
            e.preventDefault();

            if (!loading && nextPageUrl) {
                loading = true;

                $.ajax({
                    url: nextPageUrl,
                    type: 'GET',
                    success: function(response) {
                        $('#comments-container').append(response);
                        nextPageUrl = $(response).find('.load-more a').attr('href');
                        loading = false;

                        if (!nextPageUrl) {
                            $('.load-more').remove();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax Error:', error);
                        alert('コメントの読み込みに失敗しました。');
                        loading = false;
                    }
                });
            }
        });
    });
</script>
@endsection