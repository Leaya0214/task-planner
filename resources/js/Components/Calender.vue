<template>
  <FullCalendar
    :plugins="calendarPlugins"
    :initialView="'dayGridMonth'"
    :events="events"
    :eventClick="handleEventClick"
    :dateClick="handleDateClick"
  />
</template>

<script setup>
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

const props = defineProps({
  events: {
    type: Array,
    required: true,
  },
  canManageAll: {
    type: Boolean,
    default: false,
  },
})

const calendarPlugins = [dayGridPlugin, timeGridPlugin, interactionPlugin]

// Click on an event → go to event detail
const handleEventClick = (info) => {
  const eventId = info.event.id
  window.location.href = `/events/${eventId}`
}

// Click on a date → create new event (only if allowed)
const handleDateClick = (info) => {
  if (props.canManageAll) {
    window.location.href = `/events/create?date=${info.dateStr}`
  }
}
</script>
