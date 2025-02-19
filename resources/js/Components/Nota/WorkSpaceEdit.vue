<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import {useStore} from 'vuex'
import DadosBasicos from '@/Components/Nota/Menu/DadosBasicos.vue'
import ResumoMateria from '@/Components/Nota/Menu/ResumoMateria.vue'
import TextoMateria from '@/Components/Nota/Menu/TextoMateria.vue'
import MenuNotaTecnicaEdicao from '@/Components/Nota/MenuNotaTecnicaEdicao.vue'
import PosicionamentoNota from '@/Components/Nota/PosicionamentoNota.vue'
import EditorNota from '@/Components/Nota/EditorNota.vue'
import 'quill/dist/quill.snow.css'
import SpinnerLarge from '@/Components/Global/SpinnerLarge.vue'
import { usePage } from '@inertiajs/vue3'
import Gestor from '@/Layouts/Nota/Gestor/Gestor.vue'
import Observacoes from '@/Components/Nota/Menu/Observacoes.vue'
import Referencia from '@/Components/Nota/Referencia.vue'
import titles from '../../../configs/titles.json'
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';

const confirm = useConfirm();
const store = useStore();
const page = usePage()
const props = defineProps({
  proposicao: {
    type: Object || null,
    default: null
  },
  notaTecnica: {
    type: Object || null,
    default: null
  }
})
const emit = defineEmits([
  'updateReference',
  'restartReference',
  'updateMyList'
])
const collapsed = ref(true)
const menu = ref(null)
const references = computed(() => store.state.nota.references);
const referenceSelected = computed(() => store.state.nota.referenceSelected);
const working = ref(1)
const exposedPosition = ref(null)
const exposeNotes = ref(null)
const showBtnFinish = ref(false)
const textVersion = ref({})
const versionSelected = ref(null)
const showBtnSave = ref(false)
const blocked = ref(false)
const solicitarAjustes = ref(false)
const finished = ref(false)
const gestor = ref(null)
const showAdjusts = ref(false)
const btnApproves = ref(false)

function onShowBtnSave(arg) {
  showBtnSave.value = working.value === 1

  if (arg !== '<p><br></p>') {
    showBtnSave.value = true
  }
}

function onSetMenuNTEdicao(arg) {
  working.value = arg
  showBtnSave.value = false
}

function setBtnApproves(nt) {
  let size = nt?.aprovacoes.length

  if (!nt) {
    showAdjusts.value = false
    btnApproves.value = false
    blocked.value = false
    return
  }

  if (nt.concluida) {
    btnApproves.value = false
    blocked.value = false
    return
  }

  if (
    page.props.auth.access.includes(9)
    && !nt.concluida
    && size == 0
  ) {
    blocked.value = true
    btnApproves.value = true
    return
  }

  if (
    page.props.auth.access.includes(9)
    && !nt.concluida
    && size > 0
    && nt?.aprovacoes[size - 1]?.aprovador === null
    && nt?.aprovacoes[size - 1]?.observacao === null
  ) {
    blocked.value = true
    btnApproves.value = true
    return
  }

  if (size === 0) {
    btnApproves.value = false
    return
  }

  if (
    nt?.aprovacoes[size - 1]?.aprovador === null
    && nt?.aprovacoes[size - 1]?.observacao === null
  ) {
    btnApproves.value = true
    blocked.value = false
    showAdjusts.value = false
    return
  }

  if (nt?.aprovacoes[size - 1]?.observacao !== null && nt.aprovacoes.length > 0 && page.props.auth.access.includes(8)) {
    showAdjusts.value = true
    btnApproves.value = false
    blocked.value = true
    return
  }

  blocked.value = false
}

function setMenu(newMenu) {
  if (newMenu === menu.value) {
    menu.value = null
    collapsed.value = true
    return
  }

  collapsed.value = false
  menu.value = newMenu
}

function getReferences() {
  store.dispatch('nota/getReferences', props.proposicao.id).then(() => {
    if (references.value.length == 0) {
      emit('restartReference', props.proposicao.id)
    }
  });
}

function setReference(id) {
  store.commit('nota/setReferenceSelected', id)
}

function btnSaveAction(reaction = true) {
  if (working.value === 1) {
    exposedPosition.value.storeExposedPosition(reaction)
    return
  }

  exposeNotes.value.exposeStoreNotes(1)
}

function AiGenerate() {
  exposeNotes.value.exposeStoreNotes(2)
}

function updateDataReference(id) {
  emit('updateReference', id)
  showBtnSave.value = false
}

function setTextVerison(arr) {
  textVersion.value = arr.map((item) => {
    return {
      id: item.id,
      date: item.created_at
    }
  });

  versionSelected.value = arr.length > 0 ? arr[arr.length - 1].id : null
}

function preview() {
  store.dispatch('nota/preview', props.notaTecnica.id).then(() => {
    emit('updateReference', props.notaTecnica.id)
  })
}

function setBlocked() {
  blocked.value = true

  if (page.props.auth.access.includes(9) || page.props.auth.access.includes(15)) {
    blocked.value = false
    return
  }
}

function setFinished() {
  finished.value = props.notaTecnica?.concluida ?? false
}

function setBtnFinish(newNt) {
  if (!newNt) {
    showBtnFinish.value = false;
    return;
  }

  const baseConditions = newNt.esial_nota_tecnica_posicionamento_id !== null
    && checkPosicao(newNt)
    && newNt.esial_nota_tecnica_impacto_tipo_id !== null
    && newNt.esial_nota_tecnica_impacto_intensidade_id !== null
    && newNt.conclusoes.length > 0
    && newNt.concluida === false
    && newNt.data_referencia !== null;

  if (newNt.tipo === 0) {
    showBtnFinish.value = baseConditions
      && newNt.referencia_relacao_table.length > 0
      && newNt.criticos.length > 0
      && newNt.meritos.length > 0;
  } else {
    showBtnFinish.value = baseConditions;
  }
}

function checkPosicao(newNt) {
  if ([3, 4].includes(newNt.esial_nota_tecnica_posicionamento_id)) {
    if (newNt.posicao_justificativa === null) {
      return false
    }

    return newNt.posicao_justificativa.value !== null
  }

  return true
}

function checkAproves() {
  setBlocked()
  setFinished()
}

function finish() {
  store.dispatch('nota/finish', props.notaTecnica.id).then(() => {
    emit('updateReference', props.notaTecnica.id)
  })
}

function onSavedManger() {
  solicitarAjustes.value = false
  emit('updateMyList')
  emit('updateReference', props.notaTecnica.id)
}

const confirm1 = () => {
  confirm.require({
    message: titles.NotaTecnica.nt_finish,
    header: 'Nota Técnica',
    icon: 'pi pi-exclamation-triangle',
    rejectProps: {
      label: 'Cancelar',
      severity: 'secondary',
      outlined: true
    },
    acceptProps: {
      label: 'Concluir e Enviar a SRI'
    },
    accept: () => {
      finish()
    },
    reject: () => {
      console.log('reject')
    }
  });
};

const confirm2 = () => {
  confirm.require({
    message: titles.NotaTecnica.nt_pre_finish,
    header: 'Nota Técnica',
    icon: 'pi pi-exclamation-triangle',
    rejectProps: {
      label: 'Cancelar',
      severity: 'secondary',
      outlined: true
    },
    acceptProps: {
      label: 'Concluir'
    },
    accept: () => {
      preview()
    },
    reject: () => {
      console.log('reject')
    }
  });
};

const getColorClass = (type) => {
  const colors = [
    'red-500',
    'green-500',
    'yellow-500',
    'blue-500',
    'gray-500',
    'orange-500',
    'cyan-500',
    'purple-500'
  ]

  return colors[type - 1] || 'border-white'
}

function positionColor() {
  return 'esial_nota_tecnica_posicionamento_id'
}

watch(() => referenceSelected.value, (newReference) => {
  solicitarAjustes.value = true
  emit('updateReference', newReference)
})

watch(() => props.proposicao, () => {
  getReferences()
})

watch(() => showAdjusts.value, (newShow) => {
  if (!newShow) {
    menu.value = null
    collapsed.value = true
  }
})

watch(() => props.notaTecnica, (newNt) => {
  gestor.value.resetContent()
  solicitarAjustes.value = false
})

watch(() => [props.notaTecnica, working.value], ([newNt, newWorkin]) => {
  checkAproves()
  setBtnFinish(newNt)
  setBtnApproves(newNt)
  onShowBtnSave('<p><br></p>')

  if (newNt && newWorkin === 2) {
    setTextVerison(newNt.criticos)
    return
  }

  if (newNt && newWorkin === 3) {
    setTextVerison(newNt.meritos)
    return
  }
  if (newNt && newWorkin === 4) {
    setTextVerison(newNt.conclusoes)
  }
}, { immediate: true })

onMounted(() => {
  getReferences()
})
</script>

<template>
  <ConfirmDialog />
  <div class="w-full h-full grid grid-rows-12 grid-flow-col">
    <div
      :class="[collapsed ? 'row-span-1'
        : menu === 1 ? 'row-span-5'
          : menu === 2 ? 'row-span-5'
            : menu === 3 ? 'row-span-5'
              : 'row-span-5' ]"
      class="w-full rounded-t-xl bg-sky-700 px-1 pt-0.5 -mt-7"
    >
      <div
        :class="[collapsed ? '' : 'border-b-2 border-white/30 pb-3 -mb-5']"
        class="pt-5 px-2 flex items-center"
      >
        <div class="flex gap-6 w-fit z-40">
          <button
            :class="[menu === 1 ? 'bg-white/60 text-gray-700' : 'text-gray-100']"
            class="px-4 h-8 rounded-full hover:bg-white/60 cursor-pointer min-w-36 hover:text-gray-700 flex items-center"
            @click="setMenu(1)"
          >
            <i class="fa-solid fa-book mr-2" />
            Dados Básicos
          </button>
          <button
            :class="[menu === 2 ? 'bg-white/60 text-gray-700' : 'text-gray-100']"
            class="px-4 h-8 rounded-full hover:bg-white/60 cursor-pointer min-w-36 hover:text-gray-700 flex items-center"
            @click="setMenu(2)"
          >
            <i class="fa-solid fa-laptop-file mr-2" />
            Texto Matéria
          </button>
          <button
            :class="[menu === 3 ? 'bg-white/60 text-gray-700' : 'text-gray-100']"
            class="px-4 h-8 rounded-full hover:bg-white/60 cursor-pointer min-w-36 hover:text-gray-700 flex items-center"
            @click="setMenu(3)"
          >
            <i class="fa-regular fa-rectangle-list mr-2" />
            Resumo da Matéria AI
          </button>
          <button
            v-if="!finished && showAdjusts && page.props.auth.access.includes(8)"
            :class="[menu === 4 ? 'bg-white/60 text-gray-700' : 'text-gray-100']"
            class="px-4 h-8 rounded-full hover:bg-white/60 cursor-pointer min-w-36 hover:text-gray-700 flex items-center"
            @click="setMenu(4)"
          >
            <i class="fa-solid fa-triangle-exclamation mr-2 text-yellow-400" />
            Solicitações de Ajustes
          </button>
        </div>
      </div>
      <div class="w-full h-5/6 p-3 pt-8">
        <div
          v-if="notaTecnica"
          class="w-full h-full text-white rounded-xl overflow-y-auto pb-8"
        >
          <DadosBasicos
            v-if="menu === 1"
            :nota-tecnica="notaTecnica"
          />
          <TextoMateria
            v-if="menu === 2"
            :proposicao="proposicao"
          />
          <ResumoMateria
            v-if="menu === 3"
            :proposicao="proposicao"
          />
          <observacoes
            v-if="menu === 4 && !finished && showAdjusts"
            :nota-tecnica="notaTecnica"
          />
        </div>
      </div>
    </div>
    <div
      :class="[collapsed ? 'row-span-11 h-full'
        : menu === 1 ? 'row-span-7 h-full'
          : menu === 2 ? 'row-span-7 h-full'
            : menu === 3 ? 'row-span-7 h-full'
              : 'row-span-7 h-full']"
      class="w-full rounded-xl bg-white shadow-xl p-5 -mt-5"
    >
      <div class="w-full flex justify-between items-center mb-4">
        <div
          class="text-lg font-semibold pl-1.5 relative top-1 border-l-4"
          :class="'border-'+[getColorClass(notaTecnica?.esial_nota_tecnica_posicionamento_id)]"
        >
          {{ proposicao.sigla }} {{ proposicao.numero }}/{{ proposicao.ano }} - {{ proposicao.origem }}
        </div>
        <Referencia
          :nota-tecnica="notaTecnica"
          :reference-selected="referenceSelected"
          :references="references"
          @update-reference="setReference"
          @update-reference-list="getReferences"
        />
      </div>
      <div
        v-if="!referenceSelected"
        class="w-full h-full flex justify-center items-center"
      >
        <div class="flex justify-center items-center">
          <SpinnerLarge />
        </div>
      </div>
      <gestor
        v-show="solicitarAjustes"
        ref="gestor"
        :nota-tecnica="notaTecnica"
        @back="solicitarAjustes = false"
        @saved="onSavedManger"
      />
      <MenuNotaTecnicaEdicao
        v-show="referenceSelected && collapsed && !solicitarAjustes"
        :nota-tecnica="notaTecnica"
        @set-menu-n-t-edicao="onSetMenuNTEdicao"
      />
      <div
        v-if="!solicitarAjustes"
        :class="[collapsed ? ' -mt-32 pt-36 pb-16 mb-7' : 'pb-28 pt-4 mt-0.5 -mb-12']"
        class="w-full h-full"
      >
        <PosicionamentoNota
          v-show="working === 1"
          ref="exposedPosition"
          :blocked="blocked"
          :collapsed="collapsed"
          :nota-tecnica="notaTecnica"
          @saved="updateDataReference"
          @save="btnSaveAction(false)"
        />
        <EditorNota
          v-if="[2, 3, 4].includes(working)"
          ref="exposeNotes"
          :blocked="blocked"
          :nota-tecnica="notaTecnica"
          :version-selected="versionSelected"
          :working="working"
          @edited="onShowBtnSave"
          @saved="updateDataReference"
        />
      </div>
      <div
        v-if="finished"
        class="w-full h-16 flex justify-center gap-4 items-center"
        :class="[collapsed ? '-mt-12' : '-mt-24']"
      >
        <a
          :class="[collapsed ? '' : 'mt-2']"
          :href="route('relatorio.nota-tecnica.pdf', { id: notaTecnica.id })"
          class="text-sky-800 py-2 flex items-center justify-center bg-sky-800 hover:bg-sky-700 w-full border border-gray-400 rounded-full shadow-lg"
        >
          <p class="mr-3 text-white text-sm">Visualizar Posicionamento</p>
          <i class="fa-solid fa-file-pdf text-white" />
        </a>
        <div>
          <div
            class="w-8 h-8 rounded-full text-white flex justify-center items-center"
            :class="'bg-'+[getColorClass(notaTecnica?.esial_nota_tecnica_posicionamento_id)]"
          >
            <i class="fa-solid fa-star" />
          </div>
        </div>
      </div>
      <div
        v-if="!finished"
        class="grid grid-cols-12"
        :class="[collapsed ? '-mt-5' : '-mt-16']"
      >
        <div class="col-span-7 flex justify-start gap-4 w-full">
          <div
            v-if="(page.props.auth.access.includes(8) || page.props.auth.access.includes(9)) && showBtnSave && blocked && !solicitarAjustes"
          >
            <button
              class="px-3 py-1 bg-sky-400 hover:bg-sky-400/70 hover:text-gray-700 shadow-lg rounded-full text-xs"
              @click="btnSaveAction"
            >
              Salvar
              <i class="fa-regular fa-floppy-disk ml-2" />
            </button>
          </div>
          <div
            v-if="textVersion.length > 0 && blocked && working !== 1"
            class="flex items-center relative -top-0.5"
          >
            <label
              class="mr-2 text-xs font-semibold"
              for="version"
            >Versão:</label>
            <select
              id="version"
              v-model="versionSelected"
              class="w-44 h-7 border border-gray-400 hover:bg-gray-300 bg-white rounded-full p-0 text-xs px-2 focus:ring-0"
            >
              <option
                v-for="version in textVersion"
                :key="version.id"
                :value="version.id"
              >
                {{ version.date }}
              </option>
            </select>
          </div>
          <div v-if="notaTecnica && !solicitarAjustes">
            <a
              :href="route('relatorio.nota-tecnica.pdf', { id: notaTecnica.id })"
              class="px-3 py-1 bg-sky-900 hover:bg-sky-900/70 hover:text-gray-700 shadow-lg rounded-full text-xs flex relative top-0.5"
            >
              <p class="mr-3 text-white text-xs">Pré-visualizar</p>
              <i class="fa-solid fa-file-pdf text-white" />
            </a>
          </div>
        </div>

        <div class="col-span-5 flex justify-end gap-4">
          <div v-if="page.props.auth.access.includes(8) && blocked && showBtnFinish">
            <button
              class="px-3 py-1 bg-green-400 hover:bg-green-400/70 hover:text-gray-700 shadow-lg rounded-full text-xs"
              label="Save"
              outlined
              @click="confirm2()"
            >
              Concluir
              <i class="fa-solid fa-check ml-2 fa-lg" />
            </button>
          </div>
          <div v-if="page.props.auth.access.includes(9) && showBtnFinish && !solicitarAjustes && btnApproves">
            <button
              v-if="notaTecnica?.aprovacoes.length > 0"
              class="px-3 py-1 bg-yellow-400 hover:bg-yellow-400/70 hover:text-gray-700 shadow-lg rounded-full text-xs"
              @click="solicitarAjustes = true"
            >
              Solicitar Ajustes
              <i class="fa-solid fa-exclamation ml-2" />
            </button>
          </div>
          <div v-if="page.props.auth.access.includes(9) && showBtnFinish && !solicitarAjustes && btnApproves">
            <button
              class="px-3 py-1 bg-green-400 hover:bg-green-400/70 hover:text-gray-700 shadow-lg rounded-full text-xs"
              label="Save"
              outlined
              @click="confirm1()"
            >
              Aprovar e Enviar à SRI
              <i class="fa-solid fa-check ml-2 fa-lg" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
