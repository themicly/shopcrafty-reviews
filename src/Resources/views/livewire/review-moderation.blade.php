<div class="space-y-4">
    <div class="flex gap-2">@foreach (['pending', 'approved', 'rejected', 'all'] as $value)<button wire:click="$set('status', '{{ $value }}')" class="rounded border px-3 py-1.5 text-sm">{{ ucfirst($value) }}</button>@endforeach</div>
    @forelse ($reviews as $review)
        <x-ui.card><div class="flex items-start justify-between gap-4"><div><p class="text-sm">{{ str_repeat('★', $review->rating).str_repeat('☆', 5 - $review->rating) }} · {{ $review->product?->name ?? '—' }} · {{ $review->author_name }}</p><p class="mt-1 text-sm">{{ $review->body }}</p></div><div class="flex gap-2">@if ($review->status !== 'approved')<x-ui.button size="sm" wire:click="approve({{ $review->id }})">Approve</x-ui.button>@endif @if ($review->status !== 'rejected')<x-ui.button size="sm" variant="ghost" wire:click="reject({{ $review->id }})">Reject</x-ui.button>@endif</div></div></x-ui.card>
    @empty
        <x-ui.card>No reviews here.</x-ui.card>
    @endforelse
    {{ $reviews->links() }}
</div>
