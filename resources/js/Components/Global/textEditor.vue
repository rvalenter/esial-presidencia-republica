<template>
  <div
    id="editor-container"
    class="w-full h-full z-50"
  >
    <div
      v-if="maxLength"
      class="text-right text-sm relative top-8 right-4"
    >
      {{ charCount }} / {{ maxLength }}
    </div>
    <div
      id="editor"
      ref="editor"
      class="z-50"
    />
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import Quill from 'quill'
import 'quill-mention/autoregister'
import 'quill/dist/quill.snow.css'
import manager from '@/Services/Manager/index.js'
import parses from '@/Hooks/parses.js'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  blocked: {
    type: Boolean,
    default: false
  },
  maxLength: {
    type: Number,
    default: null
  }
})

async function suggestPeople(searchTerm) {
  let allPeople = []

  if (searchTerm == '') {
    return allPeople
  }

  await manager.fetchContact(searchTerm).then((response) => {
    allPeople = response.data.data.map((person) => {
      return {
        id: person.id,
        value: parses.title(person.nome) + ' - ' + parses.title(person.cargo) + ' - ' + person.parlamentar_dados?.siglaPartidoUf
      }
    })
  })

  return allPeople
}

const emit = defineEmits(['update:modelValue', 'mentions'])
const editor = ref(null)
const charCount = ref(0)
let quill = null

function startQuill() {
  quill = new Quill(editor.value, {
    theme: 'snow',
    formats: ['bold', 'italic', 'underline', 'header', 'mention'],
    modules: {
      toolbar: [['bold', 'italic', 'underline'], [{ header: [1, 2, 3, false] }]],
      mention: {
        allowedChars: /^[A-Za-z\sÅÄÖåäö]*$/,
        mentionDenotationChars: ['@', '#'],
        source: async function (searchTerm, renderList) {
          const matchedPeople = await suggestPeople(searchTerm)
          renderList(matchedPeople)
        },
        onSelect: function (item, insertItem) {
          insertItem(item)
          emit('mentions', item)
        }
      }
    },
    readOnly: !props.blocked
  })

  quill.on('text-change', () => {
    const text = quill.getText()
    charCount.value = text.trim().length

    if (props.maxLength && charCount.value > props.maxLength) {
      const truncatedText = text.slice(0, props.maxLength)
      quill.deleteText(props.maxLength, charCount.value)
      quill.root.innerHTML = truncatedText
      charCount.value = props.maxLength
    }

    emit('update:modelValue', quill.root.innerHTML)
  })

  if (props.modelValue) {
    quill.root.innerHTML = props.modelValue
    charCount.value = quill.getText().trim().length
  }
}

onMounted(() => {
  if (editor.value) {
    startQuill()
  }
})

watch(
  () => props.modelValue,
  (newValue) => {
    if (newValue !== quill.root.innerHTML) {
      quill.root.innerHTML = newValue
      charCount.value = quill.getText().trim().length
    }
  }
)

watch(
  () => props.blocked,
  (newValue) => {
    if (quill) {
      quill.enable(newValue)
    }
  }
)
</script>

<style>
.text-right {
  color: #6b7280;
}
.ql-mention-list {
  @apply bg-emerald-600/90 text-white text-base p-2 rounded-md shadow-xl cursor-pointer;

}
.ql-mention-list > li {
  @apply px-2 py-0.5 hover:bg-emerald-700/80 rounded-md font-medium;
}
.mention {
  @apply bg-emerald-700/80 text-white px-2 py-1 rounded-md shadow-md;
}
</style>
