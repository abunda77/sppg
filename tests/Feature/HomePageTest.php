<?php

test('welcome page presents the SPPG Cisadane profile and services', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeTextInOrder([
            'SPPG Cisadane',
            'Satuan Pelayanan Pemenuhan Gizi (SPPG)',
            'Menu MBG',
            'Delivery MBG',
            'Quality Control',
            '2025 All rights reserved',
        ]);
});

test('welcome page keeps authentication links and placeholder navigation', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="'.route('login').'"', false)
        ->assertSee('href="'.route('register').'"', false)
        ->assertSee('data-placeholder-link="organogram" href="#"', false)
        ->assertSee('data-placeholder-link="galeri-sop" href="#"', false)
        ->assertSee('data-placeholder-link="aplikasi-simpel" href="#"', false);
});
