<script setup>
import RadioButton from 'primevue/radiobutton'
import Panel from 'primevue/panel'
import { computed, onMounted, ref } from 'vue'
import Dialog from 'primevue/dialog'
import Password from 'primevue/password'
import { useStore } from 'vuex'

const store = useStore()
const props = defineProps({
  id: {
    type: Number,
    required: true
  }
})
const securityMethod = ref(1)
const visible = ref(false)
const password = ref('')
const password2 = ref('')
const isValidPassword = computed(() => {
  return (
    hasLowercase.value &&
    hasUppercase.value &&
    hasNumber.value &&
    hasValidLength.value &&
    hasSpecialChar.value &&
    equalsPassword.value
  )
})
const hasLowercase = computed(() => /[a-z]/.test(password.value))
const hasUppercase = computed(() => /[A-Z]/.test(password.value))
const hasNumber = computed(() => /\d/.test(password.value))
const hasSpecialChar = computed(() => /[^a-zA-Z0-9]/.test(password.value))
const hasValidLength = computed(() => password.value.length >= 12)
const equalsPassword = computed(() => password.value === password2.value)
const security = computed(() => store.state.login.security)

const savePassword = () => {
  store
    .dispatch('login/storeSecurity', {
      id: props.id,
      password: securityMethod.value === 1 ? ' ' : password.value,
      securityMethod: securityMethod.value
    })
    .then(() => {
      password.value = ''
      password2.value = ''
    })
}

onMounted(() => {
  store.dispatch('login/getSecurityId', props.id).then(() => {
    securityMethod.value = security.value.acesso_tipo.tipo_acesso
  })
})
</script>

<template>
  <div class="mt-4 w-full col-span-2">
    <div>
      <Panel header="Método de Segurança:">
        <div class="flex flex-wrap gap-12 mt-4">
          <div class="flex items-center gap-2">
            <RadioButton
              v-model="securityMethod"
              input-id="code"
              name="securityMethod"
              :value="1"
            />
            <label for="ingredient1">Código de Validação</label>
          </div>
          <div class="flex items-center gap-2" @click="visible = true">
            <RadioButton
              v-model="securityMethod"
              input-id="password"
              name="securityMethod"
              :value="2"
            />
            <label for="password">Senha</label>
          </div>
          <div class="flex items-center gap-2">
            <RadioButton
              v-model="securityMethod"
              input-id="codeAndPassword"
              name="securityMethod"
              :value="3"
            />
            <label for="ingredient3">Autenticação de dois fatores (2FA)</label>
          </div>
        </div>
      </Panel>
    </div>
    <div class="mt-4">
      <Panel header="Definir método de login:">
        <div v-if="securityMethod !== 1">
          <p>Sua nova senha:</p>
          <div class="card">
            <Password v-model="password" toggle-mask>
              <template #header>
                <div class="font-semibold text-xm mb-4">Digite sua senha:</div>
              </template>
              <template #footer>
                <Divider />
                <ul class="pl-2 ml-2 my-0 leading-normal">
                  <li :class="hasUppercase ? 'text-green-600' : 'text-red-500'">
                    Pelo menos uma letra minúscula
                  </li>
                  <li :class="hasLowercase ? 'text-green-600' : 'text-red-500'">
                    Pelo menos uma letra maiúscula
                  </li>
                  <li :class="hasNumber ? 'text-green-600' : 'text-red-500'">
                    Pelo menos um número
                  </li>
                  <li :class="hasSpecialChar ? 'text-green-600' : 'text-red-500'">
                    Pelo menos um caracter especial
                  </li>
                  <li :class="hasValidLength ? 'text-green-600' : 'text-red-500'">
                    Mínimo de 12 caracteres
                  </li>
                </ul>
              </template>
            </Password>
          </div>
          <p class="mt-4">Repetir a senha:</p>
          <div class="card">
            <Password v-model="password2" toggle-mask>
              <template #header>
                <div class="font-semibold text-xm mb-4">Digite sua senha:</div>
              </template>
              <template #footer>
                <Divider />
                <ul class="pl-2 ml-2 my-0 leading-normal">
                  <li :class="password === password2 ? 'text-green-600' : 'text-red-500'">
                    Igual a anterior
                  </li>
                </ul>
              </template>
            </Password>
          </div>
        </div>
        <div v-if="isValidPassword || securityMethod === 1" class="card mt-4">
          <Button label="Salvar" severity="info" @click="savePassword" />
        </div>
      </Panel>
    </div>
  </div>
  <Dialog
    v-model:visible="visible"
    maximizable
    modal
    header="Alerta de segurança:"
    :style="{ width: '50rem' }"
    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
  >
    <div>
      <p style="margin-bottom: 12px; line-height: 1.6">
        Você escolheu utilizar <strong>apenas uma senha</strong> como método de autenticação.
        <span style="color: #dc3545"><strong>Esse método é extremamente vulnerável</strong></span>
        frente a ataques comuns, como <em>roubo de credenciais</em>, <em>phishing</em> e
        <em>força bruta</em>.
      </p>
      <p style="margin-bottom: 12px; line-height: 1.6">
        Dado o nível de <strong>criticidade e confidencialidade</strong> do sistema
        <strong>ESIAL</strong>, a utilização exclusiva de uma senha
        <span style="color: #dc3545">não é suficiente</span> para proteger suas informações.
      </p>
      <ul style="margin-left: 16px; margin-bottom: 12px; line-height: 1.6">
        <li>
          ⚙️ <strong>Ative a autenticação de dois fatores (2FA)</strong> imediatamente para
          adicionar uma camada extra de proteção.
        </li>
        <li>
          🔒 <strong>Guarde sua senha com extremo cuidado</strong>, utilizando gerenciadores de
          senhas seguros.
        </li>
        <li>🚫 <strong>Nunca compartilhe sua senha</strong>, mesmo com pessoas de confiança.</li>
      </ul>
      <p style="font-weight: bold; color: #721c24; line-height: 1.6">
        Lembre-se: A segurança do sistema depende diretamente de suas práticas.
        <span style="color: #dc3545"
          >Utilizar apenas uma senha coloca seus dados e o sistema em risco.</span
        >
      </p>
    </div>
  </Dialog>
</template>

<style scoped></style>
