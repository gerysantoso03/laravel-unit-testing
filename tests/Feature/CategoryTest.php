<?php

use App\Models\Category;
use App\Models\Product;

describe('category controller', function(){
    test('can view category listing page', function() {
        // Create categories data
        Category::factory()->count(10)->create();
    
        $response = $this->get(route('category.index'));
    
        // Request result assertion
        $response
            ->assertStatus(200)
            ->assertViewIs('categories.index')
            ->assertViewHas('categories');
    });
    
    test('can view category creation page', function() {
        $response = $this->get(route('category.create'));
    
        // Request result assertion
        $response
            ->assertStatus(200)
            ->assertViewIs('categories.create');
    });
    
    test('can create new category', function() {
        // Category data
        $newCategory = [
            'name' => 'Home Goods', 
            'description' => 'Home goods for everyday use'
        ];
    
        $response = $this->post(route('category.store'), $newCategory);
    
        // Request result assertion
        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Category created successfully.');
    
        // Get latest category data
        $latestCategory = Category::latest('id')->first();
        expect($latestCategory)
            ->name->toBe($newCategory['name'])
            ->description->toBe($newCategory['description']);
    });
    
    test('cannot create new category with duplicate name', function () {
        // Create existing category data
        Category::factory()->create(['name' => 'Home Goods']);
    
        // New category data with same name 
        $newCategory = [
            'name' => 'Home Goods',
            'description' => 'Home goods for everyday use',
        ];
    
        $response = $this->post(route('category.store'), $newCategory);
    
        // Request result assertion
        $response->assertSessionHasErrors('name');
    });
    
    test('can view category details', function () {
        // Create category data
        $category = Category::factory()->create();
    
        $response = $this->get(route('category.show', $category));
    
        // Request result assertion
        $response
            ->assertStatus(200)
            ->assertViewIs('categories.show')
            ->assertViewHas('category');
    });
    
    test('can view category edit page', function () {
        // Create category data
        $category = Category::factory()->create();
    
        $response = $this->get(route('category.edit', $category));
    
        // Request result assertion
        $response
            ->assertStatus(200)
            ->assertViewIs('categories.edit')
            ->assertViewHas('category');
    });
    
    test('can update existing category', function () {
        // Create new category data
        $category = Category::factory()->create();
    
        // Updated category data
        $updatedCategoryData = [
            'name' => 'Electronics',
            'description' => 'Collections of electronic goods'
        ];
    
        $response = $this->put(route('category.update', $category), $updatedCategoryData);
    
        // Request result assertion
        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Category updated successfully.');
    
        // Check updated category result data
        $updatedCategory = Category::find($category->id);
        expect($updatedCategory)
            ->name->toBe($updatedCategoryData['name'])
            ->description->toBe($updatedCategoryData['description']);
    });
    
    test('can delete existing category', function () {
        // Create new category data
        $category = Category::factory()->create();
    
        $response = $this->delete(route('category.destroy', $category));
    
        // Request result assertion
        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Category deleted successfully');
        
        // Check category has been deleted in database
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    });
});

describe('category model', function(){
    test('category model has correct fillable attributes', function () {
        $category = new Category();
        
        expect($category->getFillable())
            ->toBe(['name', 'description'])
            ->toHaveCount(2);
    });
    
    test('category has products relationship', function () {
        // Create a category with products
        $category = Category::factory()
            ->has(Product::factory()->count(3))
            ->create();
    
        // Test relationship and counts
        expect($category->products)
            ->toHaveCount(3)
            ->each->toBeInstanceOf(Product::class);
    });
    
    test('category can be created using factory', function () {
        $category = Category::factory()->create();
        
        expect($category)
            ->toBeInstanceOf(Category::class)
            ->name->not->toBeEmpty()
            ->description->not->toBeEmpty();
    });
    
    test('can add products to category', function () {
        // Create a category
        $category = Category::factory()->create();
        
        // Create and associate products
        $products = Product::factory()
            ->count(2)
            ->create(['category_id' => $category->id]);
        
        // Refresh the model to get the relationships
        $category->refresh();
        
        expect($category->products)->toHaveCount(2);
        expect($category->products->pluck('id'))->toEqual($products->pluck('id'));
    });
});
