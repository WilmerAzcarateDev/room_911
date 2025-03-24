<template>
    <span class="message">{{ message }}</span>
</template>

<script setup lang="ts">
    defineProps<{
        message:string
    }>();
</script>

<style scoped>
    @tailwind components;
    @layer components {
        .message{
            @apply text-sm text-red-500;
        }
    }
</style>