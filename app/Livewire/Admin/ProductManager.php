<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductManager extends Component
{
    use WithFileUploads;

    // Filters
    public string $search = '';
    public ?int $filterCategory = null;

    // Form fields
    public ?int $productId = null;
    public string $name = '';
    public ?int $category_id = null;
    public string $short_description = '';
    public string $description = '';
    public string $badge = '';
    public string $whatsapp_number = '';
    public bool $is_active = true;
    public bool $is_featured = false;
    public bool $is_service = false;
    public $image; // File upload
    public string $external_image_url = '';

    // Dynamic Specs
    public array $specs = [];
    public string $newSpecKey = '';
    public string $newSpecVal = '';

    // Modal state
    public bool $showFormModal = false;
    public bool $showDeleteModal = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:50',
            'whatsapp_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_service' => 'boolean',
            'image' => 'nullable|image|max:2048',
            'external_image_url' => 'nullable|string',
        ];
    }

    public function createProduct(): void
    {
        $this->resetForm();
        $this->category_id = Category::first()?->id;
        $this->showFormModal = true;
    }

    public function editProduct(int $id): void
    {
        $this->resetForm();
        $product = Product::findOrFail($id);

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->short_description = $product->short_description ?? '';
        $this->description = $product->description ?? '';
        $this->badge = $product->badge ?? '';
        $this->whatsapp_number = $product->whatsapp_number ?? '';
        $this->is_active = $product->is_active;
        $this->is_featured = $product->is_featured;
        $this->is_service = $product->is_service;
        $this->external_image_url = str_starts_with($product->image_path ?? '', 'http') ? $product->image_path : '';
        $this->specs = is_array($product->specs) ? $product->specs : [];

        $this->showFormModal = true;
    }

    public function addSpec(): void
    {
        if (!empty($this->newSpecKey) && !empty($this->newSpecVal)) {
            $this->specs[$this->newSpecKey] = $this->newSpecVal;
            $this->newSpecKey = '';
            $this->newSpecVal = '';
        }
    }

    public function removeSpec(string $key): void
    {
        unset($this->specs[$key]);
    }

    public function saveProduct(): void
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        } elseif (!empty($this->external_image_url)) {
            $imagePath = $this->external_image_url;
        }

        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'badge' => $this->badge,
            'whatsapp_number' => $this->whatsapp_number,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'is_service' => $this->is_service,
            'specs' => $this->specs,
        ];

        if ($imagePath) {
            $data['image_path'] = $imagePath;
        }

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
            session()->flash('message', 'Producto actualizado correctamente.');
        } else {
            Product::create($data);
            session()->flash('message', 'Producto creado exitosamente.');
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
    }

    public function toggleFeatured(int $id): void
    {
        $product = Product::findOrFail($id);
        $product->update(['is_featured' => !$product->is_featured]);
    }

    public function confirmDelete(int $id): void
    {
        $this->productId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteProduct(): void
    {
        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->delete();
            session()->flash('message', 'Producto eliminado exitosamente.');
        }
        $this->showDeleteModal = false;
        $this->productId = null;
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('catalog');
    }

    private function resetForm(): void
    {
        $this->productId = null;
        $this->name = '';
        $this->category_id = null;
        $this->short_description = '';
        $this->description = '';
        $this->badge = '';
        $this->whatsapp_number = '';
        $this->is_active = true;
        $this->is_featured = false;
        $this->is_service = false;
        $this->image = null;
        $this->external_image_url = '';
        $this->specs = [];
        $this->newSpecKey = '';
        $this->newSpecVal = '';
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        $query = Product::with('category');

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }

        $products = $query->latest()->paginate(12);

        return view('livewire.admin.product-manager', [
            'categories' => $categories,
            'products' => $products,
        ])->layout('layouts.app', ['title' => 'Dashboard Administrativo | R.R Y Asociados']);
    }
}
