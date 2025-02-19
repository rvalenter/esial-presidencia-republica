<script setup>
import CalendarDaysAction from "@/Components/Global/CalendarDaysAction.vue";

const props = defineProps({
    hours: {
        type: String,
        required: true
    },
    hasEvent: {
        type: Object,
        required: false,
        default: () => {}
    }
});
const emits = defineEmits(['destroyed', 'updateEvent']);
const compareTime = (timeString) => {
    const now = new Date();
    const [hours, minutes] = timeString.split(':');
    const timeDate = new Date();
    timeDate.setHours(hours, minutes, 0, 0);

    return now.getTime() >= timeDate.getTime() && now.getTime() <= timeDate.getTime() + 1800000;
};

const setUpdateEvent = (id) => {
    emits('updateEvent', id);
};
</script>

<template>
  <div
    class="w-full text-xs hover:bg-gray-300 hover:text-white grid grid-cols-9 gap-2 group"
    :class="[compareTime(props.hours)
      ? 'bg-sky-600 text-white font-bold'
      : 'text-gray-400']"
  >
    <p class="col-span-1 pt-0.5 pl-0.5 font-bold">
      {{ hours }}
    </p>
    <div class="col-span-8 min-h-10 pt-1">
      <ol v-if="props.hasEvent[0] != null">
        <li
          v-for="evento in props.hasEvent"
          :key="evento.id"
          class="py-2"
        >
          <CalendarDaysAction
            :evento="evento"
            @destroyed="emits('destroyed')"
            @update-event="setUpdateEvent"
          />
        </li>
      </ol>
    </div>
  </div>
</template>

<style scoped></style>
