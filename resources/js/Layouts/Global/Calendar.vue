<script setup>
import {ref, watch, onMounted} from "vue";
import CalendarDay from "@/Components/Global/CalendarDay.vue";
import get_calendar from "@/Services/Calendar/get_calendar";

const day = ref(new Date());
const attrs = ref([]);
const fullscreen = ref(false);
const rows = ref(1);
const calendar = ref([]);
const emits = defineEmits(['updateEvent']);
function createEventObject(event, options = {}) {
    return {
        key: event.id,
        dates: new Date(event.data_inicio),
        popover: {
            label: event.titulo,
            visibility: 'hover',
            hideIndicator: false,
        },
        ...options
    };
}

function getEvents() {
    get_calendar.getCalendar().then((response) => {
        calendar.value = response.data.data;

        let guestEvents = Object.values(response.data.data.guestEvents);
        let myEvents = Object.values(response.data.data.myEvents);
        let guest = guestEvents.map(event => createEventObject(event, {
            bar: true,
            bar: {
                color: 'green',
            }
        }));
        let my = myEvents.map(event => createEventObject(event, {
            highlight: {
                color: 'blue',
                fillMode: 'light',
            }
        }));

        attrs.value = [{
            key: 'Hoje',
            highlight: {
                color: 'sky',
                fillMode: 'solid',
                content: {
                    style: {
                        color: 'brown',
                    }
                }
            },
            dates: new Date(),
        }, ...guest, ...my];
    });
}

watch(fullscreen, (nFs) => {
    nFs ? rows.value = 2 : rows.value = 1;
});

watch(day, (value) => {
    fullscreen.value = true;
});

onMounted(() => {
    getEvents();
});
</script>

<template>
  <div
    class="pb-4 px-2 pt-8"
    :class="[!fullscreen ? 'border-white ' : '']"
  >
    <div class="w-full -mt-5 relative flex justify-end right-1 mb-1">
      <button
        v-if="!fullscreen"
        class="w-6 h-6 rounded-full shadow bg-sky-700 hover:bg-sky-600 text-white"
        @click="fullscreen = true"
      >
        <i class="fa-regular fa-window-restore fa-xs" />
      </button>
    </div>
    <div
      :class="[fullscreen ? 'w-screen h-screen flex justify-center items-center bg-black/60 absolute top-0 left-0' : '']"
    >
      <div :class="[fullscreen ? 'bg-white md:10/12 2xl:w-8/12 h-7/12 rounded-xl shadow-lg p-4' : '']">
        <div
          v-if="fullscreen"
          class="w-full flex justify-between items-center mb-4 pr-2 pt-2"
        >
          <h1 class="text-center text-2xl">
            Calendário
          </h1>
          <button>
            <i
              v-if="fullscreen"
              class="fa-solid fa-xmark fa-lg"
              @click="fullscreen = false"
            />
          </button>
        </div>
        <div class="w-full h-5/6 flex justify-between gap-4">
          <div
            class="bg-white rounded-2xl h-full shadow-xl border-gray-300 border"
            :class="[fullscreen ? 'w-5/12' : 'w-full']"
          >
            <VDatePicker
              v-model="day"
              :attributes="attrs"
              mode="date"
              locale="br"
              color="sky-blue"
              expanded
              transparent
              :rows="rows"
            />
          </div>
          <div
            v-if="fullscreen"
            class="w-7/12"
          >
            <CalendarDay
              :day="day"
              :calendar="calendar"
              :new-event="fullscreen"
              @create="getEvents"
              @destroyed="getEvents()"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.vc-sky-blue {
    --vc-accent-50: #f0f9ff;
    --vc-accent-100: #e0f2fe;
    --vc-accent-200: #bae6fd;
    --vc-accent-300: #7dd3fc;
    --vc-accent-400: #38bdf8;
    --vc-accent-500: #0ea5e9;
    --vc-accent-600: #0284c7;
    --vc-accent-700: #0369a1;
    --vc-accent-800: #075985;
    --vc-accent-900: #0c4a6e;
}

.vc-title {
    font-size: 1rem;
    font-weight: 400;
    color: var(--vc-neutral-900);
}
</style>
