<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";

const { auth } = usePage().props;
const user = auth.user;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2
                            class="font-semibold text-xl text-gray-800 leading-tight mb-4"
                        >
                            Welcome back, {{ user.name }}!
                        </h2>

                        <div
                            class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200"
                        >
                            <p class="text-lg">
                                Your role:
                                <span
                                    :class="
                                        user.role === 'admin'
                                            ? 'text-green-700 font-bold'
                                            : 'text-blue-700 font-bold'
                                    "
                                >
                                    {{ user.role.toUpperCase() }}
                                </span>
                            </p>
                        </div>

                        <div
                            v-if="user.role === 'admin'"
                            class="text-green-700 mb-4"
                        >
                            → You have full access (Admin)
                        </div>
                        <div v-else class="text-blue-700 mb-4">
                            → You can only see/manage your own tasks & events
                            (Employee)
                        </div>

                        <!-- Placeholder for future links -->
                        <div class="mt-6 space-y-4">
                            <a
                                href="/tasks"
                                class="text-indigo-600 hover:underline block"
                            >
                                → Go to Tasks
                            </a>
                            <a
                                href="/calendar"
                                class="text-indigo-600 hover:underline block"
                            >
                                → Go to Calendar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
