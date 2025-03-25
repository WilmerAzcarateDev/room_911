<template>
    <button 
        :class="{
            info: type === buttonEnum.INFO,
            primary: type === buttonEnum.PRIMARY,
            danger: type === buttonEnum.DANGER,
        }" 
        class="button" 
        @click="click">
        <slot></slot>
    </button>
</template>

<script setup lang="ts">
import { EButtonType } from '@/enums';



const buttonEnum = EButtonType;

defineProps<{
    type?:EButtonType;
    click:()=>void
}>()

</script>

<style scoped>

        .button{
            @apply border border-slate-600 text-slate-600 rounded-sm;
            @apply py-2 px-3;
        }
        .button.primary{
            @apply bg-slate-600 border-none text-slate-200;
        }
        .button.info{
            @apply border-yellow-600 text-yellow-600;
        }
        .button.danger{
            @apply border-red-600 text-red-600;
        }

</style>