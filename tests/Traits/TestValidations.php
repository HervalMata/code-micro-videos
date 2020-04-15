<?php
/**
 * Created by PhpStorm.
 * User: Herval
 * Date: 15/04/2020
 * Time: 15:39
 */

namespace Tests\Traits;


use Illuminate\Foundation\Testing\TestResponse;
use Lang;

trait TestValidations
{
    protected function assertInvalidationInStoreAction(
        array $data,
        string $rule,
        array $ruleParams = []
    )
    {
        $response = $this->json('POST', $this->routeStore(), $data);
        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule, $ruleParams);
    }

    protected function assertInvalidationInUpdateAction(
        array $data,
        string $rule,
        array $ruleParams = []
    )
    {
        $response = $this->json('POST', $this->routeUpdate(), $data);
        $fields = array_keys($data);
        $this->assertInvalidationFields($response, $fields, $rule, $ruleParams);
    }

    protected function assertInvalidationFields(
        TestResponse $response,
        array $fields,
        string $rule,
        array $ruleParams = []
    )
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field) {
            $fieldName = str_replace('_', '', $field);
            $response->assertJsonFragment([
                Lang::get("validation.{$rule}", ['attribute' => $fieldName] + $ruleParams)
            ]);
        }
    }
}
