<?php

use App\Models\Product;
use App\Models\Category;

describe('product controller', function() {

    test('can view product listing page', function () {
        // Create product dummy data
        Product::factory()->for(Category::factory())->count(3)->create();
        
        // Access product list view
        $response = $this->get(route('product.index'));
        
        // Response assertion
        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas('products');
    });
    
    test('can view product creation page', function () {
        // Create two category data
        Category::factory()->count(2)->create();
        
        // Access product form
        $response = $this->get(route('product.create'));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('products.create');
        $response->assertViewHas('categories');
    });
    
    test('can create a new product', function () {
        // Create category and new product data
        $category = Category::factory()->create();
        $productData = [
            'name' => 'Test Product',
            'code' => 'PROD-123ABC',
            'price' => 1500.00,
            'stock' => 50,
            'category_id' => $category->id
        ];
        
        // Perform product creation
        $response = $this->post(route('product.store'), $productData);
        
        // Assert
        $response->assertRedirect(route('product.index'));
        $response->assertSessionHas('success', 'Product created successfully.');
        
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'code' => 'PROD-123ABC',
            'category_id' => $category->id
        ]);
    });
    
    test('cannot create product with duplicate code', function () {
        // Create category and product data
        $category = Category::factory()->create();
        Product::factory()->create(['code' => 'PROD-123ABC']);
        
        $productData = [
            'name' => 'Test Product',
            'code' => 'PROD-123ABC', // Same code as existing product
            'price' => 1500.00,
            'stock' => 50,
            'category_id' => $category->id
        ];
        
        // Perform product creation
        $response = $this->post(route('product.store'), $productData);
        
        // Assert
        $response->assertSessionHasErrors('code');
    });
    
    test('can view product details', function () {
        // Create product data
        $product = Product::factory()->create();
        
        // Access product detail page
        $response = $this->get(route('product.show', $product));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertViewHas('product');
    });
    
    test('can view product edit page', function () {
        // Create product data
        $product = Product::factory()->create();
        
        // Access product edit form
        $response = $this->get(route('product.edit', $product));
        
        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('products.edit');
        $response->assertViewHas(['product', 'categories']);
    });
    
    test('can update existing product', function () {
        // Create product and category data
        $product = Product::factory()->create();
        $newCategory = Category::factory()->create();
        
        $updatedData = [
            'name' => 'Updated Product Name',
            'price' => 2000.00,
            'stock' => 75,
            'category_id' => $newCategory->id
        ];
        
        // Perform product update
        $response = $this->put(route('product.update', $product), $updatedData);
        
        // Assert
        $response->assertRedirect(route('product.index'));
        $response->assertSessionHas('success', 'Product updated successfully');
        
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'category_id' => $newCategory->id
        ]);
    });
    
    test('can delete product', function () {
        // Create product data
        $product = Product::factory()->create();
        
        // Perform product deletion
        $response = $this->delete(route('product.destroy', $product));
        
        // Assert
        $response->assertRedirect(route('product.index'));
        $response->assertSessionHas('success', 'Product deleted successfully');
        
        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    });
});

describe('product model', function() {
    test('product has correct fillable attributes', function () {
        $product = new Product();

        expect($product->getFillable())
            ->toBe(['code', 'name', 'price', 'stock', 'category_id'])
            ->toHaveCount(5);
    });

    test('product belongs to category', function () {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id
        ]);
        
        expect($product->category)
            ->toBeInstanceOf(Category::class)
            ->id->toBe($category->id);
    });

    test('product can be created using factory', function () {
        $product = Product::factory()->create();
        
        expect($product)
            ->toBeInstanceOf(Product::class)
            ->name->not->toBeEmpty()
            ->code->toMatch('/^PROD-\w+$/')
            ->price->toBeGreaterThan(0)
            ->stock->toBeGreaterThanOrEqual(0)
            ->category_id->not->toBeNull();
    });
});

