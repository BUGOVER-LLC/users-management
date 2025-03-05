/**
 *   https://typescript-eslint.io/getting-started/
 *   https://eslint.vuejs.org/rules/
*/

module.exports = {
    parser: 'vue-eslint-parser',
    extends: [
        '@vue/eslint-config-typescript',
        '@vue/eslint-config-typescript/recommended',
        '@vue/eslint-config-prettier',
        '@vue/prettier',
        'eslint:recommended',
        'plugin:vue/essential',
        'plugin:vue/recommended',
        'plugin:vue/strongly-recommended',
        'plugin:@typescript-eslint/recommended',
        'prettier',
    ],
    env: {
        browser: true,
        node: true,
        es6: true,
        es2024: true,
        'vue/setup-compiler-macros': true,
    },
    parserOptions: {
        ecmaVersion: 'latest',
        parser: '@typescript-eslint/parser',
        emitDecoratorMetadata: true,
        experimentalDecorators: true,
        disallowAutomaticSingleRunInference: true,
        ecmaFeatures: {
            vue: true,
            ts: true,
            js: true,
        },
        extraFileExtensions: ['.vue'],
    },
    plugins: ['unused-imports', 'simple-import-sort', '@typescript-eslint'],
    overrides: [
        {
            files: ['*.js', '*.jsx', '*.ts', '*.tsx'],
        },
    ],
    rules: {
        '@typescript-eslint/adjacent-overload-signatures': 0,
        '@typescript-eslint/no-unused-vars': 'error',
        '@typescript-eslint/consistent-type-definitions': ['error'],
        '@typescript-eslint/no-unused-expressions': 'off',
        '@typescript-eslint/ban-ts-comment': 'off',
        'no-unused-expressions': 'off',
        'accessor-pairs': 2,
        'camelcase': [2, { properties: 'never' }],
        'constructor-super': 2,
        'eqeqeq': [2, 'allow-null'],
        'handle-callback-err': [2, '^(err|error)$'],
        'jsx-quotes': [2, 'prefer-single'],
        'new-cap': [2, { newIsCap: true, capIsNew: false }],
        'new-parens': 2,
        'simple-import-sort/imports': 'error',
        'simple-import-sort/exports': 'error',
        'unused-imports/no-unused-imports': 'error',
        'vue/no-unused-vars': 'error',
        'vue/no-unused-components': ['error', {
            'ignoreWhenBindingPresent': true,
        }],
        'unused-imports/no-unused-vars': [
            'warn',
            {
                vars: 'all',
                varsIgnorePattern: '^_',
                args: 'after-used',
                argsIgnorePattern: '^_',
            },
        ],
        'sort-imports': [
            'error',
            {
                ignoreDeclarationSort: true,
                ignoreCase: true,
                allowSeparatedGroups: false,
                ignoreMemberSort: false,
            },
        ],
        yoda: [2, 'always'],
        'prettier/prettier': 'error',
    },
};
