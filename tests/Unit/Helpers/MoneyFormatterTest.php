<?php

test('format price price to rupiah', function () {
    expect(format_rupiah(15000))->toBe('Rp 15.000');
    expect(format_rupiah(5000000))->toBe('Rp 5.000.000');
    expect(format_rupiah(0))->toBe('Rp 0');
    expect(format_rupiah(null))->toBe('Rp 0');
});

test('format decimal price value to rupiah', function () {
    expect(format_rupiah(1546.99))->toBe('Rp 1.547');
    expect(format_rupiah(57482.225))->toBe('Rp 57.482');
    expect(format_rupiah(49483.56))->toBe('Rp 49.484');
});

