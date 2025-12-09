<?php

declare(strict_types=1);

use App\Models\PageContent;
use App\Models\SectionSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/** HOME PAGE EMPTY STATE TESTS */
test('home page shows empty state when no content exists', function () {
    // Given: No content in database

    // When: Visiting home page
    $response = $this->get(route('home'));

    // Then: Should see empty state
    $response->assertStatus(200)
        ->assertSee(__('portfolio.empty_state.home.title'))
        ->assertSee(__('portfolio.empty_state.home.description'))
        ->assertDontSee('home_hero_title');
});

test('home page shows empty state when hero content is incomplete', function () {
    // Given: Only 2 out of 3 hero fields exist
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => 'Title', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_subtitle', 'content' => 'Subtitle', 'page' => 'home']);
    // home_hero_text is missing

    // When: Visiting home page
    $response = $this->get(route('home'));

    // Then: Should see empty state
    $response->assertStatus(200)
        ->assertSee(__('portfolio.empty_state.home.title'));
});

test('home page shows empty state when features enabled but incomplete', function () {
    // Given: Hero fields exist
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => 'Title', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_subtitle', 'content' => 'Subtitle', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_text', 'content' => 'Text', 'page' => 'home']);

    // And: Features section is enabled
    SectionSetting::factory()->create(['section_key' => 'features', 'page' => 'home', 'is_enabled' => true]);

    // And: Only 5 out of 6 features fields exist
    PageContent::factory()->create(['key' => 'home_feature_1_title', 'content' => 'Feature 1', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_feature_1_description', 'content' => 'Desc 1', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_feature_2_title', 'content' => 'Feature 2', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_feature_2_description', 'content' => 'Desc 2', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_feature_3_title', 'content' => 'Feature 3', 'page' => 'home']);
    // home_feature_3_description is missing

    // When: Visiting home page
    $response = $this->get(route('home'));

    // Then: Should see empty state
    $response->assertStatus(200)
        ->assertSee(__('portfolio.empty_state.home.title'));
});

test('home page shows normal content with complete hero and disabled features', function () {
    // Given: Hero fields exist
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => 'My Title', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_subtitle', 'content' => 'My Subtitle', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_text', 'content' => 'My Text', 'page' => 'home']);

    // And: Features section is explicitly disabled
    SectionSetting::factory()->create(['section_key' => 'features', 'page' => 'home', 'is_enabled' => false]);

    // When: Visiting home page
    $response = $this->get(route('home'));

    // Then: Should see normal content
    $response->assertStatus(200)
        ->assertSee('My Title')
        ->assertSee('My Subtitle')
        ->assertSee('My Text')
        ->assertDontSee(__('portfolio.empty_state.home.title'));
});

test('home page shows normal content with complete hero and complete features', function () {
    // Given: Hero fields exist
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => 'My Title', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_subtitle', 'content' => 'My Subtitle', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_text', 'content' => 'My Text', 'page' => 'home']);

    // And: All features fields exist
    PageContent::factory()->create(['key' => 'home_feature_1_title', 'content' => 'Feature 1', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_feature_1_description', 'content' => 'Desc 1', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_feature_2_title', 'content' => 'Feature 2', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_feature_2_description', 'content' => 'Desc 2', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_feature_3_title', 'content' => 'Feature 3', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_feature_3_description', 'content' => 'Desc 3', 'page' => 'home']);

    // And: Features section is enabled
    SectionSetting::factory()->create(['section_key' => 'features', 'page' => 'home', 'is_enabled' => true]);

    // When: Visiting home page
    $response = $this->get(route('home'));

    // Then: Should see normal content
    $response->assertStatus(200)
        ->assertSee('My Title')
        ->assertSee('Feature 1')
        ->assertDontSee(__('portfolio.empty_state.home.title'));
});

/** ABOUT PAGE EMPTY STATE TESTS */
test('about page shows empty state when no content exists', function () {
    // Given: No content in database

    // When: Visiting about page
    $response = $this->get(route('about'));

    // Then: Should see empty state
    $response->assertStatus(200)
        ->assertSee(__('portfolio.empty_state.about.title'))
        ->assertSee(__('portfolio.empty_state.about.description'));
});

test('about page shows empty state when hero bio content is incomplete', function () {
    // Given: Only 2 out of 3 hero+bio fields exist
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'Title', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_intro', 'content' => 'Intro', 'page' => 'about']);
    // about_bio is missing

    // When: Visiting about page
    $response = $this->get(route('about'));

    // Then: Should see empty state
    $response->assertStatus(200)
        ->assertSee(__('portfolio.empty_state.about.title'));
});

test('about page shows normal content with complete hero bio and disabled sections', function () {
    // Given: Hero + bio fields exist
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'My Title', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_intro', 'content' => 'My Intro', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_bio', 'content' => 'My Bio', 'page' => 'about']);

    // And: Optional sections are explicitly disabled
    SectionSetting::factory()->create(['section_key' => 'experience', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'services', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'philosophy', 'page' => 'about', 'is_enabled' => false]);

    // When: Visiting about page
    $response = $this->get(route('about'));

    // Then: Should see normal content
    $response->assertStatus(200)
        ->assertSee('My Title')
        ->assertSee('My Intro')
        ->assertSee('My Bio')
        ->assertDontSee(__('portfolio.empty_state.about.title'));
});

test('about page shows empty state when services enabled but incomplete', function () {
    // Given: Hero + bio fields exist
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'Title', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_intro', 'content' => 'Intro', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_bio', 'content' => 'Bio', 'page' => 'about']);

    // And: Services section is enabled
    SectionSetting::factory()->create(['section_key' => 'services', 'page' => 'about', 'is_enabled' => true]);

    // And: Only 5 out of 6 services exist
    PageContent::factory()->create(['key' => 'about_service_1', 'content' => 'Service 1', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_service_2', 'content' => 'Service 2', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_service_3', 'content' => 'Service 3', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_service_4', 'content' => 'Service 4', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_service_5', 'content' => 'Service 5', 'page' => 'about']);
    // about_service_6 is missing

    // When: Visiting about page
    $response = $this->get(route('about'));

    // Then: Should see empty state
    $response->assertStatus(200)
        ->assertSee(__('portfolio.empty_state.about.title'));
});

/** CONTACT PAGE EMPTY STATE TESTS */
test('contact page shows empty state when no content exists', function () {
    // Given: No content in database

    // When: Visiting contact page
    $response = $this->get(route('contact'));

    // Then: Should see empty state
    $response->assertStatus(200)
        ->assertSee(__('portfolio.empty_state.contact.title'))
        ->assertSee(__('portfolio.empty_state.contact.description'));
});

test('contact page shows empty state when content is incomplete', function () {
    // Given: Only 5 out of 6 fields exist
    PageContent::factory()->create(['key' => 'contact_title', 'content' => 'Title', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_subtitle', 'content' => 'Subtitle', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_description', 'content' => 'Description', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_email', 'content' => 'test@example.com', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_phone', 'content' => '+33 6 XX XX XX XX', 'page' => 'contact']);
    // contact_location is missing

    // When: Visiting contact page
    $response = $this->get(route('contact'));

    // Then: Should see empty state
    $response->assertStatus(200)
        ->assertSee(__('portfolio.empty_state.contact.title'));
});

test('contact page shows normal content with all fields complete', function () {
    // Given: All 6 contact fields exist
    PageContent::factory()->create(['key' => 'contact_title', 'content' => 'Get in Touch', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_subtitle', 'content' => 'Let\'s talk', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_description', 'content' => 'Contact us today', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_email', 'content' => 'test@example.com', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_phone', 'content' => '+33 6 XX XX XX XX', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_location', 'content' => 'Paris, France', 'page' => 'contact']);

    // When: Visiting contact page
    $response = $this->get(route('contact'));

    // Then: Should see normal content
    $response->assertStatus(200)
        ->assertSee('Get in Touch')
        ->assertSee('test@example.com')
        ->assertDontSee(__('portfolio.empty_state.contact.title'));
});
