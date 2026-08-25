<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class WhatWeDoPageTest extends TestCase
{
    public function test_it_describes_the_theatre_experience_before_the_disciplines(): void
    {
        $response = $this->get(route('what-we-do'));

        $response
            ->assertOk()
            ->assertSee('Bringing the Theatre to You')
            ->assertSee('thoughtfully produced, 45–60 minute performances')
            ->assertSee('We don\'t simply bring a performance to our audience, we bring the theatre experience to them.')
            ->assertSeeInOrder([
                'Bringing the Theatre to You',
                'Disciplines',
            ]);
    }
}
