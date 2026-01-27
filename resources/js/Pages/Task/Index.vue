<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    tasks: {
        type: Array,
        required: true,
    },
    canManageAll: {
        type: Boolean,
        default: false,
    },
});

const deleteTask = (taskId) => {
    if (confirm("Are you sure you want to delete this task?")) {
        router.delete(route("tasks.destroy", taskId));
    }
};

const getStatusClass = (status) => {
    const classes = {
        pending: "bg-yellow-100 text-yellow-800 border-yellow-200",
        in_progress: "bg-blue-100 text-blue-800 border-blue-200",
        completed: "bg-green-100 text-green-800 border-green-200",
    };
    return classes[status] || "bg-gray-100 text-gray-800 border-gray-200";
};

const getPriorityClass = (priority) => {
    const classes = {
        low: "bg-gray-100 text-gray-700 border-gray-200",
        medium: "bg-orange-100 text-orange-700 border-orange-200",
        high: "bg-red-100 text-red-700 border-red-200",
    };
    return classes[priority] || "bg-gray-100 text-gray-700 border-gray-200";
};

const formatStatus = (status) => {
    return status
        .split("_")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
};

const formatPriority = (priority) => {
    return priority.charAt(0).toUpperCase() + priority.slice(1);
};

const formatDateTime = (dateTime) => {
    if (!dateTime) return "N/A";
    const date = new Date(dateTime);
    return date.toLocaleString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const isOverdue = (dueDate) => {
    if (!dueDate) return false;
    return new Date(dueDate) < new Date();
};
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent"
                        >
                            Tasks
                        </h1>
                        <p class="mt-2 text-sm text-gray-600">
                            Manage and track all your tasks
                        </p>
                    </div>
                    <Link
                        v-if="canManageAll"
                        :href="route('tasks.create')"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 transform hover:scale-[1.02]"
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
                        Create Task
                    </Link>
                </div>

                <!-- Tasks List -->
                <div
                    v-if="tasks.length > 0"
                    class="grid gap-6 grid-cols-1 lg:grid-cols-2"
                >
                    <div
                        v-for="task in tasks"
                        :key="task.id"
                        class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-200"
                    >
                        <!-- Task Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-2"
                                >
                                    {{ task.title }}
                                </h3>
                                <p
                                    class="text-sm text-gray-600 line-clamp-2"
                                    v-if="task.description"
                                >
                                    {{ task.description }}
                                </p>
                            </div>
                        </div>

                        <!-- Task Details -->
                        <div class="space-y-3 mb-4">
                            <!-- Assigned To -->
                            <div class="flex items-center text-sm">
                                <svg
                                    class="w-5 h-5 text-gray-400 mr-2"
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
                                <span class="text-gray-700">
                                    <span class="font-medium"
                                        >Assigned to:</span
                                    >
                                    {{ task.assignee?.name || "Unassigned" }}
                                </span>
                            </div>

                            <!-- Due Date -->
                            <div class="flex items-center text-sm">
                                <svg
                                    class="w-5 h-5 text-gray-400 mr-2"
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
                                <span
                                    :class="
                                        isOverdue(task.due_date)
                                            ? 'text-red-600 font-semibold'
                                            : 'text-gray-700'
                                    "
                                >
                                    <span class="font-medium">Due:</span>
                                    {{ formatDateTime(task.due_date) }}
                                    <span
                                        v-if="isOverdue(task.due_date)"
                                        class="ml-1 text-xs"
                                    >
                                        (Overdue)
                                    </span>
                                </span>
                            </div>

                            <!-- Status and Priority Badges -->
                            <div class="flex items-center gap-2 pt-2">
                                <span
                                    :class="getStatusClass(task.status)"
                                    class="px-3 py-1 rounded-full text-xs font-semibold border"
                                >
                                    {{ formatStatus(task.status) }}
                                </span>
                                <span
                                    :class="getPriorityClass(task.priority)"
                                    class="px-3 py-1 rounded-full text-xs font-semibold border"
                                >
                                    {{ formatPriority(task.priority) }} Priority
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex items-center gap-2 pt-4 border-t border-gray-200"
                        >
                            <Link
                                :href="route('tasks.show', task.id)"
                                class="flex-1 px-4 py-2 text-center rounded-lg border border-indigo-300 text-indigo-700 font-medium hover:bg-indigo-50 transition-all duration-200"
                            >
                                View
                            </Link>
                            <Link
                                v-if="canManageAll"
                                :href="route('tasks.edit', task.id)"
                                class="flex-1 px-4 py-2 text-center rounded-lg border border-blue-300 text-blue-700 font-medium hover:bg-blue-50 transition-all duration-200"
                            >
                                Edit
                            </Link>
                            <button
                                v-if="canManageAll"
                                @click="deleteTask(task.id)"
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
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                        />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">
                        No tasks found
                    </h3>
                    <p class="text-gray-500 mb-6">
                        Get started by creating your first task
                    </p>
                    <Link
                        v-if="canManageAll"
                        :href="route('tasks.create')"
                        class="inline-block px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 transform hover:scale-[1.02]"
                    >
                        Create Your First Task
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
