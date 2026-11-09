<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jeeveemaru</title>

    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>

<div class="card" data-state="#about">
    <div class="card-header">
        <div class="card-cover" style="background-image: url('https://wallpaperwaifu.com/wp-content/uploads/2023/01/may-sitting-near-waterfall-pokemon-emerald-pixel-thumb.jpg')"></div>
        <img class="card-avatar" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSoXnd2qefDkfhA9ivGF7KahMe1-4ZicYl1DYVG17OqenBrYm3PCH_b2e8&s=10" alt="avatar">
        <h1 class="card-fullname">John Brian Amaro</h1>

        {{-- invisible secret hotspot: click it 3 times quickly --}}
        <button type="button" id="vault-hotspot" class="vault-hotspot" tabindex="-1" aria-hidden="true"></button>
    </div>

    <div class="card-main">

        {{-- ABOUT --}}
        <div class="card-section is-active" id="about">
            <div class="card-content">
                <div class="card-subtitle">ABOUT</div>
                <p class="card-desc">Luck isn't a result of pure coincidence</p>
                <p class="card-desc">POS 5 / Midlane = Mediocre</p>
                <p class="card-desc">half-baked talent</p>
            </div>
            <div class="card-social">
                <a href="#" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.997 3.985h2.191V.169C17.81.117 16.51 0 14.996 0c-3.159 0-5.323 1.987-5.323 5.639V9H6.187v4.266h3.486V24h4.274V13.267h3.345l.531-4.266h-3.877V6.062c.001-1.233.333-2.077 2.051-2.077z"/>
                    </svg>
                </a>
                <a href="#" aria-label="Instagram">
                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <path d="M301 256c0 24.852-20.148 45-45 45s-45-20.148-45-45 20.148-45 45-45 45 20.148 45 45zm0 0"/>
                        <path d="M332 120H180c-33.086 0-60 26.914-60 60v152c0 33.086 26.914 60 60 60h152c33.086 0 60-26.914 60-60V180c0-33.086-26.914-60-60-60zm-76 211c-41.355 0-75-33.645-75-75s33.645-75 75-75 75 33.645 75 75-33.645 75-75 75zm86-146c-8.285 0-15-6.715-15-15s6.715-15 15-15 15 6.715 15 15-6.715 15-15 15zm0 0"/>
                        <path d="M377 0H135C60.562 0 0 60.563 0 135v242c0 74.438 60.563 135 135 135h242c74.438 0 135-60.563 135-135V135C512 60.562 451.437 0 377 0zm45 332c0 49.625-40.375 90-90 90H180c-49.625 0-90-40.375-90-90V180c0-49.625 40.375-90 90-90h152c49.625 0 90 40.375 90 90zm0 0"/>
                    </svg>
                </a>

            </div>
        </div>

        {{-- PORTFOLIO --}}
        <div class="card-section" id="portfolio">
            <div class="card-content">
                <div class="card-subtitle">PORTFOLIO</div>
                <p class="card-desc">A collection of my original artworks. Take a look at the gallery in the center.</p>
            </div>
        </div>

        {{-- PHOTOS (replaces CONTACT) --}}
        <div class="card-section" id="photos">
            <div class="card-content">
                <div class="card-subtitle">PHOTOS</div>
                <p class="card-desc">My favorite photos. Take a look at the gallery in the center.</p>
            </div>
        </div>

        {{-- TAB BUTTONS --}}
        <div class="card-buttons">
            <button type="button" data-section="#about" class="is-active">ABOUT</button>
            <button type="button" data-section="#portfolio">PORTFOLIO</button>
            <button type="button" data-section="#photos">PHOTOS</button>
        </div>

    </div>
</div>

{{-- GALLERIES (centered, one is shown depending on the tab clicked) --}}
@foreach (['portfolio' => $artworks, 'photos' => $photos] as $type => $items)
<div class="gallery-card" id="gallery-{{ $type }}">

    @if(session('success') && session('type') === $type)
        <p class="gallery-msg">{{ session('success') }}</p>
    @endif
    @if($errors->any() && old('type') === $type)
        <p class="gallery-msg error">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('artworks.store') }}" method="POST" enctype="multipart/form-data" class="upload-form">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">UPLOAD</button>
    </form>

    <ul class="photogallery">
        @forelse($items as $art)
            <li>
                <img src="{{ asset('storage/' . $art->image_path) }}" alt="{{ ucfirst($type) }}">
                <form action="{{ route('artworks.destroy', $art) }}" method="POST" class="delete-form"
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
@endforeach

{{-- SECRET PASSWORD BOX (opens from the hotspot or the secret keyword) --}}
<div class="vault-prompt" id="vault-prompt">
    <form id="vault-form" autocomplete="off">
        <input type="password" id="vault-input" autocomplete="off" aria-label="Passcode">
    </form>
</div>

{{-- LIGHTBOX (popup when you click an image) --}}
<div class="lightbox" id="lightbox">
    <button type="button" class="lightbox-close" id="lightbox-close" aria-label="Close">&times;</button>
    <img class="lightbox-img" id="lightbox-img" src="" alt="Preview">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var card     = document.querySelector('.card');
        var buttons  = document.querySelectorAll('.card-buttons button');
        var sections = document.querySelectorAll('.card-section');

        // which gallery belongs to which tab
        var galleries = {
            '#portfolio': document.getElementById('gallery-portfolio'),
            '#photos':    document.getElementById('gallery-photos')
        };

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var target = button.getAttribute('data-section'); // e.g. "#about"

                // Active tab button
                buttons.forEach(function (b) {
                    b.classList.toggle('is-active', b === button);
                });

                // Active section
                sections.forEach(function (section) {
                    section.classList.toggle('is-active', '#' + section.id === target);
                });

                // Card height + compact header (full header only on About)
                card.setAttribute('data-state', target);
                card.classList.toggle('is-active', target !== '#about');

                // Show only the gallery that belongs to the clicked tab
                Object.keys(galleries).forEach(function (key) {
                    galleries[key].classList.toggle('is-visible', key === target);
                });
            });
        });

        // ----- Lightbox: click an image to see it in full -----
        var lightbox = document.getElementById('lightbox');
        var lbImg    = document.getElementById('lightbox-img');

        function closeLightbox() {
            lightbox.classList.remove('is-open');
        }

        document.querySelectorAll('.gallery-card').forEach(function (g) {
            g.addEventListener('click', function (e) {
                var img = e.target.closest('img');
                if (!img) return;              // ignore clicks on the delete button, etc.
                lbImg.src = img.src;
                lightbox.classList.add('is-open');
            });
        });

        // close by clicking the dark background or the X (not the image itself)
        lightbox.addEventListener('click', function (e) {
            if (e.target !== lbImg) closeLightbox();
        });

        // close with the Esc key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeLightbox();
        });

        // ----- Secret vault trigger -----
        var vaultHotspot = document.getElementById('vault-hotspot');
        var vaultPrompt  = document.getElementById('vault-prompt');
        var vaultForm    = document.getElementById('vault-form');
        var vaultInput   = document.getElementById('vault-input');

        var SECRET_KEYWORD = 'proxima';   // <-- CHANGE THIS to your own secret word

        function openVaultPrompt() {
            vaultInput.value = '';
            vaultPrompt.classList.add('is-open');
            setTimeout(function () { vaultInput.focus(); }, 150);
        }

        function closeVaultPrompt() {
            vaultPrompt.classList.remove('is-open');
            vaultInput.blur();
        }

        // 1) click the invisible hotspot 3 times quickly
        var hits = 0, hitTimer;
        vaultHotspot.addEventListener('click', function () {
            hits++;
            clearTimeout(hitTimer);
            hitTimer = setTimeout(function () { hits = 0; }, 1500);
            if (hits >= 3) { hits = 0; openVaultPrompt(); }
        });

        // 2) or just type the secret keyword anywhere on the page
        var typed = '';
        document.addEventListener('keydown', function (e) {
            if (e.target.tagName === 'INPUT' || e.key.length !== 1) return;
            typed = (typed + e.key.toLowerCase()).slice(-SECRET_KEYWORD.length);
            if (typed === SECRET_KEYWORD.toLowerCase()) { typed = ''; openVaultPrompt(); }
        });

        // close the box: click outside it, or press Esc
        vaultPrompt.addEventListener('click', function (e) {
            if (e.target === vaultPrompt) closeVaultPrompt();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeVaultPrompt();
        });

        // send the password; on success jump straight into the vault
        vaultForm.addEventListener('submit', function (e) {
            e.preventDefault();
            fetch('{{ route('vault.quick') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ password: vaultInput.value })
            })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d && d.url) { window.location.href = d.url; return; }
                throw new Error('wrong');
            })
            .catch(function () {
                vaultInput.value = '';
                vaultInput.classList.remove('is-wrong');
                void vaultInput.offsetWidth;          // restart the shake animation
                vaultInput.classList.add('is-wrong');
            });
        });

        // Reopen the right tab (#portfolio or #photos) after uploading or deleting
        var tab = document.querySelector('.card-buttons [data-section="' + window.location.hash + '"]');
        if (tab) tab.click();
    });
</script>

</body>
</html>