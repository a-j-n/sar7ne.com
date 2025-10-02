<div class="max-w-2xl mx-auto p-4 space-y-6" data-timeline-component-id="{{ $this->getId() }}">

    <div class="rounded-xl border border-black/10 dark:border-white/10 p-4 bg-white/70 dark:bg-black/30 backdrop-blur">
        <h2 class="text-lg font-semibold mb-3">Create a post</h2>
        <div class="space-y-3">
            <textarea wire:model.defer="body" rows="3" class="w-full rounded-md border p-2" placeholder="What's happening near you?"></textarea>
            @error('body')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

            <div class="flex items-center gap-3">
                <input type="file" multiple accept="image/*" wire:model="images" />
                <div wire:loading wire:target="images" class="text-sm text-gray-500">Uploading…</div>
            </div>
            @error('images.*')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

            <div class="grid grid-cols-3 gap-2">
                @foreach($images as $idx => $img)
                    <img src="{{ $img->temporaryUrl() }}" class="rounded-lg aspect-square object-cover" wire:key="img-{{ $idx }}" />
                @endforeach
            </div>

            <div class="grid grid-cols-2 gap-3">
                <input type="text" class="rounded-md border p-2" placeholder="Place name (optional)" wire:model.defer="place_name" />
                <div class="flex gap-2">
                    <input type="number" step="any" class="rounded-md border p-2 w-full" placeholder="Lat" wire:model.defer="lat" />
                    <input type="number" step="any" class="rounded-md border p-2 w-full" placeholder="Lng" wire:model.defer="lng" />
                </div>
            </div>
            @if($mapEnabled)
            <div class="h-56 rounded-lg overflow-hidden border" x-data="{ map: null, marker: null }" x-init="
                (async () => {
                    if (!window.L) {
                        const css = document.createElement('link');
                        css.rel = 'stylesheet';
                        css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                        css.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
                        css.crossOrigin = '';
                        document.head.appendChild(css);

                        await new Promise((resolve, reject) => {
                            const s = document.createElement('script');
                            s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                            s.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
                            s.crossOrigin = '';
                            s.defer = true;
                            s.onload = resolve;
                            s.onerror = reject;
                            document.head.appendChild(s);
                        });
                    }

                    this.map = L.map($el).setView([@this.lat ?? 0, @this.lng ?? 0], (@this.lat && @this.lng) ? 13 : 2);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(this.map);
                    this.marker = L.marker([@this.lat ?? 0, @this.lng ?? 0], { draggable: true }).addTo(this.map);
                    const updateFromMarker = () => {
                        const ll = this.marker.getLatLng();
                        Livewire.find(@this.__instance.id).set('lat', ll.lat);
                        Livewire.find(@this.__instance.id).set('lng', ll.lng);
                    };
                    this.marker.on('dragend', updateFromMarker);
                    this.map.on('click', (e) => { this.marker.setLatLng(e.latlng); updateFromMarker(); });
                })();
            "></div>
            @endif
            @error('lat')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            @error('lng')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror

            <div class="flex justify-end">
                <button wire:click="create" class="px-4 py-2 rounded-md bg-blue-600 text-white" wire:loading.attr="disabled">
                    <span wire:loading.remove>Create</span>
                    <span wire:loading>Creating…</span>
                </button>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Timeline</h2>
        <div x-data x-init="window.Echo && Echo.channel('timeline').listen('.TimelinePostCreated', async () => {
            const comp = Livewire.find(@this.__instance.id);
            const latest = await comp.call('fetchLatest');
            if (!latest || !latest.id) return;
            const container = document.querySelector('[data-timeline-list]');
            if (!container) { comp.call('render'); return; }
            const el = document.createElement('div');
            el.className = 'rounded-xl border border-black/10 dark:border-white/10 p-4 bg-white/70 dark:bg-black/30 backdrop-blur';
            el.setAttribute('data-post-id', latest.id);
            let imgs = '';
            if (latest.attachments && latest.attachments.length) {
                imgs = `<div class=\"grid grid-cols-2 gap-2\">` + latest.attachments.map(a => {
                    const url = window.routeStorageUrl ? window.routeStorageUrl(a.disk, (a.thumb ?? a.path)) : `/storage/${a.path}`;
                    return `<img class=\\"rounded-lg\\" src=\\"${url}\\" alt=\\"Post image\\" />`;
                }).join('') + `</div>`;
            }
            el.innerHTML = `
                <div class=\\"text-sm text-gray-600 dark:text-gray-300 mb-2\\"> 
                    <span>${latest.user?.username ?? 'Someone'}</span> • <span>${latest.created_at_human}</span>
                    ${latest.place_name ? ` • <span>${latest.place_name}</span>` : ''}
                </div>
                ${latest.body ? `<p class=\\"mb-2\\">${latest.body.replace(/</g,'&lt;')}</p>` : ''}
                ${imgs}
            `;
            container.prepend(el);
        })"> </div>
    </div>

    <div class="space-y-4" data-timeline-list>
        @foreach($this->posts as $post)
            <div class="rounded-xl border border-black/10 dark:border-white/10 p-4 bg-white/70 dark:bg-black/30 backdrop-blur" wire:key="post-{{ $post->id }}">
                <div class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                    <span>{{ $post->user->username ?? 'Someone' }}</span>
                    <span>•</span>
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                    @if($post->place_name)
                        <span>• {{ $post->place_name }}</span>
                    @endif
                </div>
                @if($post->body)
                    <p class="mb-2">{{ $post->body }}</p>
                @endif
                @if($post->attachments->isNotEmpty())
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($post->attachments as $att)
                            @php $thumb = $att->thumbnailPath(); @endphp
                            <img class="rounded-lg" src="{{ Storage::disk($att->disk)->url($thumb && Storage::disk($att->disk)->exists($thumb) ? $thumb : $att->path) }}" alt="Post image" />
                        @endforeach
                    </div>
                @endif
                @if(!is_null($viewerLat) && !is_null($viewerLng))
                    <div class="mt-2 text-xs text-gray-500">
                        {{ number_format(\App\Support\Geo::haversineKm($viewerLat, $viewerLng, $post->lat, $post->lng), 1) }} km away
                    </div>
                @endif
            </div>
        @endforeach
        <div>
            {{ $this->posts->links() }}
        </div>
    </div>
</div>
