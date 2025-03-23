<template>
    <main class="layout-content">
        <slot></slot>
    </main>
</template>

<script setup lang="ts">

</script>

<style>
    @tailwind components;
    @layer components {
        body {
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