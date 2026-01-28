<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

const props = defineProps({
    events: {
        type: Array,
        required: true,
    },
    tasks: {
        type: Array,
        required: false,
    },
    can: {
        type: Object,
        required: true,
    },
});

const calendarRef = ref(null);
const currentView = ref("timeGridWeek");
const showEventModal = ref(false);
const showEventDetailsModal = ref(false);
const selectedEvent = ref(null);
const eventForm = ref({
    name: "",
    description: "",
    date: "",
    start_time: "",
    end_time: "",
    task_id: null,
});

const calendarOptions = computed(() => ({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: currentView.value,
    headerToolbar: false,
    height: "auto",
    editable: props.can.manageAll,
    selectable: props.can.create,
    selectMirror: true,
    dayMaxEvents: true,
    weekends: true,
    slotMinTime: "06:00:00",
    slotMaxTime: "22:00:00",
    events: props.events,
    tasks: props.tasks,
    eventClick: handleEventClick,
    select: handleDateSelect,
    eventDrop: handleEventDrop,
    eventResize: handleEventResize,
    eventContent: renderEventContent,
}));

const changeView = (view) => {
    currentView.value = view;
    if (calendarRef.value) {
        calendarRef.value.getApi().changeView(view);
    }
};

const goToday = () => {
    if (calendarRef.value) {
        calendarRef.value.getApi().today();
    }
};

const goPrev = () => {
    if (calendarRef.value) {
        calendarRef.value.getApi().prev();
    }
};

const goNext = () => {
    if (calendarRef.value) {
        calendarRef.value.getApi().next();
    }
};

const getCurrentDate = () => {
    if (calendarRef.value) {
        const date = calendarRef.value.getApi().getDate();
        return date.toLocaleDateString("en-US", {
            month: "long",
            year: "numeric",
        });
    }
    return "";
};

const handleDateSelect = (selectInfo) => {
    if (!props.can.create) return;

    const startDate = new Date(selectInfo.start);
    const endDate = new Date(selectInfo.end);

    eventForm.value = {
        name: "",
        description: "",
        date: startDate.toISOString().split("T")[0],
        start_time: startDate.toTimeString().slice(0, 5),
        end_time: endDate.toTimeString().slice(0, 5),
        task_id: null,
    };

    showEventModal.value = true;
    selectInfo.view.calendar.unselect();
};

const handleEventClick = (clickInfo) => {
    selectedEvent.value = {
        id: clickInfo.event.id,
        title: clickInfo.event.title,
        start: clickInfo.event.start,
        end: clickInfo.event.end,
        ...clickInfo.event.extendedProps,
    };
    showEventDetailsModal.value = true;
};

const handleEventDrop = (dropInfo) => {
    if (!props.can.manageAll) {
        dropInfo.revert();
        return;
    }

    const event = dropInfo.event;
    updateEventDateTime(event.id, event.start, event.end);
};

const handleEventResize = (resizeInfo) => {
    if (!props.can.manageAll) {
        resizeInfo.revert();
        return;
    }

    const event = resizeInfo.event;
    updateEventDateTime(event.id, event.start, event.end);
};

const updateEventDateTime = (eventId, start, end) => {
    const startDate = new Date(start);
    const endDate = new Date(end);

    router.put(
        route("events.update-date", eventId),
        {
            date: startDate.toISOString().split("T")[0],
            start_time: startDate.toTimeString().slice(0, 5),
            end_time: endDate.toTimeString().slice(0, 5),
        },
        {
            preserveScroll: true,
            onError: () => {
                alert("Failed to update event");
                window.location.reload();
            },
        },
    );
};

const submitEvent = () => {
    router.post(route("events.quick-store"), eventForm.value, {
        preserveScroll: true,
        onSuccess: () => {
            showEventModal.value = false;
            resetForm();
        },
        onError: (errors) => {
            console.error("Validation errors:", errors);
        },
    });
};

const deleteEvent = () => {
    if (!confirm("Are you sure you want to delete this event?")) return;

    router.delete(route("events.destroy", selectedEvent.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            showEventDetailsModal.value = false;
            selectedEvent.value = null;
        },
    });
};

const resetForm = () => {
    eventForm.value = {
        name: "",
        description: "",
        date: "",
        start_time: "",
        end_time: "",
        task_id: "",
    };
};

const closeModal = () => {
    showEventModal.value = false;
    resetForm();
};

const closeDetailsModal = () => {
    showEventDetailsModal.value = false;
    selectedEvent.value = null;
};

const renderEventContent = (eventInfo) => {
    const time = eventInfo.timeText;
    const title = eventInfo.event.title;

    return {
        html: `
            <div class="fc-event-main-frame" style="padding: 2px 4px;">
                <div class="fc-event-time" style="font-weight: 600; font-size: 0.75rem;">${time}</div>
                <div class="fc-event-title" style="font-size: 0.875rem; margin-top: 2px;">${title}</div>
            </div>
        `,
    };
};

const formatDateTime = (date) => {
    return new Intl.DateTimeFormat("en-US", {
        timeZone: "Asia/Dhaka",
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    }).format(new Date(date));
};
</script>

<template>
    <Head title="Calendar" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8">
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent"
                    >
                        Event Calendar
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        {{
                            can.manageAll
                                ? "View and manage all events"
                                : "View your assigned events"
                        }}
                    </p>
                </div>

                <!-- Calendar Controls -->
                <div
                    class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-6 mb-6"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between gap-4"
                    >
                        <!-- View Switcher -->
                        <div
                            class="flex items-center gap-2 bg-gray-100 rounded-lg p-1"
                        >
                            <button
                                @click="changeView('timeGridDay')"
                                :class="[
                                    'px-4 py-2 rounded-lg font-medium transition-all duration-200',
                                    currentView === 'timeGridDay'
                                        ? 'bg-white text-purple-600 shadow-md'
                                        : 'text-gray-600 hover:text-purple-600',
                                ]"
                            >
                                Day
                            </button>
                            <button
                                @click="changeView('timeGridWeek')"
                                :class="[
                                    'px-4 py-2 rounded-lg font-medium transition-all duration-200',
                                    currentView === 'timeGridWeek'
                                        ? 'bg-white text-purple-600 shadow-md'
                                        : 'text-gray-600 hover:text-purple-600',
                                ]"
                            >
                                Week
                            </button>
                            <button
                                @click="changeView('dayGridMonth')"
                                :class="[
                                    'px-4 py-2 rounded-lg font-medium transition-all duration-200',
                                    currentView === 'dayGridMonth'
                                        ? 'bg-white text-purple-600 shadow-md'
                                        : 'text-gray-600 hover:text-purple-600',
                                ]"
                            >
                                Month
                            </button>
                        </div>

                        <!-- Navigation -->
                        <div class="flex items-center gap-4">
                            <button
                                @click="goPrev"
                                class="p-2 rounded-lg hover:bg-gray-100 transition-all duration-200"
                            >
                                <svg
                                    class="w-5 h-5 text-gray-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 19l-7-7 7-7"
                                    />
                                </svg>
                            </button>

                            <button
                                @click="goToday"
                                class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg font-semibold text-white shadow-lg shadow-purple-500/30 transition-all duration-200"
                            >
                                Today
                            </button>

                            <button
                                @click="goNext"
                                class="p-2 rounded-lg hover:bg-gray-100 transition-all duration-200"
                            >
                                <svg
                                    class="w-5 h-5 text-gray-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Calendar -->
                <div
                    class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-6 calendar-container"
                >
                    <FullCalendar
                        ref="calendarRef"
                        :options="calendarOptions"
                    />
                </div>
            </div>
        </div>

        <!-- Create Event Modal -->
        <Teleport to="body">
            <div
                v-if="showEventModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                @click.self="closeModal"
            >
                <div
                    class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">
                            Create Event
                        </h3>
                        <button
                            @click="closeModal"
                            class="p-2 hover:bg-gray-100 rounded-lg transition-all duration-200"
                        >
                            <svg
                                class="w-5 h-5 text-gray-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="submitEvent" class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Event Name *
                            </label>
                            <input
                                v-model="eventForm.name"
                                type="text"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Enter event name"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Description
                            </label>
                            <textarea
                                v-model="eventForm.description"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Enter event description"
                            ></textarea>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Date *
                            </label>
                            <input
                                v-model="eventForm.date"
                                type="date"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Start Time *
                                </label>
                                <input
                                    v-model="eventForm.start_time"
                                    type="time"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    End Time *
                                </label>
                                <input
                                    v-model="eventForm.end_time"
                                    type="time"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Task
                                </label>
                                <select
                                    v-model="eventForm.task_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                >
                                    <option value="">Select Task</option>
                                    <option
                                        v-for="task in tasks"
                                        :key="task.id"
                                        :value="task.id"
                                    >
                                        {{ task.title }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-4">
                            <button
                                type="submit"
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-lg font-semibold text-white shadow-lg shadow-purple-500/30 transition-all duration-200"
                            >
                                Create Event
                            </button>
                            <button
                                type="button"
                                @click="closeModal"
                                class="flex-1 px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>

        <!-- Event Details Modal -->
        <Teleport to="body">
            <div
                v-if="showEventDetailsModal && selectedEvent"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                @click.self="closeDetailsModal"
            >
                <div
                    class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">
                            Event Details
                        </h3>
                        <button
                            @click="closeDetailsModal"
                            class="p-2 hover:bg-gray-100 rounded-lg transition-all duration-200"
                        >
                            <svg
                                class="w-5 h-5 text-gray-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">
                                {{ selectedEvent.title }}
                            </h4>
                        </div>

                        <div
                            v-if="selectedEvent.description"
                            class="text-sm text-gray-600"
                        >
                            {{ selectedEvent.description }}
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <svg
                                class="w-5 h-5 mr-2 text-purple-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            {{ formatDateTime(selectedEvent.start) }}
                            <span class="mx-2">→</span>
                            {{ formatDateTime(selectedEvent.end) }}
                        </div>

                        <div
                            v-if="selectedEvent.task_title"
                            class="flex items-center text-sm text-gray-600"
                        >
                            <svg
                                class="w-5 h-5 mr-2 text-purple-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                            <span class="font-medium">Related Task:</span>
                            <span class="ml-1">{{
                                selectedEvent.task_title
                            }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <svg
                                class="w-5 h-5 mr-2 text-purple-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                            <span class="font-medium">Created by:</span>
                            <span class="ml-1">{{
                                selectedEvent.created_by
                            }}</span>
                        </div>

                        <div
                            v-if="can.manageAll"
                            class="flex items-center gap-3 pt-4 border-t border-gray-200"
                        >
                            <button
                                @click="deleteEvent"
                                class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 rounded-lg font-semibold text-white shadow-lg shadow-red-500/30 transition-all duration-200"
                            >
                                Delete Event
                            </button>
                            <button
                                @click="closeDetailsModal"
                                class="flex-1 px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200"
                            >
                                Close
                            </button>
                        </div>
                        <div v-else class="pt-4 border-t border-gray-200">
                            <button
                                @click="closeDetailsModal"
                                class="w-full px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </AuthenticatedLayout>
</template>

<style>
/* FullCalendar Custom Styling */
.calendar-container :deep(.fc) {
    font-family: inherit;
}

.calendar-container :deep(.fc-theme-standard td),
.calendar-container :deep(.fc-theme-standard th) {
    border-color: #e5e7eb;
}

.calendar-container :deep(.fc-col-header-cell) {
    background: linear-gradient(to right, #9333ea, #ec4899);
    color: white;
    font-weight: 600;
    padding: 12px 4px;
}

.calendar-container :deep(.fc-daygrid-day-number) {
    color: #374151;
    font-weight: 600;
    padding: 8px;
}

.calendar-container :deep(.fc-day-today) {
    background-color: #faf5ff !important;
}

.calendar-container :deep(.fc-event) {
    border: none;
    border-radius: 6px;
    padding: 2px 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.calendar-container :deep(.fc-event:hover) {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.calendar-container :deep(.fc-timegrid-slot) {
    height: 3em;
}

.calendar-container :deep(.fc-timegrid-slot-label) {
    color: #6b7280;
    font-size: 0.875rem;
}

.calendar-container :deep(.fc-button) {
    background: linear-gradient(to right, #9333ea, #ec4899);
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 600;
    text-transform: capitalize;
}

.calendar-container :deep(.fc-button:hover) {
    background: linear-gradient(to right, #7e22ce, #db2777);
}

.calendar-container :deep(.fc-button:disabled) {
    opacity: 0.5;
}

.calendar-container :deep(.fc-scrollgrid) {
    border-radius: 12px;
    overflow: hidden;
}
</style>
