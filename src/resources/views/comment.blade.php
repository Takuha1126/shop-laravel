@extends('layouts.add')

@section('css')
<link rel="stylesheet" href="{{ asset('css/comment.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('content')
    <div class="main">
        <div class="main__ttl">
            <div class="main__item">
                @if (App::environment('local'))
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->productName }}">
                @else
                    <img src="{{ Storage::disk('s3')->url($product->image) }}" alt="{{ $product->productName }}">
                @endif
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
                            <i class="favorite-icon {{ $isFavorite ? 'fas text-black' : 'far' }} fa-star" data-product-id="{{ $product->id }}"></i>
                            <p id="favoriteCount" class="star__number">{{ $product->favoriteByUsers->count() }}</p>
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
                                            <div class="user__info">
                                                <div class="user__icon-wrapper">
                                                    @if ($comment->user->profile->profile_image && $comment->user->profile->profile_image !== 'default.jpg')
                                                        @if (App::environment('local'))
                                                            <img src="{{ asset('storage/' .  $comment->user->profile->profile_image) }}" alt="Profile Image">
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
                                    @else
                                        <div class="user__flex--buyer">
                                            <div class="user__info">
                                                <div class="user__name">
                                                    {{ $comment->user->profile->name }}
                                                </div>
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
                                            </div>
                                        </div>
                                    @endif
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
                                @error('content')
                                    <p class="error">{{ $message }}</p>
                                @enderror
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
        $('.main__about').on('click', '.favorite-icon', function(event) {
            event.preventDefault();

            var iconElement = $(this);
            var productId = iconElement.data('product-id');
            var favoriteCountElement = iconElement.siblings('.star__number');
            var isFavorite = iconElement.hasClass('fas text-black');
            var favoriteCount = parseInt(favoriteCountElement.text(), 10);

            isFavorite = !isFavorite;
            if (isFavorite) {
                iconElement.addClass('fas text-black').removeClass('far');
                favoriteCount++;
            } else {
                iconElement.removeClass('fas text-black').addClass('far');
                favoriteCount--;
            }
            favoriteCountElement.text(favoriteCount);

            $.ajax({
                url: '{{ route("favorite.toggle") }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },

                error: function(xhr, status, error) {
                    alert('お気に入りの更新に失敗しました。');

                    if (isFavorite) {
                        iconElement.removeClass('fas text-black').addClass('far');
                        favoriteCount--;
                    } else {
                        iconElement.addClass('fas text-black').removeClass('far');
                        favoriteCount++;
                    }
                    favoriteCountElement.text(favoriteCount);
                }
            });
        });

        window.deleteComment = function(commentId) {
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
                    alert('コメントの削除に失敗しました。');
                }
            });
        };

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
                        alert('コメントの読み込みに失敗しました。');
                        loading = false;
                    }
                });
            }
        });


        $('.favorite-icon').each(function() {
            var iconElement = $(this);
            var productId = iconElement.data('product-id');

            $.ajax({
                url: '{{ route("favorite.get") }}',
                type: 'GET',
                data: {
                    product_id: productId
                },
                success: function(response) {
                    if (response.isFavorite) {
                        iconElement.addClass('fas text-black').removeClass('far');
                    } else {
                        iconElement.removeClass('fas text-black').addClass('far');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('お気に入りの状態の取得に失敗しました:', error);
                }
            });
        });
    });
    </script>


@endsection