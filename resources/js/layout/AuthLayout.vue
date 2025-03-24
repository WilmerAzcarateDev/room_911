<template>
    <main class="layout-container">
        <div class="layout-content">
            <slot></slot>
        </div>
    </main>
</template>

<script setup lang="ts">
</script>

<style>
    @tailwind components;
    @layer components {
        .layout-container {
            @apply bg-gray-200;
            @apply w-screen h-screen;
            @apply flex justify-center items-center;
        }

        .layout-content{
            @apply bg-white;
            @apply max-w-[500px] h-fit p-5;
            @apply shadow-lg shadow-gray-500 rounded-md;
        }
    }
</style>