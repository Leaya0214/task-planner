<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

const props = defineProps({
    events: {
        type: Array,
        required: true,
    },
    canManageAll: {
        type: Boolean,
        default: false,
    },
});

const deleteEvent = (eventId) => {
    if (confirm("Are you sure you want to delete this event?")) {
        router.delete(route("events.destroy", eventId));
    }
};

const formatDate = (date) => {
    if (!date) return "N/A";
    const d = new Date(date);
    return d.toLocaleDateString("en-US", {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric",
    });
};

const formatTime = (time) => {
    if (!time) return "N/A";
    // Handle time in HH:MM:SS or HH:MM format
    const timeParts = time.split(":");
    const hours = parseInt(timeParts[0]);
    const minutes = timeParts[1];
    const ampm = hours >= 12 ? "PM" : "AM";
    const displayHours = hours % 12 || 12;
    return `${displayHours}:${minutes} ${ampm}`;
};

const isPastEvent = (date) => {
    if (!date) return false;
    return new Date(date) < new Date().setHours(0, 0, 0, 0);
};

const isToday = (date) => {
    if (!date) return false;
    const today = new Date();
    const eventDate = new Date(date);
    return (
        eventDate.getDate() === today.getDate() &&
        eventDate.getMonth() === today.getMonth() &&
        eventDate.getFullYear() === today.getFullYear()
    );
};
</script>

<template>
    <Head title="Events" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent"
                        >
                            Events
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ canManageAll ? 'Manage and track all events' : 'View and manage your assigned events' }}
                        </p>
                    </div>
                    <Link
                        v-if="canManageAll"
                        :href="route('events.create')"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 rounded-lg font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-200 transform hover:scale-[1.02]"
                    >
                        <svg
                            class="w-5 h-5 inline-block mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"
                            />
                        </svg>
                        Create Event
                    </Link>
                </div>

                <!-- Events List -->
                <div
                    v-if="events.length > 0"
                    class="grid gap-6 grid-cols-1 lg:grid-cols-2"
                >
                    <div
                        v-for="event in events"
                        :key="event.id"
                        class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-200"
                        :class="{
                            'ring-2 ring-blue-400': isToday(event.date),
                            'opacity-75': isPastEvent(event.date),
                        }"
                    >
                        <!-- Event Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ event.name }}
                                    </h3>
                                    <span
                                        v-if="isToday(event.date)"
                                        class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full border border-blue-200"
                                    >
                                        Today
                                    </span>
                                    <span
                                        v-else-if="isPastEvent(event.date)"
                                        class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full border border-gray-200"
                                    >
                                        Past
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="space-y-3 mb-4">
                            <!-- Date -->
                            <div class="flex items-center text-sm">
                                <svg
                                    class="w-5 h-5 text-blue-500 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                                <span class="text-gray-700 font-medium">
                                    {{ formatDate(event.date) }}
                                </span>
                            </div>

                            <!-- Time -->
                            <div class="flex items-center text-sm">
                                <svg
                                    class="w-5 h-5 text-blue-500 mr-2"
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
                                <span class="text-gray-700">
                                    {{ formatTime(event.start_time) }} -
                                    {{ formatTime(event.end_time) }}
                                </span>
                            </div>

                            <!-- Related Task -->
                            <div
                                v-if="event.task"
                                class="flex items-center text-sm"
                            >
                                <svg
                                    class="w-5 h-5 text-blue-500 mr-2"
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
                                <span class="text-gray-700">
                                    <span class="font-medium"
                                        >Related Task:</span
                                    >
                                    {{ event.task.title }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex items-center gap-2 pt-4 border-t border-gray-200"
                        >
                            <Link
                                :href="route('events.show', event.id)"
                                class="flex-1 px-4 py-2 text-center rounded-lg border border-blue-300 text-blue-700 font-medium hover:bg-blue-50 transition-all duration-200"
                            >
                                View
                            </Link>
                            <Link
                                v-if="canManageAll"
                                :href="route('events.edit', event.id)"
                                class="flex-1 px-4 py-2 text-center rounded-lg border border-cyan-300 text-cyan-700 font-medium hover:bg-cyan-50 transition-all duration-200"
                            >
                                Edit
                            </Link>
                            <button
                                v-if="canManageAll"
                                @click="deleteEvent(event.id)"
                                class="flex-1 px-4 py-2 rounded-lg border border-red-300 text-red-700 font-medium hover:bg-red-50 transition-all duration-200"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-else
                    class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-12 text-center"
                >
                    <svg
                        class="w-16 h-16 mx-auto text-gray-400 mb-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">
                        No events found
                    </h3>
                    <p class="text-gray-500 mb-6">
                        {{ canManageAll ? 'Manage and track all events' : 'View and manage your assigned events' }}
                    </p>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
