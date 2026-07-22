<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class Catalog extends Component
{
    #[Url(except: '')]
    public string $search = '';

    #[Url(as: 'categoria', except: null)]
    public ?int $selectedCategory = null;

    #[Url(as: 'filtro', except: '')]
    public string $selectedBadge = '';

    public ?Product $activeProductModal = null;
    public bool $showModal = false;

    public function selectCategory(?int $categoryId = null): void
    {
        $this->selectedCategory = $categoryId;
    }

    public function filterBadge(string $badge): void
    {
        $this->selectedBadge = $this->selectedBadge === $badge ? '' : $badge;
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->selectedCategory = null;
        $this->selectedBadge = '';
    }

    public function openModal(int $productId): void
    {
        $this->activeProductModal = Product::with('category')->find($productId);
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->activeProductModal = null;
    }

    /**
     * Cached Computed Property for Categories.
     * Prevents running category queries on every component re-render.
     */
    #[Computed]
    public function categories()
    {
        $categories = Cache::get('catalog_categories_list');

        if ($categories && $categories->count() > 0) {
            return $categories;
        }

        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true)->where('is_service', false);
            }])
            ->orderBy('sort_order')
            ->get();

        // Only cache non-empty results to avoid caching pre-seed empty state
        if ($categories->count() > 0) {
            Cache::put('catalog_categories_list', $categories, 3600);
        }

        return $categories;
    }

    public function render()
    {
        // Public active products with category eager loading (N+1 prevention)
        $query = Product::with('category')
            ->where('is_active', true)
            ->where('is_service', false);

        if (!empty($this->search)) {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('short_description', 'like', $term)
                  ->orWhere('description', 'like', $term)
                  ->orWhereHas('category', function ($catQ) use ($term) {
                      $catQ->where('name', 'like', $term);
                  });
            });
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if (!empty($this->selectedBadge)) {
            $query->where('badge', $this->selectedBadge);
        }

        $products = $query->orderBy('is_featured', 'desc')->latest()->get();

        // Repair Services query with category eager loading
        $servicesQuery = Product::with('category')
            ->where('is_active', true)
            ->where('is_service', true);

        if (!empty($this->search)) {
            $term = '%' . trim($this->search) . '%';
            $servicesQuery->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('description', 'like', $term);
            });
        }

        $services = $servicesQuery->get();

        $currentCategoryObj = $this->selectedCategory 
            ? $this->categories->firstWhere('id', $this->selectedCategory)
            : null;

        $currentCategoryName = $currentCategoryObj ? $currentCategoryObj->name : 'Todos los Componentes';

        return view('livewire.catalog', [
            'products' => $products,
            'services' => $services,
            'currentCategoryName' => $currentCategoryName,
        ])->layout('layouts.app', ['title' => 'R.R Y Asociados | Hidraulic - Catálogo Digital']);
    }
}
