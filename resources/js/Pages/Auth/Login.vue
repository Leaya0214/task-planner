<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
    admin: Object,
    employee: Object,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

// Demo credential functions
const fillAdminCredentials = () => {
    form.email = props.admin.email;
    form.password = "password1234";
};

const fillEmployeeCredentials = () => {
    form.email = props.employee.email;
    form.password = "password1234";
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <!-- Card Container with Glassmorphism -->
        <div
            class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-8 sm:p-10"
        >
            <!-- Header Section -->
            <div class="mb-8 text-center">
                <h1
                    class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent"
                >
                    Welcome Back
                </h1>
                <p class="mt-2 text-sm text-gray-500">
                    Sign in to continue to your account
                </p>
            </div>

            <!-- Status Message -->
            <div
                v-if="status"
                class="mb-6 rounded-lg bg-green-50 border border-green-200 p-3 text-sm font-medium text-green-700"
            >
                {{ status }}
            </div>

            <!-- Demo Credentials Section -->
            <div class="mb-6">
                <p class="text-xs text-gray-500 text-center mb-3 font-medium">
                    Quick Login for Testing
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Admin Demo Button -->
                    <button
                        type="button"
                        @click="fillAdminCredentials"
                        class="flex flex-col items-center justify-center p-3 rounded-lg border-2 border-blue-200 bg-blue-50/50 hover:bg-blue-100/70 hover:border-blue-300 transition-all duration-200 group"
                    >
                        <svg
                            class="w-6 h-6 text-blue-600 mb-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                            />
                        </svg>
                        <span
                            class="text-xs font-semibold text-blue-700 group-hover:text-blue-800"
                            >Admin</span
                        >
                        <span class="text-[10px] text-blue-600/70 mt-0.5"
                            >Full Access</span
                        >
                    </button>

                    <!-- Employee Demo Button -->
                    <button
                        type="button"
                        @click="fillEmployeeCredentials"
                        class="flex flex-col items-center justify-center p-3 rounded-lg border-2 border-green-200 bg-green-50/50 hover:bg-green-100/70 hover:border-green-300 transition-all duration-200 group"
                    >
                        <svg
                            class="w-6 h-6 text-green-600 mb-1"
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
                        <span
                            class="text-xs font-semibold text-green-700 group-hover:text-green-800"
                            >Employee</span
                        >
                        <span class="text-[10px] text-green-600/70 mt-0.5"
                            >Standard User</span
                        >
                    </button>
                </div>
            </div>

            <!-- Login Form -->
            <form @submit.prevent="submit" class="space-y-5">
                <!-- Email Field -->
                <div>
                    <InputLabel
                        for="email"
                        value="Email Address"
                        class="text-gray-700 font-medium"
                    />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-2 block w-full rounded-lg border-gray-200 focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-200"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@example.com"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- Password Field -->
                <div>
                    <InputLabel
                        for="password"
                        value="Password"
                        class="text-gray-700 font-medium"
                    />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-2 block w-full rounded-lg border-gray-200 focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-200"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer group">
                        <Checkbox
                            name="remember"
                            v-model:checked="form.remember"
                        />
                        <span
                            class="ms-2 text-sm text-gray-600 group-hover:text-gray-800 transition-colors"
                        >
                            Remember me
                        </span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium text-purple-600 hover:text-purple-700 transition-colors"
                    >
                        Forgot password?
                    </Link>
                </div>

                <!-- Submit Button -->
                <PrimaryButton
                    class="w-full justify-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:ring-purple-500 py-3 rounded-lg font-semibold text-white shadow-lg shadow-purple-500/30 transition-all duration-200 transform hover:scale-[1.02]"
                    :class="{
                        'opacity-50 cursor-not-allowed': form.processing,
                    }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Signing in...</span>
                    <span v-else>Sign In</span>
                </PrimaryButton>

                <!-- Register Link -->
                <div class="text-center pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <Link
                            :href="route('register')"
                            class="font-medium text-purple-600 hover:text-purple-700 transition-colors ml-1"
                        >
                            Create one now
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
