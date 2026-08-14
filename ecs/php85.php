<?php declare(strict_types=1);

use SlevomatCodingStandard\Sniffs\Attributes\AttributeAndTargetSpacingSniff;
use SlevomatCodingStandard\Sniffs\Attributes\DisallowAttributesJoiningSniff;
use SlevomatCodingStandard\Sniffs\Classes\BackedEnumTypeSpacingSniff;
use SlevomatCodingStandard\Sniffs\Classes\EnumCaseSpacingSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowArrayTypeHintSyntaxSniff;

/**
 * Additive Kristijorgji ECS rules for PHP 8.5 apps
 * (ported from KristijorgjiCodingStandard85/ruleset.xml).
 *
 * @return array{
 *     rules: list<class-string>,
 *     rulesWithConfiguration: array<class-string, array<string, mixed>>,
 *     skip: list<string>|array<string, list<string>>
 * }
 */
return [
    'rules' => [
        BackedEnumTypeSpacingSniff::class,
        EnumCaseSpacingSniff::class,
        AttributeAndTargetSpacingSniff::class,
        DisallowAttributesJoiningSniff::class,
        DisallowArrayTypeHintSyntaxSniff::class,
    ],
    'rulesWithConfiguration' => [],
    'skip' => [],
];
