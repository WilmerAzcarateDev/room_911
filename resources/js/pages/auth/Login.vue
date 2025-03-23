<template>
    <AuthLayout>
        <Head title="Log in"></Head>
        <div class="title-container">
            <h1 class="form-title">Welcome to {{ form_title }}</h1>
            <p class="form-description">
                Access restricted to authorized personnel only.
            </p>
        </div>
        <div v-if="status" class="status-container">
            {{ status }}
        </div>
        <form @submit.prevent="submit" class="form-container">
            <div class="input-container">
                <input 
                    class="input" 
                    v-model="form.document" 
                    :tabindex="1"
                    placeholder="Identity Number"
                    type="number"
                    required>
                    <InputError v-if="form.errors.document" 
                        :message="form.errors.document" />
            </div>
            <div class="input-container">
                <input 
                    placeholder="Password"
                    class="input"
                    :tabindex="2"
                    v-model="form.password" 
                    type="password"
                    required>
                    <InputError v-if="form.errors.password" 
                        :message="form.errors.password" />
            </div>
            <button :class="{disabled:!form.isDirty && form.hasErrors}" class="submit-buttom" :tabindex="4" type="submit">Log in</button>
            <div class="checkbox-container">
                <label class="label" for="remember"><span>Remember me</span></label>
                <input :tabindex="3" id="remember" v-model="form.remember" class="input" type="checkbox">
            </div>
        </form>
    </AuthLayout>
</template>

<script setup lang="ts">
    import AuthLayout from '@/layout/AuthLayout.vue';
    import InputError from '@/components/inputs/InputErrors.vue';
    import { Head, useForm } from '@inertiajs/vue3';

    defineProps<{
        status?:string;
    }>();

    const form_title = import.meta.env.VITE_APP_NAME || 'Laravel';

    const form = useForm({
        document: null,
        password: null,
        remember: false,
    });

    function submit(){
        form.post(route('login'),{
            onFinish: ()=> form.reset('password')
        });
    }
</script>

<style scoped>
    @tailwind components;
    @layer components {
        .title-container{
            @apply flex flex-col mb-2;
        }
        .form-title {
            @apply text-3xl font-bold text-gray-600;
        }
        .form-description{
            @apply text-xs text-gray-400;
        }
        .status-container{
            @apply mb-4 text-center text-sm font-medium text-green-600;
        }
        .form-container{
            @apply flex flex-col gap-1;
        }
        .input-container {
            @apply flex flex-col gap-1;
        }
        .checkbox-container{
            @apply text-sm text-gray-400;
            @apply flex gap-1 mx-auto items-center;
        }
        .label{
            @apply flex justify-center items-center;
        }
        .submit-buttom{
            @apply px-2 py-1;
            @apply rounded-sm;
            @apply text-white bg-black;
            /*hover*/
            @apply hover:bg-gray-800;
            /* click */
            @apply hover:bg-gray-700;
        }
        .submit-buttom.disabled{
            @apply opacity-50;
        }
        .input{
            @apply rounded-sm border border-gray-300;
            @apply p-1;
            /* focus */
            @apply focus:border-4;
        }
        .input[type='number']::-webkit-inner-spin-button,
        .input[type='number']::-webkit-outer-spin-button{
            @apply hidden;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    }
</style>