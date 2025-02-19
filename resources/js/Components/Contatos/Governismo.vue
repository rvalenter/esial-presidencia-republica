<script setup>
import get_contact from "@/Services/Contact/get_contact.js";
import {onMounted, ref, watch} from "vue";

const contact = ref({
  parlamentar_dados: {
    avaliacao: '',
    absoluta: '',
    obstrucao: '',
    abstencao: '',
    presidente: '',
    contra: '',
    a_favor: '',
    ausencia_simples: '',
    cargo_lideranca_y: []
  }
});
const props = defineProps({
  id: {
    type: Number,
    default: null
  }
});

function getContact() {
  if (!props.id) return;

  get_contact.fetchPhoto(props.id).then(res => {
    contact.value = res.data.data;
  });
}

function separarString(str) {
  return str.split(' - ');
}

function setAvaliacao(avaliacao) {
  if (avaliacao === 'Oposição - OP') {
    return [
      'bg-red-500/50',
      'text-red-600',
    ];
  }

  if (avaliacao === 'Base Governo - BG') {
    return [
      'bg-green-600/80',
      'text-green-700',
    ];
  }

  if (avaliacao === 'Oposicao em Disputa - DS') {
    return [
      'bg-yellow-400/80',
      'text-yellow-500',
    ];
  }

  return [
    'bg-lime-400/80',
    'text-lime-500',
  ];
}

watch(props, () => {
  getContact();
});

onMounted(() => {
  getContact();
});
</script>

<template>
  <div class="h-full overflow-y-auto">
    <div class="grid grid-cols-12 gap-8 pl-5 pr-12 pt-4">
      <div
        class="grid grid-cols-4 gap-4"
        :class="[$page.props.auth.isMobile ? 'col-span-12' : 'col-span-8']"
      >
        <div class="col-span-4 -mb-4">
          <p class="text-sky-800 text-xl">
            Estatisticas
          </p>
        </div>
        <div
          class="flex bg-black/10 justify-between transition hover:scale-105 duration-300 ease-in-out col-span-2 h-[7rem] rounded-md shadow-lg p-4 "
        >
          <div class="border-l-4 border-sky-700 h-full" />
          <div class="grid grid-cols-1 text-center">
            <div class="flex justify-end items-end">
              <p class="text-4xl font-light mb-2">
                {{ contact.parlamentar_dados.absoluta }}<span
                  class="text-lg"
                >%</span>
              </p>
            </div>
            <div class="w-full flex pt-1">
              <p class="font-light">
                Adesão Absoluta
              </p>
            </div>
          </div>
        </div>
        <div
          class="flex bg-black/10 justify-between transition hover:scale-105 duration-300 ease-in-out col-span-2 h-[7rem] rounded-md shadow-lg p-4"
        >
          <div class="border-l-4 border-sky-700 h-full" />
          <div class="grid grid-cols-1 text-center">
            <div class="flex justify-end items-end">
              <p class="text-4xl font-light mb-2">
                {{ contact.parlamentar_dados.relativa }}<span
                  class="text-lg"
                >%</span>
              </p>
            </div>
            <div class="w-full flex pt-1">
              <p class="font-light">
                Adesão Relativa
              </p>
            </div>
          </div>
        </div>
        <div class="col-span-4 border-t border-gray-300 mt-2 -mb-3 pt-2">
          <p class="text-sky-800 text-xl">
            Votações
          </p>
        </div>
        <div
          class="col-span-2 flex justify-between bg-black/10 transition hover:scale-105 duration-300 ease-in-out h-[5rem] rounded-md shadow-lg px-3 py-2"
        >
          <div class="border-l-4 border-green-500 h-full" />
          <div class="grid grid-cols-1 text-center">
            <div class="w-full flex justify-end items-start">
              <p class="text-3xl font-light text-gray-600">
                {{ contact.parlamentar_dados.a_favor }}
              </p>
            </div>
            <div class="w-full flex justify-end items-end">
              <p class="text-sm">
                Favorável
              </p>
            </div>
          </div>
        </div>
        <div
          class="col-span-2 flex justify-between bg-black/10 transition hover:scale-105 duration-300 ease-in-out h-[5rem] rounded-md shadow-lg px-3 py-2"
        >
          <div class="border-l-4 border-yellow-500 h-full" />
          <div class="grid grid-cols-1 text-center">
            <div class="w-full flex justify-end items-start">
              <p class="text-3xl font-light text-gray-600">
                {{ contact.parlamentar_dados.ausencia_simples }}
              </p>
            </div>
            <div class="w-full flex justify-end items-end">
              <p class="text-sm">
                Ausencias
              </p>
            </div>
          </div>
        </div>
        <div
          class="col-span-2 flex justify-between bg-black/10 transition hover:scale-105 duration-300 ease-in-out h-[5rem] rounded-md shadow-lg px-3 py-2"
        >
          <div class="border-l-4 border-sky-500 h-full" />
          <div class="grid grid-cols-1 text-center">
            <div class="w-full flex justify-end items-start">
              <p class="text-3xl font-light text-gray-600">
                {{ contact.parlamentar_dados.abstencao }}
              </p>
            </div>
            <div class="w-full flex justify-end items-end">
              <p class="text-sm">
                Abstenções
              </p>
            </div>
          </div>
        </div>
        <div
          class="col-span-2 flex justify-between bg-black/10 transition hover:scale-105 duration-300 ease-in-out h-[5rem] rounded-md shadow-lg px-3 py-2"
        >
          <div class="border-l-4 border-red-500 h-full" />
          <div class="grid grid-cols-1 text-center">
            <div class="w-full flex justify-end items-start">
              <p class="text-3xl font-light text-gray-600">
                {{ contact.parlamentar_dados.contra }}
              </p>
            </div>
            <div class="w-full flex justify-end items-end">
              <p class="text-sm">
                Contrário
              </p>
            </div>
          </div>
        </div>
      </div>
      <div
        class=" pl-8"
        :class="[$page.props.auth.isMobile ? 'col-span-12' : 'col-span-4']"
      >
        <div class="grid grid-cols-1 justify-items-center pt-16">
          <div
            class="pentagon w-[12.5rem] h-[12.5rem] rotate-180 rounded-lg"
            :class="setAvaliacao(contact.parlamentar_dados.avaliacao)[0]"
          />
          <div
            class="pentagon w-[14.5rem] h-[14.5rem] rotate-180 bg-gradient-to-tl to-gray-200 from-white -mt-[15.5rem] flex justify-center items-center rounded-lg"
          >
            <div class="rotate-180 relative top-8 w-full text-center">
              <p
                class="text-7xl font-bold mb-2"
                :class="setAvaliacao(contact.parlamentar_dados.avaliacao)[1]"
              >
                {{ separarString(contact.parlamentar_dados.avaliacao)[1] }}
              </p>
              <p
                class="text-sm"
                :class="setAvaliacao(contact.parlamentar_dados.avaliacao)[1]"
              >
                {{ separarString(contact.parlamentar_dados.avaliacao)[0] }}
              </p>
            </div>
          </div>
          <p class="text-2xl mt-1">
            {{ contact.parlamentar_dados.siglaPartidoUf }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.pentagon {
  clip-path: polygon(50% 10%, 100% 38%, 100% 100%, 0% 100%, 0 38%);
}
</style>
