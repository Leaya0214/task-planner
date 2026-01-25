<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <!-- Card Container with Glassmorphism -->
        <div
            class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-8 sm:p-10"
        >
            <!-- Header Section -->
            <div class="mb-8 text-center">
                <h1
                    class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent"
                >
                    Create Account
                </h1>
                <p class="mt-2 text-sm text-gray-500">
                    Join us today and get started
                </p>
            </div>

            <!-- Registration Form -->
            <form @submit.prevent="submit" class="space-y-5">
                <!-- Name Field -->
                <div>
                    <InputLabel
                        for="name"
                        value="Full Name"
                        class="text-gray-700 font-medium"
                    />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-2 block w-full rounded-lg border-gray-200 focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-200"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="John Doe"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

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
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm Password"
                        class="text-gray-700 font-medium"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-2 block w-full rounded-lg border-gray-200 focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all duration-200"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <!-- Submit Button -->
                <PrimaryButton
                    class="w-full justify-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:ring-purple-500 py-3 rounded-lg font-semibold text-white shadow-lg shadow-purple-500/30 transition-all duration-200 transform hover:scale-[1.02]"
                    :class="{
                        'opacity-50 cursor-not-allowed': form.processing,
                    }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Creating account...</span>
                    <span v-else>Create Account</span>
                </PrimaryButton>

                <!-- Login Link -->
                <div class="text-center pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <Link
                            :href="route('login')"
                            class="font-medium text-purple-600 hover:text-purple-700 transition-colors ml-1"
                        >
                            Sign in
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
