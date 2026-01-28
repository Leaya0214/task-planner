<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
const props = defineProps({
    task: Object,
    can: {
        type: Object,
        default: () => ({ update: false, delete: false }),
    },
});
</script>

<template>
    <Head title="Task Details" />

    <AuthenticatedLayout>
        <div class="py-12 max-w-5xl mx-auto space-y-6">
            <!-- Header Card -->
            <div
                class="bg-white shadow-md rounded-xl p-6 flex justify-between items-center border border-gray-100"
            >
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ task.title }}
                </h1>

                <div class="flex gap-2">
                    <Link
                        v-if="can.update"
                        :href="route('tasks.edit', task.id)"
                        class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition"
                    >
                        Edit
                    </Link>

                    <Link
                        :href="route('tasks.index')"
                        class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 transition"
                    >
                        Back
                    </Link>
                </div>
            </div>

            <!-- Task Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    class="bg-white shadow rounded-lg p-4 border border-gray-100"
                >
                    <p class="text-gray-400 text-sm">Status</p>
                    <p class="text-gray-900 font-medium capitalize mt-1">
                        {{ task.status }}
                    </p>
                </div>

                <div
                    class="bg-white shadow rounded-lg p-4 border border-gray-100"
                >
                    <p class="text-gray-400 text-sm">Priority</p>
                    <p class="text-gray-900 font-medium capitalize mt-1">
                        {{ task.priority }}
                    </p>
                </div>

                <div
                    class="bg-white shadow rounded-lg p-4 border border-gray-100"
                >
                    <p class="text-gray-400 text-sm">Assigned To</p>
                    <p class="text-gray-900 font-medium mt-1">
                        {{ task.assignee?.name ?? "N/A" }}
                    </p>
                </div>

                <div
                    class="bg-white shadow rounded-lg p-4 border border-gray-100"
                >
                    <p class="text-gray-400 text-sm">Due Date</p>
                    <p class="text-gray-900 font-medium mt-1">
                        {{ task.due_date ?? "Not set" }}
                    </p>
                </div>
            </div>

            <!-- Description Card -->
            <div
                class="bg-white shadow-md rounded-xl p-6 border border-gray-100"
            >
                <p class="text-gray-400 text-sm mb-2">Description</p>
                <p class="text-gray-800 leading-relaxed">
                    {{ task.description || "No description provided." }}
                </p>
            </div>

            <!-- Related Events Card -->
            <div
                v-if="task.events?.length"
                class="bg-white shadow-md rounded-xl p-6 border border-gray-100"
            >
                <p class="text-gray-400 text-sm mb-2 font-medium">
                    Related Events
                </p>
                <ul class="space-y-2">
                    <li
                        v-for="event in task.events"
                        :key="event.id"
                        class="p-3 bg-gray-50 rounded-lg border border-gray-100 flex justify-between items-center hover:bg-gray-100 transition"
                    >
                        <span class="font-medium text-gray-900">{{
                            event.name
                        }}</span>
                        <span class="text-gray-500 text-sm"
                            >{{ event.start_time }} - {{ event.end_time }}</span
                        >
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
