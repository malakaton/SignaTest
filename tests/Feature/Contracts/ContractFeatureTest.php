<?php

namespace Tests\Feature\Contracts;

use App\Signaturit\Domain\Services\Contracts\ResolveServices;
use Tests\TestCase;

class ContractFeatureTest extends TestCase
{
    /**
     * @test
     */
    public function get_plaintiff_is_winner_works(): void
    {
        $params['plaintiff']['signatures'] = 'NNV';
        $params['defendant']['signatures'] = 'VVV';

        $response = $this->request('POST', '/api/contracts', $params);

        $content = json_decode($response->getContent());

        $response->assertStatus(200)
            ->assertJsonStructure([
          'data' => [],
          'meta' => ['success', 'errors'],
        ]);

        $this->assertTrue($content->meta->success);
        $this->assertEmpty($content->meta->errors);
        $this->assertNotEmpty($content->data);
        $this->assertEquals(ResolveServices::PLAINTIFF_WINS, $content->data->winner);
    }

    /**
     * @test
     */
    public function get_defendant_is_winner_works(): void
    {
        $params['defendant']['signatures'] = 'NNV';
        $params['plaintiff']['signatures'] = 'VVV';

        $response = $this->request('POST', '/api/contracts', $params);

        $content = json_decode($response->getContent());

        $response->assertStatus(200)
            ->assertJsonStructure([
          'data' => [],
          'meta' => ['success', 'errors'],
        ]);

        $this->assertTrue($content->meta->success);
        $this->assertEmpty($content->meta->errors);
        $this->assertNotEmpty($content->data);
        $this->assertEquals(ResolveServices::DEFENDANT_WINS, $content->data->winner);
    }

    /**
     * @test
     */
    public function get_tie_on_lawsuit_works(): void
    {
        $params['defendant']['signatures'] = 'NNV';
        $params['plaintiff']['signatures'] = 'NNV';

        $response = $this->request('POST', '/api/contracts', $params);

        $content = json_decode($response->getContent());

        $response->assertStatus(200)
            ->assertJsonStructure([
          'data' => [],
          'meta' => ['success', 'errors'],
        ]);

        $this->assertTrue($content->meta->success);
        $this->assertEmpty($content->meta->errors);
        $this->assertNotEmpty($content->data);
        $this->assertEquals(ResolveServices::TIE_RESULT, $content->data->winner);
    }

    /**
     * @test
     */
    public function get_points_to_make_winner_defendant_works(): void
    {
        $params['plaintiff']['signatures'] = 'NVV';
        $params['defendant']['signatures'] = 'N#V';

        $response = $this->request('POST', '/api/contracts/calculate/points_to_win', $params);

        $content = json_decode($response->getContent());

        $response->assertStatus(200)
            ->assertJsonStructure([
          'data' => [],
          'meta' => ['success', 'errors'],
        ]);

        $this->assertTrue($content->meta->success);
        $this->assertEmpty($content->meta->errors);
        $this->assertNotEmpty($content->data);
    }

    /**
     * @test
     */
    public function get_points_to_make_winner_plaintiff_works(): void
    {
        $params['plaintiff']['signatures'] = 'N#V';
        $params['defendant']['signatures'] = 'KK';

        $response = $this->request('POST', '/api/contracts/calculate/points_to_win', $params);

        $content = json_decode($response->getContent());

        $response->assertStatus(200)
            ->assertJsonStructure([
            'data' => [],
            'meta' => ['success', 'errors'],
        ]);

        $this->assertTrue($content->meta->success);
        $this->assertEmpty($content->meta->errors);
        $this->assertNotEmpty($content->data);
    }

    /**
     * @test
     */
    public function get_winner_bad_signature_fail(): void
    {
        $params['plaintiff']['signatures'] = '1,V';
        $params['defendant']['signatures'] = 'VVV';

        $response = $this->request('POST', '/api/contracts', $params);

        $content = json_decode($response->getContent());

        $response->assertStatus(422)
            ->assertJsonStructure([
            'message',
            'errors'
        ]);
        $error = (array) $content->errors;

        $this->assertNotEmpty($content->message);
        $this->assertNotEmpty($content->errors);
        $this->assertObjectHasAttribute('errors', $content);
        $this->assertObjectNotHasAttribute('data', $content);
        $this->assertEquals(__('validation.signature'), array_shift($error['plaintiff.signatures']));
    }
}
