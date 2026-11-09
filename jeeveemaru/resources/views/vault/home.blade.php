<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>arabella</title>

    {{-- same stylesheet as the profile page --}}
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <style>
        /* no profile card on this page, so the gallery uses the full width */
        @media (min-width: 801px) {
            .gallery-card { left: 20px; right: 20px; }
        }
        .vault-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }
        .vault-top .upload-form { margin-bottom: 0; }
    </style>
</head>
<body>

<div class="gallery-card is-visible" id="gallery-vault">

    @if($errors->any())
        <p class="gallery-msg error">{{ $errors->first() }}</p>
    @endif

    <div class="vault-top">
        <form action="{{ route('vault.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
            @csrf
            <input type="file" name="images[]" accept="image/*" multiple required>
            <button type="submit">UPLOAD</button>
        </form>

        <form action="{{ route('vault.lock') }}" method="POST" class="upload-form">
            @csrf
            <button type="submit">LOCK</button>
        </form>
    </div>

    <ul class="photogallery">
        @forelse($items as $item)
            <li>
                <img src="{{ route('vault.image', $item) }}" alt="Vault image">
                <form action="{{ route('vault.delete', $item) }}" method="POST" class="delete-form"
                      onsubmit="return confirm('Delete this image?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" aria-label="Delete">&times;</button>
                </form>
            </li>
        @empty
            <li class="img-empty">Nothing here yet. Upload your first one!</li>
        @endforelse
    </ul>
</div>

{{-- LIGHTBOX (popup when you click an image) --}}
<div class="lightbox" id="lightbox">
    <button type="button" class="lightbox-close" id="lightbox-close" aria-label="Close">&times;</button>
    <img class="lightbox-img" id="lightbox-img" src="" alt="Preview">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var gallery  = document.getElementById('gallery-vault');
        var lightbox = document.getElementById('lightbox');
        var lbImg    = document.getElementById('lightbox-img');

        function closeLightbox() {
            lightbox.classList.remove('is-open');
        }

        gallery.addEventListener('click', function (e) {
            var img = e.target.closest('img');
            if (!img) return;              // ignore clicks on the delete button, etc.
            lbImg.src = img.src;
            lightbox.classList.add('is-open');
        });

        // close by clicking the dark background or the X (not the image itself)
        lightbox.addEventListener('click', function (e) {
            if (e.target !== lbImg) closeLightbox();
        });

        // close with the Esc key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeLightbox();
        });
    });
</script>

</body>
</html>