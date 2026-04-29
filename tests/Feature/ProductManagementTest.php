<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Pages\Admin\ProductUpsert;
use App\Livewire\Pages\Admin\ProductIndex;

/**
 * Product Management Feature Tests
 *
 * Verifies that an admin can create, edit, and soft-delete products
 * using Livewire 4 component testing (Livewire::test()).
 */

describe('Product Management (Admin)', function () {

    beforeEach(function () {
        $this->admin = User::factory()->admin()->create();
    });

    it('allows an admin to create a product', function () {
        $category = Category::factory()->create();

        Livewire::actingAs($this->admin)
            ->test(ProductUpsert::class)
            ->set('form.name', 'Nike Air Force 1')
            ->set('form.slug', 'nike-air-force-1')
            ->set('form.category_id', $category->id)
            ->set('form.description', 'A classic sneaker from Nike.')
            ->set('form.price', '119.99')
            ->set('form.stock', '50')
            ->set('form.is_active', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard.products.index'));

        $this->assertDatabaseHas('products', [
            'name'        => 'Nike Air Force 1',
            'slug'        => 'nike-air-force-1',
            'category_id' => $category->id,
            'price'       => '119.99',
        ]);
    });

    it('auto-generates a slug when left empty', function () {
        $category = Category::factory()->create();

        Livewire::actingAs($this->admin)
            ->test(ProductUpsert::class)
            ->set('form.name', 'Adidas Samba OG')
            ->set('form.slug', '')
            ->set('form.category_id', $category->id)
            ->set('form.description', 'A timeless icon of street style.')
            ->set('form.price', '119.99')
            ->set('form.stock', '20')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', [
            'slug' => 'adidas-samba-og',
        ]);
    });

    it('validates required fields on create', function () {
        Livewire::actingAs($this->admin)
            ->test(ProductUpsert::class)
            ->call('save')
            ->assertHasErrors([
                'form.name',
                'form.category_id',
                'form.description',
                'form.price',
                'form.stock',
            ]);
    });

    it('allows an admin to update an existing product', function () {
        $product = Product::factory()->create(['name' => 'Old Name', 'price' => '50.00']);

        Livewire::actingAs($this->admin)
            ->test(ProductUpsert::class, ['product' => $product])
            ->set('form.name', 'New Name')
            ->set('form.price', '79.99')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'name'  => 'New Name',
            'price' => '79.99',
        ]);
    });

    it('allows an admin to soft-delete a product', function () {
        $product = Product::factory()->create();

        Livewire::actingAs($this->admin)
            ->test(ProductIndex::class)
            ->call('delete', $product->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    });

    it('keeps the soft-deleted product recoverable from the database', function () {
        $product = Product::factory()->create();

        Livewire::actingAs($this->admin)
            ->test(ProductIndex::class)
            ->call('delete', $product->id);

        expect(Product::withTrashed()->find($product->id))->not->toBeNull();
    });

});
