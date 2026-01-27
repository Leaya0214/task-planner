<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    tasks: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    name: props.event.name,
    date: props.event.date,
    start_time: props.event.attributes?.start_time || props.event.start_time,
    end_time: props.event.attributes?.end_time || props.event.end_time,
    task_id: props.event.task_id || "",
});

const submit = () => {
    form.put(route("events.update", props.event.id), {
        onSuccess: () => {
            // Success handled by redirect
        },
    });
};
</script>

<template>
    <Head title="Edit Event" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="mb-8">
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent"
                    >
                        Edit Event
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Update the event details below
                    </p>
                </div>

                <!-- Form Card -->
                <div
                    class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-8"
                >
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Event Name Field -->
                        <div>
                            <InputLabel
                                for="name"
                                value="Event Name"
                                class="text-gray-700 font-medium"
                            />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-2 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200"
                                v-model="form.name"
                                required
                                autofocus
                                placeholder="Enter event name"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>

                        <!-- Date Field -->
                        <div>
                            <InputLabel
                                for="date"
                                value="Event Date"
                                class="text-gray-700 font-medium"
                            />
                            <input
                                id="date"
                                type="date"
                                v-model="form.date"
                                class="mt-2 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.date"
                            />
                        </div>

                        <!-- Time Fields Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Time Field -->
                            <div>
                                <InputLabel
                                    for="start_time"
                                    value="Start Time"
                                    class="text-gray-700 font-medium"
                                />
                                <input
                                    id="start_time"
                                    type="time"
                                    v-model="form.start_time"
                                    class="mt-2 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.start_time"
                                />
                            </div>

                            <!-- End Time Field -->
                            <div>
                                <InputLabel
                                    for="end_time"
                                    value="End Time"
                                    class="text-gray-700 font-medium"
                                />
                                <input
                                    id="end_time"
                                    type="time"
                                    v-model="form.end_time"
                                    class="mt-2 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.end_time"
                                />
                            </div>
                        </div>

                        <!-- Related Task Field -->
                        <div>
                            <InputLabel
                                for="task_id"
                                value="Related Task (Optional)"
                                class="text-gray-700 font-medium"
                            />
                            <select
                                id="task_id"
                                v-model="form.task_id"
                                class="mt-2 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200"
                            >
                                <option value="">No related task</option>
                                <option
                                    v-for="task in tasks"
                                    :key="task.id"
                                    :value="task.id"
                                >
                                    {{ task.title }}
                                </option>
                            </select>
                            <InputError
                                class="mt-2"
                                :message="form.errors.task_id"
                            />
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200"
                        >
                            <a
                                :href="route('events.index')"
                                class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-all duration-200"
                            >
                                Cancel
                            </a>
                            <PrimaryButton
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 rounded-lg font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-200 transform hover:scale-[1.02]"
                                :class="{
                                    'opacity-50 cursor-not-allowed':
                                        form.processing,
                                }"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing">Updating...</span>
                                <span v-else>Update Event</span>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
