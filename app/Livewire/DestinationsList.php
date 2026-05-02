<?php

namespace App\Livewire;

use App\Models\Destination;
use Livewire\Component;
use Livewire\WithPagination;

class DestinationsList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sort = 'latest';
    public $priceRange = '';
    public $perPage = 6;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sort' => ['except' => 'latest'],
        'priceRange' => ['except' => '', 'as' => 'price_range'],
    ];

    public function mount($search = '', $category = '', $sort = 'latest', $priceRange = '')
    {
        $this->search = $search;
        $this->category = $category;
        $this->sort = $sort;
        $this->priceRange = $priceRange;
    }

    public function loadMore()
    {
        $this->perPage += 6;
    }

    public function updatedSearch()
    {
        $this->perPage = 6;
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->perPage = 6;
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->perPage = 6;
        $this->resetPage();
    }

    public function updatedPriceRange()
    {
        $this->perPage = 6;
        $this->resetPage();
    }

    public function render()
    {
        $query = Destination::with('category')
            ->where('is_active', true);

        if ($this->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $this->category));
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($this->priceRange) {
            if (str_ends_with($this->priceRange, '+')) {
                $query->where('price', '>=', (int) rtrim($this->priceRange, '+'));
            } else {
                [$min, $max] = array_pad(explode('-', $this->priceRange, 2), 2, null);
                if (is_numeric($min)) {
                    $query->where('price', '>=', (int) $min);
                }
                if (is_numeric($max)) {
                    $query->where('price', '<=', (int) $max);
                }
            }
        }

        match ($this->sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('views_count', 'desc'),
            default => $query->latest(),
        };

        $destinations = $query->paginate($this->perPage);

        return view('livewire.destinations-list', [
            'destinations' => $destinations
        ]);
    }
}
