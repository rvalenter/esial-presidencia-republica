<script setup>
import {onMounted, ref, watch} from 'vue';

const props = defineProps({
  notaTecnica: {
    type: Object,
    default: null,
  },
})
const emits = defineEmits(['setMenuNTEdicao']);
const active = ref(1);
const step1 = ref(false);
const step2 = ref(false);
const step3 = ref(false);
const step4 = ref(false);
const finished = ref(false);
const type = ref(null);

function setMenuNTEdicao(arg) {
  emits('setMenuNTEdicao', arg);
  active.value = arg;
}

watch(() => props.notaTecnica, (newNt) => {
  step1.value = false;
  step2.value = false;
  step3.value = false;
  step4.value = false;

  if (!newNt) return;

  finished.value = newNt.concluida;

  if (
    newNt.esial_nota_tecnica_posicionamento_id &&
    newNt.esial_nota_tecnica_impacto_tipo_id &&
    newNt.esial_nota_tecnica_impacto_intensidade_id &&
    checkPosicao(newNt) &&
    // newNt.referencia_relacao_completo.length > 0 &&
    newNt.data_referencia !== null
  ) {
    step1.value = true;
  }

  if (newNt.criticos) {
    step2.value = newNt.criticos.length > 0;
  }

  if (newNt.meritos) {
    step3.value = newNt.meritos.length > 0;
  }

  if (newNt.conclusoes) {
    step4.value = newNt.conclusoes.length > 0;
  }
});

function checkPosicao(newNt) {
  if ([3,4].includes(newNt.esial_nota_tecnica_posicionamento_id)) {
    if (newNt.posicao_justificativa === null) {
      return false;
    }

    return newNt.posicao_justificativa.value !== null;
  }

  return true;
}

watch(() => props.notaTecnica, (Nt) => {
  if (!Nt) return;

  type.value = Nt.tipo;
});
</script>

<template>
  <nav class="w-full h-14 rounded-xl border border-gray-400">
    <ol
      class="grid grid-cols-12"
      role="list"
    >
      <li
        class="flex justify-between  cursor-pointer group"
        :class="[type == 0 ? 'col-span-3' : 'col-span-6']"
        @click="setMenuNTEdicao(1)"
      >
        <div class="flex w-full h-14 items-center px-4">
          <i
            v-if="step1"
            :class="[active === 1 ? 'text-sky-600' : finished ? 'text-green-600' : 'text-gray-300']"
            class="fa-solid fa-lg fa-circle-check mr-2"
          />
          <div
            v-if="!step1"
            :class="[active === 1 ? 'ring-sky-600 text-sky-700 font-bold' : 'text-gray-500 ring-gray-300']"
            class="rounded-full h-5 w-5 ring-2 ring-gray-300 mr-2 flex justify-center items-center text-xs text-gray-500"
          >
            01
          </div>
          <p
            :class="[active === 1 ? 'font-bold' : 'font-light']"
            class="group-hover:text-sky-700 text-sm"
          >
            Posição
          </p>
        </div>
        <div class="h-16 relative -top-1.5">
          <svg
            class="h-16"
            fill="none"
            stroke="#d1d5db"
            stroke-width="0.5"
            viewBox="0 0 18.00 24.00"
            xmlns="http://www.w3.org/2000/svg"
          >
            <g
              id="SVGRepo_bgCarrier"
              stroke-width="0"
            />
            <g
              id="SVGRepo_tracerCarrier"
              stroke="#CCCCCC"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="0.05"
            />
            <g id="SVGRepo_iconCarrier">
              <path
                d="M7.6728 22L16.1434 13.0294C16.4081 12.75 16.4081 12.3088 16.1434 12.0147L7.65808 3"
                stroke="#d1d5db"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-miterlimit="10"
              />
            </g>
          </svg>
        </div>
      </li>
      <li
        v-show="type == 0"
        class="flex justify-between col-span-3 cursor-pointer group"
        @click="setMenuNTEdicao(2)"
      >
        <div class="flex w-full h-14 items-center px-4">
          <i
            v-if="step2"
            :class="[active === 2 ? 'text-sky-600' : finished ? 'text-green-600' : 'text-gray-300']"
            class="fa-solid fa-lg fa-circle-check mr-2"
          />
          <div
            v-if="!step2"
            :class="[active === 2 ? 'ring-sky-600 text-sky-700 font-bold' : 'text-gray-500 ring-gray-300']"
            class="rounded-full h-5 w-5 ring-2 ring-gray-300 mr-2 flex justify-center items-center text-xs text-gray-500"
          >
            02
          </div>
          <p
            :class="[active === 2 ? 'font-bold' : 'font-light']"
            class="group-hover:text-sky-700 text-sm"
          >
            Pontos Centrais
          </p>
        </div>
        <div class="h-16 relative -top-1.5">
          <svg
            class="h-16"
            fill="none"
            stroke="#d1d5db"
            stroke-width="0.5"
            viewBox="0 0 18.00 24.00"
            xmlns="http://www.w3.org/2000/svg"
          >
            <g
              id="SVGRepo_bgCarrier"
              stroke-width="0"
            />
            <g
              id="SVGRepo_tracerCarrier"
              stroke="#CCCCCC"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="0.05"
            />
            <g id="SVGRepo_iconCarrier">
              <path
                d="M7.6728 22L16.1434 13.0294C16.4081 12.75 16.4081 12.3088 16.1434 12.0147L7.65808 3"
                stroke="#d1d5db"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-miterlimit="10"
              />
            </g>
          </svg>
        </div>
      </li>
      <li
        v-show="type == 0"
        class="flex justify-between col-span-3 cursor-pointer group"
        @click="setMenuNTEdicao(3)"
      >
        <div class="flex w-full h-14 items-center px-4">
          <i
            v-if="step3"
            :class="[active === 3 ? 'text-sky-600' : finished ? 'text-green-600' : 'text-gray-300']"
            class="fa-solid fa-lg fa-circle-check mr-2"
          />
          <div
            v-if="!step3"
            :class="[active === 3 ? 'ring-sky-600 text-sky-700 font-bold' : 'text-gray-500 ring-gray-300']"
            class="rounded-full h-5 w-5 ring-2 ring-gray-300 mr-2 flex justify-center items-center text-xs text-gray-500"
          >
            03
          </div>
          <p
            :class="[active === 3 ? 'font-bold' : 'font-light']"
            class="group-hover:text-sky-700  text-sm"
          >
            Análise do Mérito
          </p>
        </div>
        <div class="h-16 relative -top-1.5">
          <svg
            class="h-16"
            fill="none"
            stroke="#d1d5db"
            stroke-width="0.5"
            viewBox="0 0 18.00 24.00"
            xmlns="http://www.w3.org/2000/svg"
          >
            <g
              id="SVGRepo_bgCarrier"
              stroke-width="0"
            />
            <g
              id="SVGRepo_tracerCarrier"
              stroke="#CCCCCC"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="0.05"
            />
            <g id="SVGRepo_iconCarrier">
              <path
                d="M7.6728 22L16.1434 13.0294C16.4081 12.75 16.4081 12.3088 16.1434 12.0147L7.65808 3"
                stroke="#d1d5db"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-miterlimit="10"
              />
            </g>
          </svg>
        </div>
      </li>
      <li
        class="flex justify-between cursor-pointer group"
        :class="[type == 0 ? 'col-span-3' : 'col-span-6']"
        @click="setMenuNTEdicao(4)"
      >
        <div class="flex w-full h-14 items-center px-4">
          <i
            v-if="step4"
            :class="[active === 4 ? 'text-sky-600' : finished ? 'text-green-600' : 'text-gray-300']"
            class="fa-solid fa-lg fa-circle-check mr-2"
          />
          <div
            v-if="!step4"
            :class="[active === 4 ? 'ring-sky-600 text-sky-700 font-bold' : 'text-gray-500 ring-gray-300']"
            class="rounded-full h-5 w-5 ring-2  mr-2 flex justify-center items-center text-xs"
          >
            04
          </div>
          <p
            :class="[active === 4 ? 'font-bold' : 'font-light']"
            class="group-hover:text-sky-700 text-sm"
          >
            {{ type == 0 ? 'Conclusão' : 'Nota Técnica SEI' }}
          </p>
        </div>
      </li>
    </ol>
  </nav>
</template>

<style scoped>
.btn {
  @apply w-full hover:bg-gray-200 hover:text-gray-700 p-2 cursor-pointer rounded-lg
}
</style>
