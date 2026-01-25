<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-purple-50 via-white to-pink-50 p-4"
    >
        <div class="w-full max-w-md">
            <slot />
        </div>
    </div>
</template>
