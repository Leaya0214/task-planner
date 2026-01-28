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
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
