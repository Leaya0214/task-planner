<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    can: {
        type: Object,
        default: () => ({ update: false, delete: false }),
    },
});

// Optional: format date/time nicely
const formatDateTime = (date, time) => {
    if (!date || !time) return "N/A";
    const d = new Date(date + "T" + time);
    return d.toLocaleString("en-US", {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Event Details" />

        <div class="py-12 max-w-xl mx-auto sm:px-6 lg:px-8">
            <!-- Event Name -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                {{ event.name }}
            </h1>

            <!-- Description -->
            <p class="text-gray-700 mb-4 leading-relaxed">
                {{ event.description || "No description provided." }}
            </p>

            <!-- Date & Time -->
            <div class="flex items-center text-gray-800 mb-2 space-x-2">
                <span>{{ formatDateTime(event.date, event.start_time) }}</span>
                <span class="font-semibold">→</span>
                <span>{{ formatDateTime(event.date, event.end_time) }}</span>
            </div>

            <!-- Related Task -->
            <div v-if="event.task_id" class="mb-2">
                <p class="text-gray-500 text-sm">Related Task:</p>
                <p class="font-medium text-gray-800">{{ event.task?.title }}</p>
            </div>

            <!-- Created By -->
            <div class="mb-4">
                <p class="text-gray-500 text-sm">Created by:</p>
                <p class="font-medium text-gray-800">
                    {{ event.createdBy?.name }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <Link
                    v-if="can.update"
                    :href="route('events.edit', event.id)"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200"
                >
                    Edit
                </Link>

                <Link
                    :href="route('events.index')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200"
                >
                    Back
                </Link>

                <button
                    v-if="can.delete"
                    @click="
                        if (confirm('Are you sure?'))
                            $router.delete(route('events.destroy', event.id));
                    "
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200"
                >
                    Delete
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
