<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

// Assingning whole test to 'homepage_browser_test' group
pest()->group('browser_test');

it('can register, login, and view dashboard', function() {
    // Visit dashboard page
    $page = visit('/');

    // Redirect back to login and register new account
    $page->assertUrlIs(route('login'))
        ->click('#createAccountBtn') // link to register page
        ->assertUrlIs(route('register'))
        ->fill('name', 'Gery Santoso')
        ->fill('email', 'gerysantos03@gmail.com')
        ->fill('password', '0Plet09!#')
        ->fill('password_confirmation', '0Plet09!#')
        ->click('#registerBtn')
        ->assertUrlIs(route('login')) // Back to login page and fill the credentials
        ->assertSee('Please login to your account')
        ->fill('email', 'gerysantos03@gmail.com')
        ->fill('password', '0Plet09!#')
        ->click('Sign In')
        ->assertUrlIs(route('dashboard'))
        ->assertSee('Welcome Gery Santoso')
        ->click('#user-menu-button') // user profile at navbar
        ->assertSee('Gery Santoso') // See authenticated user fullname
        ->assertSee('gerysantos03@gmail.com')
        ->assertNoAccessibilityIssues()
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors(); // See authenticated user email
});

describe('authenticated user', function() {
    beforeEach(function() {
        // Create new user
        User::factory()->create([
            'name' => 'Gery Santoso',
            'email' => 'gerysantos03@gmail.com',
            'password' => '0Plet09!#'
        ]);

        // Visit login page to get authentication
        $page = visit('/login');
        
        $page->assertSee('Please login to your account')
            ->fill('email', 'gerysantos03@gmail.com')
            ->fill('password', '0Plet09!#')
            ->Submit('Sign In');
    });

    it('can view homepage then navigate to product page and see the content', function () {
        // Visit home page
        $page = visit('/');
    
        // Create products data within its category for product list page
        $products = Product::factory()->for(Category::factory()->create())->count(10)->create();
    
        // Simulate viewing product list page
        $page->click('Products')
            ->assertUrlIs(route('product.index'))
            ->assertNoConsoleLogs()
            ->assertNoJavaScriptErrors()
            ->assertSee('Products');
        
        foreach($products as $product) {
            $page->assertSee($product->name);
            $page->assertSee($product->code);
            $page->assertSee(format_rupiah($product->price));
            $page->assertSee($product->stock);
            $page->assertSee($product->category->name);
        }
    });
    
    it('can view home page then navigate to product page and create new product', function () {
        $page = visit('/');
    
        // Get existing category
        $category = Category::factory()->create();
    
        // View product page list and add new product
        $page->click('Products')
            ->assertUrlIs(route('product.index'))
            ->click('Add Product')
            ->assertUrlIs(route('product.create'))
            ->assertSee('Create New Product')
            ->fill('name', 'Samsung A54')
            ->fill('code', 'SAMA54')
            ->fill('price', (string) 4000000)
            ->fill('stock', (string) 10)
            ->select('category_id', $category->id)
            ->click('Save')
            ->assertUrlIs(route('product.index'))
            ->assertSee('Samsung A54');
    
        // Check if product exists in database
        $this->assertDatabaseHas('products', [
            'name' => 'Samsung A54',
            'code' => 'SAMA54',
            'price' => 4000000,
            'stock' => 10,
            'category_id' => $category->id
        ]);
    });

    it('can view home page then navigate to product page and show product detail', function() {
        // Visit home page
        $page = visit('/');

        // Create existing product and category
        $category = Category::factory()->create();
        $existingProduct = Product::factory()->create([
            'name' => 'Samsung A54',
            'code' => 'PROD-SM45',
            'price' => 5000000,
            'stock' => 10,
            'category_id' => $category->id
        ]);

        // View product list page and update one of the product
        $page->click('Products')
            ->assertUrlIs(route('product.index'))
            ->click($existingProduct->id . '-show-btn')
            ->assertUrlIs(route('product.show', $existingProduct))
            ->assertSee($existingProduct->name)
            ->assertSee($existingProduct->code)
            ->assertSee(format_rupiah($existingProduct->price))
            ->assertSee($existingProduct->stock)
            ->assertSee($existingProduct->category->name);
    });

    it('can view home page then navigate to product page and edit product', function() {
        // Visit home page
        $page = visit('/');

        // Create existing product and category
        $category = Category::factory()->create();
        $existingProduct = Product::factory()->create([
            'name' => 'Samsung A54',
            'code' => 'PROD-SM45',
            'price' => 5000000,
            'stock' => 10,
            'category_id' => $category->id
        ]);

        // Create new category
        $newCategory = Category::factory()->create(['name' => 'Electronics']);

        // View product list page and update one of the product
        $page->click('Products')
            ->assertUrlIs(route('product.index'))
            ->click($existingProduct->id . '-edit-btn')
            ->assertUrlIs(route('product.edit', $existingProduct))
            ->assertValue('input[name=name]', $existingProduct->name)
            ->assertValue('input[name=code]', $existingProduct->code)
            ->assertValue('input[name=stock]', $existingProduct->stock)
            ->assertValue('input[name=price]', $existingProduct->price)
            ->assertValue('select[name=category_id]', $existingProduct->category->id)
            ->fill('name', 'Samsung A52')
            ->fill('price', (string) 10000000)
            ->fill('stock', (string) 200)
            ->select('category_id', $newCategory->id)
            ->click('Save Changes')
            ->assertUrlIs(route('product.index'))
            ->assertSee('Product updated successfully');
        
        // Refresh existing product
        $existingProduct->refresh();

        // Verify existing product data has been updated
        expect($existingProduct)
            ->name->toBe($existingProduct->name)
            ->code->toBe($existingProduct->code)
            ->price->toBe($existingProduct->price)
            ->stock->toBe($existingProduct->stock)
            ->category_id->toBe($existingProduct->category->id);
    });

    it('can view home page then navigate to product page and delete product', function() {
        // Visit home page
        $page = visit('/');

        // Create existing product and category
        $category = Category::factory()->create();
        $existingProduct = Product::factory()->create([
            'name' => 'Samsung A54',
            'code' => 'PROD-SM45',
            'price' => 5000000,
            'stock' => 10,
            'category_id' => $category->id
        ]);

        // View product list page and delete existing product
        $page->click('Products')->assertUrlIs(route('product.index'));

        // Bypass window confirmation
        $page->script('window.confirm = () => true;');

        $page->click($existingProduct->id . '-delete-btn')
            ->assertUrlIs(route('product.index'))
            ->assertSee('Product deleted successfully')
            ->assertDontSee($existingProduct->name);

        // Ensure product has been deleted in the database
        $this->assertDatabaseMissing('products', [
            'id' => $existingProduct->id
        ]);
    });
});

describe('unauthenticated user', function() {
    it('cannot view homepage and redirect back to login page', function() {
        // Visiting home page
        $page = visit('/');

        // Redirect back to login page
        $page->assertUrlIs(route('login'))
            ->assertSee('Welcome Back')
            ->assertSee('Please login to your account');
    });
});

