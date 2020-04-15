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
