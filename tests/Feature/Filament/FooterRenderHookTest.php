<?php

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

test('footer is registered on the panels footer render hook', function () {
    expect(FilamentView::hasRenderHook(PanelsRenderHook::FOOTER))->toBeTrue();

    $footer = FilamentView::renderHook(PanelsRenderHook::FOOTER)->toHtml();

    expect($footer)
        ->toContain('University of Illinois Springfield')
        ->toContain('Information Technology Services');
});

test('footer is not registered on the body end render hook', function () {
    $bodyEnd = FilamentView::renderHook(PanelsRenderHook::BODY_END)->toHtml();

    expect($bodyEnd)->not->toContain('University of Illinois Springfield');
});
