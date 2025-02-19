<script setup>
import Stepper from 'primevue/stepper'
import StepItem from 'primevue/stepitem'
import Step from 'primevue/step'
import StepPanel from 'primevue/steppanel'
import ManagerDetails from '@/Components/Manager/ManagerDetails.vue'

const props = defineProps({
  proposition: {
    type: Object,
    required: true
  }
})
const emits = defineEmits(['id'])

function checkShowComponent() {
  return props.proposition.relacionamentos && props.proposition.relacionamentos.length > 0
}

const setId = (id) => {
  emits('id', id)
}
</script>

<template>
  <div
    v-if="checkShowComponent()"
    class="w-full mt-8"
  >
    <div class="w-full bg-white rounded-xl shadow-xl p-4">
      <div class="mb-3">
        <p class="font-semibold">
          Matérias Correlatas:
        </p>
      </div>
      <div class="px-4">
        <Stepper value="1">
          <StepItem
            v-for="(relacionamentos, index) in proposition.relacionamentos"
            :key="relacionamentos.id"
            :value="index + 1"
          >
            <Step>
              <div class="flex gap-4">
                <div>
                  {{ relacionamentos.sigla }} {{ relacionamentos.numero }} /
                  {{ relacionamentos.ano }} -
                  {{ relacionamentos.origem }}
                </div>
                <div>
                  <button
                    class="z-50 hover:text-blue-500"
                    @click="setId(relacionamentos.id)"
                  >
                    <i class="pi pi-link" />
                  </button>
                </div>
              </div>
            </Step>
            <StepPanel>
              <div class="flex flex-col h-auto p-4">
                <div
                  class="p-2 border-2 border-dashed border-surface-200 dark:border-surface-700 rounded bg-surface-50 dark:bg-surface-950 flex-auto flex justify-start items-start font-medium"
                >
                  <ManagerDetails :proposition="relacionamentos" />
                </div>
              </div>
            </StepPanel>
          </StepItem>
        </Stepper>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
