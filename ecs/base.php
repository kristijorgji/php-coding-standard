<?php declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Phpdoc\AlignMultilineCommentFixer;
use PhpCsFixer\Fixer\Phpdoc\NoEmptyPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocIndentFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSingleLineVarSpacingFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitSetUpTearDownVisibilityFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Strings\UnnecessaryStringConcatSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\ScopeIndentSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Files\EndFileNewlineSniff;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Methods\FunctionCallSignatureSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Functions\MultiLineFunctionDeclarationSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\SuperfluousWhitespaceSniff;
use PhpCsFixerCustomFixers\Fixer\PhpUnitAssertArgumentsOrderFixer;
use PhpCsFixerCustomFixers\Fixer\PhpUnitDedicatedAssertFixer;
use SlevomatCodingStandard\Sniffs\Arrays\DisallowImplicitArrayCreationSniff;
use SlevomatCodingStandard\Sniffs\Arrays\TrailingArrayCommaSniff;
use SlevomatCodingStandard\Sniffs\Classes\ClassConstantVisibilitySniff;
use SlevomatCodingStandard\Sniffs\Classes\MethodSpacingSniff;
use SlevomatCodingStandard\Sniffs\Classes\RequireConstructorPropertyPromotionSniff;
use SlevomatCodingStandard\Sniffs\Classes\RequireMultiLineMethodSignatureSniff;
use SlevomatCodingStandard\Sniffs\Classes\TraitUseDeclarationSniff;
use SlevomatCodingStandard\Sniffs\Classes\UselessLateStaticBindingSniff;
use SlevomatCodingStandard\Sniffs\Commenting\EmptyCommentSniff;
use SlevomatCodingStandard\Sniffs\Commenting\UselessInheritDocCommentSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowContinueWithoutIntegerOperandInSwitchSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowShortTernaryOperatorSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowYodaComparisonSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\NewWithoutParenthesesSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\RequireMultiLineConditionSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\RequireNullCoalesceOperatorSniff;
use SlevomatCodingStandard\Sniffs\Exceptions\ReferenceThrowableOnlySniff;
use SlevomatCodingStandard\Sniffs\Exceptions\RequireNonCapturingCatchSniff;
use SlevomatCodingStandard\Sniffs\Functions\DisallowTrailingCommaInCallSniff;
use SlevomatCodingStandard\Sniffs\Functions\DisallowTrailingCommaInClosureUseSniff;
use SlevomatCodingStandard\Sniffs\Functions\DisallowTrailingCommaInDeclarationSniff;
use SlevomatCodingStandard\Sniffs\Functions\RequireMultiLineCallSniff;
use SlevomatCodingStandard\Sniffs\Functions\RequireTrailingCommaInCallSniff;
use SlevomatCodingStandard\Sniffs\Functions\RequireTrailingCommaInDeclarationSniff;
use SlevomatCodingStandard\Sniffs\Functions\UnusedInheritedVariablePassedToClosureSniff;
use SlevomatCodingStandard\Sniffs\Functions\UnusedParameterSniff;
use SlevomatCodingStandard\Sniffs\Functions\UselessParameterDefaultValueSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UnusedUsesSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseFromSameNamespaceSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UselessAliasSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseSpacingSniff;
use SlevomatCodingStandard\Sniffs\Operators\DisallowEqualOperatorsSniff;
use SlevomatCodingStandard\Sniffs\PHP\DisallowDirectMagicInvokeCallSniff;
use SlevomatCodingStandard\Sniffs\PHP\TypeCastSniff;
use SlevomatCodingStandard\Sniffs\PHP\UselessParenthesesSniff;
use SlevomatCodingStandard\Sniffs\PHP\UselessSemicolonSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DNFTypeHintFormatSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSpacingSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\UselessConstantTypeHintSniff;
use SlevomatCodingStandard\Sniffs\Variables\DuplicateAssignmentToVariableSniff;
use SlevomatCodingStandard\Sniffs\Variables\UnusedVariableSniff;
use SlevomatCodingStandard\Sniffs\Variables\UselessVariableSniff;

/**
 * Shared Kristijorgji ECS base rules (ported from KristijorgjiCodingStandard/ruleset.xml)
 * plus auto-fixable wrapping sniffs and PHP-CS-Fixer phpdoc/comment fixers.
 *
 * @return array{
 *     rules: list<class-string>,
 *     rulesWithConfiguration: array<class-string, array<string, mixed>>,
 *     skip: list<string>|array<string, list<string>>
 * }
 */
return [
    'rules' => [
        // Ported from KristijorgjiCodingStandard/ruleset.xml
        AlphabeticallySortedUsesSniff::class,
        UselessInheritDocCommentSniff::class,
        ParameterTypeHintSniff::class,
        PropertyTypeHintSniff::class,
        ReturnTypeHintSniff::class,
        UselessConstantTypeHintSniff::class,
        UnusedInheritedVariablePassedToClosureSniff::class,
        UnusedParameterSniff::class,
        UselessParameterDefaultValueSniff::class,
        UselessParenthesesSniff::class,
        UselessSemicolonSniff::class,
        UselessVariableSniff::class,
        TypeCastSniff::class,
        ParameterTypeHintSpacingSniff::class,
        DisallowImplicitArrayCreationSniff::class,
        DisallowContinueWithoutIntegerOperandInSwitchSniff::class,
        DisallowEmptySniff::class,
        DisallowShortTernaryOperatorSniff::class,
        EmptyCommentSniff::class,
        SuperfluousWhitespaceSniff::class,
        EndFileNewlineSniff::class,
        NewWithoutParenthesesSniff::class,
        TrailingArrayCommaSniff::class,
        RequireTrailingCommaInCallSniff::class,
        RequireTrailingCommaInDeclarationSniff::class,
        DisallowYodaComparisonSniff::class,
        RequireNullCoalesceOperatorSniff::class,
        RequireConstructorPropertyPromotionSniff::class,
        UselessLateStaticBindingSniff::class,
        DisallowDirectMagicInvokeCallSniff::class,
        DisallowEqualOperatorsSniff::class,
        ReferenceThrowableOnlySniff::class,
        RequireNonCapturingCatchSniff::class,
        UseFromSameNamespaceSniff::class,
        UselessAliasSniff::class,
        UnusedVariableSniff::class,
        TraitUseDeclarationSniff::class,
        DisallowMixedTypeHintSniff::class,
        DuplicateAssignmentToVariableSniff::class,

        // Wrapping / indent (auto-fixable "line too long" helpers)
        FunctionCallSignatureSniff::class,
        MultiLineFunctionDeclarationSniff::class,
        RequireMultiLineCallSniff::class,
        RequireMultiLineMethodSignatureSniff::class,
        RequireMultiLineConditionSniff::class,

        // PHP-CS-Fixer (phpdoc / comments / imports / whitespace / PHPUnit)
        AlignMultilineCommentFixer::class,
        NoEmptyPhpdocFixer::class,
        PhpdocIndentFixer::class,
        PhpdocSingleLineVarSpacingFixer::class,
        NoUnusedImportsFixer::class,
        IndentationTypeFixer::class,
        PhpUnitConstructFixer::class,
        PhpUnitDedicateAssertFixer::class,
        PhpUnitSetUpTearDownVisibilityFixer::class,
        PhpUnitAssertArgumentsOrderFixer::class,
        PhpUnitDedicatedAssertFixer::class,
    ],
    'rulesWithConfiguration' => [
        DeclareStrictTypesSniff::class => [
            'declareOnFirstLine' => true,
        ],
        UnusedUsesSniff::class => [
            'searchAnnotations' => true,
        ],
        DNFTypeHintFormatSniff::class => [
            'withSpacesAroundOperators' => 'no',
            'withSpacesInsideParentheses' => 'no',
        ],
        UseSpacingSniff::class => [
            'linesCountAfterLastUse' => 1,
            'linesCountBeforeFirstUse' => 1,
            'linesCountBetweenUseTypes' => 0,
        ],
        ClassConstantVisibilitySniff::class => [
            'fixable' => true,
        ],
        LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 120,
            'ignoreComments' => true,
        ],
        ScopeIndentSniff::class => [
            'ignoreIndentationTokens' => [
                'T_COMMENT',
                'T_DOC_COMMENT_OPEN_TAG',
            ],
        ],
        PhpdocAlignFixer::class => [
            'align' => 'left',
        ],
        MethodSpacingSniff::class => [
            'minLinesCount' => 1,
            'maxLinesCount' => 1,
        ],
        ClassAttributesSeparationFixer::class => [
            'elements' => [
                'const' => 'none',
                'property' => 'none',
                'trait_import' => 'none',
                'case' => 'none',
                'method' => 'one',
            ],
        ],
        UnnecessaryStringConcatSniff::class => [
            // true: allow literal splits across lines for the 120-char limit;
            // still forbids pointless same-line `'a' . 'b'`.
            'allowMultiline' => true,
        ],
        DisallowTrailingCommaInCallSniff::class => [
            'onlySingleLine' => true,
        ],
        DisallowTrailingCommaInDeclarationSniff::class => [
            'onlySingleLine' => true,
        ],
        DisallowTrailingCommaInClosureUseSniff::class => [
            'onlySingleLine' => true,
        ],
        PhpUnitTestAnnotationFixer::class => [
            'style' => 'prefix',
        ],
    ],
    'skip' => [
        // These two codes fight other rules when left enabled
        FunctionCallSignatureSniff::class . '.SpaceAfterCloseBracket',
        FunctionCallSignatureSniff::class . '.OpeningIndent',
        // FunctionCallSignature.Indent fights MultiLineFunctionDeclaration over closure "use ("
        FunctionCallSignatureSniff::class . '.Indent',
    ],
];
