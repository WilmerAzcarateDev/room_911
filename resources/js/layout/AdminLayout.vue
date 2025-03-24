<template>
    <header class="header">
        <h1>Administrative Menu</h1>
        <div class="info-container">
            <div class="info"><span>[ {{ currentTime.toLocaleString() }} ]</span></div>
            <div class="info"><span>[ Welcome - {{ $page.props.auth.user.name }} {{ $page.props.auth.user.last_name }} ]</span></div>
        </div>
    </header>
    <main class="content">
        <slot></slot>
    </main>
    <footer class="footer">
        <button @click="logout">
            <i class="pi pi-sign-out"></i>
        </button>
    </footer>
</template>

<script setup lang="ts">
    import { ref, onMounted,onUnmounted } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import 'primeicons/primeicons.css'

    let intervalId:number;

    const currentTime = ref(new Date());
    
    const updateTime = () => {
        currentTime.value = new Date();
    };

    onMounted(()=>{
        intervalId = window.setInterval(updateTime,1000);
    });

    onUnmounted(()=>{
        clearInterval(intervalId);
    });

    async function logout(){
        await Inertia.post('/logout');
    }

</script>

<style>
    @tailwind components;
    @layer components{
        body{
            @apply bg-slate-200;
            @apply w-screen h-[100vh];
            @apply flex flex-col justify-between;
        }
        .header{
            @apply mb-auto;
            @apply w-full h-[100px];
            @apply flex flex-col gap-1;
        }
        .header > h1 {
            @apply bg-gray-400 py-2;
            @apply text-center text-2xl font-bold text-gray-800;
        }
        .info-container{
            @apply w-full px-3 py-1;
            @apply flex flex-wrap justify-between;
        }
        .info{
            @apply text-blue-500;
        }
        .content{
            @apply mx-auto my-auto;
            @apply w-10/12;
        }
        .footer{
            @apply fixed bottom-0 left-0 right-0 px-3 py-2;
            @apply flex justify-end items-center;
        }
    }
</style>