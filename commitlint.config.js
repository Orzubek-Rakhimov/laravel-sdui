module.exports = {
  extends: ['@commitlint/config-conventional'],
  rules: {
    'type-enum': [
      2,
      'always',
      [
        'feat',      // New feature
        'fix',       // Bug fix
        'docs',      // Documentation
        'style',     // Code style
        'refactor',  // Code refactoring
        'perf',      // Performance
        'test',      // Tests
        'chore',     // Maintenance
        'ci',        // CI/CD
      ],
    ],
    'subject-case': [2, 'never', ['start-case', 'pascal-case']],
    'subject-full-stop': [2, 'never', '.'],
  },
};
