<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import CalendarDays from "@/Components/Global/CalendarDays.vue";
import CalendarNewEvent from "@/Components/Global/CalendarNewEvent.vue";

const generateTimeArray = () => {
    const timeArray = [];

    for (let i = 0; i < 24; i++) {
        timeArray.push(i < 10 ? `0${i}:00` : `${i}:00`);
        timeArray.push(i < 10 ? `0${i}:30` : `${i}:30`);
    }

    return timeArray;
};
const date = ref(new Date());
const props = defineProps({
    day: {
        type: [Date, null],
        required: false,
        default: new Date()
    },
    calendar: {
        type: Object,
        required: false,
        default: () => ({
          myEvents: [],
          guestEvents: []
        })
    }
});
const emits = defineEmits(['create', 'destroyed']);

const timeSlots = ref(generateTimeArray());
const minutes = computed(() => date.value.getMinutes() > 29 ? '30' : '00');
const checkDate = computed(() => formatDate(date.value) !== formatDate(props.day));
const showNewEvent = ref(false);
const eventToEdit = ref(null);
const setScroll = () => {
    let id = `hour_${date.value.getHours().toString().padStart(2, '0')}:${minutes.value}`;
    let element = document.getElementById(id);

    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
};
const updateDate = () => {
    date.value = new Date();
};
const formatDate = (date) => {
    if (date instanceof Date) {
        return date.toLocaleDateString('pt-BR', {
            weekday: 'long',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        }).split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }
};

function parseDate(date) {
    let d = new Date(date);
    let year = d.getFullYear();
    let month = ('0' + (d.getMonth() + 1)).slice(-2);
    let day = ('0' + d.getDate()).slice(-2);

    return `${year}-${month}-${day}`;
}

function hasEvent(time = "00:00") {
    let now = time.split(':');
    let nextNow = parseInt(now[1]) + 30;

    return props.calendar.myEvents.concat(props.calendar.guestEvents).map(calendar => {

        let eventStartDate = parseDate(calendar.data_inicio);
        let checkTime = parseDate(props.day);

        if (eventStartDate == checkTime) {

            let date = new Date(calendar.data_inicio);
            let hours = date.getUTCHours();
            let minutes = date.getUTCMinutes();

            if (
                (hours == parseInt(now[0]) && minutes >= parseInt(now[1]))
                && (hours == parseInt(now[0]) && minutes < nextNow)
            ) {
                return calendar;
            }
        }
    }).filter(event => event != null);
}

function setEventoToUpdate(id) {
    eventToEdit.value = id;
    showNewEvent.value = true;
}

watch(() => props.day, () => {
    hasEvent();
});

watch(() => minutes.value, () => {
    setScroll();
});

watch(() => showNewEvent.value, () => {
    setTimeout(() => {
        setScroll();
    }, 200);
});

watch(props.calendar, (newCalendar) => {
    // console.log(newCalendar, data.myEvents.concat(data.guestEvents));
});

onMounted(() => {
    setScroll();
    setInterval(updateDate, 60 * 1000);
});

</script>

<template>
  <div class="w-full h-full bg-gray-200 shadow-xl rounded-lg p-4 pt-4">
    <div class="flex justify-between items-center">
      <div
        class="text-lg"
        :class="[checkDate ? 'text-black font-black' : 'text-gray-400 font-medium']"
      >
        {{ checkDate ? formatDate(props.day) : formatDate(date) }}
      </div>
      <div v-if="$page.props.auth.access.includes(7)">
        <button
          v-if="!showNewEvent"
          class="bg-blue-500 hover:bg-sky-500 text-white px-2 py-1 text-sm rounded-md shadow-md gap-3 flex justify-around items-center"
          @click="() => { showNewEvent = true; eventToEdit = null}"
        >
          Novo Evento
          <i class="fa-regular fa-calendar-check" />
        </button>
        <button
          v-if="showNewEvent"
          class="bg-gray-400 hover:bg-gray-600 text-white px-2 py-1 text-sm rounded-md shadow-md gap-3 flex justify-around items-center"
          @click="() => { showNewEvent = false; }"
        >
          Cancelar
          <i class="fa-solid fa-xmark" />
        </button>
      </div>
    </div>
    <CalendarNewEvent
      :show="showNewEvent"
      :start="checkDate ? props.day : date"
      :event-to-edit="eventToEdit"
      @create="callCreate"
    />
    <div
      v-if="!showNewEvent"
      class="bg-white rounded-lg shadow-inner w-full mt-6 grid divide-y-2 divide-gray-200 overflow-y-auto h-[28.5rem]"
    >
      <CalendarDays
        v-for="(time, index) in timeSlots"
        :id="'hour_' + time"
        :key="index"
        :hours="time"
        :has-event="hasEvent(time)"
        @destroyed="emits('destroyed')"
        @update-event="setEventoToUpdate"
      />
    </div>
  </div>
</template>

<style scoped>
.v-enter-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}

/* :hasEvent="hasEvent(time)" */
</style>
