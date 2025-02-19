import pluginVue from 'eslint-plugin-vue'

export default [
  ...pluginVue.configs['flat/recommended'],
  {
    ignores: ['node_modules/*', 'vendor/*', '**/vendor/*', '*/vendor/*'],
    rules: {
      'vue/no-unused-vars': 'error',
      'vue/no-unused-components': 'error',
      'vue/no-unused-properties': 'error',
      'vue/multi-word-component-names': 'off',
      'vue/no-v-html': 'off'
    }
  }
]
