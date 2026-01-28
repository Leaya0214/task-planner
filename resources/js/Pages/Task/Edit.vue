<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import dayjs from "dayjs";

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
    employees: {
        type: Array,
        required: true,
    },
    canManageAll: {
        type: Boolean,
        required: true,
    },
    userRole: {
        type: String,
        required: true,
    },
});

const form = useForm({
    title: props.task.title,
    description: props.task.description,
    assigned_to: props.task.assigned_to,
    status: props.task.status,
    priority: props.task.priority,
    due_date: props.task?.due_date
        ? dayjs(props.task.due_date).format("YYYY-MM-DDTHH:mm")
        : "",
});

const submit = () => {
    form.put(route("tasks.update", props.task.id), {
        onSuccess: () => {
            // Success handled by redirect
        },
    });
};
</script>

<template>
    <Head title="Edit Task" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8">
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent"
                    >
                        Edit Task
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Update the task details below
                    </p>
                </div>

                <!-- Form Card -->
                <div
                    class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-8"
                >
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Title Field -->
                        <div v-if="userRole === 'admin'">
                            <InputLabel
                                for="title"
                                value="Task Title"
                                class="text-gray-700 font-medium"
                            />
                            <TextInput
                                id="title"
                                type="text"
                                class="mt-2 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200"
                                v-model="form.title"
                                required
                                autofocus
                                placeholder="Enter task title"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.title"
                            />
                        </div>

                        <!-- Description Field -->
                        <div>
                            <InputLabel
                                for="description"
                                value="Description"
                                class="text-gray-700 font-medium"
                            />
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="mt-2 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200 resize-none"
                                placeholder="Enter task description"
                            ></textarea>
                            <InputError
                                class="mt-2"
                                :message="form.errors.description"
                            />
                        </div>

                        <!-- Assigned User Field -->
                        <div v-if="canManageAll || userRole === 'admin'">
                            <InputLabel
                                for="assigned_to"
                                value="Assign to Employee"
                                class="text-gray-700 font-medium"
                            />
                            <select
                                id="assigned_to"
                                v-model="form.assigned_to"
                                class="mt-2 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200"
                                required
                            >
                                <option value="" disabled>
                                    Select an employee
                                </option>
                                <option
                                    v-for="employee in employees"
                                    :key="employee.id"
                                    :value="employee.id"
                                >
                                    {{ employee.name }}
                                </option>
                            </select>
                            <InputError
                                class="mt-2"
                                :message="form.errors.assigned_to"
                            />
                        </div>

                        <!-- Status and Priority Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status Field -->
                            <div>
                                <InputLabel
                                    for="status"
                                    value="Status"
                                    class="text-gray-700 font-medium"
                                />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-2 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200"
                                    required
                                >
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">
                                        In Progress
                                    </option>
                                    <option value="completed">Completed</option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.status"
                                />
                            </div>

                            <!-- Priority Field -->
                            <div v-if="canManageAll || userRole === 'admin'">
                                <InputLabel
                                    for="priority"
                                    value="Priority"
                                    class="text-gray-700 font-medium"
                                />
                                <select
                                    id="priority"
                                    v-model="form.priority"
                                    class="mt-2 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200"
                                    required
                                >
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.priority"
                                />
                            </div>
                        </div>

                        <!-- Due Date & Time Field -->
                        <div v-if="canManageAll || userRole === 'admin'">
                            <InputLabel
                                for="due_date"
                                value="Due Date & Time"
                                class="text-gray-700 font-medium"
                            />
                            <input
                                id="due_date"
                                type="datetime-local"
                                v-model="form.due_date"
                                class="mt-2 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-200"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.due_date"
                            />
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200"
                        >
                            <a
                                :href="route('tasks.index')"
                                class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-all duration-200"
                            >
                                Cancel
                            </a>
                            <PrimaryButton
                                class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 transform hover:scale-[1.02]"
                                :class="{
                                    'opacity-50 cursor-not-allowed':
                                        form.processing,
                                }"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing">Updating...</span>
                                <span v-else>Update Task</span>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
